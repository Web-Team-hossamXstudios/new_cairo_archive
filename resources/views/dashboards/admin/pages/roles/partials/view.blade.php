<!-- Role Name -->
<div class="mb-4">
    <label class="form-label fw-semibold">Role Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control form-control-sm bg-light border-0" value="{{ ucfirst(str_replace('-', ' ', $role->name)) }}" readonly>
</div>

<!-- Description -->
<div class="mb-4">
    <label class="form-label fw-semibold">{{ x_('Description', 'dashboards.admin.pages.user.roles.partials.view') }}</label>
    <input type="text" class="form-control bg-light border-0" value="{{ $role->description ?? ucfirst(str_replace('-', ' ', $role->name)) . ' Role' }}" readonly>
</div>

<!-- Role Permissions Section -->
<div class="mb-3">
    <label class="form-label fw-semibold">{{ x_('Role Permissions', 'dashboards.admin.pages.user.roles.partials.view') }}</label>
</div>

<div class="mb-3">
    <h5 class="fw-bold mb-2">{{ x_('Manage Permissions', 'dashboards.admin.pages.user.roles.partials.view') }}</h5>
    <p class="text-muted mb-1">Select permissions for this role. You can select all permissions at once or manage them by module.</p>
    <p class="text-warning small mb-3"><i class="ti ti-info-circle me-1"></i> Note: Only permissions for modules available to your role are shown.</p>
</div>

@php
    $rolePermissions = $role->permissions->pluck('name')->toArray();
    $totalPermissions = $allPermissions->count();
    $selectedPermissions = count($rolePermissions);
@endphp

<!-- Select All Permissions -->
<div class="d-flex justify-content-between align-items-center px-3 py-2 bg-light border rounded mb-3">
    <div class="form-check mb-0">
        <input type="checkbox" class="form-check-input" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid #17a2b8;" {{ $selectedPermissions === $totalPermissions ? 'checked' : '' }} disabled>
        <label class="form-check-label fw-semibold ms-1">{{ x_('Select All Permissions', 'dashboards.admin.pages.user.roles.partials.view') }}</label>
    </div>
    <span class="text-muted">{{ $selectedPermissions }} of {{ $totalPermissions }} selected</span>
</div>

<!-- Permission Groups -->
<div>
    @foreach($groupedPermissions as $group => $groupPermissions)
        @php
            $groupPermissionNames = $groupPermissions->pluck('name')->toArray();
            $selectedInGroup = count(array_intersect($rolePermissions, $groupPermissionNames));
            $totalInGroup = count($groupPermissionNames);
            $isGroupSelected = $selectedInGroup === $totalInGroup && $totalInGroup > 0;
        @endphp
        <div class="permission-module-card mb-3 border rounded">
            <!-- Module Header -->
            <div class="d-flex justify-content-between align-items-center px-3 py-2 bg-light border-bottom">
                <div class="form-check mb-0">
                    <input type="checkbox" class="form-check-input" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid #17a2b8;" {{ $isGroupSelected ? 'checked' : '' }} disabled>
                    <label class="form-check-label fw-medium ms-1">{{ ucwords(str_replace(['-', '_', '.'], ' ', $group)) }}</label>
                </div>
                <span class="text-muted small">{{ $selectedInGroup }} of {{ $totalInGroup }} selected</span>
            </div>
            <!-- Permissions Grid -->
            <div class="px-3 py-3">
                <div class="row g-2">
                    @foreach($groupPermissions as $permission)
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="form-check mb-0">
                                <input type="checkbox" class="form-check-input" style="width: 16px; height: 16px; border-radius: 50%; border: 2px solid #17a2b8;" {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }} disabled>
                                <label class="form-check-label small">
                                    @php
                                        $permissionLabel = $permission->name;
                                        if (str_ends_with($permissionLabel, '-' . $group)) {
                                            $permissionLabel = substr($permissionLabel, 0, -strlen('-' . $group));
                                        }
                                    @endphp
                                    {{ ucwords(str_replace(['-', '_', '.'], ' ', $permissionLabel)) }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
