<div class="row g-3">
    <!-- Client Info Section -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <!-- Client Header -->
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block">
                        <div class="avatar avatar-xl bg-gradient-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center shadow"
                            style="width: 90px; height: 90px; font-size: 2.5rem; font-weight: 600;">
                            {{ mb_substr($client->name, 0, 1) }}
                        </div>
                    </div>
                    <h4 class="mt-3 mb-2 fw-bold">{{ $client->name }}</h4>
                </div>

                <!-- Quick Stats -->
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="card bg-success-subtle border-0 text-center p-3">
                            <div class="fs-4 fw-bold text-success">{{ $client->lands->count() }}</div>
                            <small class="text-muted">قطعه</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-primary-subtle border-0 text-center p-3">
                            <div class="fs-4 fw-bold text-primary">
                                {{ $client->lands->sum(fn($l) => $l->mainFiles->count()) }}</div>
                            <small class="text-muted">ملف</small>
                        </div>
                    </div>
                </div>

                <!-- Client Details -->
                <div class="info-list">
                    <div class="d-flex align-items-center py-3 border-bottom">
                        <div class="icon-box bg-primary-subtle text-primary rounded me-3"
                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-id"></i>
                        </div>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">الرقم القومي</small>
                            <span class="fw-semibold">{{ $client->national_id ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center py-3 border-bottom">
                        <div class="icon-box bg-info-subtle text-info rounded me-3"
                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-phone"></i>
                        </div>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">التليفون</small>
                            <span class="fw-semibold">{{ $client->telephone ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center py-3 border-bottom">
                        <div class="icon-box bg-success-subtle text-success rounded me-3"
                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-device-mobile"></i>
                        </div>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">الموبايل</small>
                            <span class="fw-semibold">{{ $client->mobile ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center py-3">
                        <div class="icon-box bg-warning-subtle text-warning rounded me-3"
                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-calendar"></i>
                        </div>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">تاريخ الإنشاء</small>
                            <span class="fw-semibold">{{ $client->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                </div>

                @if ($client->notes)
                    <div class="mt-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="ti ti-note text-muted me-2"></i>
                            <small class="text-muted fw-semibold">ملاحظات</small>
                        </div>
                        <p class="mb-0 small text-secondary">{{ $client->notes }}</p>
                    </div>
                @endif

                @if ($client->files_code && count($client->files_code) > 0)
                    <div class="mt-3 p-3 bg-info-subtle rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="ti ti-barcode text-info me-2"></i>
                            <small class="text-info fw-semibold">أكواد الملفات</small>
                        </div>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach ($client->files_code as $code)
                                <span class="badge bg-info">{{ $code }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Lands & Files Section -->
    <div class="col-md-8">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0 fw-bold"><i class="ti ti-map-2 me-2 text-primary"></i> القطع المملوكة</h5>
            <span class="badge bg-primary-subtle text-primary px-3 py-2">{{ $client->lands->count() }} قطعه</span>
        </div>

        @forelse($client->lands as $land)
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-header bg-gradient-light py-3 cursor-pointer tex" data-bs-toggle="collapse"
                    data-bs-target="#land-{{ $land->id }}"
                    style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center flex-grow-1">
                            <div class="icon-box bg-primary text-white rounded me-3"
                                style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                <i class="ti ti-building fs-5"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-semibold">{{ $land->full_address }}</h6>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="badge bg-secondary-subtle text-secondary">قطعة:
                                        {{ $land->land_no }}</span>
                                    @if ($land->unit_no)
                                        <span class="badge bg-secondary-subtle text-secondary">وحدة:
                                            {{ $land->unit_no }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary px-3 py-2">{{ $land->mainFiles->count() }} ملف</span>
                            <i class="ti ti-chevron-down text-muted"></i>
                        </div>
                    </div>
                </div>
                <div class="collapse" id="land-{{ $land->id }}">
                    <div class="card-body p-3">
                        @if ($land->mainFiles->count() > 0)
                            <div class="row g-3">
                                @foreach ($land->mainFiles as $file)
                                    @php
                                        $hasDocument = $file->getFirstMedia('documents') !== null;
                                        $gradients = [
                                            'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                            'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
                                            'linear-gradient(135deg, #ee0979 0%, #ff6a00 100%)',
                                            'linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%)',
                                            'linear-gradient(135deg, #cc2b5e 0%, #753a88 100%)',
                                            'linear-gradient(135deg, #56ab2f 0%, #a8e063 100%)',
                                        ];
                                    @endphp
                                    <div class="col-12">
                                        <div class="card border-0 shadow"
                                            style="border-radius: 16px; overflow: hidden;">
                                            <!-- Main File Header -->
                                            <div class="card-header py-3 px-4"
                                                style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-white bg-opacity-25 rounded-circle me-3"
                                                            style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="ti ti-file-type-pdf text-white fs-4"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0 fw-bold text-white">{{ $file->file_name }}
                                                            </h5>
                                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                                <span class="badge bg-white bg-opacity-25 text-white">
                                                                    <i
                                                                        class="ti ti-files me-1"></i>{{ $file->pages_count }}
                                                                    صفحة
                                                                </span>
                                                                @if ($file->subFiles && $file->subFiles->count() > 0)
                                                                    <span class="badge bg-warning text-dark">
                                                                        <i
                                                                            class="ti ti-folder me-1"></i>{{ $file->subFiles->count() }}
                                                                        مستند
                                                                    </span>
                                                                @endif
                                                                <span
                                                                    class="badge {{ $file->status_badge['class'] }}">{{ $file->status_badge['text'] }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="btn-group">
                                                        @if ($hasDocument)
                                                            <button class="btn btn-light"
                                                                onclick="viewFile({{ $file->id }})"
                                                                title="عرض">
                                                                <i class="ti ti-eye"></i>
                                                            </button>
                                                            <a href="{{ route('admin.files.download', $file) }}"
                                                                class="btn btn-light" title="تحميل">
                                                                <i class="ti ti-download"></i>
                                                            </a>
                                                        @else
                                                            <button class="btn btn-warning"
                                                                onclick="openUploadForFile({{ $file->id }}, '{{ $file->file_name }}')"
                                                                title="رفع ملف">
                                                                <i class="ti ti-upload me-1"></i> رفع
                                                            </button>
                                                        @endif
                                                        @if ($file->barcode)
                                                            <button class="btn btn-dark"
                                                                onclick="printBarcode('{{ $file->barcode }}', '{{ $file->file_name }}')"
                                                                title="طباعة الباركود">
                                                                <i class="ti ti-barcode"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Subfiles as Grid -->
                                            <div class="card-body p-4" style="background: #fafbfc;">
                                                @if ($file->subFiles && $file->subFiles->count() > 0)
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <h6 class="mb-0 fw-bold text-primary">
                                                            <i class="ti ti-folders me-2"></i>المستندات المستخرجة
                                                            ({{ $file->subFiles->count() }})
                                                        </h6>
                                                    </div>
                                                    <div class="row g-3">
                                                        @foreach ($file->subFiles as $subFile)
                                                            @php
                                                                $subFileItem = $subFile->fileItems->first();
                                                                $subFileMedia = $subFile->getFirstMedia('documents');
                                                                $subPdfUrl = $subFileMedia ? $subFileMedia->getUrl() : null;
                                                                $itemName = $subFileItem->item->name ?? 'غير محدد';
                                                                $itemDescription = $subFileItem->item->description ?? '';
                                                                $pagesCount = $subFile->pages_count ?? 0;
                                                                $fromPage = $subFileItem->from_page ?? '-';
                                                                $toPage = $subFileItem->to_page ?? '-';
                                                            @endphp
                                                            <div class="col-md-2 col-sm-4 col-6">
                                                                <div class="card border h-100 shadow-sm hover-shadow" style="transition: all 0.3s;">
                                                                    <div class="card-body p-3">
                                                                        <div class="d-flex align-items-start mb-2">
                                                                            <div class="avatar avatar-sm bg-primary-subtle text-primary rounded me-2 flex-shrink-0">
                                                                                <i class="ti ti-file-text"></i>
                                                                            </div>
                                                                            <div class="flex-grow-1 min-w-0">
                                                                                <h6 class="mb-0 text-truncate small fw-bold" title="{{ $itemName }}">{{ $itemName }}</h6>
                                                                                @if($itemDescription)
                                                                                    <small class="text-muted d-block text-truncate" title="{{ $itemDescription }}">{{ $itemDescription }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="mb-2">
                                                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                                                <small class="text-muted">الصفحات:</small>
                                                                                <span class="badge bg-info-subtle text-info small">{{ $fromPage }} - {{ $toPage }}</span>
                                                                            </div>
                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                <small class="text-muted">العدد:</small>
                                                                                <span class="badge bg-success-subtle text-success small">{{ $pagesCount }}</span>
                                                                            </div>
                                                                        </div>
                                                                        @if ($subPdfUrl)
                                                                            <div class="d-flex gap-1 justify-content-center">
                                                                                <button type="button" class="btn btn-sm btn-info flex-fill" title="عرض" onclick="viewFile({{ $subFile->id }})">
                                                                                    <i class="ti ti-eye"></i>
                                                                                </button>
                                                                                <a href="{{ $subPdfUrl }}" target="_blank" class="btn btn-sm btn-secondary" title="فتح">
                                                                                    <i class="ti ti-external-link"></i>
                                                                                </a>
                                                                                <a href="{{ $subPdfUrl }}" download class="btn btn-sm btn-primary" title="تحميل">
                                                                                    <i class="ti ti-download"></i>
                                                                                </a>
                                                                            </div>
                                                                        @else
                                                                            <div class="text-center">
                                                                                <small class="text-muted">غير متوفر</small>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="text-center text-muted py-3">
                                                        <i class="ti ti-folder-off fs-3 d-block mb-2 opacity-50"></i>
                                                        <small>لا توجد مستندات مستخرجة</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="ti ti-file-off fs-1 d-block mb-3 opacity-50"></i>
                                <p class="mb-0">لا توجد ملفات لهذه القطعة</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="ti ti-map-off fs-1 d-block mb-3 text-muted opacity-50"></i>
                    <h6 class="text-muted">لا توجد قطع مسجلة لهذا العميل</h6>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- File View Modal (Nested) -->
<div class="modal fade" id="fileViewModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileViewModalTitle">عرض الملف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="fileViewFrame" style="width: 100%; height: 80vh; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <a href="#" id="fileDownloadBtn" class="btn btn-success"><i class="ti ti-download me-1"></i>
                    تحميل</a>
            </div>
        </div>
    </div>
</div>

<!-- Barcode Print Modal -->
<div class="modal fade" id="barcodePrintModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="ti ti-barcode me-2"></i>طباعة الباركود
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <h6 id="barcodeFileName" class="text-muted mb-3"></h6>
                    <div id="barcodeContainer" class="d-flex justify-content-center align-items-center"
                        style="min-height: 150px;">
                        <!-- Barcode will be generated here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>إغلاق
                </button>
                <button type="button" class="btn btn-dark" onclick="printBarcodeContent()">
                    <i class="ti ti-printer me-1"></i>طباعة
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Show File Modal (from files module) -->
<div class="modal fade" id="showFileModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-header-info">
                <h5 class="modal-title">
                    <i class="ti ti-file-text"></i>
                    <span id="fileModalTitle">تفاصيل الملف</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="fileModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                    <p class="mt-3 text-muted">جاري تحميل بيانات الملف...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- File Upload Modal -->
<div class="modal fade" id="fileUploadModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="height: 100vh;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ti ti-upload me-2"></i>رفع ملف PDF
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="fileUploadForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="file_id" id="uploadFileId">
                <div class="modal-body p-0" style="height: calc(100vh - 140px); overflow: hidden;">
                    <div class="row g-0" style="height: 100%;">
                        <!-- Left Side: PDF Preview -->
                        <div class="col-md-5 border-end" style="height: 100%; overflow-y: auto; background: #f8f9fa;">
                            <div class="p-4">
                                <h6 class="mb-3 text-muted">
                                    <i class="ti ti-file-text me-2"></i>
                                    معاينة الصفحة الأولى من الملف
                                </h6>
                                <div id="uploadPdfPreviewContainer" class="text-center">
                                    <div class="alert alert-info" id="uploadPdfPlaceholder">
                                        <i class="ti ti-upload fs-1 mb-3 d-block"></i>
                                        <p class="mb-0">قم برفع ملف PDF لمعاينة الصفحة الأولى</p>
                                    </div>
                                    <canvas id="uploadPdfCanvas" class="border rounded shadow-sm"
                                        style="max-width: 100%; display: none;"></canvas>
                                    <div id="uploadPdfLoading" class="d-none">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">جاري التحميل...</span>
                                        </div>
                                        <p class="mt-2 text-muted">جاري تحميل المعاينة...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Form -->
                        <div class="col-md-7" style="height: 100%; overflow-y: auto;">
                            <div class="p-4">
                                <div class="row g-3">
                                    <!-- File Info Alert -->
                                    <div class="col-12">
                                        <div class="alert alert-info d-flex align-items-center mb-3">
                                            <i class="ti ti-info-circle fs-4 me-3"></i>
                                            <div>
                                                <strong>اسم الملف:</strong> <span id="uploadFileName"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PDF File Upload -->
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            <i class="ti ti-file-upload me-2 text-primary"></i>
                                            ملف PDF <span class="text-danger">*</span>
                                        </label>
                                        <input type="file" name="document" id="uploadPdfFileInput"
                                            class="form-control form-control-lg" accept=".pdf" required
                                            onchange="previewUploadPDF(this)">
                                        <small class="text-muted">
                                            <i class="ti ti-info-circle me-1"></i>
                                            الحد الأقصى: 50 ميجابايت
                                        </small>
                                    </div>

                                    <!-- Content Types Section -->
                                    <div class="col-12">
                                        <label class="form-label d-flex align-items-center justify-content-between">
                                            <span class="fw-semibold">
                                                <i class="ti ti-checkbox me-2 text-primary"></i>
                                                أنواع المحتوى (حدد نوع المحتوى ونطاق الصفحات)
                                            </span>
                                            <span class="badge bg-primary" id="uploadSelectedItemsCount">0 محدد</span>
                                        </label>

                                        <!-- Search Box -->
                                        <div class="mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="ti ti-search text-muted"></i>
                                                </span>
                                                <input type="text" id="uploadItemSearchInput"
                                                    class="form-control border-start-0 ps-0"
                                                    placeholder="ابحث عن نوع المحتوى..."
                                                    onkeyup="filterUploadItems()">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="clearUploadItemSearch()">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Quick Actions -->
                                        <div class="mb-3 d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="selectAllUploadItems()">
                                                <i class="ti ti-checkbox me-1"></i>تحديد الكل
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="deselectAllUploadItems()">
                                                <i class="ti ti-square me-1"></i>إلغاء الكل
                                            </button>
                                        </div>

                                        <!-- Items Table -->
                                        <div class="border rounded"
                                            style="max-height: 400px; overflow-y: auto; background: white;">
                                            <table class="table table-bordered table-hover mb-0" id="uploadItemsTable"
                                                style="direction: rtl">
                                                <thead class="table-light"
                                                    style="position: sticky; top: 0; z-index: 10;">
                                                    <tr>
                                                        <th class="text-center" style="width: 30%;">توصيف المستند</th>
                                                        <th class="text-center" style="width: 10%;">من</th>
                                                        <th class="text-center" style="width: 10%;">إلى</th>
                                                        <th class="text-center" style="width: 30%;">توصيف المستند</th>
                                                        <th class="text-center" style="width: 10%;">من</th>
                                                        <th class="text-center" style="width: 10%;">إلى</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="uploadItemsContainer">
                                                    @php
                                                        $allItems = \App\Models\Item::orderBy('order')->get();
                                                        $rightItems = $allItems->where('order', '<=', 37)->values();
                                                        $leftItems = $allItems->where('order', '>', 37)->values();
                                                        $maxRows = max($rightItems->count(), $leftItems->count());
                                                    @endphp
                                                    @for ($i = 0; $i < $maxRows; $i++)
                                                        @php
                                                            $rightItem = $rightItems->get($i);
                                                            $leftItem = $leftItems->get($i);
                                                            $searchName = strtolower(
                                                                ($rightItem->name ?? '') .
                                                                    ' ' .
                                                                    ($leftItem->name ?? ''),
                                                            );
                                                        @endphp
                                                        <tr class="upload-item-row"
                                                            data-item-name="{{ $searchName }}">
                                                            @if ($rightItem)
                                                                <td class="align-middle">
                                                                    <div class="form-check mb-0">
                                                                        <input
                                                                            class="form-check-input upload-item-checkbox"
                                                                            type="checkbox"
                                                                            data-item-id="{{ $rightItem->id }}"
                                                                            id="uploadItem{{ $rightItem->id }}"
                                                                            onchange="toggleUploadPageRange({{ $rightItem->id }})">
                                                                        <label class="form-check-label cursor-pointer"
                                                                            for="uploadItem{{ $rightItem->id }}">
                                                                            {{ $rightItem->name }}
                                                                        </label>
                                                                    </div>
                                                                    <input type="hidden"
                                                                        name="items[{{ $rightItem->id }}][item_id]"
                                                                        value="{{ $rightItem->id }}">
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <input type="number"
                                                                        name="items[{{ $rightItem->id }}][from_page]"
                                                                        class="form-control form-control-sm text-center d-none page-input"
                                                                        id="uploadFromPage{{ $rightItem->id }}"
                                                                        min="1" placeholder="من">
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <input type="number"
                                                                        name="items[{{ $rightItem->id }}][to_page]"
                                                                        class="form-control form-control-sm text-center d-none page-input"
                                                                        id="uploadToPage{{ $rightItem->id }}"
                                                                        min="1" placeholder="إلى">
                                                                </td>
                                                            @else
                                                                <td class="align-middle"></td>
                                                                <td class="text-center align-middle"></td>
                                                                <td class="text-center align-middle"></td>
                                                            @endif
                                                            @if ($leftItem)
                                                                <td class="align-middle">
                                                                    <div class="form-check mb-0">
                                                                        <input
                                                                            class="form-check-input upload-item-checkbox"
                                                                            type="checkbox"
                                                                            data-item-id="{{ $leftItem->id }}"
                                                                            id="uploadItem{{ $leftItem->id }}"
                                                                            onchange="toggleUploadPageRange({{ $leftItem->id }})">
                                                                        <label class="form-check-label cursor-pointer"
                                                                            for="uploadItem{{ $leftItem->id }}">
                                                                            {{ $leftItem->name }}
                                                                        </label>
                                                                    </div>
                                                                    <input type="hidden"
                                                                        name="items[{{ $leftItem->id }}][item_id]"
                                                                        value="{{ $leftItem->id }}">
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <input type="number"
                                                                        name="items[{{ $leftItem->id }}][from_page]"
                                                                        class="form-control form-control-sm text-center d-none page-input"
                                                                        id="uploadFromPage{{ $leftItem->id }}"
                                                                        min="1" placeholder="من">
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <input type="number"
                                                                        name="items[{{ $leftItem->id }}][to_page]"
                                                                        class="form-control form-control-sm text-center d-none page-input"
                                                                        id="uploadToPage{{ $leftItem->id }}"
                                                                        min="1" placeholder="إلى">
                                                                </td>
                                                            @else
                                                                <td class="align-middle"></td>
                                                                <td class="text-center align-middle"></td>
                                                                <td class="text-center align-middle"></td>
                                                            @endif
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>
                                        <small class="text-muted mt-2 d-block">
                                            <i class="ti ti-bulb text-warning me-1"></i>
                                            عند تحديد نطاق الصفحات، سيتم قص الصفحات المحددة وإنشاء ملف فرعي جديد لكل نوع
                                            محتوى
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-upload me-1"></i>رفع ومعالجة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PDF.js Library for PDF Preview -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<!-- JsBarcode Library -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<script>
    // Configure PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    function viewFile(fileId) {
        const viewUrl = "{{ route('admin.files.view', ':id') }}".replace(':id', fileId);
        const downloadUrl = "{{ route('admin.files.download', ':id') }}".replace(':id', fileId);

        document.getElementById('fileViewFrame').src = viewUrl;
        document.getElementById('fileDownloadBtn').href = downloadUrl;

        const modal = new bootstrap.Modal(document.getElementById('fileViewModal'));
        modal.show();
    }

    function toggleSubFiles(fileId) {
        const row = document.getElementById(`subfiles-${fileId}`);
        if (row.classList.contains('d-none')) {
            row.classList.remove('d-none');
        } else {
            row.classList.add('d-none');
        }
    }

    function togglePages(fileId) {
        const row = document.getElementById(`pages-${fileId}`);
        const container = document.getElementById(`pages-container-${fileId}`);

        if (row.classList.contains('d-none')) {
            row.classList.remove('d-none');

            // Load pages
            fetch("{{ url('admin/files') }}/" + fileId + "/pages")
                .then(res => res.json())
                .then(data => {
                    if (data.pages && data.pages.length > 0) {
                        container.innerHTML = data.pages.map(page => `
                            <div class="col-md-3 col-sm-4 col-6">
                                <div class="card border h-100">
                                    <div class="card-body p-2 text-center">
                                        <div class="bg-light rounded mb-2 d-flex align-items-center justify-content-center" style="height: 100px;">
                                            <i class="ti ti-file-text fs-1 text-muted"></i>
                                        </div>
                                        <small class="d-block">صفحة ${page.page_number}</small>
                                        <div class="btn-group btn-group-sm mt-1">
                                            <button class="btn btn-outline-info btn-sm" onclick="viewFile(${page.id})"><i class="ti ti-eye"></i></button>
                                            <a href="{{ url('admin/files') }}/${page.id}/download" class="btn btn-outline-success btn-sm"><i class="ti ti-download"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        container.innerHTML =
                            '<div class="col-12 text-center text-muted">لا توجد صفحات منفصلة</div>';
                    }
                })
                .catch(err => {
                    container.innerHTML =
                        '<div class="col-12 text-center text-danger">حدث خطأ أثناء تحميل الصفحات</div>';
                });
        } else {
            row.classList.add('d-none');
        }
    }

    // Barcode Print Functions
    function printBarcode(barcode, fileName) {
        document.getElementById('barcodeFileName').textContent = fileName;
        const container = document.getElementById('barcodeContainer');
        container.innerHTML = '<svg id="barcodeSvg"></svg>';

        try {
            JsBarcode("#barcodeSvg", barcode, {
                format: "CODE128",
                width: 2,
                height: 100,
                displayValue: true,
                fontSize: 20,
                margin: 10
            });

            const modal = new bootstrap.Modal(document.getElementById('barcodePrintModal'));
            modal.show();
        } catch (error) {
            console.error('Barcode generation error:', error);
            showToast.error('فشل إنشاء الباركود');
        }
    }

    function printBarcodeContent() {
        const printWindow = window.open('', '', 'width=600,height=400');
        const barcodeHtml = document.getElementById('barcodeContainer').innerHTML;
        const fileName = document.getElementById('barcodeFileName').textContent;

        printWindow.document.write(`
            <html dir="rtl">
            <head>
                <title>طباعة الباركود</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        padding: 20px;
                    }
                    h3 {
                        margin-bottom: 20px;
                        text-align: center;
                    }
                    @media print {
                        @page {
                            margin: 1cm;
                        }
                    }
                </style>
            </head>
            <body>
                <h3>${fileName}</h3>
                ${barcodeHtml}
                <script>
                    window.onload = function() {
                        window.print();
                        window.onafterprint = function() {
                            window.close();
                        };
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    // Upload Modal Functions
    function openUploadForFile(fileId, fileName) {
        document.getElementById('uploadFileId').value = fileId;
        document.getElementById('uploadFileName').textContent = fileName;
        document.getElementById('fileUploadForm').reset();
        document.getElementById('uploadFileId').value = fileId;

        // Reset all checkboxes and page inputs
        document.querySelectorAll('.upload-item-checkbox').forEach(cb => {
            cb.checked = false;
        });
        document.querySelectorAll('#uploadItemsTable .page-input').forEach(input => {
            input.classList.add('d-none');
            input.value = '';
        });
        updateUploadItemsCount();

        const modal = new bootstrap.Modal(document.getElementById('fileUploadModal'));
        modal.show();
    }

    function toggleUploadPageRange(itemId) {
        const checkbox = document.getElementById(`uploadItem${itemId}`);
        const fromPage = document.getElementById(`uploadFromPage${itemId}`);
        const toPage = document.getElementById(`uploadToPage${itemId}`);

        if (checkbox.checked) {
            fromPage.classList.remove('d-none');
            toPage.classList.remove('d-none');
        } else {
            fromPage.classList.add('d-none');
            toPage.classList.add('d-none');
            fromPage.value = '';
            toPage.value = '';
        }
        updateUploadItemsCount();
    }

    function updateUploadItemsCount() {
        const count = document.querySelectorAll('.upload-item-checkbox:checked').length;
        document.getElementById('uploadSelectedItemsCount').textContent = count + ' محدد';
    }

    function filterUploadItems() {
        const searchValue = document.getElementById('uploadItemSearchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.upload-item-row');

        rows.forEach(row => {
            const itemName = row.getAttribute('data-item-name');
            if (itemName.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function clearUploadItemSearch() {
        document.getElementById('uploadItemSearchInput').value = '';
        filterUploadItems();
    }

    function selectAllUploadItems() {
        document.querySelectorAll('.upload-item-checkbox').forEach(checkbox => {
            if (!checkbox.checked) {
                checkbox.checked = true;
                toggleUploadPageRange(checkbox.getAttribute('data-item-id'));
            }
        });
    }

    function deselectAllUploadItems() {
        document.querySelectorAll('.upload-item-checkbox').forEach(checkbox => {
            if (checkbox.checked) {
                checkbox.checked = false;
                toggleUploadPageRange(checkbox.getAttribute('data-item-id'));
            }
        });
    }

    function previewUploadPDF(input) {
        const file = input.files[0];
        if (!file || file.type !== 'application/pdf') {
            return;
        }

        const fileSize = file.size / 1024 / 1024;
        if (fileSize > 50) {
            showToast.error('حجم الملف يتجاوز 50 ميجابايت');
            input.value = '';
            return;
        }

        const placeholder = document.getElementById('uploadPdfPlaceholder');
        const canvas = document.getElementById('uploadPdfCanvas');
        const loading = document.getElementById('uploadPdfLoading');

        // Show loading
        placeholder.classList.add('d-none');
        canvas.style.display = 'none';
        loading.classList.remove('d-none');

        const fileReader = new FileReader();
        fileReader.onload = function() {
            const typedarray = new Uint8Array(this.result);

            // Load PDF
            pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                // Get first page
                pdf.getPage(1).then(function(page) {
                    const viewport = page.getViewport({
                        scale: 1.5
                    });
                    const context = canvas.getContext('2d');

                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page
                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };

                    page.render(renderContext).promise.then(function() {
                        loading.classList.add('d-none');
                        canvas.style.display = 'block';
                    });
                });
            }).catch(function(error) {
                console.error('Error loading PDF:', error);
                loading.classList.add('d-none');
                placeholder.classList.remove('d-none');
                placeholder.innerHTML =
                    '<i class="ti ti-alert-circle fs-1 mb-3 d-block text-danger"></i><p class="mb-0 text-danger">خطأ في تحميل الملف</p>';
            });
        };

        fileReader.readAsArrayBuffer(file);
    }

    // Reset preview when modal is closed
    document.getElementById('fileUploadModal')?.addEventListener('hidden.bs.modal', function() {
        const placeholder = document.getElementById('uploadPdfPlaceholder');
        const canvas = document.getElementById('uploadPdfCanvas');
        const loading = document.getElementById('uploadPdfLoading');
        const fileInput = document.getElementById('uploadPdfFileInput');

        if (canvas) canvas.style.display = 'none';
        if (loading) loading.classList.add('d-none');
        if (placeholder) {
            placeholder.classList.remove('d-none');
            placeholder.innerHTML =
                '<i class="ti ti-upload fs-1 mb-3 d-block"></i><p class="mb-0">قم برفع ملف PDF لمعاينة الصفحة الأولى</p>';
        }
        if (fileInput) fileInput.value = '';
    });

    // Handle Upload Form Submission
    document.getElementById('fileUploadForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const fileId = document.getElementById('uploadFileId').value;
        const submitBtn = this.querySelector('button[type="submit"]');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الرفع...';

        fetch(`/admin/files/${fileId}/upload-document`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast.success(data.message || 'تم رفع الملف بنجاح');
                    bootstrap.Modal.getInstance(document.getElementById('fileUploadModal')).hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast.error(data.message || 'حدث خطأ أثناء رفع الملف');
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                showToast.error('حدث خطأ في الاتصال بالخادم');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ti ti-upload me-1"></i>رفع ومعالجة';
            });
    });

    // Show File function (from files module)
    function showFile(id) {
        const modal = new bootstrap.Modal(document.getElementById('showFileModal'));
        modal.show();

        fetch(`{{ url('admin/files') }}/${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderFileDetails(data.file, data.pdf_url);
                } else {
                    document.getElementById('fileModalBody').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="ti ti-alert-circle me-2"></i>
                            ${data.error || 'حدث خطأ أثناء تحميل البيانات'}
                        </div>
                    `;
                }
            })
            .catch(err => {
                document.getElementById('fileModalBody').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i>
                        حدث خطأ في الاتصال
                    </div>
                `;
            });
    }

    function renderFileDetails(file, pdfUrl) {
        document.getElementById('fileModalTitle').textContent = file.file_name;
        const modalBody = document.getElementById('fileModalBody');

        let html = `
            <div class="row g-4 mb-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="ti ti-info-circle me-2"></i>معلومات الملف</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12 mb-2">
                                    <div class="d-flex align-items-center justify-content-between p-3 bg-dark text-white rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-white text-dark rounded me-3">
                                                <i class="ti ti-barcode"></i>
                                            </div>
                                            <div>
                                                <small class="text-white-50 d-block">الباركود</small>
                                                <strong class="font-monospace fs-5">${file.barcode || 'غير متوفر'}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-primary-subtle text-primary rounded me-3">
                                            <i class="ti ti-user"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">العميل</small>
                                            <strong>${file.client?.name || 'غير محدد'}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-warning-subtle text-warning rounded me-3">
                                            <i class="ti ti-file"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">عدد الصفحات</small>
                                            <strong>${file.pages_count} صفحة</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="ti ti-download me-2"></i>تحميل الملف</h6>
                        </div>
                        <div class="card-body text-center">
                            ${pdfUrl ? `
                                <div class="mb-3">
                                    <i class="ti ti-file-type-pdf text-danger" style="font-size: 3rem;"></i>
                                </div>
                                <a href="${pdfUrl}" target="_blank" class="btn btn-outline-secondary w-100 mb-2">
                                    <i class="ti ti-external-link me-2"></i>فتح في تبويب جديد
                                </a>
                                <a href="${pdfUrl}" download class="btn btn-outline-primary w-100">
                                    <i class="ti ti-download me-2"></i>تحميل
                                </a>
                            ` : '<p class="text-muted">الملف غير متوفر</p>'}
                        </div>
                    </div>
                </div>
            </div>
        `;

        modalBody.innerHTML = html;
    }
</script>
