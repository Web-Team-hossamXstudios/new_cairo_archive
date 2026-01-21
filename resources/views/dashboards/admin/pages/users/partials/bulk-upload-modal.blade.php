<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-upload me-2"></i>استيراد المستخدمين</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkUploadForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ملف Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">الصيغ المدعومة: xlsx, xls, csv</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوضع</label>
                        <select name="mode" class="form-select">
                            <option value="create">إنشاء جديد فقط</option>
                            <option value="upsert">إنشاء أو تحديث</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-1"></i>
                        <a href="{{ route('admin.users.download-sample') }}">تحميل ملف النموذج</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">استيراد</button>
                </div>
            </form>
        </div>
    </div>
</div>
