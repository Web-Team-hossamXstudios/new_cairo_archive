<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Import;
use App\Models\Client;
use App\Models\Land;
use App\Models\Governorate;
use App\Models\City;
use App\Models\District;
use App\Models\Zone;
use App\Models\Area;
use App\Models\Room;
use App\Models\Lane;
use App\Models\Stand;
use App\Models\Rack;
use App\Jobs\ProcessImportJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    /**
     * Display a listing of all imports with pagination
     * Shows import history with status, type, and statistics
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            $imports = Import::with('user')
                ->latest()
                ->paginate(25)
                ->withQueryString();

            return view('dashboards.admin.pages.imports.index', compact('imports'));
        } catch (\Exception $e) {
            Log::error('ImportController@index: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل البيانات');
        }
    }

    /**
     * Show the form for creating a new import
     * Displays upload interface with import type selection
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashboards.admin.pages.imports.create');
    }

    /**
     * Handle the upload of an Excel file for import
     * Validates file, creates import record, and auto-executes archive imports
     *
     * Supported import types:
     * - full: Complete data (clients, lands, geographic areas)
     * - clients: Client data only
     * - lands: Land data only
     * - geographic: Geographic hierarchy only
     * - archive: Archive format with Arabic headers (auto-executes)
     *
     * @param Request $request Contains file, type, skip_errors, update_existing
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        try {
            // Validate uploaded file and import parameters
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:10240',
                'type' => 'required|in:full,clients,lands,geographic,archive',
                'skip_errors' => 'boolean',
                'update_existing' => 'boolean',
            ], [
                'file.required' => 'الملف مطلوب',
                'file.mimes' => 'يجب أن يكون الملف بصيغة Excel',
                'file.max' => 'حجم الملف يجب ألا يتجاوز 10 ميجابايت',
            ]);

            DB::beginTransaction();

            $file = $request->file('file');
            $filename = 'import_' . time() . '_' . auth()->id() . '.' . $file->getClientOriginalExtension();

            $import = Import::create([
                'user_id' => auth()->id(),
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'type' => $request->type,
                'status' => 'pending',
            ]);

            $import->addMedia($file)->toMediaCollection('imports');

            DB::commit();

            // Auto-execute for archive type (skip validation step)
            if ($request->type === 'archive') {
                $import->update([
                    'status' => 'processing',
                    'started_at' => now(),
                ]);

                ProcessImportJob::dispatch(
                    $import,
                    $request->boolean('skip_errors', true),
                    $request->boolean('update_existing', false)
                );

                return response()->json([
                    'success' => true,
                    'message' => 'تم رفع الملف وبدأت عملية الاستيراد',
                    'import_id' => $import->id,
                    'redirect' => route('admin.imports.show', $import),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم رفع الملف بنجاح',
                'import_id' => $import->id,
                'redirect' => route('admin.imports.validate', $import),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ImportController@upload: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء رفع الملف: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Validate the imported Excel file before processing
     * Checks headers, required columns, and performs row-level validation
     *
     * @param Import $import The import record to validate
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function validateImport(Import $import)
    {
        try {
            // Only validate if import is in pending or validating status
            if (!in_array($import->status, ['pending', 'validating'])) {
                return redirect()->route('admin.imports.show', $import);
            }

            $import->update(['status' => 'validating']);

            $media = $import->getFirstMedia('imports');
            if (!$media) {
                throw new \Exception('ملف الاستيراد غير موجود');
            }

            $validationResults = $this->performValidation($media->getPath(), $import->type);

            $import->update([
                'total_rows' => $validationResults['total'],
                'errors' => $validationResults['errors'],
                'summary' => $validationResults['summary'],
            ]);

            return view('dashboards.admin.pages.imports.validate', compact('import', 'validationResults'));

        } catch (\Exception $e) {
            Log::error('ImportController@validateImport: ' . $e->getMessage());
            $import->update(['status' => 'failed', 'errors' => ['general' => $e->getMessage()]]);
            return back()->with('error', 'حدث خطأ أثناء التحقق من الملف: ' . $e->getMessage());
        }
    }

    /**
     * Execute the import process by dispatching a background job
     * Queues the ProcessImportJob to handle actual data import
     *
     * @param Request $request Contains skip_errors and update_existing flags
     * @param Import $import The import record to execute
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute(Request $request, Import $import)
    {
        try {
            // Only execute if import is pending or validated
            if (!in_array($import->status, ['pending', 'validating'])) {
                return response()->json(['error' => 'لا يمكن تنفيذ هذا الاستيراد'], 400);
            }

            DB::beginTransaction();

            $import->update([
                'status' => 'processing',
                'started_at' => now(),
            ]);

            DB::commit();

            ProcessImportJob::dispatch($import, $request->boolean('skip_errors'), $request->boolean('update_existing'));

            return response()->json([
                'success' => true,
                'message' => 'بدأت عملية الاستيراد في الخلفية',
                'redirect' => route('admin.imports.show', $import),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ImportController@execute: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    /**
     * Display detailed information about a specific import
     * Shows status, statistics, errors, and validation results
     * Logs failed rows to laravel.log for debugging
     *
     * @param Import $import The import record to display
     * @return \Illuminate\View\View
     */
    public function show(Import $import)
    {
        // Log failed rows to laravel.log if there are any
        if ($import->failed_rows > 0 && !empty($import->errors)) {
            try {
                Log::channel('daily')->warning('Import Failed Rows', [
                    'import_id' => $import->id,
                    'type' => $import->type,
                    'user_id' => $import->user_id,
                    'user_name' => $import->user->name ?? 'Unknown',
                    'filename' => $import->original_filename,
                    'total_rows' => $import->total_rows,
                    'failed_rows' => $import->failed_rows,
                    'success_rows' => $import->success_rows,
                    'created_at' => $import->created_at->toDateTimeString(),
                ]);

                // Log general errors if any
                if (!empty($import->errors['general'])) {
                    Log::channel('daily')->error('Import General Error', [
                        'import_id' => $import->id,
                        'error' => $import->errors['general'],
                    ]);
                }

                // Log each failed row with details
                if (!empty($import->errors['rows'])) {
                    foreach ($import->errors['rows'] as $rowNum => $rowData) {
                        $errorDetails = [
                            'import_id' => $import->id,
                            'row_number' => $rowNum,
                        ];

                        if (is_array($rowData)) {
                            if (isset($rowData['errors'])) {
                                $errorDetails['errors'] = $rowData['errors'];
                            }
                            if (isset($rowData['data'])) {
                                $errorDetails['row_data'] = $rowData['data'];
                            }
                        } else {
                            $errorDetails['error'] = $rowData;
                        }

                        Log::channel('daily')->error("Import Row {$rowNum} Failed", $errorDetails);
                    }

                    Log::channel('daily')->info('Import Failed Rows Summary', [
                        'import_id' => $import->id,
                        'total_failed_rows' => count($import->errors['rows']),
                        'failed_row_numbers' => array_keys($import->errors['rows']),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to log import errors: ' . $e->getMessage());
            }
        }

        return view('dashboards.admin.pages.imports.show', compact('import'));
    }

    /**
     * Get the current status of an import (for AJAX polling)
     * Returns real-time progress information
     *
     * @param Import $import The import record to check
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Import $import)
    {
        return response()->json([
            'status' => $import->status,
            'processed_rows' => $import->processed_rows,
            'total_rows' => $import->total_rows,
            'success_rows' => $import->success_rows,
            'failed_rows' => $import->failed_rows,
            'progress' => $import->progress_percentage,
        ]);
    }

    /**
     * Download an Excel template for the specified import type
     * Generates template if it doesn't exist
     *
     * @param Request $request Contains the import type
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadTemplate(Request $request)
    {
        $type = $request->get('type', 'full');
        $templatePath = resource_path("templates/import_template_{$type}.xlsx");

        if (!file_exists($templatePath)) {
            $templatePath = $this->generateTemplate($type);
        }

        return response()->download($templatePath, "import_template_{$type}.xlsx");
    }

    /**
     * Download or display errors from a failed import
     * Returns JSON of all validation and processing errors
     *
     * @param Import $import The import record with errors
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function downloadErrors(Import $import)
    {
        try {
            if (empty($import->errors)) {
                return back()->with('error', 'لا توجد أخطاء للتصدير');
            }

            return response()->json(['errors' => $import->errors]);

        } catch (\Exception $e) {
            Log::error('ImportController@downloadErrors: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ');
        }
    }

    /**
     * Perform validation on the Excel file
     * Reads file, validates headers and data rows
     *
     * @param string $filePath Absolute path to the Excel file
     * @param string $type Import type (full, clients, lands, geographic, archive)
     * @return array Validation results with total, valid, invalid counts and errors
     */
    private function performValidation(string $filePath, string $type): array
    {
        $results = [
            'total' => 0,
            'valid' => 0,
            'invalid' => 0,
            'errors' => [],
            'summary' => [],
            'rows' => [],
        ];

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (count($rows) < 2) {
                $results['errors']['general'] = 'الملف فارغ أو لا يحتوي على بيانات';
                return $results;
            }

            $headers = array_map('trim', $rows[0]);

            // For archive type, map Arabic headers to English keys
            if ($type === 'archive') {
                $headers = $this->mapArabicHeaders($headers);
            } else {
                $headers = array_map('strtolower', $headers);
            }
            $requiredColumns = $this->getRequiredColumns($type);

            foreach ($requiredColumns as $col) {
                if (!in_array(strtolower($col), $headers)) {
                    $results['errors']['columns'][] = "العمود المطلوب '{$col}' غير موجود";
                }
            }

            if (!empty($results['errors']['columns'])) {
                return $results;
            }

            $dataRows = array_slice($rows, 1);
            $results['total'] = count($dataRows);

            foreach ($dataRows as $index => $row) {
                $rowNumber = $index + 2;
                $rowData = array_combine($headers, $row);
                $rowErrors = $this->validateRow($rowData, $type, $rowNumber);

                if (empty($rowErrors)) {
                    $results['valid']++;
                    $results['rows'][$rowNumber] = ['status' => 'valid', 'data' => $rowData];
                } else {
                    $results['invalid']++;
                    $results['rows'][$rowNumber] = ['status' => 'invalid', 'data' => $rowData, 'errors' => $rowErrors];
                    $results['errors']['rows'][$rowNumber] = $rowErrors;
                }
            }

            $results['summary'] = [
                'total' => $results['total'],
                'valid' => $results['valid'],
                'invalid' => $results['invalid'],
            ];

        } catch (\Exception $e) {
            $results['errors']['general'] = 'خطأ في قراءة الملف: ' . $e->getMessage();
        }

        return $results;
    }

    /**
     * Get required columns for each import type
     * Defines which columns must be present in the Excel file
     *
     * @param string $type Import type
     * @return array List of required column names
     */
    private function getRequiredColumns(string $type): array
    {
        return match ($type) {
            'full' => ['client_name', 'land_no', 'governorate'],
            'clients' => ['client_name'],
            'lands' => ['client_name', 'land_no', 'governorate'],
            'geographic' => ['governorate'],
            'archive' => ['owner_name', 'land_no'],
            default => ['client_name'],
        };
    }

    /**
     * Map Arabic headers to English keys for archive imports
     * Handles variations in spelling and spacing of Arabic column names
     *
     * Actual file format: رقم, الملف, المالك, القطعه, الحي, المنطقة, المجاورة, الاوضة, الممر, الاستند, الرف
     * Maps to: file_number, client_code, owner_name, land_no, district, zone, area, room, lane, stand, rack
     *
     * @param array $headers Array of Arabic header names from Excel
     * @return array Array of English key names
     */
    private function mapArabicHeaders(array $headers): array
    {
        $mapping = [
            // Actual file headers (with variations for spacing/typos)
            'رقم' => 'file_number',
            'الملف' => 'client_code',
            'الملف ' => 'client_code', // with trailing space
            'المالك' => 'owner_name',
            'القطعه' => 'land_no',
            'القطعة' => 'land_no',
            'الحي' => 'district',
            'الحى' => 'district',
            'المنطقة' => 'zone',
            'المجاورة' => 'area',
            'الاوضة' => 'room',
            'الاوضه' => 'room',
            'الممر' => 'lane',
            'الاستند' => 'stand',
            'الرف' => 'rack',
            // Alternative formats
            'كود' => 'client_code',
            'الاسم' => 'owner_name',
            'المحافظة' => 'governorate',
            'الوظيفة' => 'job',
            'العمر' => 'age',
            'الأسرة' => 'family',
            'الدور' => 'floor',
        ];

        $mappedHeaders = [];
        foreach ($headers as $header) {
            $trimmed = trim($header);
            $mappedHeaders[] = $mapping[$trimmed] ?? strtolower($trimmed);
        }

        return $mappedHeaders;
    }

    /**
     * Validate a single data row based on import type
     * Checks required fields and data format
     *
     * @param array $row Associative array of row data
     * @param string $type Import type
     * @param int $rowNumber Row number in Excel (for error reporting)
     * @return array Array of error messages (empty if valid)
     */
    private function validateRow(array $row, string $type, int $rowNumber): array
    {
        $errors = [];

        if (in_array($type, ['full', 'clients', 'lands'])) {
            if (empty($row['client_name'])) {
                $errors[] = 'اسم العميل مطلوب';
            }

            if (!empty($row['national_id']) && strlen($row['national_id']) !== 14) {
                $errors[] = 'الرقم القومي يجب أن يكون 14 رقم';
            }
        }

        if (in_array($type, ['full', 'lands'])) {
            if (empty($row['land_no'])) {
                $errors[] = 'رقم الأرض مطلوب';
            }
            if (empty($row['governorate'])) {
                $errors[] = 'المحافظة مطلوبة';
            }
        }

        if ($type === 'geographic') {
            if (empty($row['governorate'])) {
                $errors[] = 'المحافظة مطلوبة';
            }
        }

        if ($type === 'archive') {
            if (empty($row['owner_name'])) {
                $errors[] = 'اسم المالك مطلوب';
            }
            if (empty($row['land_no'])) {
                $errors[] = 'رقم القطعة مطلوب';
            }
        }

        return $errors;
    }

    /**
     * Generate an Excel template file for the specified import type
     * Creates a new Excel file with appropriate headers
     *
     * @param string $type Import type
     * @return string Path to the generated template file
     */
    private function generateTemplate(string $type): string
    {
        $templateDir = resource_path('templates');
        if (!is_dir($templateDir)) {
            mkdir($templateDir, 0755, true);
        }

        $templatePath = "{$templateDir}/import_template_{$type}.xlsx";

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = match ($type) {
            'full' => ['client_name', 'national_id', 'client_code', 'telephone', 'mobile', 'notes', 'land_no', 'unit_no', 'governorate', 'city', 'district', 'zone', 'area'],
            'clients' => ['client_name', 'national_id', 'client_code', 'telephone', 'mobile', 'notes'],
            'lands' => ['client_name', 'land_no', 'unit_no', 'governorate', 'city', 'district', 'zone', 'area'],
            'geographic' => ['governorate', 'city', 'district', 'zone', 'area'],
            'archive' => ['رقم', 'الملف', 'المالك', 'القطعه', 'الحي', 'المنطقة', 'المجاورة', 'الاوضة', 'الممر', 'الاستند', 'الرف'],
            default => ['client_name'],
        };

        foreach ($headers as $col => $header) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
            $sheet->setCellValue($columnLetter . '1', $header);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($templatePath);

        return $templatePath;
    }
}
