<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md bg-success text-white rounded me-2">
                        <i class="ti ti-upload fs-4"></i>
                    </div>
                    <h5 class="modal-title mb-0" id="bulkUploadModalLabel">{{ x_('Bulk Upload Users', 'dashboards.admin.pages.user.users.partials.bulk-upload-modal') }}</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ x_('Close', 'dashboards.admin.pages.user.users.partials.bulk-upload-modal') }}"></button>
            </div>
            <form id="bulkUploadForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Download Sample Section -->
                    <div class="text-center mb-4 p-3 bg-light rounded">
                        <i class="ti ti-file-spreadsheet fs-1 text-success mb-2 d-block"></i>
                        <p class="text-muted mb-2">Download a sample template to see the required format</p>
                        <a href="{{ route('admin.users.download-sample') }}" class="btn btn-outline-success btn-sm">
                            <i class="ti ti-download me-1"></i> {{ x_('Download Sample Template', 'dashboards.admin.pages.user.users.partials.bulk-upload-modal') }}</a>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload File <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                        <small class="text-muted">{{ x_('Accepted formats: CSV, XLSX, XLS', 'dashboards.admin.pages.user.users.partials.bulk-upload-modal') }}</small>
                    </div>

                    <div class="alert alert-info py-2 mb-0">
                        <i class="ti ti-info-circle me-1"></i>
                        <strong>Required columns:</strong> name, email, password
                        <span class="d-block"><strong>Optional:</strong> first_name, last_name, phone, role, is_active</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> {{ x_('Cancel', 'dashboards.admin.pages.user.users.partials.bulk-upload-modal') }}</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-upload me-1"></i> {{ x_('Upload', 'dashboards.admin.pages.user.users.partials.bulk-upload-modal') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
