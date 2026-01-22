<!-- View Client Details Modal -->
<div class="modal fade" id="viewClientModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);    ">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2">
                    <div class="bg-white bg-opacity-25 rounded-circle ">
                        <i class="ti ti-user-check"></i>
                    </div>
                    تفاصيل العميل
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="background: #f8fafc; height: calc(107vh - 140px); overflow-y: auto; max-height: calc(107vh - 140px);">
                <div class="container-fluid p-4">
                    <!-- Client Profile Card -->
                    <div class="card border-0 shadow mb-3" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-body p-0">
                            <div class="row g-0">
                                <!-- Profile Section -->
                                <div class="col-md-2 text-center p-3" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
                                    <div class="avatar bg-white text-dark rounded-circle mx-auto mb-2 shadow" id="viewClientAvatar" style="width: 70px; height: 70px; font-size: 1.8rem; font-weight: 700; display: flex; align-items: center; justify-content: center;">
                                        <span id="viewClientInitial"></span>
                                    </div>
                                    <h6 id="viewClientName" class="text-white mb-2 fw-bold small"></h6>
                                    <span class="badge bg-white text-dark px-2 py-1 small" id="viewClientCode" style="border-radius: 20px; font-size: 0.7rem;"></span>
                                </div>
                                <!-- Details Section -->
                                <div class="col-md-10 p-3">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center bg-light rounded p-2">
                                                <div class="bg-primary rounded me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <i class="ti ti-id text-white"></i>
                                                </div>
                                                <div class="flex-grow-1 min-w-0">
                                                    <small class="text-muted d-block" style="font-size: 0.7rem;">الرقم القومي</small>
                                                    <span class="fw-semibold text-dark small" id="viewNationalId">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center bg-light rounded p-2">
                                                <div class="bg-success rounded me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <i class="ti ti-phone text-white"></i>
                                                </div>
                                                <div class="flex-grow-1 min-w-0">
                                                    <small class="text-muted d-block" style="font-size: 0.7rem;">التليفون</small>
                                                    <span class="fw-semibold text-dark small" id="viewTelephone">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center bg-light rounded p-2">
                                                <div class="bg-info rounded me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <i class="ti ti-device-mobile text-white"></i>
                                                </div>
                                                <div class="flex-grow-1 min-w-0">
                                                    <small class="text-muted d-block" style="font-size: 0.7rem;">الموبايل</small>
                                                    <span class="fw-semibold text-dark small" id="viewMobile">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center bg-light rounded p-2">
                                                <div class="bg-warning rounded me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <i class="ti ti-notes text-white"></i>
                                                </div>
                                                <div class="flex-grow-1 min-w-0">
                                                    <small class="text-muted d-block" style="font-size: 0.7rem;">ملاحظات</small>
                                                    <span class="fw-semibold text-dark small text-truncate d-block" id="viewNotes">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lands and Files Card -->
                    <div class="card border-0 shadow-lg" style="border-radius: 16px;">
                        <div class="card-header py-3 px-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 2px solid #e2e8f0;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle me-3 shadow-sm" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ti ti-map-2 text-white fs-5"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-dark">القطع والملفات</h5>
                                        <small class="text-muted">عرض تفصيلي لجميع القطع والملفات المرتبطة</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="text-center">
                                        <div class="badge bg-success px-3 py-2" style="border-radius: 10px;">
                                            <i class="ti ti-map-pin me-1"></i>
                                            <span id="viewLandsCountStat">0</span>
                                        </div>
                                        <small class="d-block text-muted mt-1" style="font-size: 0.7rem;">قطعه</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="badge bg-primary px-3 py-2" style="border-radius: 10px;">
                                            <i class="ti ti-file-text me-1"></i>
                                            <span id="viewFilesCountStat">0</span>
                                        </div>
                                        <small class="d-block text-muted mt-1" style="font-size: 0.7rem;">ملف</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4" style="background: #fafbfc;">
                            <div id="landsContent" class="row g-3"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light" style="padding: 1.25rem 2rem;">
                <button type="button" class="btn btn-md btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 50px;">
                    <i class="ti ti-x me-2"></i>إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

<!-- File Viewer Modal (nested) -->
<div class="modal fade" id="fileViewerModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
                <h5 class="modal-title text-white d-flex align-items-center gap-2">
                    <div class="bg-white bg-opacity-25 rounded-circle p-2">
                        <i class="ti ti-file-text"></i>
                    </div>
                    <span id="fileViewerTitle"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="height: calc(100vh - 70px); background: #f8fafc;">
                <div id="fileViewerContent" class="w-100 h-100"></div>
            </div>
        </div>
    </div>
</div>
