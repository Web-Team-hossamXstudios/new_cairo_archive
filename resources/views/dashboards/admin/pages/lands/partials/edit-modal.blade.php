<!-- Edit Land Modal -->
<div class="modal fade" id="editLandModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-warning">
                <h5 class="modal-title">
                    <i class="ti ti-edit"></i>
                    تعديل القطعة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLandForm">
                @csrf
                <input type="hidden" id="editLandId" name="land_id">
                <div class="modal-body">
                    <!-- Client & Land Info Section -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <i class="ti ti-user"></i>
                            <h6>بيانات العميل والقطعة</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">العميل <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-user text-warning"></i></span>
                                    <select name="client_id" id="editClientId" class="form-select" required>
                                        <option value="">اختر العميل</option>
                                        @foreach($clients ?? [] as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->client_code }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">رقم القطعة</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-hash text-warning"></i></span>
                                    <input type="text" name="land_no" id="editLandNo" class="form-control" placeholder="رقم القطعة">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">رقم الوحدة</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-building text-warning"></i></span>
                                    <input type="text" name="unit_no" id="editUnitNo" class="form-control" placeholder="رقم الوحدة">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Geographic Location Section -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <i class="ti ti-map-2"></i>
                            <h6>الموقع الجغرافي</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">المحافظة <span class="text-danger">*</span></label>
                                <select name="governorate_id" id="editGovernorateId" class="form-select" required onchange="loadEditCities(this.value)">
                                    <option value="">اختر المحافظة</option>
                                    @foreach($governorates ?? [] as $gov)
                                        <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">المدينة</label>
                                <select name="city_id" id="editCityId" class="form-select" onchange="loadEditDistricts(this.value)">
                                    <option value="">اختر المدينة</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">الحي</label>
                                <select name="district_id" id="editDistrictId" class="form-select" onchange="loadEditZones(this.value)">
                                    <option value="">اختر الحي</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">المنطقة</label>
                                <select name="zone_id" id="editZoneId" class="form-select" onchange="loadEditAreas(this.value)">
                                    <option value="">اختر المنطقة</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">القطاع</label>
                                <select name="area_id" id="editAreaId" class="form-select">
                                    <option value="">اختر القطاع</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info Section -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <i class="ti ti-info-circle"></i>
                            <h6>معلومات إضافية</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">العنوان</label>
                                <textarea name="address" id="editAddress" class="form-control" rows="2" placeholder="العنوان التفصيلي..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">ملاحظات</label>
                                <textarea name="notes" id="editNotes" class="form-control" rows="2" placeholder="ملاحظات إضافية..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Warning Box -->
                    <div class="warning-box">
                        <i class="ti ti-alert-triangle"></i>
                        <div>
                            <strong>تنبيه:</strong> سيتم تحديث بيانات القطعة فوراً. تأكد من صحة البيانات قبل الحفظ.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-check me-1"></i>حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
