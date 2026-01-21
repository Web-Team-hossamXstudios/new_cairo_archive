<!-- Delete Client Modal -->
<div class="modal fade" id="deleteClientModal" tabindex="-1">
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
                <h5 class="mb-2">هل أنت متأكد من حذف هذا العميل؟</h5>
                <p class="text-muted mb-0" id="deleteClientName"></p>
                <input type="hidden" id="deleteClientId">
                <div class="alert alert-warning mt-3 text-start">
                    <i class="ti ti-info-circle me-1"></i>
                    <small>سيتم نقل العميل إلى سلة المحذوفات ويمكن استرجاعه لاحقاً</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="ti ti-trash me-1"></i>حذف
                </button>
            </div>
        </div>
    </div>
</div>
