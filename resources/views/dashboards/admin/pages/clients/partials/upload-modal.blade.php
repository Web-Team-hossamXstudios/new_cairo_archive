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
            <form id="uploadClientFileForm" enctype="multipart/form-data">
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
                                        <i class="ti ti-upload fs-1 mb-3 d-block"></i>
                                        <p class="mb-0">قم برفع ملف PDF لمعاينة الصفحة الأولى</p>
                                    </div>
                                    <canvas id="clientPdfCanvas" class="border rounded shadow-sm" style="max-width: 100%; display: none;"></canvas>
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
                            <div class="alert alert-info mb-3">
                                <i class="ti ti-info-circle me-1"></i>
                                <strong>العميل:</strong> <span id="uploadClientName"></span>
                            </div>
                        </div>

                        <!-- Land Selection -->
                        <div class="col-md-12 mt-1">
                            <label class="form-label">الأرض <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="land_id" id="uploadLandId" class="form-select" required>
                                    <option value="">اختر الأرض</option>
                                </select>
                                <button type="button" class="btn btn-outline-primary" onclick="toggleClientNewLandForm()">
                                    <i class="ti ti-plus"></i> أرض جديدة
                                </button>
                            </div>
                        </div>

                        <!-- New Land Form (Hidden by default) -->
                        <div id="clientNewLandForm" class="col-12 d-none">
                            <div class="card border-primary">
                                <div class="card-header bg-primary-subtle d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-primary"><i class="ti ti-map-pin me-2"></i>إضافة أرض جديدة</h6>
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
                                        <div class="col-md-6">
                                            <label class="form-label">رقم الأرض</label>
                                            <input type="text" name="new_land_no" id="clientNewLandNo" class="form-control" placeholder="رقم الأرض" oninput="updateClientNewLandAddress();">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">رقم الوحدة</label>
                                            <input type="text" name="new_unit_no" id="clientNewUnitNo" class="form-control" placeholder="رقم الوحدة" oninput="updateClientNewLandAddress();">
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
                                                <i class="ti ti-plus me-1"></i>إنشاء الأرض وتحديدها
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Physical Location Collapsible -->
                        <div class="col-12">
                            <div class="card border" style="background-color: #f3f4f6;">
                                <div class="card-header p-0 border-0" style="background-color: #f3f4f6;">
                                    <button class="btn w-100 text-start p-3 text-dark d-flex align-items-center justify-content-between" type="button" onclick="toggleClientStorageLocation(event)" style="background-color: #f3f4f6; border: none;">
                                        <span>
                                            <i class="ti ti-map-pin me-2"></i>
                                            اضافة موقع تخزين
                                        </span>
                                        <i class="ti ti-chevron-down" id="clientStorageChevron"></i>
                                    </button>
                                </div>
                                <div id="clientStorageLocationCollapse" class="collapse">
                                    <div class="card-body bg-white">
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
                            <div class="mb-3 d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllClientItems()">
                                    <i class="ti ti-checkbox me-1"></i>تحديد الكل
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllClientItems()">
                                    <i class="ti ti-square me-1"></i>إلغاء الكل
                                </button>
                            </div>

                            <!-- Items Table -->
                            <div class="border rounded" style="max-height: 500px; overflow-y: auto; background: white;">
                                <table class="table table-bordered mb-0" id="clientItemsTable" style="direction: rtl">
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
                                                        <div class="form-check mb-0">
                                                            <input class="form-check-input client-item-checkbox" type="checkbox"
                                                                data-item-id="{{ $rightItem->id }}"
                                                                id="clientItem{{ $rightItem->id }}"
                                                                onchange="toggleClientPageRange({{ $rightItem->id }})">
                                                            <label class="form-check-label cursor-pointer" for="clientItem{{ $rightItem->id }}">
                                                                {{ $rightItem->name }}
                                                            </label>
                                                        </div>
                                                        <input type="hidden" name="items[{{ $rightItem->id }}][item_id]" value="{{ $rightItem->id }}">
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <input type="number" name="items[{{ $rightItem->id }}][from_page]"
                                                            class="form-control form-control-sm text-center d-none page-input"
                                                            id="clientFromPage{{ $rightItem->id }}"
                                                            min="1" placeholder="من">
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <input type="number" name="items[{{ $rightItem->id }}][to_page]"
                                                            class="form-control form-control-sm text-center d-none page-input"
                                                            id="clientToPage{{ $rightItem->id }}"
                                                            min="1" placeholder="إلى">
                                                    </td>
                                                @else
                                                    <td class="align-middle"></td>
                                                    <td class="text-center align-middle"></td>
                                                    <td class="text-center align-middle"></td>
                                                @endif
                                                @if($leftItem)
                                                    <td class="align-middle">
                                                        <div class="form-check mb-0">
                                                            <input class="form-check-input client-item-checkbox" type="checkbox"
                                                                data-item-id="{{ $leftItem->id }}"
                                                                id="clientItem{{ $leftItem->id }}"
                                                                onchange="toggleClientPageRange({{ $leftItem->id }})">
                                                            <label class="form-check-label cursor-pointer" for="clientItem{{ $leftItem->id }}">
                                                                {{ $leftItem->name }}
                                                            </label>
                                                        </div>
                                                        <input type="hidden" name="items[{{ $leftItem->id }}][item_id]" value="{{ $leftItem->id }}">
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <input type="number" name="items[{{ $leftItem->id }}][from_page]"
                                                            class="form-control form-control-sm text-center d-none page-input"
                                                            id="clientFromPage{{ $leftItem->id }}"
                                                            min="1" placeholder="من">
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <input type="number" name="items[{{ $leftItem->id }}][to_page]"
                                                            class="form-control form-control-sm text-center d-none page-input"
                                                            id="clientToPage{{ $leftItem->id }}"
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
                        <button type="submit" class="btn btn-primary">
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
// Auto-populate address from location selections
function updateClientNewLandAddress() {
    const governorateSelect = document.getElementById('clientNewGovernorateId');
    const citySelect = document.getElementById('clientNewCityId');
    const districtSelect = document.getElementById('clientNewDistrictId');
    const zoneSelect = document.getElementById('clientNewZoneId');
    const areaSelect = document.getElementById('clientNewAreaId');
    const landNo = document.getElementById('clientNewLandNo').value;
    const unitNo = document.getElementById('clientNewUnitNo').value;

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

    if (unitNo) {
        addressParts.push('وحدة رقم: ' + unitNo);
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
});

// Create New Land via AJAX
function createNewLandAjax() {
    const clientId = document.getElementById('uploadClientId').value;
    const landNo = document.getElementById('clientNewLandNo').value;
    const unitNo = document.getElementById('clientNewUnitNo').value;
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
            unit_no: unitNo,
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
            showToast.success('تم إنشاء الأرض بنجاح');

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
            document.getElementById('clientNewUnitNo').value = '';
            document.getElementById('clientNewGovernorateId').value = '';
            document.getElementById('clientNewCityId').innerHTML = '<option value="">اختر المدينة</option>';
            document.getElementById('clientNewDistrictId').innerHTML = '<option value="">اختر الحي</option>';
            document.getElementById('clientNewZoneId').innerHTML = '<option value="">اختر المنطقة</option>';
            document.getElementById('clientNewAreaId').innerHTML = '<option value="">اختر المجاورة</option>';
            document.getElementById('clientNewAddress').value = '';
            document.getElementById('clientNewNotes').value = '';
        } else {
            showToast.error(data.message || 'حدث خطأ أثناء إنشاء الأرض');
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
