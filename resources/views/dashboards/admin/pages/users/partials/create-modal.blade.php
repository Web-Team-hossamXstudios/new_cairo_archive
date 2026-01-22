<!-- Create User Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <h5 class="modal-title">
                    <i class="ti ti-user-plus"></i>
                    إضافة مستخدم جديد
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createForm" enctype="multipart/form-data">
                @csrf
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
                                    <span class="input-group-text bg-light"><i class="ti ti-user text-primary"></i></span>
                                    <input type="text" name="first_name" class="form-control" placeholder="الاسم الأول" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">اسم العائلة <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-users text-primary"></i></span>
                                    <input type="text" name="last_name" class="form-control" placeholder="اسم العائلة" required>
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
                                    <span class="input-group-text bg-light"><i class="ti ti-building text-primary"></i></span>
                                    <input type="text" name="department" class="form-control" placeholder="الإدارة">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-mail text-primary"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="البريد الإلكتروني" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-key text-primary"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="كلمة المرور" required minlength="8">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-key text-primary"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="تأكيد كلمة المرور" required>
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
                                <select name="role" class="form-select" required>
                                    <option value="">اختر الدور</option>
                                    @foreach($roles ?? [] as $role)
                                        <option value="{{ $role->name }}">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الحالة</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="createIsActive" style="width: 3em; height: 1.5em;">
                                    <label class="form-check-label ms-2" for="createIsActive">
                                        <span class="badge bg-success">نشط</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="info-box">
                        <i class="ti ti-info-circle"></i>
                        <div class="info-box-content">
                            <div class="info-box-title">معلومات</div>
                            <div class="info-box-text">سيتم إرسال بيانات الدخول للمستخدم عبر البريد الإلكتروني بعد إنشاء الحساب.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-1"></i>إنشاء المستخدم
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
