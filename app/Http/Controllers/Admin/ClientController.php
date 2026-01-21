<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Models\Client;
use App\Models\File;
use App\Models\Governorate;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Client::query()
                ->withCount(['lands', 'mainFiles as files_count']);

            if ($request->filled('search')) {
                $query->search($request->search);
            }

            if ($request->filled('national_id')) {
                $query->where('national_id', 'like', "%{$request->national_id}%");
            }

            if ($request->filled('client_code')) {
                $query->where('client_code', 'like', "%{$request->client_code}%");
            }

            if ($request->filled('mobile')) {
                $query->where('mobile', 'like', "%{$request->mobile}%");
            }

            if ($request->filled('governorate_id')) {
                $query->filterByGovernorate($request->governorate_id);
            }

            if ($request->trashed === 'only') {
                $query->onlyTrashed();
            }

            $perPage = $request->per_page ?? 25;
            $clients = $query->latest()->paginate($perPage)->withQueryString();
            $governorates = Governorate::orderBy('name')->get();

            return view('dashboards.admin.pages.clients.index', compact('clients', 'governorates'));
        } catch (\Exception $e) {
            Log::error('ClientController@index: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل البيانات');
        }
    }

    public function create()
    {
        try {
            $governorates = Governorate::with('cities')->orderBy('name')->get();
            return response()->json([
                'html' => view('dashboards.admin.pages.clients.partials.create', compact('governorates'))->render()
            ]);
        } catch (\Exception $e) {
            Log::error('ClientController@create: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء تحميل البيانات'], 500);
        }
    }

    public function store(StoreClientRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Generate client code if not provided
            if (empty($data['client_code'])) {
                $data['client_code'] = Client::generateClientCode();
            }

            $client = Client::create($data);

            if ($request->filled('lands')) {
                foreach ($request->lands as $landData) {
                    $client->lands()->create($landData);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة العميل بنجاح',
                'client' => $client->load('lands'),
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
            Log::error('ClientController@store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة العميل: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Client $client)
    {
        try {
            $client->load([
                'lands.governorate',
                'lands.city',
                'lands.district',
                'lands.zone',
                'lands.area',
                'lands.mainFiles' => fn($q) => $q->with([
                    'items',
                    'room',
                    'lane',
                    'stand',
                    'rack',
                    'pages.media',
                    'subFiles' => fn($q) => $q->with(['items', 'fileItems.item', 'media', 'pages.media']),
                    'children' => fn($q) => $q->with(['items', 'fileItems.item', 'pages.media', 'media'])
                ]),
            ]);

            return response()->json([
                'success' => true,
                'client' => $client
            ]);
        } catch (\Exception $e) {
            Log::error('ClientController@show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل البيانات'
            ], 500);
        }
    }

    public function edit(Client $client)
    {
        try {
            $governorates = Governorate::with('cities')->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'html' => view('dashboards.admin.pages.clients.partials.edit', compact('client', 'governorates'))->render()
            ]);
        } catch (\Exception $e) {
            Log::error('ClientController@edit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل البيانات'
            ], 500);
        }
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        try {
            DB::beginTransaction();
            $client->update($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث بيانات العميل بنجاح',
                'client' => $client->fresh(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ClientController@update: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء تحديث البيانات'], 500);
        }
    }

    public function destroy(Client $client)
    {
        try {
            DB::beginTransaction();
            $client->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف العميل بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ClientController@destroy: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء حذف العميل'], 500);
        }
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();
            $client = Client::withTrashed()->findOrFail($id);
            $client->restore();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم استرجاع العميل بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ClientController@restore: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء استرجاع العميل'], 500);
        }
    }

    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();
            $client = Client::withTrashed()->findOrFail($id);
            $client->forceDelete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم الحذف النهائي للعميل بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ClientController@forceDelete: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الحذف النهائي'], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:clients,id']);

            DB::beginTransaction();
            Client::whereIn('id', $request->ids)->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف العملاء المحددين بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ClientController@bulkDelete: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الحذف'], 500);
        }
    }

    public function bulkRestore(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array']);

            DB::beginTransaction();
            Client::withTrashed()->whereIn('id', $request->ids)->restore();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم استرجاع العملاء المحددين بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ClientController@bulkRestore: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الاسترجاع'], 500);
        }
    }

    public function bulkForceDelete(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array']);

            DB::beginTransaction();
            Client::withTrashed()->whereIn('id', $request->ids)->forceDelete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم الحذف النهائي للعملاء المحددين بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ClientController@bulkForceDelete: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الحذف النهائي'], 500);
        }
    }

    public function generateCode()
    {
        return response()->json([
            'code' => Client::generateClientCode(),
        ]);
    }

    public function getLands(Client $client)
    {
        try {
            $lands = $client->lands()->with(['governorate', 'city', 'district', 'zone', 'area'])->get();
            return response()->json(['lands' => $lands]);
        } catch (\Exception $e) {
            Log::error('ClientController@getLands: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    /**
     * Search for a file by barcode and return file with its client
     * Used by external barcode scanner devices
     */
    public function searchByBarcode(Request $request)
    {
        try {
            $barcode = $request->input('barcode');

            if (empty($barcode)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الرجاء إدخال الباركود'
                ], 400);
            }

            // Find file by barcode
            $file = File::where('barcode', $barcode)
                ->with([
                    'land.client',
                    'land.governorate',
                    'land.city',
                    'land.district',
                    'land.zone',
                    'land.area',
                    'room',
                    'lane',
                    'stand',
                    'rack',
                    'items',
                    'pages.media',
                    'media'
                ])
                ->first();

            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على ملف بهذا الباركود: ' . $barcode
                ], 404);
            }

            $client = $file->land?->client;

            return response()->json([
                'success' => true,
                'file' => $file,
                'client' => $client ? $client->load(['lands']) : null,
                'land' => $file->land,
                'message' => 'تم العثور على الملف بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('ClientController@searchByBarcode: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء البحث: ' . $e->getMessage()
            ], 500);
        }
    }
}
