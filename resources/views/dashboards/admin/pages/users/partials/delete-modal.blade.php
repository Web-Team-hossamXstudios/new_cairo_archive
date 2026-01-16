<!-- Delete User Modal -->
<div class="modal fade delete-modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-5">
                <div class="delete-icon bg-danger-subtle text-danger">
                    <i class="ti ti-trash"></i>
                </div>
                <h4 class="mb-2">{{ x_('Delete User?', 'dashboards.admin.pages.user.users.partials.delete-modal') }}</h4>
                <p class="text-muted mb-4">This action will soft delete the user. You can restore them later from the trash.</p>
                <input type="hidden" id="deleteId">
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> {{ x_('Cancel', 'dashboards.admin.pages.user.users.partials.delete-modal') }}</button>
                    <button type="button" class="btn btn-danger px-4" onclick="confirmDelete()">
                        <i class="ti ti-trash me-1"></i> {{ x_('Delete', 'dashboards.admin.pages.user.users.partials.delete-modal') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation Modal -->
<div class="modal fade delete-modal" id="bulkDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-5">
                <div class="delete-icon bg-warning-subtle text-warning">
                    <i class="ti ti-alert-triangle"></i>
                </div>
                <h4 class="mb-2">{{ x_('Bulk Delete Confirmation', 'dashboards.admin.pages.user.users.partials.delete-modal') }}</h4>
                <p class="text-muted mb-2">You are about to delete <strong id="bulkDeleteCount">0</strong> users.</p>
                <p class="text-danger small mb-4">This action can be undone!</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> {{ x_('Cancel', 'dashboards.admin.pages.user.users.partials.delete-modal') }}</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmBulkDeleteBtn">
                        <i class="ti ti-trash me-1"></i> {{ x_('Delete Selected', 'dashboards.admin.pages.user.users.partials.delete-modal') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Force Delete Confirmation Modal -->
<div class="modal fade delete-modal" id="forceDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-5">
                <div class="delete-icon bg-danger text-white">
                    <i class="ti ti-alert-octagon"></i>
                </div>
                <h4 class="mb-2 text-danger">{{ x_('Permanent Delete', 'dashboards.admin.pages.user.users.partials.delete-modal') }}</h4>
                <p class="text-muted mb-2">This will <strong class="text-danger">permanently</strong> delete this user.</p>
                <p class="text-danger small mb-4">This action is irreversible!</p>
                <input type="hidden" id="forceDeleteId">
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> {{ x_('Cancel', 'dashboards.admin.pages.user.users.partials.delete-modal') }}</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmForceDeleteBtn">
                        <i class="ti ti-trash me-1"></i> {{ x_('Permanently Delete', 'dashboards.admin.pages.user.users.partials.delete-modal') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Force Delete Confirmation Modal -->
<div class="modal fade delete-modal" id="bulkForceDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-5">
                <div class="delete-icon bg-danger text-white">
                    <i class="ti ti-alert-octagon"></i>
                </div>
                <h4 class="mb-2 text-danger">{{ x_('Bulk Permanent Delete', 'dashboards.admin.pages.user.users.partials.delete-modal') }}</h4>
                <p class="text-muted mb-2">You are about to <strong class="text-danger">permanently</strong> delete <strong id="bulkForceDeleteCount">0</strong> users.</p>
                <p class="text-danger small mb-4">This action is irreversible!</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> {{ x_('Cancel', 'dashboards.admin.pages.user.users.partials.delete-modal') }}</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmBulkForceDeleteBtn">
                        <i class="ti ti-trash me-1"></i> {{ x_('Permanently Delete All', 'dashboards.admin.pages.user.users.partials.delete-modal') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <i class="ti ti-check-circle fs-5 me-2"></i>
                <span id="successToastMessage">Operation completed successfully!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <i class="ti ti-alert-circle fs-5 me-2"></i>
                <span id="errorToastMessage">An error occurred!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
