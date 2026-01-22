<!-- Show Land Modal -->
<div class="modal fade" id="showLandModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-header-dark">
                <h5 class="modal-title">
                    <i class="ti ti-map-pin"></i>
                    تفاصيل القطعة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Client & Land Info Section -->
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="ti ti-user"></i>
                        <h6>معلومات العميل والقطعة</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-primary-subtle text-primary rounded me-3">
                                    <i class="ti ti-user"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">العميل</small>
                                    <span class="fw-semibold" id="showLandClient">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-success-subtle text-success rounded me-3">
                                    <i class="ti ti-hash"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">رقم القطعة</small>
                                    <span class="fw-semibold" id="showLandNo">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-info-subtle text-info rounded me-3">
                                    <i class="ti ti-building"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">رقم الوحدة</small>
                                    <span class="fw-semibold" id="showUnitNo">-</span>
                                </div>
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
                        <div class="col-md-4">
                            <div class="card border h-100">
                                <div class="card-body text-center py-3">
                                    <i class="ti ti-building-community text-primary fs-4 mb-2 d-block"></i>
                                    <small class="text-muted d-block">المحافظة</small>
                                    <span class="fw-semibold" id="showGovernorate">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border h-100">
                                <div class="card-body text-center py-3">
                                    <i class="ti ti-building text-success fs-4 mb-2 d-block"></i>
                                    <small class="text-muted d-block">المدينة</small>
                                    <span class="fw-semibold" id="showCity">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border h-100">
                                <div class="card-body text-center py-3">
                                    <i class="ti ti-home text-info fs-4 mb-2 d-block"></i>
                                    <small class="text-muted d-block">الحي</small>
                                    <span class="fw-semibold" id="showDistrict">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border h-100">
                                <div class="card-body text-center py-3">
                                    <i class="ti ti-map-pin text-warning fs-4 mb-2 d-block"></i>
                                    <small class="text-muted d-block">المنطقة</small>
                                    <span class="fw-semibold" id="showZone">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border h-100">
                                <div class="card-body text-center py-3">
                                    <i class="ti ti-map-pins text-danger fs-4 mb-2 d-block"></i>
                                    <small class="text-muted d-block">القطاع</small>
                                    <span class="fw-semibold" id="showArea">-</span>
                                </div>
                            </div>
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
                            <div class="d-flex">
                                <div class="avatar avatar-sm bg-secondary-subtle text-secondary rounded me-3 flex-shrink-0">
                                    <i class="ti ti-map"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">العنوان</small>
                                    <span id="showAddress">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex">
                                <div class="avatar avatar-sm bg-warning-subtle text-warning rounded me-3 flex-shrink-0">
                                    <i class="ti ti-notes"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">ملاحظات</small>
                                    <span id="showNotes">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-light text-muted rounded me-3">
                                    <i class="ti ti-calendar-plus"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">تاريخ الإنشاء</small>
                                    <span class="fw-semibold" id="showCreatedAt">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-light text-muted rounded me-3">
                                    <i class="ti ti-calendar-event"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">آخر تحديث</small>
                                    <span class="fw-semibold" id="showUpdatedAt">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>إغلاق
                </button>
                <button type="button" class="btn btn-warning" onclick="openEditFromShow()">
                    <i class="ti ti-edit me-1"></i>تعديل
                </button>
            </div>
        </div>
    </div>
</div>
