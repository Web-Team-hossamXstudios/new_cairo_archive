<!-- Delete Room Modal -->
<div class="modal fade" id="deleteRoomModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <h5 class="modal-title">
                    <i class="ti ti-alert-triangle"></i>
                    تأكيد الحذف
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="avatar avatar-xl bg-danger-subtle text-danger rounded-circle mx-auto mb-3">
                    <i class="ti ti-trash fs-1"></i>
                </div>
                <h5 class="mb-2">هل أنت متأكد من حذف هذه الغرفة؟</h5>
                <p class="text-muted mb-0" id="deleteRoomName"></p>
                <input type="hidden" id="deleteRoomId">
                <div class="alert alert-warning mt-3 text-start">
                    <i class="ti ti-info-circle me-1"></i>
                    <small>سيتم حذف الغرفة وجميع الممرات والأرفف المرتبطة بها</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteRoom()">
                    <i class="ti ti-trash me-1"></i>حذف
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Lane Modal -->
<div class="modal fade" id="deleteLaneModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <h5 class="modal-title">
                    <i class="ti ti-alert-triangle"></i>
                    تأكيد الحذف
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="avatar avatar-xl bg-danger-subtle text-danger rounded-circle mx-auto mb-3">
                    <i class="ti ti-trash fs-1"></i>
                </div>
                <h5 class="mb-2">هل أنت متأكد من حذف هذا الممر؟</h5>
                <p class="text-muted mb-0" id="deleteLaneName"></p>
                <input type="hidden" id="deleteLaneId">
                <div class="alert alert-warning mt-3 text-start">
                    <i class="ti ti-info-circle me-1"></i>
                    <small>سيتم حذف الممر وجميع الحوامل والأرفف المرتبطة به</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteLane()">
                    <i class="ti ti-trash me-1"></i>حذف
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Stand Modal -->
<div class="modal fade" id="deleteStandModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <h5 class="modal-title">
                    <i class="ti ti-alert-triangle"></i>
                    تأكيد الحذف
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="avatar avatar-xl bg-danger-subtle text-danger rounded-circle mx-auto mb-3">
                    <i class="ti ti-trash fs-1"></i>
                </div>
                <h5 class="mb-2">هل أنت متأكد من حذف هذا الحامل؟</h5>
                <p class="text-muted mb-0" id="deleteStandName"></p>
                <input type="hidden" id="deleteStandId">
                <div class="alert alert-warning mt-3 text-start">
                    <i class="ti ti-info-circle me-1"></i>
                    <small>سيتم حذف الحامل وجميع الأرفف المرتبطة به</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteStand()">
                    <i class="ti ti-trash me-1"></i>حذف
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Rack Modal -->
<div class="modal fade" id="deleteRackModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <h5 class="modal-title">
                    <i class="ti ti-alert-triangle"></i>
                    تأكيد الحذف
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="avatar avatar-xl bg-danger-subtle text-danger rounded-circle mx-auto mb-3">
                    <i class="ti ti-trash fs-1"></i>
                </div>
                <h5 class="mb-2">هل أنت متأكد من حذف هذا الرف؟</h5>
                <p class="text-muted mb-0" id="deleteRackName"></p>
                <input type="hidden" id="deleteRackId">
                <div class="alert alert-warning mt-3 text-start">
                    <i class="ti ti-info-circle me-1"></i>
                    <small>سيتم حذف الرف نهائياً</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteRack()">
                    <i class="ti ti-trash me-1"></i>حذف
                </button>
            </div>
        </div>
    </div>
</div>
