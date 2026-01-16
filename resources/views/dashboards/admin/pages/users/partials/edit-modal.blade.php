<!-- Edit User Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h4 class="modal-title fw-bold" id="editModalLabel">{{ x_('Edit User', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</h4>
                    <p class="text-muted mb-0">Update user account information and permissions</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ x_('Close', 'dashboards.admin.pages.user.users.partials.edit-modal') }}"></button>
            </div>
            <form id="editForm">
                @csrf
                <input type="hidden" name="id" id="editId">
                <div class="modal-body pt-3">
                    <ul class="nav nav-pills nav-fill mb-4 bg-light rounded p-1" id="editTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded" id="edit-basic-tab" data-bs-toggle="tab" data-bs-target="#edit-basic" type="button" role="tab">
                                <i class="ti ti-user me-1"></i> {{ x_('Basic Info', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded" id="edit-employee-tab" data-bs-toggle="tab" data-bs-target="#edit-employee" type="button" role="tab">
                                <i class="ti ti-briefcase me-1"></i> {{ x_('Employee Info', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded" id="edit-access-tab" data-bs-toggle="tab" data-bs-target="#edit-access" type="button" role="tab">
                                <i class="ti ti-shield me-1"></i> Access & Role
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="editTabContent">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="edit-basic" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('First Name', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <input type="text" name="first_name" id="editFirstName" class="form-control" placeholder="{{ x_('Enter first name', 'dashboards.admin.pages.user.users.partials.edit-modal') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Last Name', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <input type="text" name="last_name" id="editLastName" class="form-control" placeholder="{{ x_('Enter last name', 'dashboards.admin.pages.user.users.partials.edit-modal') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('National ID', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <input type="text" name="national_id" id="editNationalId" class="form-control" placeholder="{{ x_('Enter national ID', 'dashboards.admin.pages.user.users.partials.edit-modal') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="editEmail" class="form-control" placeholder="{{ x_('Enter email', 'dashboards.admin.pages.user.users.partials.edit-modal') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('New Password', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" placeholder="{{ x_('Leave blank to keep current', 'dashboards.admin.pages.user.users.partials.edit-modal') }}" id="editPassword">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('editPassword')">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">{{ x_('Leave blank to keep current password', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Confirm New Password', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="{{ x_('Confirm new password', 'dashboards.admin.pages.user.users.partials.edit-modal') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Phone', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <input type="text" name="phone" id="editPhone" class="form-control" placeholder="{{ x_('Enter phone number', 'dashboards.admin.pages.user.users.partials.edit-modal') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Gender', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <select name="gender" id="editGender" class="form-select">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Birth Date', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <input type="date" name="birth_date" id="editBirthDate" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ x_('Address', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <textarea name="address" id="editAddress" class="form-control" rows="2" placeholder="{{ x_('Enter address', 'dashboards.admin.pages.user.users.partials.edit-modal') }}"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Employee Info Tab -->
                        <div class="tab-pane fade" id="edit-employee" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Employee Code', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <input type="text" id="editEmployeeCode" class="form-control" readonly disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Employee Type', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <select name="employee_type" id="editEmployeeType" class="form-select">
                                        <option value="">Select Type</option>
                                        <option value="admin">Admin</option>
                                        <option value="permanent">Permanent</option>
                                        <option value="daily">Daily</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Hire Date', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <input type="date" name="hire_date" id="editHireDate" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Basic Salary', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <div class="input-group">
                                        <input type="number" name="basic_salary" id="editBasicSalary" class="form-control" placeholder="0.00" step="0.01" min="0">
                                        <span class="input-group-text">EGP</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Piece Rate', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <div class="input-group">
                                        <input type="number" name="piece_rate" id="editPieceRate" class="form-control" placeholder="0.00" step="0.01" min="0">
                                        <span class="input-group-text">EGP</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Employment Status', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</label>
                                    <select name="employment_status" id="editEmploymentStatus" class="form-select">
                                        <option value="">Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="on_leave">On Leave</option>
                                        <option value="terminated">Terminated</option>
                                        <option value="resigned">Resigned</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Access & Role Tab -->
                        <div class="tab-pane fade" id="edit-access" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select name="role" id="editRole" class="form-select" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles ?? [] as $role)
                                            <option value="{{ $role->name }}">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4">
                                        <input type="checkbox" name="is_active" class="form-check-input" id="editIsActive">
                                        <label class="form-check-label" for="editIsActive">
                                            <strong>Account Active</strong>
                                            <small class="text-muted d-block">{{ x_('User can login to the system', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">{{ x_('Cancel', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-check me-1"></i> {{ x_('Save Changes', 'dashboards.admin.pages.user.users.partials.edit-modal') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
