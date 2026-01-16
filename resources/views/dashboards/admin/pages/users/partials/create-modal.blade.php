<!-- Create User Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h4 class="modal-title fw-bold" id="createModalLabel">{{ x_('Add New User', 'dashboards.admin.pages.user.users.partials.create-modal') }}</h4>
                    <p class="text-muted mb-0">Create a new user account with role and permissions</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ x_('Close', 'dashboards.admin.pages.user.users.partials.create-modal') }}"></button>
            </div>
            <form id="createForm">
                @csrf
                <div class="modal-body pt-3">
                    <ul class="nav nav-pills nav-fill mb-4 bg-light rounded p-1" id="createTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                                <i class="ti ti-user me-1"></i> {{ x_('Basic Info', 'dashboards.admin.pages.user.users.partials.create-modal') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded" id="employee-tab" data-bs-toggle="tab" data-bs-target="#employee" type="button" role="tab">
                                <i class="ti ti-briefcase me-1"></i> {{ x_('Employee Info', 'dashboards.admin.pages.user.users.partials.create-modal') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded" id="access-tab" data-bs-toggle="tab" data-bs-target="#access" type="button" role="tab">
                                <i class="ti ti-shield me-1"></i> Access & Role
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="createTabContent">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('First Name', 'dashboards.admin.pages.user.users.partials.create-modal') }}</label>
                                    <input type="text" name="first_name" class="form-control" placeholder="{{ x_('Enter first name', 'dashboards.admin.pages.user.users.partials.create-modal') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Last Name', 'dashboards.admin.pages.user.users.partials.create-modal') }}</label>
                                    <input type="text" name="last_name" class="form-control" placeholder="{{ x_('Enter last name', 'dashboards.admin.pages.user.users.partials.create-modal') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('National ID', 'dashboards.admin.pages.user.users.partials.create-modal') }}</label>
                                    <input type="text" name="national_id" class="form-control" placeholder="{{ x_('Enter national ID', 'dashboards.admin.pages.user.users.partials.create-modal') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="{{ x_('Enter email', 'dashboards.admin.pages.user.users.partials.create-modal') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" placeholder="{{ x_('Enter password', 'dashboards.admin.pages.user.users.partials.create-modal') }}" required minlength="8" id="createPassword">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('createPassword')">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="{{ x_('Confirm password', 'dashboards.admin.pages.user.users.partials.create-modal') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Phone', 'dashboards.admin.pages.user.users.partials.create-modal') }}</label>
                                    <input type="text" name="phone" class="form-control" placeholder="{{ x_('Enter phone number', 'dashboards.admin.pages.user.users.partials.create-modal') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Gender', 'dashboards.admin.pages.user.users.partials.create-modal') }}</label>
                                    <select name="gender" class="form-select">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Birth Date', 'dashboards.admin.pages.user.users.partials.create-modal') }}</label>
                                    <input type="date" name="birth_date" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ x_('Address', 'dashboards.admin.pages.user.users.partials.create-modal') }}</label>
                                    <textarea name="address" class="form-control" rows="2" placeholder="{{ x_('Enter address', 'dashboards.admin.pages.user.users.partials.create-modal') }}"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Employee Info Tab -->
                        <div class="tab-pane fade" id="employee" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Employee Type', 'dashboards.admin.pages.user.users.partials.create-modal') }}</label>
                                    <select name="employee_type" class="form-select">
                                        <option value="">Select Type</option>
                                        <option value="admin">Admin</option>
                                        <option value="permanent">Permanent</option>
                                        <option value="daily">Daily</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Hire Date', 'dashboards.admin.pages.user.users.partials.create-modal') }}</label>
                                    <input type="date" name="hire_date" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Basic Salary', 'dashboards.admin.pages.user.users.partials.create-modal') }}</label>
                                    <div class="input-group">
                                        <input type="number" name="basic_salary" class="form-control" placeholder="0.00" step="0.01" min="0">
                                        <span class="input-group-text">EGP</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Piece Rate', 'dashboards.admin.pages.user.users.partials.create-modal') }}</label>
                                    <div class="input-group">
                                        <input type="number" name="piece_rate" class="form-control" placeholder="0.00" step="0.01" min="0">
                                        <span class="input-group-text">EGP</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ x_('Employment Status', 'dashboards.admin.pages.user.users.partials.create-modal') }}</label>
                                    <select name="employment_status" class="form-select">
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
                        <div class="tab-pane fade" id="access" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles ?? [] as $role)
                                            <option value="{{ $role->name }}">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4">
                                        <input type="checkbox" name="is_active" class="form-check-input" id="createIsActive" checked>
                                        <label class="form-check-label" for="createIsActive">
                                            <strong>Account Active</strong>
                                            <small class="text-muted d-block">{{ x_('User can login to the system', 'dashboards.admin.pages.user.users.partials.create-modal') }}</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">{{ x_('Cancel', 'dashboards.admin.pages.user.users.partials.create-modal') }}</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-check me-1"></i> {{ x_('Create User', 'dashboards.admin.pages.user.users.partials.create-modal') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
