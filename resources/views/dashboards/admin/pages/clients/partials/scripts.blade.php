<script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">

<!-- PDF.js Library for PDF Preview -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<!-- JsBarcode Library -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<!-- Barcode Search Result Modal -->
<div class="modal fade" id="barcodeResultModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="ti ti-barcode me-2"></i>نتيجة البحث بالباركود
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="barcodeResultBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-dark" role="status"></div>
                    <p class="mt-3 text-muted">جاري البحث...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>إغلاق
                </button>
                <button type="button" class="btn btn-dark" id="viewClientFromBarcodeBtn" style="display:none;">
                    <i class="ti ti-user me-1"></i>عرض العميل
                </button>
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
                    <div id="barcodeContainer" class="d-flex justify-content-center align-items-center" style="min-height: 150px;">
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

<!-- File Upload Modal for Client Index -->
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
                                    <canvas id="uploadPdfCanvas" class="border rounded shadow-sm" style="max-width: 100%; display: none;"></canvas>
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
                                        <input type="file" name="document" id="uploadPdfFileInput" class="form-control form-control-lg" accept=".pdf" required onchange="previewUploadPDF(this)">
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
                                                <input type="text" id="uploadItemSearchInput" class="form-control border-start-0 ps-0" placeholder="ابحث عن نوع المحتوى..." onkeyup="filterUploadItems()">
                                                <button class="btn btn-outline-secondary" type="button" onclick="clearUploadItemSearch()">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Quick Actions -->
                                        <div class="mb-3 d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllUploadItems()">
                                                <i class="ti ti-checkbox me-1"></i>تحديد الكل
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllUploadItems()">
                                                <i class="ti ti-square me-1"></i>إلغاء الكل
                                            </button>
                                        </div>

                                        <!-- Items Table -->
                                        <div class="border rounded" style="max-height: 400px; overflow-y: auto; background: white;">
                                            <table class="table table-bordered table-hover mb-0" id="uploadItemsTable" style="direction: rtl">
                                                <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
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
                                                    @for($i = 0; $i < $maxRows; $i++)
                                                        @php
                                                            $rightItem = $rightItems->get($i);
                                                            $leftItem = $leftItems->get($i);
                                                            $searchName = strtolower(($rightItem->name ?? '') . ' ' . ($leftItem->name ?? ''));
                                                        @endphp
                                                        <tr class="upload-item-row" data-item-name="{{ $searchName }}">
                                                            @if($rightItem)
                                                                <td class="align-middle">
                                                                    <div class="form-check mb-0">
                                                                        <input class="form-check-input upload-item-checkbox" type="checkbox" data-item-id="{{ $rightItem->id }}" id="uploadItem{{ $rightItem->id }}" onchange="toggleUploadPageRange({{ $rightItem->id }})">
                                                                        <label class="form-check-label cursor-pointer" for="uploadItem{{ $rightItem->id }}">
                                                                            {{ $rightItem->name }}
                                                                        </label>
                                                                    </div>
                                                                    <input type="hidden" name="items[{{ $rightItem->id }}][item_id]" value="{{ $rightItem->id }}">
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <input type="number" name="items[{{ $rightItem->id }}][from_page]" class="form-control form-control-sm text-center d-none page-input" id="uploadFromPage{{ $rightItem->id }}" min="1" placeholder="من">
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <input type="number" name="items[{{ $rightItem->id }}][to_page]" class="form-control form-control-sm text-center d-none page-input" id="uploadToPage{{ $rightItem->id }}" min="1" placeholder="إلى">
                                                                </td>
                                                            @else
                                                                <td class="align-middle"></td>
                                                                <td class="text-center align-middle"></td>
                                                                <td class="text-center align-middle"></td>
                                                            @endif
                                                            @if($leftItem)
                                                                <td class="align-middle">
                                                                    <div class="form-check mb-0">
                                                                        <input class="form-check-input upload-item-checkbox" type="checkbox" data-item-id="{{ $leftItem->id }}" id="uploadItem{{ $leftItem->id }}" onchange="toggleUploadPageRange({{ $leftItem->id }})">
                                                                        <label class="form-check-label cursor-pointer" for="uploadItem{{ $leftItem->id }}">
                                                                            {{ $leftItem->name }}
                                                                        </label>
                                                                    </div>
                                                                    <input type="hidden" name="items[{{ $leftItem->id }}][item_id]" value="{{ $leftItem->id }}">
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <input type="number" name="items[{{ $leftItem->id }}][from_page]" class="form-control form-control-sm text-center d-none page-input" id="uploadFromPage{{ $leftItem->id }}" min="1" placeholder="من">
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <input type="number" name="items[{{ $leftItem->id }}][to_page]" class="form-control form-control-sm text-center d-none page-input" id="uploadToPage{{ $leftItem->id }}" min="1" placeholder="إلى">
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
                                            عند تحديد نطاق الصفحات، سيتم قص الصفحات المحددة وإنشاء ملف فرعي جديد لكل نوع محتوى
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

<script>
// Configure PDF.js worker
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
// Toast Notification Helper
const showToast = {
    success: (message) => {
        iziToast.success({
            title: 'نجح',
            message: message,
            position: 'topRight',
            rtl: true,
            timeout: 3000,
            transitionIn: 'fadeInDown',
            transitionOut: 'fadeOutUp'
        });
    },
    error: (message) => {
        iziToast.error({
            title: 'خطأ',
            message: message,
            position: 'topRight',
            rtl: true,
            timeout: 5000,
            transitionIn: 'fadeInDown',
            transitionOut: 'fadeOutUp'
        });
    },
    warning: (message) => {
        iziToast.warning({
            title: 'تحذير',
            message: message,
            position: 'topRight',
            rtl: true,
            timeout: 4000
        });
    },
    info: (message) => {
        iziToast.info({
            title: 'معلومة',
            message: message,
            position: 'topRight',
            rtl: true,
            timeout: 3000
        });
    }
};

// Clear form errors
function clearFormErrors(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    }
}

// Display form errors
function displayFormErrors(formId, errors) {
    clearFormErrors(formId);
    Object.keys(errors).forEach(field => {
        const input = document.querySelector(`#${formId} [name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = errors[field][0];
            }
        }
    });
}

// ==================== Barcode Functions ====================
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

// Print all barcodes for a client's files
function printClientBarcodes(clientId) {
    fetch(`{{ url('admin/clients') }}/${clientId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.client) {
                const client = data.client;
                const files = client.files || [];

                if (files.length === 0) {
                    alert('لا توجد ملفات لهذا العميل');
                    return;
                }

                // Create print window
                const printWindow = window.open('', '', 'width=800,height=600');

                let barcodesHtml = '';
                files.forEach((file, index) => {
                    const pageCount = file.items ? file.items.length : 0;
                    barcodesHtml += `
                        <div class="barcode-item" style="page-break-inside: avoid; margin-bottom: 30px;">
                            <h4 style="text-align: center; margin-bottom: 10px;">ملف: ${file.file_name}</h4>
                            <p style="text-align: center; margin-bottom: 10px;">عدد الصفحات: ${pageCount}</p>
                            <div style="text-align: center;">
                                <svg id="barcode-${index}"></svg>
                            </div>
                            ${index < files.length - 1 ? '<hr style="margin: 20px 0;">' : ''}
                        </div>
                    `;
                });

                printWindow.document.write(`
                    <html dir="rtl">
                    <head>
                        <title>طباعة باركودات - ${client.name}</title>
                        <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                padding: 20px;
                            }
                            h2 {
                                text-align: center;
                                margin-bottom: 30px;
                            }
                            .barcode-item {
                                margin-bottom: 30px;
                            }
                            @media print {
                                body { margin: 0; padding: 10px; }
                                .no-print { display: none; }
                            }
                        </style>
                    </head>
                    <body>
                        <h2>باركودات ملفات العميل: ${client.name}</h2>
                        ${barcodesHtml}
                        <div class="no-print" style="text-align: center; margin-top: 20px;">
                            <button onclick="window.print()" style="padding: 10px 30px; font-size: 16px; cursor: pointer;">طباعة</button>
                            <button onclick="window.close()" style="padding: 10px 30px; font-size: 16px; cursor: pointer; margin-right: 10px;">إغلاق</button>
                        </div>
                        <script>
                            window.onload = function() {
                                ${files.map((file, index) => `
                                    JsBarcode("#barcode-${index}", "${file.barcode}", {
                                        format: "CODE128",
                                        width: 2,
                                        height: 80,
                                        displayValue: true,
                                        fontSize: 18,
                                        margin: 10
                                    });
                                `).join('\n')}
                            };
                        <\/script>
                    </body>
                    </html>
                `);
                printWindow.document.close();
            } else {
                alert('فشل تحميل بيانات العميل');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحميل البيانات');
        });
}

// Bulk print barcodes for selected clients
function bulkPrintBarcodes() {
    const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
    const clientIds = Array.from(selectedCheckboxes).map(cb => cb.value);

    if (clientIds.length === 0) {
        alert('الرجاء تحديد عملاء لطباعة باركوداتهم');
        return;
    }

    // Show modal with loading state
    const modal = new bootstrap.Modal(document.getElementById('bulkBarcodePrintModal'));
    modal.show();

    document.getElementById('barcodeModalTitle').textContent = `طباعة باركودات - ${clientIds.length} عميل`;
    document.getElementById('bulkBarcodeContent').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
            <p class="mt-3 text-muted fs-5">جاري تحميل بيانات ${clientIds.length} عميل...</p>
        </div>
    `;

    // Fetch all clients data
    const fetchPromises = clientIds.map(id =>
        fetch(`{{ url('admin/clients') }}/${id}`)
            .then(response => response.json())
            .then(data => data.success ? data.client : null)
    );

    Promise.all(fetchPromises)
        .then(clients => {
            // Filter out any failed requests
            const validClients = clients.filter(c => c !== null);

            if (validClients.length === 0) {
                alert('فشل تحميل بيانات العملاء');
                return;
            }

            // Collect all files from all clients
            let allBarcodes = [];
            validClients.forEach(client => {
                const files = client.main_files || client.files || [];
                const lands = client.lands || [];

                // Build land addresses string
                let landAddresses = '';
                if (lands.length > 0) {
                    landAddresses = lands.map(land => {
                        let address = '';
                        if (land.district) address += land.district.name;
                        if (land.zone) address += (address ? ', ' : '') + land.zone.name;
                        if (land.area) address += (address ? ', ' : '') + land.area.name;
                        if (land.land_no) address += (address ? '<br>' : '') + `<strong>قطعة: ${land.land_no}</strong>`;
                        return address || 'غير محدد';
                    }).join('<hr style="margin: 5px 0; border-color: #ddd;">');
                } else {
                    landAddresses = 'لا توجد قطع';
                }

                // If client has files, add them
                if (files.length > 0) {
                    files.forEach(file => {
                        const pageCount = file.items ? file.items.length : 1;

                        // Build storage location string
                        let storageLocation = '';
                        if (file.room) storageLocation += `غرفة ${file.room.name}`;
                        if (file.lane) storageLocation += ` <- ممر ${file.lane.name}`;
                        if (file.stand) storageLocation += ` <- ستاند ${file.stand.name}`;
                        if (file.rack) storageLocation += ` <- رف ${file.rack.name}`;
                        if (!storageLocation) storageLocation = 'غير محدد';

                        allBarcodes.push({
                            clientName: client.name,
                            clientNationalId: client.national_id || '-',
                            clientMobile: client.mobile || '-',
                            excelRowNumber: client.excel_row_number || '-',
                            fileName: file.file_name,
                            barcode: file.barcode,
                            pageCount: pageCount,
                            storageLocation: storageLocation,
                            landAddresses: landAddresses
                        });
                    });
                } else {
                    // If client has no files, still show client name with "لا يوجد ملف"
                    allBarcodes.push({
                        clientName: client.name,
                        clientNationalId: client.national_id || '-',
                        clientMobile: client.mobile || '-',
                        excelRowNumber: client.excel_row_number || '-',
                        fileName: 'لا يوجد ملف',
                        barcode: null,
                        pageCount: 0,
                        storageLocation: 'غير محدد',
                        landAddresses: landAddresses
                    });
                }
            });

            // Display in modal instead of new window
            let barcodesHtml = '';
            allBarcodes.forEach((item, index) => {
                const barcodeSection = item.barcode
                    ? `<div style="text-align: center; margin-top: 15px;">
                         <svg id="barcode-${index}"></svg>
                         <p style="margin-top: 10px; font-size: 14px; color: #666; font-family: monospace;">
                           ${item.barcode}
                         </p>
                       </div>`
                    : `<div style="text-align: center; padding: 20px; background: #f8d7da; color: #721c24; border-radius: 6px; margin-top: 10px;">
                         <p style="margin: 0; font-size: 16px;">لا يوجد باركود لهذا العميل</p>
                       </div>`;

                barcodesHtml += `
                    <div class="barcode-item" style="page-break-inside: avoid; margin-bottom: 30px; border: 1px solid #ddd; padding: 20px; background: white;">
                        <!-- Client Information -->
                        <div style="background: #f8f9fa; padding: 12px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                            <h3 style="margin: 0; font-size: 20px; color: #333;">${item.clientName}</h3>
                            ${item.excelRowNumber !== '-' ? `<p style="margin: 5px 0 0 0; font-size: 13px; color: #666;">صف Excel: #${item.excelRowNumber}</p>` : ''}
                        </div>

                        <!-- File Information -->
                        <div style="background: #fff; padding: 12px; border: 1px solid #e0e0e0; border-radius: 5px; margin-bottom: 12px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <div>
                                    <strong style="color: #555; font-size: 14px;">رقم الملف:</strong>
                                    <p style="margin: 3px 0 0 0; font-size: 16px; color: #000;">${item.fileName}</p>
                                </div>
                                <div>
                                    <strong style="color: #555; font-size: 14px;">عدد الصفحات:</strong>
                                    <p style="margin: 3px 0 0 0; font-size: 16px; color: #000;">${item.pageCount}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Land Addresses -->
                        <div style="background: #fff; padding: 12px; border: 1px solid #e0e0e0; border-radius: 5px; margin-bottom: 12px;">
                            <strong style="color: #555; font-size: 14px;">عناوين القطع:</strong>
                            <div style="margin-top: 8px; font-size: 14px; color: #333; line-height: 1.5;">
                                ${item.landAddresses}
                            </div>
                        </div>

                        <!-- Storage Location -->
                        <div style="background: #fff; padding: 12px; border: 1px solid #e0e0e0; border-radius: 5px; margin-bottom: 15px;">
                            <strong style="color: #555; font-size: 14px;">موقع التخزين:</strong>
                            <p style="margin: 5px 0 0 0; font-size: 14px; color: #333;">${item.storageLocation}</p>
                        </div>

                        <!-- Barcode Section -->
                        ${barcodeSection}
                    </div>
                `;
            });

            // Update modal content with barcodes
            document.getElementById('bulkBarcodeContent').innerHTML = barcodesHtml;
            document.getElementById('barcodeModalTitle').textContent = `باركودات الملفات - ${validClients.length} عميل (${allBarcodes.length} ملف)`;

            // Generate barcodes after content is loaded
            setTimeout(() => {
                allBarcodes.forEach((item, index) => {
                    if (item.barcode) {
                        try {
                            JsBarcode(`#barcode-${index}`, item.barcode, {
                                format: "CODE128",
                                width: 2,
                                height: 100,
                                displayValue: true,
                                fontSize: 20,
                                margin: 10
                            });
                        } catch(e) {
                            console.error('Barcode generation error for ' + item.barcode + ':', e);
                        }
                    }
                });
            }, 100);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('bulkBarcodeContent').innerHTML = `
                <div class="alert alert-danger text-center">
                    <i class="ti ti-alert-circle fs-1"></i>
                    <p class="mt-3">حدث خطأ أثناء تحميل بيانات العملاء</p>
                </div>
            `;
        });
}

// Print barcode modal content
function printBarcodeModal() {
    const modalContent = document.getElementById('bulkBarcodeContent').innerHTML;
    const modalTitle = document.getElementById('barcodeModalTitle').textContent;

    const printWindow = window.open('', '', 'width=900,height=700');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html dir="rtl">
        <head>
            <meta charset="UTF-8">
            <title>${modalTitle}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                    background-color: #f5f5f5;
                }
                .barcode-item {
                    background: white;
                    margin-bottom: 30px;
                    page-break-inside: avoid;
                }
                @media print {
                    body {
                        margin: 0;
                        padding: 10px;
                        background-color: white;
                    }
                    .barcode-item {
                        page-break-inside: avoid;
                        border: 1px solid #333;
                    }
                }
            </style>
        </head>
        <body>
            ${modalContent}
            <script>
                window.onload = function() {
                    window.print();
                };
            <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
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
    `);
    printWindow.document.close();
}

// ==================== Upload Functions ====================
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

    // Reset PDF preview
    const placeholder = document.getElementById('uploadPdfPlaceholder');
    const canvas = document.getElementById('uploadPdfCanvas');
    const loading = document.getElementById('uploadPdfLoading');
    if (canvas) canvas.style.display = 'none';
    if (loading) loading.classList.add('d-none');
    if (placeholder) {
        placeholder.classList.remove('d-none');
        placeholder.innerHTML = '<i class="ti ti-upload fs-1 mb-3 d-block"></i><p class="mb-0">قم برفع ملف PDF لمعاينة الصفحة الأولى</p>';
    }

    const modal = new bootstrap.Modal(document.getElementById('fileUploadModal'));
    modal.show();
}

function toggleUploadPageRange(itemId) {
    const checkbox = document.getElementById(`uploadItem${itemId}`);
    const fromPage = document.getElementById(`uploadFromPage${itemId}`);
    const toPage = document.getElementById(`uploadToPage${itemId}`);

    if (checkbox && checkbox.checked) {
        if (fromPage) fromPage.classList.remove('d-none');
        if (toPage) toPage.classList.remove('d-none');
    } else {
        if (fromPage) {
            fromPage.classList.add('d-none');
            fromPage.value = '';
        }
        if (toPage) {
            toPage.classList.add('d-none');
            toPage.value = '';
        }
    }
    updateUploadItemsCount();
}

function updateUploadItemsCount() {
    const count = document.querySelectorAll('.upload-item-checkbox:checked').length;
    const countEl = document.getElementById('uploadSelectedItemsCount');
    if (countEl) countEl.textContent = count + ' محدد';
}

function filterUploadItems() {
    const searchInput = document.getElementById('uploadItemSearchInput');
    if (!searchInput) return;

    const searchValue = searchInput.value.toLowerCase();
    const rows = document.querySelectorAll('.upload-item-row');

    rows.forEach(row => {
        const itemName = row.getAttribute('data-item-name');
        if (itemName && itemName.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function clearUploadItemSearch() {
    const searchInput = document.getElementById('uploadItemSearchInput');
    if (searchInput) searchInput.value = '';
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
    if (placeholder) placeholder.classList.add('d-none');
    if (canvas) canvas.style.display = 'none';
    if (loading) loading.classList.remove('d-none');

    const fileReader = new FileReader();
    fileReader.onload = function() {
        const typedarray = new Uint8Array(this.result);

        // Load PDF
        pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
            pdf.getPage(1).then(function(page) {
                const viewport = page.getViewport({ scale: 1.5 });
                const context = canvas.getContext('2d');

                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };

                page.render(renderContext).promise.then(function() {
                    if (loading) loading.classList.add('d-none');
                    if (canvas) canvas.style.display = 'block';
                });
            });
        }).catch(function(error) {
            console.error('Error loading PDF:', error);
            if (loading) loading.classList.add('d-none');
            if (placeholder) {
                placeholder.classList.remove('d-none');
                placeholder.innerHTML = '<i class="ti ti-alert-circle fs-1 mb-3 d-block text-danger"></i><p class="mb-0 text-danger">خطأ في تحميل الملف</p>';
            }
        });
    };

    fileReader.readAsArrayBuffer(file);
}

// Reset preview when upload modal is closed
document.getElementById('fileUploadModal')?.addEventListener('hidden.bs.modal', function() {
    const placeholder = document.getElementById('uploadPdfPlaceholder');
    const canvas = document.getElementById('uploadPdfCanvas');
    const loading = document.getElementById('uploadPdfLoading');
    const fileInput = document.getElementById('uploadPdfFileInput');

    if (canvas) canvas.style.display = 'none';
    if (loading) loading.classList.add('d-none');
    if (placeholder) {
        placeholder.classList.remove('d-none');
        placeholder.innerHTML = '<i class="ti ti-upload fs-1 mb-3 d-block"></i><p class="mb-0">قم برفع ملف PDF لمعاينة الصفحة الأولى</p>';
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

// Generate Client Code
let landRowIndex = 0;
let governoratesData = @json($governorates ?? []);

function generateClientCode() {
    fetch('{{ route("admin.clients.generate-code") }}')
        .then(res => res.json())
        .then(data => {
            document.getElementById('clientCode').value = data.code;
        })
        .catch(() => showToast.error('فشل توليد الكود'));
}

// Add Land Row
function addLandRow() {
    const container = document.getElementById('landsContainer');
    const row = document.createElement('div');
    row.className = 'card mb-2 border-primary';
    row.innerHTML = `
        <div class="card-header bg-primary-subtle d-flex justify-content-between align-items-center py-2">
            <h6 class="mb-0 text-primary small"><i class="ti ti-map-pin me-1"></i>قطعه #${landRowIndex + 1}</h6>
            <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.card').remove()">
                <i class="ti ti-trash"></i> حذف
            </button>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label small">المحافظة <span class="text-danger">*</span></label>
                    <select name="lands[${landRowIndex}][governorate_id]" class="form-select form-select-sm governorate-select-${landRowIndex}" onchange="loadClientLandCities(this.value, ${landRowIndex})" required>
                        <option value="">اختر المحافظة</option>
                        ${governoratesData.map(g => `<option value="${g.id}">${g.name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">المدينة</label>
                    <select name="lands[${landRowIndex}][city_id]" class="form-select form-select-sm city-select-${landRowIndex}" onchange="loadClientLandDistricts(this.value, ${landRowIndex})">
                        <option value="">اختر المدينة</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">الحي</label>
                    <select name="lands[${landRowIndex}][district_id]" class="form-select form-select-sm district-select-${landRowIndex}" onchange="loadClientLandZones(this.value, ${landRowIndex})">
                        <option value="">اختر الحي</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">المنطقة</label>
                    <select name="lands[${landRowIndex}][zone_id]" class="form-select form-select-sm zone-select-${landRowIndex}" onchange="loadClientLandAreas(this.value, ${landRowIndex})">
                        <option value="">اختر المنطقة</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">القطاع</label>
                    <select name="lands[${landRowIndex}][area_id]" class="form-select form-select-sm area-select-${landRowIndex}">
                        <option value="">اختر القطاع</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">رقم القطعة <span class="text-danger">*</span></label>
                    <input type="text" name="lands[${landRowIndex}][land_no]" class="form-control form-control-sm" placeholder="رقم القطعة" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">رقم الوحدة</label>
                    <input type="text" name="lands[${landRowIndex}][unit_no]" class="form-control form-control-sm" placeholder="رقم الوحدة">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">&nbsp;</label>
                    <div class="text-muted small">
                        <i class="ti ti-info-circle"></i> اختياري
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label small">العنوان</label>
                    <textarea name="lands[${landRowIndex}][address]" class="form-control form-control-sm" rows="2" placeholder="العنوان التفصيلي"></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label small">ملاحظات</label>
                    <textarea name="lands[${landRowIndex}][notes]" class="form-control form-control-sm" rows="2" placeholder="ملاحظات إضافية"></textarea>
                </div>
            </div>
        </div>
    `;
    container.appendChild(row);
    landRowIndex++;
}

// Load Cities for Client Lands
function loadClientLandCities(governorateId, index) {
    const citySelect = document.querySelector(`.city-select-${index}`);
    const districtSelect = document.querySelector(`.district-select-${index}`);
    const zoneSelect = document.querySelector(`.zone-select-${index}`);
    const areaSelect = document.querySelector(`.area-select-${index}`);

    if (!citySelect) return;

    citySelect.innerHTML = '<option value="">جاري التحميل...</option>';
    if (districtSelect) districtSelect.innerHTML = '<option value="">اختر الحي</option>';
    if (zoneSelect) zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
    if (areaSelect) areaSelect.innerHTML = '<option value="">اختر القطاع</option>';

    if (!governorateId) {
        citySelect.innerHTML = '<option value="">اختر المدينة</option>';
        return;
    }

    fetch(`{{ url('admin/geographic-areas/cities/by-governorate') }}/${governorateId}`)
        .then(res => res.json())
        .then(data => {
            citySelect.innerHTML = '<option value="">اختر المدينة</option>';
            data.forEach(city => {
                citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
            });
        })
        .catch(() => {
            citySelect.innerHTML = '<option value="">اختر المدينة</option>';
        });
}

function loadClientLandDistricts(cityId, index) {
    const districtSelect = document.querySelector(`.district-select-${index}`);
    const zoneSelect = document.querySelector(`.zone-select-${index}`);
    const areaSelect = document.querySelector(`.area-select-${index}`);

    if (!districtSelect) return;

    districtSelect.innerHTML = '<option value="">جاري التحميل...</option>';
    if (zoneSelect) zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
    if (areaSelect) areaSelect.innerHTML = '<option value="">اختر القطاع</option>';

    if (!cityId) {
        districtSelect.innerHTML = '<option value="">اختر الحي</option>';
        return;
    }

    fetch(`{{ url('admin/geographic-areas/districts/by-city') }}/${cityId}`)
        .then(res => res.json())
        .then(data => {
            districtSelect.innerHTML = '<option value="">اختر الحي</option>';
            data.forEach(district => {
                districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
            });
        })
        .catch(() => {
            districtSelect.innerHTML = '<option value="">اختر الحي</option>';
        });
}

function loadClientLandZones(districtId, index) {
    const zoneSelect = document.querySelector(`.zone-select-${index}`);
    const areaSelect = document.querySelector(`.area-select-${index}`);

    if (!zoneSelect) return;

    zoneSelect.innerHTML = '<option value="">جاري التحميل...</option>';
    if (areaSelect) areaSelect.innerHTML = '<option value="">اختر القطاع</option>';

    if (!districtId) {
        zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
        return;
    }

    fetch(`{{ url('admin/geographic-areas/zones/by-district') }}/${districtId}`)
        .then(res => res.json())
        .then(data => {
            zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
            data.forEach(zone => {
                zoneSelect.innerHTML += `<option value="${zone.id}">${zone.name}</option>`;
            });
        })
        .catch(() => {
            zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
        });
}

function loadClientLandAreas(zoneId, index) {
    const areaSelect = document.querySelector(`.area-select-${index}`);

    if (!areaSelect) return;

    areaSelect.innerHTML = '<option value="">جاري التحميل...</option>';

    if (!zoneId) {
        areaSelect.innerHTML = '<option value="">اختر القطاع</option>';
        return;
    }

    fetch(`{{ url('admin/geographic-areas/areas/by-zone') }}/${zoneId}`)
        .then(res => res.json())
        .then(data => {
            areaSelect.innerHTML = '<option value="">اختر القطاع</option>';
            data.forEach(area => {
                areaSelect.innerHTML += `<option value="${area.id}">${area.name}</option>`;
            });
        })
        .catch(() => {
            areaSelect.innerHTML = '<option value="">اختر القطاع</option>';
        });
}

// Open Create Modal
function openCreateModal() {
    clearFormErrors('createClientForm');
    document.getElementById('createClientForm').reset();
    document.getElementById('landsContainer').innerHTML = '';
    landRowIndex = 0;
    generateClientCode();
    new bootstrap.Modal(document.getElementById('createClientModal')).show();
}

// Create Client Form Submit
document.getElementById('createClientForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    clearFormErrors('createClientForm');

    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحفظ...';

    fetch('{{ route("admin.clients.store") }}', {
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
            showToast.success(data.message);
            bootstrap.Modal.getInstance(document.getElementById('createClientModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            if (data.errors) {
                displayFormErrors('createClientForm', data.errors);
                showToast.error('يرجى تصحيح الأخطاء في النموذج');
            } else {
                showToast.error(data.message || 'حدث خطأ');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast.error('حدث خطأ في الاتصال بالخادم');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ti ti-check me-1"></i>حفظ';
    });
});

// Edit Client
function editClient(id) {
    console.log('[EditClient] Loading client:', id);

    fetch(`/admin/clients/${id}/edit`)
        .then(res => {
            console.log('[EditClient] Response status:', res.status);
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('[EditClient] Response data:', data);

            if (data.success && data.html) {
                const modalBody = document.querySelector('#editClientModal .modal-body');
                if (modalBody) {
                    modalBody.innerHTML = data.html;
                    console.log('[EditClient] Modal body updated with HTML');

                    // Attach submit event listener to the dynamically loaded form
                    const form = document.getElementById('editClientForm');
                    if (form) {
                        console.log('[EditClient] Form found, attaching submit handler');

                        form.onsubmit = function(e) {
                            e.preventDefault();
                            console.log('[EditClient] Form submitted');
                            clearFormErrors('editClientForm');

                            const clientId = document.getElementById('editClientId').value;
                            console.log('[EditClient] Updating client:', clientId);

                            const formData = new FormData(form);
                            const submitBtn = form.querySelector('button[type="submit"]');

                            if (submitBtn) {
                                submitBtn.disabled = true;
                                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري التحديث...';
                            }

                            fetch(`/admin/clients/${clientId}`, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => {
                                console.log('[EditClient] Update response status:', res.status);
                                return res.json();
                            })
                            .then(data => {
                                console.log('[EditClient] Update response data:', data);
                                if (data.success) {
                                    showToast.success(data.message);
                                    bootstrap.Modal.getInstance(document.getElementById('editClientModal')).hide();
                                    setTimeout(() => location.reload(), 1000);
                                } else {
                                    if (data.errors) {
                                        displayFormErrors('editClientForm', data.errors);
                                        showToast.error('يرجى تصحيح الأخطاء');
                                    } else {
                                        showToast.error(data.message || 'حدث خطأ');
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('[EditClient] Update error:', error);
                                showToast.error('حدث خطأ في الاتصال');
                            })
                            .finally(() => {
                                if (submitBtn) {
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = '<i class="ti ti-check me-1"></i>حفظ التعديلات';
                                }
                            });
                        };
                    } else {
                        console.error('[EditClient] Form not found in modal');
                    }
                } else {
                    console.error('[EditClient] Modal body not found');
                }
                clearFormErrors('editClientForm');
                new bootstrap.Modal(document.getElementById('editClientModal')).show();
            } else {
                console.error('[EditClient] Invalid response:', data);
                showToast.error(data.message || 'فشل تحميل البيانات');
            }
        })
        .catch(error => {
            console.error('[EditClient] Fetch error:', error);
            showToast.error('فشل تحميل البيانات');
        });
}

// Delete Client
function deleteClient(id, name) {
    document.getElementById('deleteClientId').value = id;
    document.getElementById('deleteClientName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteClientModal')).show();
}

function confirmDelete() {
    const id = document.getElementById('deleteClientId').value;
    const deleteBtn = event.target;
    deleteBtn.disabled = true;
    deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحذف...';

    fetch(`/admin/clients/${id}/delete`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast.success(data.message);
            bootstrap.Modal.getInstance(document.getElementById('deleteClientModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast.error(data.message);
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = '<i class="ti ti-trash me-1"></i>حذف';
        }
    })
    .catch(() => {
        showToast.error('حدث خطأ');
        deleteBtn.disabled = false;
        deleteBtn.innerHTML = '<i class="ti ti-trash me-1"></i>حذف';
    });
}

// Show Client Details
function showClient(id) {
    fetch(`/admin/clients/${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const client = data.client;

                // Client Info
                document.getElementById('viewClientInitial').textContent = client.name.charAt(0);
                document.getElementById('viewClientName').textContent = client.name;
                document.getElementById('viewClientCode').textContent = client.client_code || '-';
                document.getElementById('viewNationalId').textContent = client.national_id || '-';
                document.getElementById('viewTelephone').textContent = client.telephone || '-';
                document.getElementById('viewMobile').textContent = client.mobile || '-';
                document.getElementById('viewNotes').textContent = client.notes || '-';

                // Stats
                const landsCount = client.lands?.length || 0;
                const filesCount = client.lands?.reduce((total, land) => total + (land.main_files?.length || 0), 0) || 0;
                document.getElementById('viewLandsCountStat').textContent = landsCount;
                document.getElementById('viewFilesCountStat').textContent = filesCount;
                const landsContent = document.getElementById('landsContent');
                landsContent.innerHTML = '';

                if (client.lands && client.lands.length > 0) {
                    client.lands.forEach(land => {
                        const landCard = createLandCard(land);
                        landsContent.appendChild(landCard);
                    });
                } else {
                    landsContent.innerHTML = '<div class="col-12 text-center text-muted py-4">لا توجد قطع مسجلة</div>';
                }

                new bootstrap.Modal(document.getElementById('viewClientModal')).show();
            } else {
                showToast.error(data.message);
            }
        })
        .catch(() => showToast.error('فشل تحميل البيانات'));
}

// Create Land Card with Files
function createLandCard(land) {
    const div = document.createElement('div');
    div.className = 'col-12';

    // Build files HTML - each file takes full row with subfiles in columns (5 per row)
    const filesHtml = land.main_files && land.main_files.length > 0
        ? land.main_files.map(file => {
            // Build subfiles HTML as cards (6 per row)
            let subFilesHtml = '';
            if (file.sub_files && file.sub_files.length > 0) {
                const subFileCards = file.sub_files.map((subFile, index) => {
                    const item = subFile.items && subFile.items[0];
                    const fileItem = subFile.file_items && subFile.file_items[0];
                    const itemName = item?.name || fileItem?.item?.name || 'غير محدد';
                    const fromPage = fileItem?.from_page || '-';
                    const toPage = fileItem?.to_page || '-';
                    const pagesCount = subFile.pages_count || 0;
                    const subPdfUrl = subFile.media && subFile.media[0] ? subFile.media[0].original_url : null;

                    return `
                        <div class="col-md-2 col-sm-4 col-6">
                            <div class="card border h-100 shadow-sm hover-shadow" style="transition: all 0.3s;">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-start mb-2">
                                        <div class="avatar avatar-sm bg-primary-subtle text-primary rounded me-2 flex-shrink-0">
                                            <i class="ti ti-file-text"></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <h6 class="mb-0 text-truncate small fw-bold" title="${itemName}">${itemName}</h6>
                                            ${item?.description ? `<small class="text-muted d-block text-truncate" title="${item.description}">${item.description}</small>` : ''}
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <small class="text-muted">الصفحات:</small>
                                            <span class="badge bg-info-subtle text-info small">${fromPage} - ${toPage}</span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <small class="text-muted">العدد:</small>
                                            <span class="badge bg-success-subtle text-success small">${pagesCount}</span>
                                        </div>
                                    </div>
                                    ${subPdfUrl ? `
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-info flex-fill" title="عرض" onclick="openSubFileIframe('${subPdfUrl}', '${itemName}')">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <a href="${subPdfUrl}" target="_blank" class="btn btn-sm btn-secondary" title="فتح">
                                                <i class="ti ti-external-link"></i>
                                            </a>
                                            <a href="${subPdfUrl}" download class="btn btn-sm btn-primary" title="تحميل">
                                                <i class="ti ti-download"></i>
                                            </a>
                                        </div>
                                    ` : '<div class="text-center"><small class="text-muted">غير متوفر</small></div>'}
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');

                subFilesHtml = `
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header bg-gradient " style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h6 class="mb-0 text-dark">
                                <i class="ti ti-folders me-2"></i>
                                الملفات الفرعية المستخرجة (${file.sub_files.length})
                            </h6>
                            <small class="text-dark-50">الملفات التي تم إنشاؤها بناءً على أنواع المحتوى المحددة</small>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                ${subFileCards}
                            </div>
                        </div>
                    </div>
                `;
            }

            // Check if file has document
            const hasDocument = file.media && file.media.length > 0;
            const pdfUrl = hasDocument ? file.media[0].original_url : null;

            // Build action buttons
            let actionButtons = '';
            const hasSubFiles = file.sub_files && file.sub_files.length > 0;

            if (hasDocument) {
                actionButtons = `
                    <button class="btn btn-info btn-sm" onclick="showFile(${file.id})" title="عرض">
                        <i class="ti ti-eye"></i>
                    </button>
                    <a href="${pdfUrl}" download class="btn btn-success btn-sm" title="تحميل">
                        <i class="ti ti-download"></i>
                    </a>
                `;

                // Don't show upload button in file header if subfiles exist
            }
            // Add barcode button if barcode exists
            if (file.barcode) {
                actionButtons += `
                    <button class="btn btn-warning btn-sm" onclick="printBarcode('${file.barcode}', '${file.file_name || 'ملف'}')" title="طباعة الباركود">
                        <i class="ti ti-barcode"></i>
                    </button>
                `;
            }

            return `
                <div class="col-12 mb-3">
                    <div class="card border-0 shadow" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header py-3 px-4" style="background: #000;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="bg-white bg-opacity-25 rounded-circle me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ti ti-file-type-pdf text-white fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0 fw-bold text-white">${file.file_name || 'ملف'}</h5>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="badge bg-white bg-opacity-25 text-white">
                                                <i class="ti ti-files me-1"></i>${file.pages_count || 0} صفحة
                                            </span>
                                            ${file.sub_files && file.sub_files.length > 0 ? `
                                                <span class="badge bg-warning text-dark">
                                                    <i class="ti ti-folder me-1"></i>${file.sub_files.length} مستند
                                                </span>
                                            ` : ''}
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-group ms-3">
                                    ${actionButtons.replace(/btn-sm/g, '')}
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4" style="background: #fafbfc;">
                            ${subFilesHtml || '<div class="text-center text-muted py-3"><i class="ti ti-folder-off fs-3 d-block mb-2 opacity-50"></i><small>لا توجد مستندات مستخرجة</small></div>'}
                        </div>
                    </div>
                </div>
            `;
        }).join('')
        : '<div class="col-12 text-center text-muted py-4"><i class="ti ti-folder-off fs-1 d-block mb-2"></i>لا توجد ملفات</div>';

    div.innerHTML = `
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold mx-3">
                        <i class="ti ti-map-pin text-success me-2"></i>
                        ${land.governorate?.name || ''} - ${land.city?.name || ''} - رقم القطعة: ${land.land_no}
                    </h6>
                    <div class="d-flex align-items-center gap-2">
                        ${land.main_files && land.main_files.length > 0 && land.main_files[0].id ? `
                            <button class="btn btn-sm btn-info" onclick="showFile(${land.main_files[0].id})" title="عرض تفاصيل الملف">
                                <i class="ti ti-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-primary" onclick="openUploadForFile(${land.main_files[0].id}, 'ملف جديد')" title="رفع ملف جديد">
                                <i class="ti ti-upload"></i>
                            </button>
                        ` : ''}
                        <span class="badge bg-primary">${land.main_files?.length || 0} ملف</span>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="row g-0">
                    ${filesHtml}
                </div>
            </div>
        </div>
    `;

    return div;
}

// Show File Details in Client Modal
function showFileInClient(fileId) {
    const modal = new bootstrap.Modal(document.getElementById('fileViewerModal'));
    modal.show();

    // Show loading state
    document.getElementById('fileViewerTitle').textContent = 'جاري التحميل...';
    document.getElementById('fileViewerContent').innerHTML = `
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
                <p class="mt-3 text-muted">جاري تحميل بيانات الملف...</p>
            </div>
        </div>
    `;

    fetch(`{{ url('admin/files') }}/${fileId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderClientFileDetails(data.file, data.pdf_url);
            } else {
                document.getElementById('fileViewerContent').innerHTML = `
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="alert alert-danger">
                            <i class="ti ti-alert-circle me-2"></i>فشل تحميل بيانات الملف
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('fileViewerContent').innerHTML = `
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i>حدث خطأ في الاتصال
                    </div>
                </div>
            `;
        });
}

// Render File Details in Client Modal
function renderClientFileDetails(file, pdfUrl) {
    document.getElementById('fileViewerTitle').textContent = file.file_name;

    const viewerContent = document.getElementById('fileViewerContent');

    let html = `
        <div style="height: 100%; overflow-y: auto; padding: 1.5rem;">
            <!-- PDF Viewer Section (Hidden by default) -->
            <div id="clientPdfViewerSection" class="d-none mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="ti ti-file-type-pdf text-danger me-2"></i>
                            <span id="clientCurrentPdfTitle">معاينة الملف</span>
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="closeClientPdfViewer()">
                            <i class="ti ti-x me-1"></i>إغلاق المعاينة
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <iframe id="clientPdfIframe" style="width: 100%; height: 500px; border: none;" src=""></iframe>
                    </div>
                </div>
            </div>

            <!-- File Info Section -->
            <div id="clientFileInfoSection">
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
                                            ${file.barcode ? '<button class="btn btn-sm btn-outline-light text-white" onclick="copyBarcode(\'' + file.barcode + '\')"><i class="ti ti-copy"></i></button>' : ''}
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
                                            <div class="avatar avatar-sm bg-success-subtle text-success rounded me-3">
                                                <i class="ti ti-map-pin"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">القطعة</small>
                                                <strong>${file.land?.land_no || 'غير محدد'}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-info-subtle text-info rounded me-3">
                                                <i class="ti ti-building"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">موقع التخزين</small>
                                                <strong>${file.room?.name || 'غير محدد'} - ${file.rack?.name || ''}</strong>
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
                                    <button type="button" class="btn btn-primary w-100 mb-2" onclick="viewClientPdfInModal('${pdfUrl}', '${file.file_name}')">
                                        <i class="ti ti-eye me-2"></i>عرض في المعاينة
                                    </button>
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

                <!-- Sub-Files Section -->
                ${file.sub_files && file.sub_files.length > 0 ? `
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient text-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5 class="mb-0 text-dark">
                                <i class="ti ti-folders me-2"></i>
                                الملفات الفرعية المستخرجة (${file.sub_files.length})
                            </h5>
                            <small class="text-dark-50">الملفات التي تم إنشاؤها بناءً على أنواع المحتوى المحددة</small>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                ${file.sub_files.map((subFile, index) => {
                                    const item = subFile.items && subFile.items[0];
                                    const fileItem = subFile.file_items && subFile.file_items[0];
                                    const itemName = item?.name || 'غير محدد';
                                    const fromPage = fileItem?.from_page || '-';
                                    const toPage = fileItem?.to_page || '-';
                                    const subPdfUrl = subFile.media && subFile.media[0] ? subFile.media[0].original_url : null;

                                    return '<div class="col-md-4 col-lg-3">' +
                                        '<div class="card border shadow-sm h-100">' +
                                            '<div class="card-img-top bg-light position-relative" style="height: 120px; overflow: hidden;">' +
                                                (subPdfUrl ?
                                                    '<iframe src="' + subPdfUrl + '#toolbar=0&navpanes=0&scrollbar=0" style="width: 100%; height: 200px; border: none; pointer-events: none; transform: scale(0.8); transform-origin: top center;" loading="lazy"></iframe>' +
                                                    '<div class="position-absolute top-0 start-0 w-100 h-100" style="cursor: pointer;" onclick="viewSubFilePdf(\'' + subPdfUrl + '\', \'' + itemName + '\')"></div>'
                                                : '<div class="d-flex align-items-center justify-content-center h-100"><i class="ti ti-file-off text-muted fs-1"></i></div>') +
                                            '</div>' +
                                            '<div class="card-body p-2">' +
                                                '<h6 class="card-title mb-1 small fw-bold text-truncate" title="' + itemName + '">' + itemName + '</h6>' +
                                                '<div class="d-flex align-items-center gap-2 mb-2">' +
                                                    '<span class="badge bg-info-subtle text-info small">ص ' + fromPage + '-' + toPage + '</span>' +
                                                    '<span class="badge bg-success-subtle text-success small">' + (subFile.pages_count || 0) + ' صفحة</span>' +
                                                '</div>' +
                                                '<div class="btn-group btn-group-sm w-100">' +
                                                    (subPdfUrl ?
                                                        '<button type="button" class="btn btn-info" onclick="viewSubFilePdf(\'' + subPdfUrl + '\', \'' + itemName + '\')" title="عرض"><i class="ti ti-eye"></i></button>' +
                                                        '<a href="' + subPdfUrl + '" target="_blank" class="btn btn-secondary" title="فتح"><i class="ti ti-external-link"></i></a>' +
                                                        '<a href="' + subPdfUrl + '" download class="btn btn-primary" title="تحميل"><i class="ti ti-download"></i></a>'
                                                    : '<button class="btn btn-secondary w-100" disabled>غير متوفر</button>') +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>';
                                }).join('')}
                            </div>
                        </div>
                    </div>
                ` : ''}
            </div>
        </div>
    `;

    viewerContent.innerHTML = html;
}

// View PDF in Modal for Client
function viewClientPdfInModal(pdfUrl, title) {
    document.getElementById('clientPdfViewerSection').classList.remove('d-none');
    document.getElementById('clientFileInfoSection').classList.add('d-none');
    document.getElementById('clientCurrentPdfTitle').textContent = title;
    document.getElementById('clientPdfIframe').src = pdfUrl;
}

// Close Client PDF Viewer
function closeClientPdfViewer() {
    document.getElementById('clientPdfViewerSection').classList.add('d-none');
    document.getElementById('clientFileInfoSection').classList.remove('d-none');
    document.getElementById('clientPdfIframe').src = '';
}

// Copy Barcode to clipboard
function copyBarcode(barcode) {
    navigator.clipboard.writeText(barcode).then(() => {
        showToast.success('تم نسخ الباركود');
    }).catch(() => {
        showToast.error('فشل نسخ الباركود');
    });
}

// View SubFile PDF in iframe
function viewSubFilePdf(pdfUrl, title) {
    document.getElementById('clientPdfViewerSection').classList.remove('d-none');
    document.getElementById('clientFileInfoSection').classList.add('d-none');
    document.getElementById('clientCurrentPdfTitle').textContent = title;
    document.getElementById('clientPdfIframe').src = pdfUrl;
}

// Open SubFile in iframe modal (from lands card view)
function openSubFileIframe(pdfUrl, title) {
    // Set the title and show modal
    document.getElementById('fileViewerTitle').textContent = title;

    // Set content to iframe with PDF
    document.getElementById('fileViewerContent').innerHTML = `
        <div class="h-100 d-flex flex-column">
            <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="ti ti-file-type-pdf text-danger fs-4 me-2"></i>
                    <span class="fw-bold">${title}</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="${pdfUrl}" target="_blank" class="btn btn-sm btn-outline-secondary">
                        <i class="ti ti-external-link me-1"></i>فتح في تبويب جديد
                    </a>
                    <a href="${pdfUrl}" download class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-download me-1"></i>تحميل
                    </a>
                </div>
            </div>
            <div class="flex-grow-1">
                <iframe src="${pdfUrl}" class="w-100 h-100" style="border: none;"></iframe>
            </div>
        </div>
    `;

    // Show the modal
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('fileViewerModal'));
    modal.show();
}

// Upload File
function uploadFile(clientId) {
    // Load client details and lands
    fetch(`/admin/clients/${clientId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.client) {
                // Set client name
                document.getElementById('uploadClientName').textContent = data.client.name;
                document.getElementById('uploadClientId').value = clientId;

                // Load client lands
                const landSelect = document.getElementById('uploadLandId');
                landSelect.innerHTML = '<option value="">اختر القطعة</option>';
                if (data.client.lands && data.client.lands.length > 0) {
                    data.client.lands.forEach(land => {
                        const govName = land.governorate?.name || '';
                        const landNo = land.land_no || '';
                        landSelect.innerHTML += `<option value="${land.id}">${govName} - ${landNo}</option>`;
                    });
                }

                // Show modal
                new bootstrap.Modal(document.getElementById('uploadFileModal')).show();
            } else {
                showToast.error('فشل تحميل بيانات العميل');
            }
        })
        .catch(error => {
            console.error('Error loading client:', error);
            showToast.error('حدث خطأ في تحميل بيانات العميل');
        });
}

// Toggle View
function toggleView(view) {
    if (view === 'list') {
        document.getElementById('listView').classList.remove('d-none');
        document.getElementById('cardView').classList.add('d-none');
        document.getElementById('listViewBtn').classList.add('active');
        document.getElementById('cardViewBtn').classList.remove('active');
    } else {
        document.getElementById('listView').classList.add('d-none');
        document.getElementById('cardView').classList.remove('d-none');
        document.getElementById('listViewBtn').classList.remove('active');
        document.getElementById('cardViewBtn').classList.add('active');
    }
}

// Select All Checkboxes
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
    updateBulkActions();
});

document.querySelectorAll('.row-checkbox').forEach(cb => {
    cb.addEventListener('change', updateBulkActions);
});

function updateBulkActions() {
    const checked = document.querySelectorAll('.row-checkbox:checked').length;
    const bulkActions = document.getElementById('bulkActions');
    if (checked > 0) {
        bulkActions.classList.remove('d-none');
    } else {
        bulkActions.classList.add('d-none');
    }
}

function getSelectedIds() {
    return Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
}

// Bulk Delete
function bulkDelete() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        showToast.error('الرجاء تحديد عملاء للحذف');
        return;
    }

    document.getElementById('bulkDeleteCount').textContent = ids.length;
    new bootstrap.Modal(document.getElementById('bulkDeleteModal')).show();
}

function confirmBulkDelete() {
    const ids = getSelectedIds();
    const deleteBtn = event.target;
    deleteBtn.disabled = true;
    deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحذف...';

    fetch('{{ route("admin.clients.bulk-delete") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast.success(data.message);
            bootstrap.Modal.getInstance(document.getElementById('bulkDeleteModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast.error(data.message);
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = '<i class="ti ti-trash me-1"></i>حذف الكل';
        }
    })
    .catch(() => {
        showToast.error('حدث خطأ');
        deleteBtn.disabled = false;
        deleteBtn.innerHTML = '<i class="ti ti-trash me-1"></i>حذف الكل';
    });
}

// Bulk Restore
function bulkRestore() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        showToast.error('الرجاء تحديد عملاء للاسترجاع');
        return;
    }

    document.getElementById('bulkRestoreCount').textContent = ids.length;
    new bootstrap.Modal(document.getElementById('bulkRestoreModal')).show();
}

function confirmBulkRestore() {
    const ids = getSelectedIds();
    const restoreBtn = event.target;
    restoreBtn.disabled = true;
    restoreBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الاسترجاع...';

    fetch('{{ route("admin.clients.bulk-restore") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast.success(data.message);
            bootstrap.Modal.getInstance(document.getElementById('bulkRestoreModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast.error(data.message);
            restoreBtn.disabled = false;
            restoreBtn.innerHTML = '<i class="ti ti-refresh me-1"></i>استرجاع الكل';
        }
    })
    .catch(() => {
        showToast.error('حدث خطأ');
        restoreBtn.disabled = false;
        restoreBtn.innerHTML = '<i class="ti ti-refresh me-1"></i>استرجاع الكل';
    });
}

// Bulk Force Delete
function bulkForceDelete() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        showToast.error('الرجاء تحديد عملاء للحذف');
        return;
    }

    document.getElementById('bulkForceDeleteCount').textContent = ids.length;
    new bootstrap.Modal(document.getElementById('bulkForceDeleteModal')).show();
}

function confirmBulkForceDelete() {
    const ids = getSelectedIds();
    const deleteBtn = event.target;
    deleteBtn.disabled = true;
    deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحذف...';

    fetch('{{ route("admin.clients.bulk-force-delete") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast.success(data.message);
            bootstrap.Modal.getInstance(document.getElementById('bulkForceDeleteModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast.error(data.message);
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = '<i class="ti ti-trash-x me-1"></i>حذف نهائي';
        }
    })
    .catch(() => {
        showToast.error('حدث خطأ');
        deleteBtn.disabled = false;
        deleteBtn.innerHTML = '<i class="ti ti-trash-x me-1"></i>حذف نهائي';
    });
}

// Restore Client
function restoreClient(id) {
    if (confirm('هل تريد استرجاع هذا العميل؟')) {
        fetch(`/admin/clients/${id}/restore`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast.success(data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast.error(data.message);
            }
        })
        .catch(() => showToast.error('حدث خطأ'));
    }
}

// Force Delete Client
function forceDeleteClient(id) {
    if (confirm('هل أنت متأكد من الحذف النهائي؟ لا يمكن التراجع!')) {
        fetch(`/admin/clients/${id}/force-delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast.success(data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast.error(data.message);
            }
        })
        .catch(() => showToast.error('حدث خطأ'));
    }
}

// =====================================================
// Client Upload Modal Functions
// =====================================================

// PDF.js Configuration for Client Upload
if (typeof pdfjsLib !== 'undefined') {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
}

// Preview Client PDF
function previewClientPDF(input) {
    const file = input.files[0];
    if (!file || file.type !== 'application/pdf') {
        return;
    }

    const placeholder = document.getElementById('clientPdfPlaceholder');
    const canvas = document.getElementById('clientPdfCanvas');
    const loading = document.getElementById('clientPdfLoading');

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
                const viewport = page.getViewport({ scale: 1.5 });
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
            placeholder.innerHTML = '<i class="ti ti-alert-circle fs-1 mb-3 d-block text-danger"></i><p class="mb-0 text-danger">خطأ في تحميل الملف</p>';
        });
    };

    fileReader.readAsArrayBuffer(file);
}

// Reset client PDF preview when modal is closed
document.getElementById('uploadFileModal')?.addEventListener('hidden.bs.modal', function() {
    const placeholder = document.getElementById('clientPdfPlaceholder');
    const canvas = document.getElementById('clientPdfCanvas');
    const loading = document.getElementById('clientPdfLoading');
    const fileInput = document.getElementById('clientPdfFileInput');

    if (canvas) canvas.style.display = 'none';
    if (loading) loading.classList.add('d-none');
    if (placeholder) {
        placeholder.classList.remove('d-none');
        placeholder.innerHTML = '<i class="ti ti-upload fs-1 mb-3 d-block"></i><p class="mb-0">قم برفع ملف PDF لمعاينة الصفحة الأولى</p>';
    }
    if (fileInput) fileInput.value = '';
});

// Toggle New Land Form
function toggleClientNewLandForm() {
    const form = document.getElementById('clientNewLandForm');
    const landSelect = document.getElementById('uploadLandId');

    if (form.classList.contains('d-none')) {
        form.classList.remove('d-none');
        landSelect.removeAttribute('required');
        landSelect.value = '';
    } else {
        form.classList.add('d-none');
        landSelect.setAttribute('required', 'required');
    }
}

// Toggle Page Range for Client Items
function toggleClientPageRange(itemId) {
    const checkbox = document.getElementById('clientItem' + itemId);
    const fromPageInput = document.getElementById('clientFromPage' + itemId);
    const toPageInput = document.getElementById('clientToPage' + itemId);

    if (checkbox.checked) {
        fromPageInput.classList.remove('d-none');
        toPageInput.classList.remove('d-none');
        fromPageInput.focus();
    } else {
        fromPageInput.classList.add('d-none');
        toPageInput.classList.add('d-none');
        fromPageInput.value = '';
        toPageInput.value = '';
    }
    updateClientSelectedItemsCount();
}

// Update selected items count
function updateClientSelectedItemsCount() {
    const count = document.querySelectorAll('.client-item-checkbox:checked').length;
    document.getElementById('clientSelectedItemsCount').textContent = count + ' محدد';
}

// Filter Client Items
function filterClientItems() {
    const searchValue = document.getElementById('clientItemSearchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.client-item-row');

    rows.forEach(row => {
        const itemName = row.getAttribute('data-item-name') || '';
        if (itemName.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Clear Client Item Search
function clearClientItemSearch() {
    document.getElementById('clientItemSearchInput').value = '';
    filterClientItems();
}

// Select All Client Items
function selectAllClientItems() {
    document.querySelectorAll('.client-item-checkbox').forEach(cb => {
        if (!cb.checked) {
            cb.checked = true;
            const itemId = cb.getAttribute('data-item-id');
            document.getElementById('clientFromPage' + itemId)?.classList.remove('d-none');
            document.getElementById('clientToPage' + itemId)?.classList.remove('d-none');
        }
    });
    updateClientSelectedItemsCount();
}

// Deselect All Client Items
function deselectAllClientItems() {
    document.querySelectorAll('.client-item-checkbox').forEach(cb => {
        cb.checked = false;
        const itemId = cb.getAttribute('data-item-id');
        const fromInput = document.getElementById('clientFromPage' + itemId);
        const toInput = document.getElementById('clientToPage' + itemId);
        if (fromInput) {
            fromInput.classList.add('d-none');
            fromInput.value = '';
        }
        if (toInput) {
            toInput.classList.add('d-none');
            toInput.value = '';
        }
    });
    updateClientSelectedItemsCount();
}

// Load Cities for New Land in Client Upload
function loadClientNewLandCities(governorateId) {
    const citySelect = document.getElementById('clientNewCityId');
    const cityHiddenInput = document.getElementById('clientNewCityIdHidden');
    const districtSelect = document.getElementById('clientNewDistrictId');
    const zoneSelect = document.getElementById('clientNewZoneId');
    const areaSelect = document.getElementById('clientNewAreaId');

    citySelect.innerHTML = '<option value="">جاري التحميل...</option>';
    districtSelect.innerHTML = '<option value="">اختر الحي</option>';
    zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
    areaSelect.innerHTML = '<option value="">اختر المجاورة</option>';

    if (!governorateId) {
        citySelect.innerHTML = '<option value="">اختر المدينة</option>';
        if (cityHiddenInput) cityHiddenInput.value = '';
        return;
    }

    fetch(`{{ url('admin/geographic-areas/cities/by-governorate') }}/${governorateId}`)
        .then(res => res.json())
        .then(data => {
            citySelect.innerHTML = '';
            if (data && data.length > 0) {
                data.forEach((city, index) => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    if (index === 0) option.selected = true;
                    citySelect.appendChild(option);
                });

                // Update hidden input with first city
                if (cityHiddenInput) {
                    cityHiddenInput.value = data[0].id;
                }

                // Auto-trigger loading districts for first city
                loadClientNewLandDistricts(data[0].id);
            } else {
                citySelect.innerHTML = '<option value="">لا توجد مدن</option>';
                if (cityHiddenInput) cityHiddenInput.value = '';
            }

            // Update address
            updateClientNewLandAddress();
        })
        .catch(() => {
            citySelect.innerHTML = '<option value="">اختر المدينة</option>';
            if (cityHiddenInput) cityHiddenInput.value = '';
        });
}

// Load Districts for New Land in Client Upload
function loadClientNewLandDistricts(cityId) {
    const districtSelect = document.getElementById('clientNewDistrictId');
    const zoneSelect = document.getElementById('clientNewZoneId');
    const areaSelect = document.getElementById('clientNewAreaId');

    districtSelect.innerHTML = '<option value="">جاري التحميل...</option>';
    zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
    areaSelect.innerHTML = '<option value="">اختر المجاورة</option>';

    if (!cityId) {
        districtSelect.innerHTML = '<option value="">اختر الحي</option>';
        return;
    }

    fetch(`{{ url('admin/geographic-areas/districts/by-city') }}/${cityId}`)
        .then(res => res.json())
        .then(data => {
            districtSelect.innerHTML = '';
            if (data && data.length > 0) {
                data.forEach((district, index) => {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    if (index === 0) option.selected = true;
                    districtSelect.appendChild(option);
                });

                // Auto-trigger loading zones for first district
                loadClientNewLandZones(data[0].id);
            } else {
                districtSelect.innerHTML = '<option value="">لا توجد أحياء</option>';
            }

            // Update address
            updateClientNewLandAddress();
        })
        .catch(() => {
            districtSelect.innerHTML = '<option value="">اختر الحي</option>';
        });
}

// Load Zones for New Land in Client Upload
function loadClientNewLandZones(districtId) {
    const zoneSelect = document.getElementById('clientNewZoneId');
    const areaSelect = document.getElementById('clientNewAreaId');

    zoneSelect.innerHTML = '<option value="">جاري التحميل...</option>';
    areaSelect.innerHTML = '<option value="">اختر المجاورة</option>';

    if (!districtId) {
        zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
        return;
    }

    fetch(`{{ url('admin/geographic-areas/zones/by-district') }}/${districtId}`)
        .then(res => res.json())
        .then(data => {
            zoneSelect.innerHTML = '';
            if (data && data.length > 0) {
                data.forEach((zone, index) => {
                    const option = document.createElement('option');
                    option.value = zone.id;
                    option.textContent = zone.name;
                    if (index === 0) option.selected = true;
                    zoneSelect.appendChild(option);
                });

                // Auto-trigger loading areas for first zone
                loadClientNewLandAreas(data[0].id);
            } else {
                zoneSelect.innerHTML = '<option value="">لا توجد مناطق</option>';
            }

            // Update address
            updateClientNewLandAddress();
        })
        .catch(() => {
            zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
        });
}

// Load Areas for New Land in Client Upload
function loadClientNewLandAreas(zoneId) {
    const areaSelect = document.getElementById('clientNewAreaId');

    areaSelect.innerHTML = '<option value="">جاري التحميل...</option>';

    if (!zoneId) {
        areaSelect.innerHTML = '<option value="">اختر المجاورة</option>';
        return;
    }

    fetch(`{{ url('admin/geographic-areas/areas/by-zone') }}/${zoneId}`)
        .then(res => res.json())
        .then(data => {
            areaSelect.innerHTML = '';
            if (data && data.length > 0) {
                data.forEach((area, index) => {
                    const option = document.createElement('option');
                    option.value = area.id;
                    option.textContent = area.name;
                    if (index === 0) option.selected = true;
                    areaSelect.appendChild(option);
                });
            } else {
                areaSelect.innerHTML = '<option value="">لا توجد مجاورات</option>';
            }

            // Update address
            updateClientNewLandAddress();
        })
        .catch(() => {
            areaSelect.innerHTML = '<option value="">اختر المجاورة</option>';
        });
}

// Load Lanes for Client Upload
function loadClientLanes(roomId) {
    const laneSelect = document.getElementById('clientLaneSelect');
    const standSelect = document.getElementById('clientStandSelect');
    const rackSelect = document.getElementById('clientRackSelect');

    laneSelect.innerHTML = '<option value="">جاري التحميل...</option>';
    standSelect.innerHTML = '<option value="">اختر الستاند</option>';
    rackSelect.innerHTML = '<option value="">اختر الرف</option>';

    if (!roomId) {
        laneSelect.innerHTML = '<option value="">اختر الممر</option>';
        return;
    }

    fetch(`{{ url('admin/physical-locations/rooms') }}/${roomId}/lanes`)
        .then(res => res.json())
        .then(data => {
            laneSelect.innerHTML = '<option value="">اختر الممر</option>';
            (data.lanes || data).forEach(lane => {
                laneSelect.innerHTML += `<option value="${lane.id}">${lane.name}</option>`;
            });
        })
        .catch(() => {
            laneSelect.innerHTML = '<option value="">اختر الممر</option>';
        });
}

// Load Stands for Client Upload
function loadClientStands(laneId) {
    const standSelect = document.getElementById('clientStandSelect');
    const rackSelect = document.getElementById('clientRackSelect');

    standSelect.innerHTML = '<option value="">جاري التحميل...</option>';
    rackSelect.innerHTML = '<option value="">اختر الرف</option>';

    if (!laneId) {
        standSelect.innerHTML = '<option value="">اختر الستاند</option>';
        return;
    }

    fetch(`{{ url('admin/physical-locations/lanes') }}/${laneId}/stands`)
        .then(res => res.json())
        .then(data => {
            standSelect.innerHTML = '<option value="">اختر الستاند</option>';
            (data.stands || data).forEach(stand => {
                standSelect.innerHTML += `<option value="${stand.id}">${stand.name}</option>`;
            });
        })
        .catch(() => {
            standSelect.innerHTML = '<option value="">اختر الستاند</option>';
        });
}

// Load Racks for Client Upload
function loadClientRacks(standId) {
    const rackSelect = document.getElementById('clientRackSelect');

    rackSelect.innerHTML = '<option value="">جاري التحميل...</option>';

    if (!standId) {
        rackSelect.innerHTML = '<option value="">اختر الرف</option>';
        return;
    }

    fetch(`{{ url('admin/physical-locations/stands') }}/${standId}/racks`)
        .then(res => res.json())
        .then(data => {
            rackSelect.innerHTML = '<option value="">اختر الرف</option>';
            (data.racks || data).forEach(rack => {
                rackSelect.innerHTML += `<option value="${rack.id}">${rack.name}</option>`;
            });
        })
        .catch(() => {
            rackSelect.innerHTML = '<option value="">اختر الرف</option>';
        });
}

// Toggle Client Storage Location Collapse
function toggleClientStorageLocation(event) {
    event.preventDefault();
    event.stopPropagation();

    const collapseElement = document.getElementById('clientStorageLocationCollapse');
    const chevron = document.getElementById('clientStorageChevron');

    if (collapseElement.classList.contains('show')) {
        collapseElement.classList.remove('show');
        chevron.classList.remove('ti-chevron-up');
        chevron.classList.add('ti-chevron-down');
    } else {
        collapseElement.classList.add('show');
        chevron.classList.remove('ti-chevron-down');
        chevron.classList.add('ti-chevron-up');
    }
}

// Client Upload Form Submit
document.getElementById('uploadClientFileForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Collect selected items with page ranges (same as files upload)
    const selectedItems = [];
    document.querySelectorAll('.client-item-checkbox:checked').forEach(checkbox => {
        const itemId = checkbox.dataset.itemId;
        const fromPageInput = document.getElementById('clientFromPage' + itemId);
        const toPageInput = document.getElementById('clientToPage' + itemId);
        const fromPage = fromPageInput ? fromPageInput.value : '';
        const toPage = toPageInput ? toPageInput.value : '';

        selectedItems.push({
            item_id: itemId,
            from_page: fromPage || null,
            to_page: toPage || null
        });
    });

    // Add items as JSON
    formData.delete('items'); // Remove old format
    formData.append('items_json', JSON.stringify(selectedItems));

    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الرفع...';

    fetch('{{ route("admin.files.store") }}', {
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
            bootstrap.Modal.getInstance(document.getElementById('uploadFileModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast.error(data.error || data.message || 'حدث خطأ');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast.error('حدث خطأ في رفع الملف');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ti ti-upload me-2"></i>رفع ومعالجة';
    });
});

// ============================================
// BARCODE SCANNER SEARCH FUNCTIONALITY
// ============================================

/**
 * Barcode Scanner Search
 * Supports both external barcode scanner devices and manual input
 * External scanners typically send characters followed by Enter key
 */
(function() {
    const barcodeInput = document.getElementById('barcodeSearchInput');
    const statusBadge = document.getElementById('barcodeScannerStatus');
    let lastKeyTime = 0;
    let barcodeBuffer = '';
    const SCANNER_THRESHOLD = 50; // Max ms between keystrokes for scanner detection

    if (!barcodeInput) return;

    // Focus on barcode input when page loads
    setTimeout(() => barcodeInput.focus(), 500);

    // Handle input event for automatic search
    barcodeInput.addEventListener('input', function(e) {
        const currentTime = Date.now();
        const value = e.target.value.trim();

        // Clear any existing timeout
        if (window.barcodeSearchTimeout) {
            clearTimeout(window.barcodeSearchTimeout);
        }

        // Detect rapid input (barcode scanner behavior)
        if (currentTime - lastKeyTime < SCANNER_THRESHOLD && value.length > 3) {
            updateScannerStatus('scanning');

            // Auto-search after scanner completes (100ms delay)
            window.barcodeSearchTimeout = setTimeout(() => {
                if (value.length >= 5) { // Minimum barcode length
                    searchByBarcode();
                }
            }, 100);
        }

        lastKeyTime = currentTime;
    });

    // Handle keydown events for Enter key
    barcodeInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();

            // Clear any pending timeout
            if (window.barcodeSearchTimeout) {
                clearTimeout(window.barcodeSearchTimeout);
            }

            searchByBarcode();
            return;
        }
    });

    // Update scanner status indicator
    function updateScannerStatus(status) {
        if (!statusBadge) return;

        const statuses = {
            ready: `<span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                        <i class="ti ti-device-desktop-analytics me-1"></i>جاهز للمسح
                    </span>`,
            scanning: `<span class="badge bg-warning-subtle text-warning fs-6 px-3 py-2">
                        <i class="ti ti-loader me-1 spinner-border spinner-border-sm"></i>جاري المسح...
                    </span>`,
            success: `<span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                        <i class="ti ti-check me-1"></i>تم العثور على الملف
                    </span>`,
            error: `<span class="badge bg-danger-subtle text-danger fs-6 px-3 py-2">
                        <i class="ti ti-x me-1"></i>لم يتم العثور
                    </span>`
        };

        statusBadge.innerHTML = statuses[status] || statuses.ready;

        // Reset to ready after 3 seconds
        if (status !== 'ready' && status !== 'scanning') {
            setTimeout(() => updateScannerStatus('ready'), 3000);
        }
    }

    // Make updateScannerStatus globally accessible
    window.updateScannerStatus = updateScannerStatus;

    // Global keyboard shortcut: Press F2 to focus barcode input
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F2') {
            e.preventDefault();
            barcodeInput.focus();
            barcodeInput.select();
        }
    });
})();

/**
 * Search by barcode - main function
 */
function searchByBarcode() {
    const barcodeInput = document.getElementById('barcodeSearchInput');
    const barcode = barcodeInput.value.trim();

    if (!barcode) {
        showToast.warning('الرجاء إدخال الباركود أو استخدام الماسح الضوئي');
        barcodeInput.focus();
        return;
    }

    // Update status
    if (window.updateScannerStatus) {
        window.updateScannerStatus('scanning');
    }

    // Show modal with loading state
    const modal = new bootstrap.Modal(document.getElementById('barcodeResultModal'));
    document.getElementById('barcodeResultBody').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-dark" role="status"></div>
            <p class="mt-3 text-muted">جاري البحث عن الباركود: <strong>${barcode}</strong></p>
        </div>
    `;
    document.getElementById('viewClientFromBarcodeBtn').style.display = 'none';
    modal.show();

    // Make API request
    fetch(`{{ route('admin.clients.search-barcode') }}?barcode=${encodeURIComponent(barcode)}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (window.updateScannerStatus) {
                window.updateScannerStatus('success');
            }
            renderBarcodeSearchResult(data);
            showToast.success(data.message || 'تم العثور على الملف');
        } else {
            if (window.updateScannerStatus) {
                window.updateScannerStatus('error');
            }
            renderBarcodeNotFound(barcode, data.message);
            showToast.error(data.message || 'لم يتم العثور على الملف');
        }
    })
    .catch(error => {
        console.error('Barcode search error:', error);
        if (window.updateScannerStatus) {
            window.updateScannerStatus('error');
        }
        renderBarcodeNotFound(barcode, 'حدث خطأ أثناء البحث');
        showToast.error('حدث خطأ أثناء البحث');
    })
    .finally(() => {
        // Clear input and refocus for next scan
        barcodeInput.value = '';
        barcodeInput.focus();
    });
}

/**
 * Render successful barcode search result
 */
function renderBarcodeSearchResult(data) {
    const file = data.file;
    const client = data.client;
    const land = data.land;

    let html = `
        <div class="row g-4">
            <!-- File Information -->
            <div class="col-md-6">
                <div class="card border-dark h-100">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0"><i class="ti ti-file me-2"></i>معلومات الملف</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="40%">الباركود:</td>
                                <td><strong class="text-dark">${file.barcode || '-'}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">اسم الملف:</td>
                                <td>${file.file_name || '-'}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">النوع:</td>
                                <td><span class="badge bg-info-subtle text-info">${file.is_main_file ? 'ملف رئيسي' : 'ملف فرعي'}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">رقم الصفحة:</td>
                                <td>${file.page_number || '-'}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">عدد الصفحات:</td>
                                <td>${file.pages_count || 0}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">تاريخ الإنشاء:</td>
                                <td>${file.created_at ? new Date(file.created_at).toLocaleDateString('ar-EG') : '-'}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Physical Location -->
            <div class="col-md-6">
                <div class="card border-success h-100">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="ti ti-building-warehouse me-2"></i>موقع التخزين</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="40%">الغرفة:</td>
                                <td>${file.room?.name || '-'}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">الممر:</td>
                                <td>${file.lane?.name || '-'}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">الستاند:</td>
                                <td>${file.stand?.name || '-'}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">الرف:</td>
                                <td>${file.rack?.name || '-'}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
    `;

    // Client Information
    if (client) {
        html += `
            <div class="col-md-6">
                <div class="card border-primary h-100">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="ti ti-user me-2"></i>معلومات العميل</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="40%">الاسم:</td>
                                <td><strong>${client.name || '-'}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">كود العميل:</td>
                                <td><span class="badge bg-primary-subtle text-primary">${client.client_code || '-'}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">الرقم القومي:</td>
                                <td>${client.national_id || '-'}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">الموبايل:</td>
                                <td>${client.mobile || '-'}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">العنوان:</td>
                                <td>${client.address || '-'}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        `;

        // Show view client button
        document.getElementById('viewClientFromBarcodeBtn').style.display = 'inline-block';
        document.getElementById('viewClientFromBarcodeBtn').onclick = function() {
            bootstrap.Modal.getInstance(document.getElementById('barcodeResultModal')).hide();
            showClient(client.id);
        };
    }

    // Land Information
    if (land) {
        html += `
            <div class="col-md-6">
                <div class="card border-warning h-100">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="ti ti-map-pin me-2"></i>معلومات القطعة</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="40%">رقم القطعة:</td>
                                <td><strong>${land.plot_number || '-'}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">المحافظة:</td>
                                <td>${land.governorate?.name || '-'}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">المدينة:</td>
                                <td>${land.city?.name || '-'}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">الحي:</td>
                                <td>${land.district?.name || '-'}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">المنطقة:</td>
                                <td>${land.zone?.name || '-'}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">القطاع:</td>
                                <td>${land.area?.name || '-'}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        `;
    }

    html += '</div>';

    document.getElementById('barcodeResultBody').innerHTML = html;
}

/**
 * Render not found result
 */
function renderBarcodeNotFound(barcode, message) {
    document.getElementById('barcodeResultBody').innerHTML = `
        <div class="text-center py-5">
            <div class="avatar avatar-lg bg-danger-subtle text-danger rounded-circle mx-auto mb-3">
                <i class="ti ti-barcode-off fs-1"></i>
            </div>
            <h5 class="text-danger mb-2">لم يتم العثور على الملف</h5>
            <p class="text-muted mb-3">الباركود: <strong>${barcode}</strong></p>
            <p class="text-muted">${message || 'تأكد من صحة الباركود وحاول مرة أخرى'}</p>
            <div class="mt-4">
                <button class="btn btn-outline-dark" onclick="document.getElementById('barcodeSearchInput').focus(); bootstrap.Modal.getInstance(document.getElementById('barcodeResultModal')).hide();">
                    <i class="ti ti-scan me-1"></i>إعادة المسح
                </button>
            </div>
        </div>
    `;
    document.getElementById('viewClientFromBarcodeBtn').style.display = 'none';
}

// ==================== Show File Functions ====================
function showFile(id) {
    console.log('showFile called with id:', id);

    const modalElement = document.getElementById('showFileModal');
    if (!modalElement) {
        console.error('Modal element #showFileModal not found');
        showToast.error('خطأ: لم يتم العثور على نافذة العرض');
        return;
    }

    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    // Reset modal body to loading state
    document.getElementById('fileModalBody').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
            <p class="mt-3 text-muted">جاري تحميل بيانات الملف...</p>
        </div>
    `;

    fetch(`{{ url('admin/files') }}/${id}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(res => {
            console.log('Response status:', res.status);
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('File data received:', data);
            if (data.success) {
                try {
                    renderFileDetails(data.file, data.pdf_url);
                    console.log('renderFileDetails completed successfully');
                } catch (error) {
                    console.error('Error in renderFileDetails:', error);
                    document.getElementById('fileModalBody').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="ti ti-alert-circle me-2"></i>
                            خطأ في عرض التفاصيل: ${error.message}
                        </div>
                    `;
                }
            } else {
                document.getElementById('fileModalBody').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i>
                        ${data.message || data.error || 'حدث خطأ أثناء تحميل البيانات'}
                    </div>
                `;
            }
        })
        .catch(err => {
            console.error('Error fetching file:', err);
            document.getElementById('fileModalBody').innerHTML = `
                <div class="alert alert-danger">
                    <i class="ti ti-alert-circle me-2"></i>
                    حدث خطأ في الاتصال: ${err.message}
                </div>
            `;
        });
}

function renderFileDetails(file, pdfUrl) {
    console.log('renderFileDetails called', { file, pdfUrl });

    const titleElement = document.getElementById('fileModalTitle');
    const modalBody = document.getElementById('fileModalBody');

    if (!titleElement) {
        console.error('fileModalTitle element not found');
        return;
    }
    if (!modalBody) {
        console.error('fileModalBody element not found');
        return;
    }

    titleElement.textContent = file.file_name;

    let html = `
        <!-- PDF Viewer Section (Hidden by default) -->
        <div id="pdfViewerSection" class="d-none mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="ti ti-file-type-pdf text-danger me-2"></i>
                        <span id="currentPdfTitle">معاينة الملف</span>
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="closePdfViewer()">
                        <i class="ti ti-x me-1"></i>إغلاق المعاينة
                    </button>
                </div>
                <div class="card-body p-0">
                    <iframe id="pdfIframe" style="width: 100%; height: 600px; border: none;" src=""></iframe>
                </div>
            </div>
        </div>

        <!-- File Info Section -->
        <div id="fileInfoSection">
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
                                    ${file.barcode ? '<button class="btn btn-sm btn-outline-light text-white" onclick="copyFileBarcode(\'' + file.barcode + '\')"><i class="ti ti-copy"></i></button>' : ''}
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
                                    <div class="avatar avatar-sm bg-success-subtle text-success rounded me-3">
                                        <i class="ti ti-map-pin"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">القطعة</small>
                                        <strong>${file.land?.land_no || 'غير محدد'}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-info-subtle text-info rounded me-3">
                                        <i class="ti ti-building"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">موقع التخزين</small>
                                        <strong>${file.room?.name || 'غير محدد'} - ${file.rack?.name || ''}</strong>
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
                            <button type="button" class="btn btn-primary w-100 mb-2" onclick="viewPdfInModal('${pdfUrl}', '${file.file_name}')">
                                <i class="ti ti-eye me-2"></i>عرض في المعاينة
                            </button>
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

        <!-- Sub-Files Section -->
        ${file.subFiles && file.subFiles.length > 0 ? `
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0 text-dark">
                        <i class="ti ti-folders me-2"></i>
                        الملفات الفرعية المستخرجة (${file.subFiles.length})
                    </h5>
                    <small class="text-dark-50">الملفات التي تم إنشاؤها بناءً على أنواع المحتوى المحددة</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>نوع المحتوى</th>
                                    <th width="150">نطاق الصفحات</th>
                                    <th width="100">عدد الصفحات</th>
                                    <th width="150" class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${file.subFiles.map((subFile, index) => {
                                    const item = subFile.items && subFile.items[0];
                                    const fileItem = subFile.file_items && subFile.file_items[0];
                                    const fromPage = fileItem?.from_page || '-';
                                    const toPage = fileItem?.to_page || '-';
                                    const subPdfUrl = subFile.media && subFile.media[0] ? subFile.media[0].original_url : null;

                                    return `
                                        <tr>
                                            <td>
                                                <div class="avatar avatar-sm bg-primary-subtle text-primary rounded">
                                                    <i class="ti ti-file-text"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong class="d-block">${item?.name || 'غير محدد'}</strong>
                                                    <small class="text-muted">${item?.description || ''}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info">
                                                    ${fromPage} - ${toPage}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success">
                                                    ${subFile.pages_count} صفحة
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                ${subPdfUrl ? `
                                                    <button type="button" class="btn btn-sm btn-info" title="عرض في المعاينة" onclick="viewPdfInModal('${subPdfUrl}', '${item?.name || 'ملف فرعي'}')">
                                                        <i class="ti ti-eye"></i>
                                                    </button>
                                                    <a href="${subPdfUrl}" target="_blank" class="btn btn-sm btn-secondary" title="فتح في تبويب جديد">
                                                        <i class="ti ti-external-link"></i>
                                                    </a>
                                                    <a href="${subPdfUrl}" download class="btn btn-sm btn-primary" title="تحميل">
                                                        <i class="ti ti-download"></i>
                                                    </a>
                                                ` : '<span class="text-muted">غير متوفر</span>'}
                                            </td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        ` : ''}

        <!-- All Items Summary Section -->
        ${file.items && file.items.length > 0 ? `
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="ti ti-list me-2"></i>ملخص أنواع المحتوى (${file.items.length})</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        ${file.items.map(item => `
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 border rounded">
                                    <i class="ti ti-file-text text-primary me-2"></i>
                                    <div class="flex-grow-1">
                                        <strong class="d-block">${item.name}</strong>
                                        ${item.pivot ? `
                                            <small class="text-muted">
                                                صفحات ${item.pivot.from_page} - ${item.pivot.to_page}
                                            </small>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        ` : ''}
        </div>
    `;

    modalBody.innerHTML = html;
}

function viewPdfInModal(pdfUrl, title) {
    // Hide file info section
    document.getElementById('fileInfoSection').classList.add('d-none');

    // Show PDF viewer section
    const pdfViewerSection = document.getElementById('pdfViewerSection');
    pdfViewerSection.classList.remove('d-none');

    // Update title and load PDF
    document.getElementById('currentPdfTitle').textContent = title;
    document.getElementById('pdfIframe').src = pdfUrl;

    // Scroll to top of modal
    document.getElementById('fileModalBody').scrollTop = 0;
}

function closePdfViewer() {
    // Hide PDF viewer section
    document.getElementById('pdfViewerSection').classList.add('d-none');

    // Show file info section
    document.getElementById('fileInfoSection').classList.remove('d-none');

    // Clear iframe
    document.getElementById('pdfIframe').src = '';
}

function copyFileBarcode(barcode) {
    navigator.clipboard.writeText(barcode).then(() => {
        showToast.success('تم نسخ الباركود');
    }).catch(() => {
        showToast.error('فشل نسخ الباركود');
    });
}
</script>
