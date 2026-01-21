<!-- Create Client Modal -->
<div class="modal fade" id="createClientModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <h5 class="modal-title">
                    <i class="ti ti-user-plus"></i>
                    إضافة عميل جديد
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createClientForm">
                @csrf
                <div class="modal-body">
                    <!-- Client Information Section -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <i class="ti ti-user-circle"></i>
                            <h6>بيانات العميل الأساسية</h6>
                            <span class="badge bg-primary-subtle text-primary">مطلوب</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">اسم العميل <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-user text-primary"></i></span>
                                    <input type="text" name="name" class="form-control" placeholder="أدخل اسم العميل الكامل" required>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">الرقم القومي</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-id text-primary"></i></span>
                                    <input type="text" name="national_id" class="form-control" maxlength="14" placeholder="14 رقم">
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">كود العميل</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-hash text-primary"></i></span>
                                    <input type="text" name="client_code" id="clientCode" class="form-control bg-light" readonly>
                                    <button type="button" class="btn btn-outline-primary" onclick="generateClientCode()" title="توليد كود جديد">
                                        <i class="ti ti-refresh"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">التليفون</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-phone text-primary"></i></span>
                                    <input type="text" name="telephone" class="form-control" placeholder="رقم التليفون الأرضي">
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">الموبايل</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-device-mobile text-primary"></i></span>
                                    <input type="text" name="mobile" class="form-control" placeholder="رقم الموبايل">
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">ملاحظات</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="أي ملاحظات إضافية عن العميل..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Lands Section -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <i class="ti ti-map-2"></i>
                            <h6>الأراضي والعقارات</h6>
                            <span class="badge bg-secondary-subtle text-secondary">اختياري</span>
                            <button type="button" class="btn btn-sm btn-primary me-auto" onclick="addLandRow()">
                                <i class="ti ti-plus me-1"></i>إضافة أرض
                            </button>
                        </div>
                        <div id="landsContainer">
                            <div class="text-center text-muted py-4" id="noLandsMessage">
                                <i class="ti ti-map-pin-off fs-1 d-block mb-2 opacity-50"></i>
                                <p class="mb-0">لم يتم إضافة أراضي بعد</p>
                                <small>اضغط على "إضافة أرض" لإضافة أرض جديدة للعميل</small>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="info-box">
                        <i class="ti ti-info-circle"></i>
                        <div class="info-box-content">
                            <div class="info-box-title">معلومات</div>
                            <div class="info-box-text">سيتم إنشاء كود العميل تلقائياً. يمكنك إضافة الأراضي الآن أو لاحقاً من صفحة تفاصيل العميل.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-1"></i>حفظ العميل
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
