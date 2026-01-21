<form onsubmit="return storeClient(this)">
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#clientDataTab">بيانات العميل</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#landsTab">الأراضي</a></li>
    </ul>

    <div class="tab-content">
        <!-- Client Data Tab -->
        <div class="tab-pane fade show active" id="clientDataTab">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">اسم العميل <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="أدخل اسم العميل">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الرقم القومي</label>
                    <input type="text" name="national_id" class="form-control" maxlength="14" placeholder="الرقم القومي (14 رقم)">
                </div>
                <div class="col-md-6">
                    <label class="form-label">كود العميل</label>
                    <div class="input-group">
                        <input type="text" name="client_code" class="form-control" id="clientCode" placeholder="كود العميل">
                        <button type="button" class="btn btn-outline-primary" onclick="generateCode()">
                            <i class="ti ti-refresh"></i> توليد
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">التليفون</label>
                    <input type="text" name="telephone" class="form-control" placeholder="رقم التليفون">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الموبايل</label>
                    <input type="text" name="mobile" class="form-control" placeholder="رقم الموبايل">
                </div>
                <div class="col-md-6">
                    <label class="form-label">أكواد الملفات</label>
                    <input type="text" name="files_code_text" class="form-control" placeholder="أكواد الملفات (مفصولة بفاصلة)">
                </div>
                <div class="col-12">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="ملاحظات إضافية"></textarea>
                </div>
            </div>
        </div>

        <!-- Lands Tab -->
        <div class="tab-pane fade" id="landsTab">
            <div id="landsContainer">
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-1"></i>
                    يمكنك إضافة أراضي للعميل بعد إنشائه أو إضافتها الآن من هنا.
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addLandRow()">
                <i class="ti ti-plus me-1"></i> إضافة أرض
            </button>
        </div>
    </div>

    <div class="modal-footer mt-3 px-0 pb-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-check me-1"></i> حفظ
        </button>
    </div>
</form>

<script>
    let landIndex = 0;
    const governorates = @json($governorates);

    function generateCode() {
        fetch("{{ route('admin.clients.generate-code') }}")
            .then(res => res.json())
            .then(data => {
                document.getElementById('clientCode').value = data.code;
            });
    }

    function addLandRow() {
        const container = document.getElementById('landsContainer');
        const html = `
            <div class="card mb-3 land-row" data-index="${landIndex}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">أرض ${landIndex + 1}</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLandRow(${landIndex})">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">المحافظة <span class="text-danger">*</span></label>
                            <select name="lands[${landIndex}][governorate_id]" class="form-select" required onchange="loadCities(this, ${landIndex})">
                                <option value="">اختر المحافظة</option>
                                ${governorates.map(g => `<option value="${g.id}">${g.name}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">المدينة</label>
                            <select name="lands[${landIndex}][city_id]" class="form-select" id="cities_${landIndex}" disabled>
                                <option value="">اختر المدينة</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">الحي</label>
                            <select name="lands[${landIndex}][district_id]" class="form-select" id="districts_${landIndex}" disabled>
                                <option value="">اختر الحي</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">رقم الأرض <span class="text-danger">*</span></label>
                            <input type="text" name="lands[${landIndex}][land_no]" class="form-control" required placeholder="رقم الأرض">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">رقم الوحدة</label>
                            <input type="text" name="lands[${landIndex}][unit_no]" class="form-control" placeholder="رقم الوحدة">
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        landIndex++;
    }

    function removeLandRow(index) {
        document.querySelector(`.land-row[data-index="${index}"]`)?.remove();
    }

    function loadCities(select, index) {
        const governorateId = select.value;
        const citiesSelect = document.getElementById(`cities_${index}`);

        if (!governorateId) {
            citiesSelect.innerHTML = '<option value="">اختر المدينة</option>';
            citiesSelect.disabled = true;
            return;
        }

        fetch(`{{ url('admin/lands/cities') }}/${governorateId}`)
            .then(res => res.json())
            .then(data => {
                citiesSelect.innerHTML = '<option value="">اختر المدينة</option>' +
                    data.cities.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
                citiesSelect.disabled = false;
            });
    }
</script>
