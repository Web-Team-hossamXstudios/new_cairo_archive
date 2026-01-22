<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <h5 class="modal-title">
                    <i class="ti ti-key"></i>
                    تغيير كلمة المرور
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changePasswordForm">
                @csrf
                <input type="hidden" name="user_id" id="changePasswordUserId">
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center mb-3">
                        <i class="ti ti-info-circle fs-4 me-2"></i>
                        <div>
                            <strong>ملاحظة:</strong> سيتم تغيير كلمة المرور للمستخدم: <span id="changePasswordUserName" class="fw-bold"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">كلمة المرور الجديدة <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="ti ti-key text-primary"></i></span>
                            <input type="password" name="password" id="newPassword" class="form-control" required minlength="8" placeholder="أدخل كلمة المرور الجديدة">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('newPassword')">
                                <i class="ti ti-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">يجب أن تكون كلمة المرور 8 أحرف على الأقل</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="ti ti-key text-primary"></i></span>
                            <input type="password" name="password_confirmation" id="confirmPassword" class="form-control" required placeholder="أعد إدخال كلمة المرور">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('confirmPassword')">
                                <i class="ti ti-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="warning-box">
                        <i class="ti ti-alert-triangle"></i>
                        <div>
                            <strong>تحذير:</strong> سيتم تغيير كلمة المرور فوراً ولن يتمكن المستخدم من تسجيل الدخول بكلمة المرور القديمة.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-1"></i>تغيير كلمة المرور
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
