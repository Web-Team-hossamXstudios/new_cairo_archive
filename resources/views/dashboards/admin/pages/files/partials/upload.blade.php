<!-- Upload File Modal -->
<div class="modal fade" id="uploadFileModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="height: 100vh;">
            <div class="modal-header modal-header-primary">
                <h5 class="modal-title">
                    <i class="ti ti-upload"></i>
                    رفع ملف PDF جديد
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadFileForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="height: calc(107vh - 140px); max-height: calc(107vh - 200px);overflow: hidden;">
                    <div class="row g-0" style="height: 100%;">
                        <!-- Left Side: PDF Preview -->
                        <div class="col-md-6 border-end" style="height: 100%; overflow-y: auto; background: #f8f9fa;">
                            <div class="p-4">
                                <h6 class="mb-3 text-muted">
                                    <i class="ti ti-file-text me-2"></i>
                                    معاينة الصفحة الأولى من الملف
                                </h6>
                                <div id="pdfPreviewContainer" class="text-center">
                                    <div class="alert alert-info" id="pdfPlaceholder">
                                        <i class="ti ti-upload fs-1 mb-3 d-block"></i>
                                        <p class="mb-0">قم برفع ملف PDF لمعاينة الصفحة الأولى</p>
                                    </div>
                                    <canvas id="pdfCanvas" class="border rounded shadow-sm" style="max-width: 100%; display: none;"></canvas>
                                    <div id="pdfLoading" class="d-none">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">جاري التحميل...</span>
                                        </div>
                                        <p class="mt-2 text-muted">جاري تحميل المعاينة...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Form -->
                        <div class="col-md-6" style="height: 100%; overflow-y: auto;">
                            <div class="p-4">
                                <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">العميل <span class="text-danger">*</span></label>
                            <select name="client_id" class="form-select" required
                                onchange="loadClientLands(this.value)">
                                <option value="">اختر العميل</option>
                                @foreach ($clients ?? [] as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">القطعة <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="land_id" id="landSelect" class="form-select" required>
                                    <option value="">اختر القطعة</option>
                                </select>
                                <button type="button" class="btn btn-outline-primary" onclick="toggleNewLandForm()">
                                    <i class="ti ti-plus"></i> قطعه جديدة
                                </button>
                            </div>
                        </div>

                        <!-- New Land Form (Hidden by default) -->
                        <div id="newLandForm" class="col-12 d-none">
                            <div class="card border-primary">
                                <div
                                    class="card-header bg-primary-subtle d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-primary"><i class="ti ti-map-pin me-2"></i>إضافة قطعه جديدة</h6>
                                    <button type="button" class="btn-close btn-sm"
                                        onclick="toggleNewLandForm()"></button>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">رقم القطعة</label>
                                            <input type="text" name="new_land_no" id="newLandNo" class="form-control"
                                                placeholder="رقم القطعة">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">رقم الوحدة</label>
                                            <input type="text" name="new_unit_no" id="newUnitNo" class="form-control"
                                                placeholder="رقم الوحدة">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">المحافظة <span
                                                    class="text-danger">*</span></label>
                                            <select name="new_governorate_id" id="newGovernorateId" class="form-select"
                                                onchange="loadNewLandCities(this.value)">
                                                <option value="">اختر المحافظة</option>
                                                @foreach ($governorates ?? [] as $gov)
                                                    <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">المدينة</label>
                                            <select name="new_city_id" id="newCityId" class="form-select"
                                                onchange="loadNewLandDistricts(this.value)">
                                                <option value="">اختر المدينة</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">الحي</label>
                                            <select name="new_district_id" id="newDistrictId" class="form-select"
                                                onchange="loadNewLandZones(this.value)">
                                                <option value="">اختر الحي</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">المنطقة</label>
                                            <select name="new_zone_id" id="newZoneId" class="form-select"
                                                onchange="loadNewLandAreas(this.value)">
                                                <option value="">اختر المنطقة</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">القطاع</label>
                                            <select name="new_area_id" id="newAreaId" class="form-select">
                                                <option value="">اختر القطاع</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">العنوان</label>
                                            <textarea name="new_address" id="newAddress" class="form-control" rows="2" placeholder="العنوان التفصيلي"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">ملاحظات</label>
                                            <textarea name="new_notes" id="newNotes" class="form-control" rows="2" placeholder="ملاحظات إضافية"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Physical Location Collapsible -->
                        <div class="col-12">
                            <div class="card border" style="background-color: #f3f4f6;">
                                <div class="card-header p-0 border-0" style="background-color: #f3f4f6;">
                                    <button class="btn w-100 text-start p-3 text-dark d-flex align-items-center justify-content-between" type="button" onclick="toggleStorageLocation(event)" style="background-color: #f3f4f6; border: none;">
                                        <span>
                                            <i class="ti ti-map-pin me-2"></i>
                                            اضافة موقع تخزين
                                        </span>
                                        <i class="ti ti-chevron-down" id="storageChevron"></i>
                                    </button>
                                </div>
                                <div id="storageLocationCollapse" class="collapse">
                                    <div class="card-body bg-white">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">الغرفة</label>
                                                <select name="room_id" id="roomSelect" class="form-select" onchange="loadLanes(this.value)">
                                                    <option value="">اختر الغرفة</option>
                                                    @foreach ($rooms ?? [] as $room)
                                                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">الممر</label>
                                                <select name="lane_id" id="laneSelect" class="form-select" onchange="loadStands(this.value)">
                                                    <option value="">اختر الممر</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">الستاند</label>
                                                <select name="stand_id" id="standSelect" class="form-select" onchange="loadRacks(this.value)">
                                                    <option value="">اختر الستاند</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">الرف</label>
                                                <select name="rack_id" id="rackSelect" class="form-select">
                                                    <option value="">اختر الرف</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">ملف PDF <span class="text-danger">*</span></label>
                            <input type="file" name="document" id="pdfFileInput" class="form-control" accept=".pdf" required onchange="previewPDF(this)">
                            <small class="text-muted">الحد الأقصى: 50 ميجابايت</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label d-flex align-items-center justify-content-between">
                                <span>
                                    <i class="ti ti-checkbox me-2 text-primary"></i>
                                    أنواع المحتوى (حدد نوع المحتوى ونطاق الصفحات)
                                </span>
                                <span class="badge bg-primary-subtle text-primary" id="selectedItemsCount">0
                                    محدد</span>
                            </label>

                            <!-- Search Box -->
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ti ti-search text-muted"></i>
                                    </span>
                                    <input type="text" id="itemSearchInput"
                                        class="form-control border-start-0 ps-0" placeholder="ابحث عن نوع المحتوى..."
                                        onkeyup="filterItems()">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="clearItemSearch()">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="mb-3 d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    onclick="selectAllItems()">
                                    <i class="ti ti-checkbox me-1"></i>تحديد الكل
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="deselectAllItems()">
                                    <i class="ti ti-square me-1"></i>إلغاء الكل
                                </button>
                            </div>

                            <!-- Items Table -->
                            <div class="border rounded"
                                style="max-height: 500px; overflow-y: auto; background: white;">
                                <table class="table table-bordered mb-0" id="itemsTable" style="direction: rtl">
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
                                    <tbody id="itemsContainer">
                                        @php
                                            $rightItems = ($items ?? collect())->where('order', '<=', 37)->values();
                                            $leftItems = ($items ?? collect())->where('order', '>', 37)->values();
                                            $maxRows = max($rightItems->count(), $leftItems->count());
                                        @endphp
                                        @for($i = 0; $i < $maxRows; $i++)
                                            @php
                                                $rightItem = $rightItems->get($i);
                                                $leftItem = $leftItems->get($i);
                                                $searchName = strtolower(($rightItem->name ?? '') . ' ' . ($leftItem->name ?? ''));
                                            @endphp
                                            <tr class="item-row" data-item-name="{{ $searchName }}">
                                                @if($rightItem)
                                                    <td class="align-middle">
                                                        <div class="form-check mb-0">
                                                            <input class="form-check-input item-checkbox"
                                                                type="checkbox" data-item-id="{{ $rightItem->id }}"
                                                                id="item{{ $rightItem->id }}"
                                                                onchange="togglePageRange({{ $rightItem->id }})">
                                                            <label class="form-check-label cursor-pointer"
                                                                for="item{{ $rightItem->id }}">
                                                                {{ $rightItem->name }}
                                                            </label>
                                                        </div>
                                                        <input type="hidden"
                                                            name="items[{{ $rightItem->id }}][item_id]"
                                                            value="{{ $rightItem->id }}">
                                                    </td>
                                                    <td class="text-center align-middle"
                                                        id="fromPageCell{{ $rightItem->id }}">
                                                        <input type="number"
                                                            name="items[{{ $rightItem->id }}][from_page]"
                                                            class="form-control form-control-sm text-center d-none page-input"
                                                            id="fromPage{{ $rightItem->id }}" min="1"
                                                            placeholder="من">
                                                    </td>
                                                    <td class="text-center align-middle"
                                                        id="toPageCell{{ $rightItem->id }}">
                                                        <input type="number"
                                                            name="items[{{ $rightItem->id }}][to_page]"
                                                            class="form-control form-control-sm text-center d-none page-input"
                                                            id="toPage{{ $rightItem->id }}" min="1"
                                                            placeholder="إلى">
                                                    </td>
                                                @else
                                                    <td class="align-middle"></td>
                                                    <td class="text-center align-middle"></td>
                                                    <td class="text-center align-middle"></td>
                                                @endif
                                                @if($leftItem)
                                                    <td class="align-middle">
                                                        <div class="form-check mb-0">
                                                            <input class="form-check-input item-checkbox"
                                                                type="checkbox" data-item-id="{{ $leftItem->id }}"
                                                                id="item{{ $leftItem->id }}"
                                                                onchange="togglePageRange({{ $leftItem->id }})">
                                                            <label class="form-check-label cursor-pointer"
                                                                for="item{{ $leftItem->id }}">
                                                                {{ $leftItem->name }}
                                                            </label>
                                                        </div>
                                                        <input type="hidden"
                                                            name="items[{{ $leftItem->id }}][item_id]"
                                                            value="{{ $leftItem->id }}">
                                                    </td>
                                                    <td class="text-center align-middle"
                                                        id="fromPageCell{{ $leftItem->id }}">
                                                        <input type="number"
                                                            name="items[{{ $leftItem->id }}][from_page]"
                                                            class="form-control form-control-sm text-center d-none page-input"
                                                            id="fromPage{{ $leftItem->id }}" min="1"
                                                            placeholder="من">
                                                    </td>
                                                    <td class="text-center align-middle"
                                                        id="toPageCell{{ $leftItem->id }}">
                                                        <input type="number"
                                                            name="items[{{ $leftItem->id }}][to_page]"
                                                            class="form-control form-control-sm text-center d-none page-input"
                                                            id="toPage{{ $leftItem->id }}" min="1"
                                                            placeholder="إلى">
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
                                <i class="ti ti-bulb text-warning"></i>
                                عند تحديد نطاق الصفحات، سيتم قص الصفحات المحددة وإنشاء ملف فرعي جديد لكل نوع محتوى
                            </small>
                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-upload me-2"></i>
                        رفع ومعالجة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PDF.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    // Configure PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    /**
     * Preview first page of uploaded PDF
     */
    function previewPDF(input) {
        const file = input.files[0];
        if (!file || file.type !== 'application/pdf') {
            return;
        }

        const placeholder = document.getElementById('pdfPlaceholder');
        const canvas = document.getElementById('pdfCanvas');
        const loading = document.getElementById('pdfLoading');

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

    // Reset preview when modal is closed
    document.getElementById('uploadFileModal').addEventListener('hidden.bs.modal', function() {
        const placeholder = document.getElementById('pdfPlaceholder');
        const canvas = document.getElementById('pdfCanvas');
        const loading = document.getElementById('pdfLoading');
        const fileInput = document.getElementById('pdfFileInput');

        canvas.style.display = 'none';
        loading.classList.add('d-none');
        placeholder.classList.remove('d-none');
        placeholder.innerHTML = '<i class="ti ti-upload fs-1 mb-3 d-block"></i><p class="mb-0">قم برفع ملف PDF لمعاينة الصفحة الأولى</p>';

        if (fileInput) {
            fileInput.value = '';
        }
    });

    // Toggle Storage Location Collapse
    function toggleStorageLocation(event) {
        event.preventDefault();
        event.stopPropagation();

        const collapseElement = document.getElementById('storageLocationCollapse');
        const chevron = document.getElementById('storageChevron');

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
</script>
