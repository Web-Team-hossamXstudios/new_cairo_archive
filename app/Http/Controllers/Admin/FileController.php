<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\StoreFileRequest;
use App\Models\File;
use App\Models\Client;
use App\Models\Land;
use App\Models\Room;
use App\Models\Item;
use App\Jobs\ProcessPdfJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = File::query()
                ->mainFiles()
                ->with(['client', 'land', 'room', 'rack', 'items', 'uploader']);

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('file_name', 'like', "%{$request->search}%")
                      ->orWhere('barcode', 'like', "%{$request->search}%")
                      ->orWhereHas('client', fn($q2) => $q2->where('name', 'like', "%{$request->search}%"));
                });
            }

            if ($request->filled('client_id')) {
                $query->where('client_id', $request->client_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->trashed === 'only') {
                $query->onlyTrashed();
            }

            $perPage = $request->per_page ?? 25;
            $files = $query->latest()->paginate($perPage)->withQueryString();
            $clients = Client::orderBy('name')->get();
            $rooms = Room::orderBy('name')->get();
            $items = Item::orderBy('order')->get();

            return view('dashboards.admin.pages.files.index', compact('files', 'clients', 'rooms', 'items'));
        } catch (\Exception $e) {
            Log::error('FileController@index: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل البيانات');
        }
    }

    public function create(Request $request)
    {
        try {
            $clients = Client::orderBy('name')->get();
            $rooms = Room::with('lanes.stands.racks')->orderBy('name')->get();
            $items = Item::orderBy('order')->get();
            $selectedClientId = $request->client_id;

            return response()->json([
                'html' => view('dashboards.admin.pages.files.partials.create', compact('clients', 'rooms', 'items', 'selectedClientId'))->render()
            ]);
        } catch (\Exception $e) {
            Log::error('FileController@create: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function store(StoreFileRequest $request)
    {
        try {
            DB::beginTransaction();

            // Handle new land creation if provided
            $landId = $request->land_id;
            if ($request->filled('new_governorate_id')) {
                $newLand = \App\Models\Land::create([
                    'client_id' => $request->client_id,
                    'governorate_id' => $request->new_governorate_id,
                    'city_id' => $request->new_city_id ?: null,
                    'district_id' => $request->new_district_id ?: null,
                    'zone_id' => $request->new_zone_id ?: null,
                    'area_id' => $request->new_area_id ?: null,
                    'land_no' => $request->new_land_no ?: 'غير محدد',
                    'unit_no' => $request->new_unit_no ?: null,
                    'address' => $request->new_address ?: null,
                    'notes' => $request->new_notes ?: null,
                ]);
                $landId = $newLand->id;
                Log::info('New land created during file upload', ['land_id' => $landId, 'client_id' => $request->client_id]);
            }

            $pdfFile = $request->file('document');
            $pagesCount = $this->getPdfPageCount($pdfFile);

            $file = File::create([
                'client_id' => $request->client_id,
                'land_id' => $landId,
                'room_id' => $request->room_id,
                'lane_id' => $request->lane_id,
                'stand_id' => $request->stand_id,
                'rack_id' => $request->rack_id,
                'file_name' => $request->file_name ?? pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME),
                'barcode' => File::generateBarcode(),
                'original_name' => $pdfFile->getClientOriginalName(),
                'pages_count' => $pagesCount,
                'status' => 'processing',
                'uploaded_by' => auth()->id(),
            ]);

            $file->addMedia($pdfFile)->toMediaCollection('documents');

            // Handle items with page ranges (new format with items_json)
            if ($request->filled('items_json')) {
                $items = json_decode($request->items_json, true);
                if (is_array($items)) {
                    foreach ($items as $itemData) {
                        $fromPage = !empty($itemData['from_page']) ? (int)$itemData['from_page'] : null;
                        $toPage = !empty($itemData['to_page']) ? (int)$itemData['to_page'] : null;

                        // If only from_page is set, to_page should equal from_page (single page)
                        if ($fromPage && !$toPage) {
                            $toPage = $fromPage;
                        }

                        $file->fileItems()->create([
                            'item_id' => $itemData['item_id'],
                            'from_page' => $fromPage,
                            'to_page' => $toPage,
                        ]);
                    }
                }
            } elseif ($request->filled('items')) {
                // Legacy format support
                foreach ($request->items as $itemData) {
                    if (is_array($itemData)) {
                        $fromPage = !empty($itemData['from_page']) ? (int)$itemData['from_page'] : null;
                        $toPage = !empty($itemData['to_page']) ? (int)$itemData['to_page'] : null;

                        // If only from_page is set, to_page should equal from_page (single page)
                        if ($fromPage && !$toPage) {
                            $toPage = $fromPage;
                        }

                        $file->fileItems()->create([
                            'item_id' => $itemData['item_id'],
                            'from_page' => $fromPage,
                            'to_page' => $toPage,
                        ]);
                    } else {
                        // Simple checkbox format (just item_id)
                        $file->fileItems()->create([
                            'item_id' => $itemData,
                            'from_page' => null,
                            'to_page' => null,
                        ]);
                    }
                }
            }

            DB::commit();

            // Dispatch job to process PDF and create sub-files from page ranges
            ProcessPdfJob::dispatch($file);

            return response()->json([
                'success' => true,
                'message' => 'تم رفع الملف بنجاح! جاري معالجته في الخلفية...',
                'file' => $file,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FileController@store: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء رفع الملف: ' . $e->getMessage()], 500);
        }
    }

    public function show(File $file)
    {
        try {
            $file->load([
                'client',
                'land',
                'room',
                'lane',
                'stand',
                'rack',
                'items',
                'pages.media',
                'subFiles.items',
                'subFiles.fileItems.item',
                'subFiles.media'
            ]);

            // Get PDF URL
            $pdfUrl = null;
            if ($file->hasMedia('documents')) {
                $pdfUrl = $file->getFirstMediaUrl('documents');
            } elseif ($file->pages && $file->pages->count() > 0) {
                $pdfUrl = $file->pages->first()->getFirstMediaUrl('page_images');
            }

            return response()->json([
                'success' => true,
                'file' => $file,
                'pdf_url' => $pdfUrl
            ]);
        } catch (\Exception $e) {
            Log::error('FileController@show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل الملف'
            ], 500);
        }
    }

    public function findByBarcode(string $barcode)
    {
        try {
            $file = File::where('barcode', $barcode)
                ->with([
                    'client',
                    'land',
                    'room',
                    'lane',
                    'stand',
                    'rack',
                    'items',
                    'pages.media',
                    'subFiles.items',
                    'subFiles.fileItems.item',
                    'subFiles.media'
                ])
                ->first();

            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على ملف بهذا الباركود'
                ], 404);
            }

            $pdfUrl = null;
            if ($file->hasMedia('documents')) {
                $pdfUrl = $file->getFirstMediaUrl('documents');
            } elseif ($file->pages && $file->pages->count() > 0) {
                $pdfUrl = $file->pages->first()->getFirstMediaUrl('page_images');
            }

            return response()->json([
                'success' => true,
                'file' => $file,
                'pdf_url' => $pdfUrl
            ]);
        } catch (\Exception $e) {
            Log::error('FileController@findByBarcode: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء البحث'
            ], 500);
        }
    }

    public function edit(File $file)
    {
        try {
            $file->load(['client', 'land', 'room', 'lane', 'stand', 'rack']);
            $clients = Client::orderBy('name')->get();
            $rooms = Room::with('lanes.stands.racks')->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'html' => view('dashboards.admin.pages.files.partials.edit', compact('file', 'clients', 'rooms'))->render()
            ]);
        } catch (\Exception $e) {
            Log::error('FileController@edit: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء تحميل بيانات الملف'], 500);
        }
    }

    public function update(Request $request, File $file)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'file_name' => 'required|string|max:255',
                'room_id' => 'nullable|exists:rooms,id',
                'lane_id' => 'nullable|exists:lanes,id',
                'stand_id' => 'nullable|exists:stands,id',
                'rack_id' => 'nullable|exists:racks,id',
                'document' => 'nullable|file|mimes:pdf|max:51200',
            ], [
                'file_name.required' => 'رقم الملف مطلوب',
                'file_name.max' => 'رقم الملف يجب ألا يتجاوز 255 حرف',
                'document.mimes' => 'يجب أن يكون الملف بصيغة PDF',
                'document.max' => 'حجم الملف يجب ألا يتجاوز 50 ميجابايت',
            ]);

            // Update file data
            $file->update([
                'file_name' => $validated['file_name'],
                'room_id' => $validated['room_id'] ?? $file->room_id,
                'lane_id' => $validated['lane_id'] ?? $file->lane_id,
                'stand_id' => $validated['stand_id'] ?? $file->stand_id,
                'rack_id' => $validated['rack_id'] ?? $file->rack_id,
            ]);

            // Handle file upload for empty files (code-only files)
            if ($request->hasFile('document')) {
                $pdfFile = $request->file('document');
                $pagesCount = $this->getPdfPageCount($pdfFile);

                // Clear existing media if any
                $file->clearMediaCollection('documents');

                // Add new document
                $file->addMedia($pdfFile)->toMediaCollection('documents');

                $file->update([
                    'original_name' => $pdfFile->getClientOriginalName(),
                    'pages_count' => $pagesCount,
                    'status' => 'processing',
                ]);

                DB::commit();

                // Dispatch job to process PDF
                ProcessPdfJob::dispatch($file);

                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث الملف ورفع المستند بنجاح! جاري معالجته في الخلفية...',
                    'file' => $file->fresh(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث بيانات الملف بنجاح',
                'file' => $file->fresh(),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FileController@update: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء تحديث الملف: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(File $file)
    {
        try {
            DB::beginTransaction();
            $file->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الملف بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FileController@destroy: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الحذف'], 500);
        }
    }

    public function view(File $file)
    {
        try {
            $media = $file->getFirstMedia('documents');
            if (!$media) {
                abort(404, 'الملف غير موجود');
            }

            return response()->file($media->getPath());
        } catch (\Exception $e) {
            Log::error('FileController@view: ' . $e->getMessage());
            abort(500, 'حدث خطأ أثناء عرض الملف');
        }
    }

    public function download(File $file)
    {
        try {
            $media = $file->getFirstMedia('documents');
            if (!$media) {
                abort(404, 'الملف غير موجود');
            }

            return response()->download($media->getPath(), $file->original_name ?? $file->file_name . '.pdf');
        } catch (\Exception $e) {
            Log::error('FileController@download: ' . $e->getMessage());
            abort(500, 'حدث خطأ أثناء تحميل الملف');
        }
    }

    public function retry(File $file)
    {
        try {
            if ($file->status !== 'failed') {
                return response()->json(['error' => 'الملف ليس في حالة فشل'], 400);
            }

            DB::beginTransaction();
            $file->update(['status' => 'processing', 'error_message' => null]);
            DB::commit();

            ProcessPdfJob::dispatch($file);

            return response()->json([
                'success' => true,
                'message' => 'تم إعادة معالجة الملف',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FileController@retry: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function getPages(File $file)
    {
        try {
            $pages = $file->pages()->with('media')->get();
            return response()->json(['pages' => $pages]);
        } catch (\Exception $e) {
            Log::error('FileController@getPages: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    private function getPdfPageCount($file): int
    {
        try {
            $pdfPath = $file->getPathname();

            if (extension_loaded('imagick')) {
                $imagick = new \Imagick();
                $imagick->pingImage($pdfPath);
                return $imagick->getNumberImages();
            }

            $content = file_get_contents($pdfPath);
            preg_match_all("/\/Page\W/", $content, $matches);
            return count($matches[0]) ?: 1;
        } catch (\Exception $e) {
            Log::warning('Could not determine PDF page count: ' . $e->getMessage());
            return 1;
        }
    }

    public function uploadDocument(Request $request, File $file)
    {
        try {
            $request->validate([
                'document' => 'required|file|mimes:pdf|max:51200',
            ]);

            DB::beginTransaction();

            $pdfFile = $request->file('document');
            $pagesCount = $this->getPdfPageCount($pdfFile);

            // Clear existing media
            $file->clearMediaCollection('documents');

            // Add new media
            $file->addMedia($pdfFile)->toMediaCollection('documents');

            // Update file metadata
            $file->update([
                'original_name' => $pdfFile->getClientOriginalName(),
                'pages_count' => $pagesCount,
                'status' => 'processing',
            ]);

            // Clear existing file items
            $file->fileItems()->delete();

            // Handle items with page ranges - only create when page ranges are provided
            if ($request->filled('items')) {
                foreach ($request->items as $itemData) {
                    if (is_array($itemData) && isset($itemData['item_id'])) {
                        $fromPage = !empty($itemData['from_page']) ? (int)$itemData['from_page'] : null;
                        $toPage = !empty($itemData['to_page']) ? (int)$itemData['to_page'] : null;

                        // Only create file_item if at least from_page is provided
                        if ($fromPage !== null) {
                            // If only from_page is provided, use it for to_page as well
                            if ($toPage === null) {
                                $toPage = $fromPage;
                            }

                            $file->fileItems()->create([
                                'item_id' => $itemData['item_id'],
                                'from_page' => $fromPage,
                                'to_page' => $toPage,
                            ]);
                        }
                    }
                }
            }

            // Dispatch processing job - pass the File model, not the ID
            ProcessPdfJob::dispatch($file);

            DB::commit();

            Log::info('Document uploaded to existing file', [
                'file_id' => $file->id,
                'pages_count' => $pagesCount,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم رفع الملف بنجاح وجاري المعالجة',
                'file' => $file->fresh()
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FileController@uploadDocument: ' . $e->getMessage(), [
                'file_id' => $file->id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع الملف: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:files,id']);

            DB::beginTransaction();
            File::whereIn('id', $request->ids)->delete();
            DB::commit();

            Log::info('Files bulk deleted', ['ids' => $request->ids, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الملفات بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FileController@bulkDelete: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'حدث خطأ أثناء الحذف'], 500);
        }
    }

    public function bulkRestore(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

            DB::beginTransaction();
            File::withTrashed()->whereIn('id', $request->ids)->restore();
            DB::commit();

            Log::info('Files bulk restored', ['ids' => $request->ids, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'تم استرجاع الملفات بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FileController@bulkRestore: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'حدث خطأ أثناء الاسترجاع'], 500);
        }
    }

    public function bulkForceDelete(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

            DB::beginTransaction();
            $files = File::withTrashed()->whereIn('id', $request->ids)->get();
            foreach ($files as $file) {
                $file->clearMediaCollection('documents');
                $file->forceDelete();
            }
            DB::commit();

            Log::info('Files bulk force deleted', ['ids' => $request->ids, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'تم الحذف النهائي للملفات بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FileController@bulkForceDelete: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'حدث خطأ أثناء الحذف النهائي'], 500);
        }
    }
}
