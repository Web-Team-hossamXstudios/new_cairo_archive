<!-- Edit User Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-warning">
                <h5 class="modal-title">
                    <i class="ti ti-user-edit"></i>
                    تعديل المستخدم
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="editId">
                <div class="modal-body">
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <i class="ti ti-user"></i>
                            <h6>البيانات الشخصية</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">الاسم الأول <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-user text-warning"></i></span>
                                    <input type="text" name="first_name" id="editFirstName" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">اسم العائلة <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-users text-warning"></i></span>
                                    <input type="text" name="last_name" id="editLastName" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information Section -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <i class="ti ti-lock"></i>
                            <h6>بيانات الحساب</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">الإدارة</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-building text-warning"></i></span>
                                    <input type="text" name="department" id="editDepartment" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-mail text-warning"></i></span>
                                    <input type="email" name="email" id="editEmail" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Role & Status Section -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <i class="ti ti-shield"></i>
                            <h6>الدور والحالة</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">الدور <span class="text-danger">*</span></label>
                                <select name="role" id="editRole" class="form-select" required>
                                    <option value="">اختر الدور</option>
                                    @foreach($roles ?? [] as $role)
                                        <option value="{{ $role->name }}">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الحالة</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="editIsActive" style="width: 3em; height: 1.5em;">
                                    <label class="form-check-label ms-2" for="editIsActive">
                                        <span class="badge bg-success">نشط</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Warning Box -->
                    <div class="warning-box">
                        <i class="ti ti-alert-triangle"></i>
                        <div>
                            <strong>تنبيه:</strong> سيتم تحديث بيانات المستخدم فوراً. تأكد من صحة البيانات قبل الحفظ.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-check me-1"></i>حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
