<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger-subtle">
                <h5 class="modal-title text-danger"><i class="ti ti-alert-triangle me-2"></i>تأكيد الحذف الجماعي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="avatar avatar-xl bg-danger-subtle text-danger rounded-circle mx-auto mb-3">
                    <i class="ti ti-trash fs-1"></i>
                </div>
                <h5 class="mb-2">هل أنت متأكد من حذف العملاء المحددين؟</h5>
                <p class="text-muted mb-0">عدد العملاء: <span class="fw-bold" id="bulkDeleteCount">0</span></p>
                <div class="alert alert-warning mt-3 text-start">
                    <i class="ti ti-info-circle me-1"></i>
                    <small>سيتم نقل العملاء إلى سلة المحذوفات ويمكن استرجاعهم لاحقاً</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" onclick="confirmBulkDelete()">
                    <i class="ti ti-trash me-1"></i>حذف الكل
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Restore Modal -->
<div class="modal fade" id="bulkRestoreModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success-subtle">
                <h5 class="modal-title text-success"><i class="ti ti-refresh me-2"></i>تأكيد الاسترجاع الجماعي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="avatar avatar-xl bg-success-subtle text-success rounded-circle mx-auto mb-3">
                    <i class="ti ti-refresh fs-1"></i>
                </div>
                <h5 class="mb-2">هل تريد استرجاع العملاء المحددين؟</h5>
                <p class="text-muted mb-0">عدد العملاء: <span class="fw-bold" id="bulkRestoreCount">0</span></p>
                <div class="alert alert-info mt-3 text-start">
                    <i class="ti ti-info-circle me-1"></i>
                    <small>سيتم استرجاع العملاء من سلة المحذوفات</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-success" onclick="confirmBulkRestore()">
                    <i class="ti ti-refresh me-1"></i>استرجاع الكل
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Force Delete Modal -->
<div class="modal fade" id="bulkForceDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white"><i class="ti ti-alert-octagon me-2"></i>تحذير: حذف نهائي جماعي</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="avatar avatar-xl bg-danger text-white rounded-circle mx-auto mb-3">
                    <i class="ti ti-trash-x fs-1"></i>
                </div>
                <h5 class="mb-2 text-danger">هل أنت متأكد من الحذف النهائي؟</h5>
                <p class="text-muted mb-0">عدد العملاء: <span class="fw-bold" id="bulkForceDeleteCount">0</span></p>
                <div class="alert alert-danger mt-3 text-start">
                    <i class="ti ti-alert-octagon me-1"></i>
                    <small class="fw-bold">تحذير: هذا الإجراء لا يمكن التراجع عنه! سيتم حذف العملاء وجميع بياناتهم نهائياً</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" onclick="confirmBulkForceDelete()">
                    <i class="ti ti-trash-x me-1"></i>حذف نهائي
                </button>
            </div>
        </div>
    </div>
</div>
