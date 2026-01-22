<!-- Upload File Modal for Client -->
<div class="modal fade" id="uploadFileModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="height: auto;">
            <div class="modal-header modal-header-primary">
                <h5 class="modal-title">
                    <i class="ti ti-upload"></i>
                    رفع ملف PDF للعميل
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadClientFileForm" enctype="multipart/form-data" onsubmit="return handleClientFileUpload(event)">
                @csrf
                <input type="hidden" name="client_id" id="uploadClientId">
                <div class="modal-body" style="height: calc(107vh - 140px); max-height: calc(107vh - 200px);overflow: hidden;">
                    <div class="row g-0" style="height: 100%;">
                        <!-- Left Side: PDF Preview -->
                        <div class="col-md-6 border-end" style="height: 100%; overflow-y: auto; background: #f8f9fa;">
                            <div class="p-4">
                                <h6 class="mb-3 text-muted">
                                    <i class="ti ti-file-text me-2"></i>
                                    معاينة الصفحة الأولى من الملف
                                </h6>
                                <div id="clientPdfPreviewContainer" class="text-center">
                                    <div class="alert alert-info" id="clientPdfPlaceholder">
                                        <i class="mb-3 ti ti-upload fs-1 d-block"></i>
                                        <p class="mb-0">قم برفع ملف PDF لمعاينة الصفحة الأولى</p>
                                    </div>
                                    <canvas id="clientPdfCanvas" class="rounded border shadow-sm" style="max-width: 100%; display: none;"></canvas>
                                    <div id="clientPdfLoading" class="d-none">
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
                        <!-- Client Info Alert -->
                        <div class="col-12">
                            <div class="mb-3 alert alert-info">
                                <i class="ti ti-info-circle me-1"></i>
                                <strong>العميل:</strong> <span id="uploadClientName"></span>
                            </div>
                        </div>

                        <!-- Land Selection -->
                        <div class="mt-1 col-md-12">
                            <label class="form-label">القطعة <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="land_id" id="uploadLandId" class="form-select">
                                    <option value="">اختر القطعة</option>
                                </select>
                                <button type="button" class="btn btn-outline-primary" onclick="toggleClientNewLandForm()">
                                    <i class="ti ti-plus"></i> قطعه جديدة
                                </button>
                            </div>
                            <div class="invalid-feedback" id="landIdError"></div>
                        </div>

                        <!-- New Land Form (Hidden by default) -->
                        <div id="clientNewLandForm" class="col-12 d-none">
                            <div class="card border-primary">
                                <div class="card-header bg-primary-subtle d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-primary"><i class="ti ti-map-pin me-2"></i>إضافة قطعه جديدة</h6>
                                    <button type="button" class="btn-close btn-sm" onclick="toggleClientNewLandForm()"></button>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <label class="form-label">المحافظة <span class="text-danger">*</span></label>
                                            <select name="new_governorate_id" id="clientNewGovernorateId" class="form-select" disabled style="background-color: #e9ecef; cursor: not-allowed;">
                                                @foreach(\App\Models\Governorate::orderBy('name')->get() as $gov)
                                                    <option value="{{ $gov->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $gov->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="new_governorate_id" id="clientNewGovernorateIdHidden" value="{{ \App\Models\Governorate::orderBy('name')->first()->id ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">المدينة</label>
                                            <select name="new_city_id" id="clientNewCityId" class="form-select" disabled style="background-color: #e9ecef; cursor: not-allowed;">
                                                <option value="">اختر المدينة</option>
                                            </select>
                                            <input type="hidden" name="new_city_id" id="clientNewCityIdHidden" value="">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">الحي</label>
                                            <select name="new_district_id" id="clientNewDistrictId" class="form-select" onchange="loadClientNewLandZones(this.value); updateClientNewLandAddress();">
                                                <option value="">اختر الحي</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">المنطقة</label>
                                            <select name="new_zone_id" id="clientNewZoneId" class="form-select" onchange="loadClientNewLandAreas(this.value); updateClientNewLandAddress();">
                                                <option value="">اختر المنطقة</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">المجاورة</label>
                                            <select name="new_area_id" id="clientNewAreaId" class="form-select" onchange="updateClientNewLandAddress();">
                                                <option value="">اختر المجاورة</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">رقم القطعة</label>
                                            <input type="text" name="new_land_no" id="clientNewLandNo" class="form-control" placeholder="رقم القطعة" oninput="updateClientNewLandAddress();">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">العنوان</label>
                                            <textarea name="new_address" id="clientNewAddress" class="form-control" rows="2" placeholder="العنوان التفصيلي" readonly style="background-color: #e9ecef;"></textarea>
                                            <small class="text-muted"><i class="ti ti-info-circle me-1"></i>يتم إنشاء العنوان تلقائياً من البيانات أعلاه</small>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">ملاحظات</label>
                                            <textarea name="new_notes" id="clientNewNotes" class="form-control" rows="2" placeholder="ملاحظات إضافية"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-success w-100" onclick="createNewLandAjax()">
                                                <i class="ti ti-plus me-1"></i>إنشاء القطعة وتحديدها
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Physical Location Collapsible -->
                        <div class="col-12">
                            <div class="border card" style="background-color: #f3f4f6;">
                                <div class="p-0 border-0 card-header" style="background-color: #f3f4f6;">
                                    <button class="p-3 btn w-100 text-start text-dark d-flex align-items-center justify-content-between" type="button" onclick="toggleClientStorageLocation(event)" style="background-color: #f3f4f6; border: none;">
                                        <span>
                                            <i class="ti ti-map-pin me-2"></i>
                                            اضافة موقع تخزين
                                        </span>
                                        <i class="ti ti-chevron-down" id="clientStorageChevron"></i>
                                    </button>
                                </div>
                                <div id="clientStorageLocationCollapse" class="collapse">
                                    <div class="bg-white card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">الغرفة</label>
                                                    <select name="room_id" id="clientRoomSelect" class="form-select" onchange="loadClientLanes(this.value)">
                                                        <option value="">اختر الغرفة</option>
                                                        @foreach(\App\Models\Room::orderBy('name')->get() as $room)
                                                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">الممر</label>
                                                    <select name="lane_id" id="clientLaneSelect" class="form-select" onchange="loadClientStands(this.value)">
                                                        <option value="">اختر الممر</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">الستاند</label>
                                                    <select name="stand_id" id="clientStandSelect" class="form-select" onchange="loadClientRacks(this.value)">
                                                        <option value="">اختر الستاند</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">الرف</label>
                                                    <select name="rack_id" id="clientRackSelect" class="form-select">
                                                        <option value="">اختر الرف</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File Name/Number -->
                        <div class="col-12">
                            <label class="form-label">رقم الملف <span class="text-danger">*</span></label>
                            <input type="text" name="file_name" id="clientFileName" class="form-control" placeholder="أدخل رقم الملف" required>
                            <small class="text-muted"><i class="ti ti-info-circle me-1"></i>سيتم حفظ هذا الرقم كاسم للملف</small>
                        </div>

                        <!-- PDF File -->
                        <div class="col-12">
                            <label class="form-label">ملف PDF <span class="text-danger">*</span></label>
                            <input type="file" name="document" id="clientPdfFileInput" class="form-control" accept=".pdf" required onchange="previewClientPDF(this)">
                            <small class="text-muted">الحد الأقصى: 50 ميجابايت</small>
                        </div>

                        <!-- Content Types with Page Ranges -->
                        <div class="col-12">
                            <label class="form-label d-flex align-items-center justify-content-between">
                                <span>
                                    <i class="ti ti-checkbox me-2 text-primary"></i>
                                    أنواع المحتوى (حدد نوع المحتوى ونطاق الصفحات)
                                </span>
                                <span class="badge bg-primary-subtle text-primary" id="clientSelectedItemsCount">0 محدد</span>
                            </label>

                            <!-- Search Box -->
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ti ti-search text-muted"></i>
                                    </span>
                                    <input type="text" id="clientItemSearchInput" class="form-control border-start-0 ps-0"
                                        placeholder="ابحث عن نوع المحتوى..." onkeyup="filterClientItems()">
                                    <button class="btn btn-outline-secondary" type="button" onclick="clearClientItemSearch()">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="gap-2 mb-3 d-flex">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllClientItems()">
                                    <i class="ti ti-checkbox me-1"></i>تحديد الكل
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllClientItems()">
                                    <i class="ti ti-square me-1"></i>إلغاء الكل
                                </button>
                            </div>

                            <!-- Items Table - Remove hidden inputs, only use checkboxes -->
                            <div class="rounded border" style="max-height: 100%; overflow-y: auto; background: white;">
                                <table class="table mb-0 table-bordered" id="clientItemsTable" style="direction: rtl">
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
                                    <tbody id="clientItemsContainer">
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
                                            <tr class="client-item-row" data-item-name="{{ $searchName }}">
                                                @if($rightItem)
                                                    <td class="align-middle">
                                                        <div class="mb-0 form-check">
                                                            <input class="form-check-input client-item-checkbox" type="checkbox"
                                                                data-item-id="{{ $rightItem->id }}"
                                                                id="clientItem{{ $rightItem->id }}"
                                                                onchange="toggleClientPageRange({{ $rightItem->id }})">
                                                            <label class="cursor-pointer form-check-label" for="clientItem{{ $rightItem->id }}">
                                                                {{ $rightItem->name }}
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <select class="text-center form-select form-select-sm d-none page-select"
                                                            data-item-id="{{ $rightItem->id }}"
                                                            data-type="from"
                                                            id="clientFromPage{{ $rightItem->id }}">
                                                            <option value=""></option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <select class="text-center form-select form-select-sm d-none page-select"
                                                            data-item-id="{{ $rightItem->id }}"
                                                            data-type="to"
                                                            id="clientToPage{{ $rightItem->id }}">
                                                            <option value=""></option>
                                                        </select>
                                                    </td>
                                                @else
                                                    <td class="align-middle"></td>
                                                    <td class="text-center align-middle"></td>
                                                    <td class="text-center align-middle"></td>
                                                @endif
                                                @if($leftItem)
                                                    <td class="align-middle">
                                                        <div class="mb-0 form-check">
                                                            <input class="form-check-input client-item-checkbox" type="checkbox"
                                                                data-item-id="{{ $leftItem->id }}"
                                                                id="clientItem{{ $leftItem->id }}"
                                                                onchange="toggleClientPageRange({{ $leftItem->id }})">
                                                            <label class="cursor-pointer form-check-label" for="clientItem{{ $leftItem->id }}">
                                                                {{ $leftItem->name }}
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <select class="text-center form-select form-select-sm d-none page-select"
                                                            data-item-id="{{ $leftItem->id }}"
                                                            data-type="from"
                                                            id="clientFromPage{{ $leftItem->id }}">
                                                            <option value="">من</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <select class="text-center form-select form-select-sm d-none page-select"
                                                            data-item-id="{{ $leftItem->id }}"
                                                            data-type="to"
                                                            id="clientToPage{{ $leftItem->id }}">
                                                            <option value="">إلى</option>
                                                        </select>
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
                            <small class="mt-2 text-muted d-block">
                                <i class="ti ti-bulb text-warning"></i>
                                عند تحديد نطاق الصفحات، سيتم قص الصفحات المحددة وإنشاء ملف فرعي جديد لكل نوع محتوى
                            </small>
                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary" id="uploadClientFileBtn">
                            <i class="ti ti-upload me-2"></i>
                            رفع ومعالجة
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- PDF.js Library for Client Upload -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
// ============================================
// MAIN FORM SUBMISSION HANDLER
// ============================================
function handleClientFileUpload(event) {
    event.preventDefault();
    event.stopPropagation();

    console.log('=== handleClientFileUpload called ===');

    const form = document.getElementById('uploadClientFileForm');
    const submitBtn = document.getElementById('uploadClientFileBtn');

    // Clear previous errors
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    // Validate client_id
    const clientId = document.getElementById('uploadClientId').value;
    if (!clientId) {
        showToast.error('خطأ: لم يتم تحديد العميل');
        console.error('Client ID is missing');
        return false;
    }
    console.log('Client ID:', clientId);

    // Validate land selection
    const landId = document.getElementById('uploadLandId').value;
    const newLandFormVisible = !document.getElementById('clientNewLandForm').classList.contains('d-none');

    if (!landId && !newLandFormVisible) {
        showToast.error('يجب اختيار القطعة أو إنشاء قطعة جديدة');
        document.getElementById('uploadLandId').classList.add('is-invalid');
        return false;
    }
    console.log('Land ID:', landId, 'New Land Form Visible:', newLandFormVisible);

    // Validate file
    const fileInput = document.getElementById('clientPdfFileInput');
    if (!fileInput.files || fileInput.files.length === 0) {
        showToast.error('يجب اختيار ملف PDF');
        fileInput.classList.add('is-invalid');
        return false;
    }
    console.log('File selected:', fileInput.files[0].name);

    // Validate file name
    const fileName = document.getElementById('clientFileName').value.trim();
    if (!fileName) {
        showToast.error('يجب إدخال رقم الملف');
        document.getElementById('clientFileName').classList.add('is-invalid');
        return false;
    }
    console.log('File name:', fileName);

    // Prepare FormData
    const formData = new FormData(form);

    // Collect selected items with page ranges
    const selectedItems = [];
    document.querySelectorAll('.client-item-checkbox:checked').forEach(checkbox => {
        const itemId = checkbox.dataset.itemId;
        const fromPageSelect = document.getElementById('clientFromPage' + itemId);
        const toPageSelect = document.getElementById('clientToPage' + itemId);
        const fromPage = fromPageSelect ? fromPageSelect.value : '';
        const toPage = toPageSelect ? toPageSelect.value : '';

        selectedItems.push({
            item_id: itemId,
            from_page: fromPage || null,
            to_page: toPage || null
        });
    });

    // Add items as JSON
    formData.append('items_json', JSON.stringify(selectedItems));

    console.log('Selected items:', selectedItems);
    console.log('FormData entries:');
    for (let pair of formData.entries()) {
        if (pair[0] !== 'document') {
            console.log('  ' + pair[0] + ': ' + pair[1]);
        } else {
            console.log('  document: [File]');
        }
    }

    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الرفع...';

    // Send request
    fetch('{{ route("admin.files.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            return response.json().then(data => {
                throw { status: response.status, data: data };
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showToast.success(data.message || 'تم رفع الملف بنجاح! جاري معالجته...');

            // Hide modal
            const modalEl = document.getElementById('uploadFileModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }

            // Reload page after delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Handle validation errors
            if (data.errors) {
                const errorMessages = Object.values(data.errors).flat();
                errorMessages.forEach(msg => showToast.error(msg));
                console.error('Validation errors:', data.errors);
            } else {
                showToast.error(data.error || data.message || 'حدث خطأ أثناء رفع الملف');
            }
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        if (error.data && error.data.errors) {
            const errorMessages = Object.values(error.data.errors).flat();
            errorMessages.forEach(msg => showToast.error(msg));
        } else if (error.data && error.data.message) {
            showToast.error(error.data.message);
        } else {
            showToast.error('حدث خطأ في الاتصال بالخادم');
        }
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ti ti-upload me-2"></i>رفع ومعالجة';
    });

    return false; // Prevent default form submission
}

// ============================================
// HELPER FUNCTIONS
// ============================================

// Auto-populate address from location selections
function updateClientNewLandAddress() {
    const governorateSelect = document.getElementById('clientNewGovernorateId');
    const citySelect = document.getElementById('clientNewCityId');
    const districtSelect = document.getElementById('clientNewDistrictId');
    const zoneSelect = document.getElementById('clientNewZoneId');
    const areaSelect = document.getElementById('clientNewAreaId');
    const landNo = document.getElementById('clientNewLandNo').value;

    let addressParts = [];

    if (governorateSelect.value) {
        const selectedOption = governorateSelect.options[governorateSelect.selectedIndex];
        addressParts.push(selectedOption.text);
    }

    if (citySelect.value) {
        const selectedOption = citySelect.options[citySelect.selectedIndex];
        addressParts.push(selectedOption.text);
    }

    if (districtSelect.value) {
        const selectedOption = districtSelect.options[districtSelect.selectedIndex];
        addressParts.push(selectedOption.text);
    }

    if (zoneSelect.value) {
        const selectedOption = zoneSelect.options[zoneSelect.selectedIndex];
        addressParts.push(selectedOption.text);
    }

    if (areaSelect.value) {
        const selectedOption = areaSelect.options[areaSelect.selectedIndex];
        addressParts.push(selectedOption.text);
    }

    if (landNo) {
        addressParts.push('قطعة رقم: ' + landNo);
    }

    document.getElementById('clientNewAddress').value = addressParts.join(' - ');
}

// Initialize address on form open
document.addEventListener('DOMContentLoaded', function() {
    // Trigger initial city loading and address population when form is shown
    const newLandFormObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.target.id === 'clientNewLandForm' && !mutation.target.classList.contains('d-none')) {
                // Trigger loading cities for the selected governorate
                const governorateSelect = document.getElementById('clientNewGovernorateId');
                const governorateHiddenInput = document.getElementById('clientNewGovernorateIdHidden');
                if (governorateSelect && governorateSelect.value) {
                    loadClientNewLandCities(governorateSelect.value);
                } else if (governorateHiddenInput && governorateHiddenInput.value) {
                    loadClientNewLandCities(governorateHiddenInput.value);
                }
                updateClientNewLandAddress();
            }
        });
    });

    const newLandForm = document.getElementById('clientNewLandForm');
    if (newLandForm) {
        newLandFormObserver.observe(newLandForm, { attributes: true, attributeFilter: ['class'] });
    }

    // Reset modal when hidden
    const modalEl = document.getElementById('uploadFileModal');
    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function() {
            // Reset form
            const form = document.getElementById('uploadClientFileForm');
            if (form) form.reset();

            // Reset preview
            const placeholder = document.getElementById('clientPdfPlaceholder');
            const canvas = document.getElementById('clientPdfCanvas');
            const loading = document.getElementById('clientPdfLoading');

            if (canvas) canvas.style.display = 'none';
            if (loading) loading.classList.add('d-none');
            if (placeholder) {
                placeholder.classList.remove('d-none');
                placeholder.innerHTML = '<i class="mb-3 ti ti-upload fs-1 d-block"></i><p class="mb-0">قم برفع ملف PDF لمعاينة الصفحة الأولى</p>';
            }

            // Reset land form
            document.getElementById('clientNewLandForm')?.classList.add('d-none');

            // Reset storage collapse
            document.getElementById('clientStorageLocationCollapse')?.classList.remove('show');

            // Reset checkboxes
            deselectAllClientItems();

            // Clear validation errors
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });
    }
});

// Create New Land via AJAX
function createNewLandAjax() {
    const clientId = document.getElementById('uploadClientId').value;
    const landNo = document.getElementById('clientNewLandNo').value;
    const governorateId = document.getElementById('clientNewGovernorateIdHidden').value;
    const cityId = document.getElementById('clientNewCityIdHidden').value;
    const districtId = document.getElementById('clientNewDistrictId').value;
    const zoneId = document.getElementById('clientNewZoneId').value;
    const areaId = document.getElementById('clientNewAreaId').value;
    const address = document.getElementById('clientNewAddress').value;
    const notes = document.getElementById('clientNewNotes').value;

    // Validation
    if (!clientId) {
        showToast.error('يجب تحديد العميل أولاً');
        return;
    }
    if (!governorateId) {
        showToast.error('يجب اختيار المحافظة');
        return;
    }

    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الإنشاء...';

    fetch('{{ route("admin.lands.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            client_id: clientId,
            land_no: landNo,
            governorate_id: governorateId,
            city_id: cityId || null,
            district_id: districtId || null,
            zone_id: zoneId || null,
            area_id: areaId || null,
            address: address,
            notes: notes
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.land) {
            showToast.success('تم إنشاء القطعة بنجاح');

            // Add the new land to the select dropdown
            const landSelect = document.getElementById('uploadLandId');
            const option = document.createElement('option');
            option.value = data.land.id;
            option.textContent = `${data.land.governorate?.name || ''} - ${data.land.city?.name || ''} - رقم: ${data.land.land_no || 'غير محدد'}`;
            option.selected = true;
            landSelect.appendChild(option);

            // Hide the new land form
            document.getElementById('clientNewLandForm').classList.add('d-none');

            // Clear the form fields
            document.getElementById('clientNewLandNo').value = '';
            document.getElementById('clientNewGovernorateId').value = '';
            document.getElementById('clientNewCityId').innerHTML = '<option value="">اختر المدينة</option>';
            document.getElementById('clientNewDistrictId').innerHTML = '<option value="">اختر الحي</option>';
            document.getElementById('clientNewZoneId').innerHTML = '<option value="">اختر المنطقة</option>';
            document.getElementById('clientNewAreaId').innerHTML = '<option value="">اختر المجاورة</option>';
            document.getElementById('clientNewAddress').value = '';
            document.getElementById('clientNewNotes').value = '';
        } else {
            showToast.error(data.message || 'حدث خطأ أثناء إنشاء القطعة');
        }
    })
    .catch(error => {
        console.error('Create land error:', error);
        showToast.error('حدث خطأ في الاتصال بالخادم');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}
</script>
