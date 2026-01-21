<!-- Show User Modal -->
<div class="modal fade" id="showModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-dark">
                <h5 class="modal-title">
                    <i class="ti ti-user-check"></i>
                    تفاصيل المستخدم
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Profile Header -->
                <div class="text-center py-4 mb-4" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 12px;">
                    <div id="showAvatar" class="mb-3"></div>
                    <h4 id="showFullName" class="mb-2 text-dark"></h4>
                    <p id="showEmail" class="text-muted mb-3"></p>
                    <div id="showRole"></div>
                </div>

                <!-- User Details Section -->
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="ti ti-info-circle"></i>
                        <h6>معلومات الحساب</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-primary-subtle text-primary rounded me-3">
                                    <i class="ti ti-at"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">اسم المستخدم</small>
                                    <span class="fw-semibold" id="showName">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-success-subtle text-success rounded me-3">
                                    <i class="ti ti-phone"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">رقم الهاتف</small>
                                    <span class="fw-semibold" id="showPhone">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-info-subtle text-info rounded me-3">
                                    <i class="ti ti-id"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">كود الموظف</small>
                                    <span class="fw-semibold" id="showEmployeeCode">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-warning-subtle text-warning rounded me-3">
                                    <i class="ti ti-toggle-left"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">الحالة</small>
                                    <span id="showStatus">-</span>
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
                                    <i class="ti ti-login"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">آخر دخول</small>
                                    <span class="fw-semibold" id="showLastLogin">-</span>
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
            </div>
        </div>
    </div>
</div>
