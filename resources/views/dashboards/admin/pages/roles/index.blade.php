<!DOCTYPE html>
@include('dashboards.shared.html')
<head>
    @include('dashboards.shared.meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="wrapper">
        @include('dashboards.shared.topbar')
        @include('dashboards.shared.sidebar')
        <div class="content-page">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box mb-2 mt-3">
                            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between py-2 px-3 bg-body border border-secondary border-opacity-10 shadow-sm" style="border-radius: var(--ins-border-radius);">
                                <div class="d-flex align-items-start align-items-md-center">
                                    <div>
                                        <span class="badge badge-default fw-normal shadow px-2 fst-italic fs-sm d-inline-flex align-items-center">
                                            <i class="ti ti-shield me-1"></i> Roles & Permissions
                                        </span>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb mb-0">
                                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ x_('Dashboard', 'dashboards.admin.pages.user.roles.index') }}</a></li>
                                                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                                                <li class="breadcrumb-item active" aria-current="page">Roles</li>
                                            </ol>
                                        </nav>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-2 mt-lg-0">
                                    @can('view-users')
                                    <a href="{{ route('admin.users.index') }}" style="border-radius: var(--ins-border-radius);" class="btn btn-outline-secondary shadow-sm px-3">
                                        <i class="ti ti-arrow-left me-1"></i>
                                        <span>Back to Users</span>
                                    </a>
                                    @endcan
                                    @can('bulk-download-roles')
                                    <a href="{{ route('admin.roles.bulk-download') }}" style="border-radius: var(--ins-border-radius);" class="btn btn-soft-success shadow-sm px-3">
                                        <i class="ti ti-download me-1"></i>
                                        <span>{{ x_('Export', 'dashboards.admin.pages.user.roles.index') }}</span>
                                    </a>
                                    @endcan
                                    @can('create-roles')
                                    <button type="button" class="btn btn-primary shadow-sm px-3" style="border-radius: var(--ins-border-radius);" data-bs-toggle="modal" data-bs-target="#createModal">
                                        <i class="ti ti-plus me-1"></i>
                                        <span>Add Role</span>
                                    </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row ">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary-subtle text-primary rounded me-3">
                                        <i class="ti ti-shield fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ $roles->total() }}</h4>
                                        <small class="text-muted">{{ x_('Total Roles', 'dashboards.admin.pages.user.roles.index') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-success-subtle text-success rounded me-3">
                                        <i class="ti ti-key fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \Spatie\Permission\Models\Permission::count() }}</h4>
                                        <small class="text-muted">{{ x_('Total Permissions', 'dashboards.admin.pages.user.roles.index') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-info-subtle text-info rounded me-3">
                                        <i class="ti ti-users fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\User::whereHas('roles')->count() }}</h4>
                                        <small class="text-muted">{{ x_('Users with Roles', 'dashboards.admin.pages.user.roles.index') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Card -->
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="border-radius: var(--ins-border-radius);">
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.roles.index') }}" class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <label class="form-label fw-semibold">{{ x_('Search', 'dashboards.admin.pages.user.roles.index') }}</label>
                                        <input type="text" name="search" class="form-control shadow-sm border border-secondary border-opacity-10" style="border-radius: var(--ins-border-radius);" placeholder="{{ x_('Search by role name...', 'dashboards.admin.pages.user.roles.index') }}" value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary shadow-sm" style="border-radius: var(--ins-border-radius);">
                                                <i class="ti ti-filter me-1"></i> {{ x_('Filter', 'dashboards.admin.pages.user.roles.index') }}</button>
                                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary shadow-sm" style="border-radius: var(--ins-border-radius);">
                                                <i class="ti ti-refresh"></i> {{ x_('Reset', 'dashboards.admin.pages.user.roles.index') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="border-radius: var(--ins-border-radius);">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <h5 class="card-title mb-0">{{ x_('Roles', 'dashboards.admin.pages.user.roles.index') }}</h5>
                                    <span class="badge bg-primary-subtle text-primary">{{ $roles->total() }} Roles</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Bulk Actions -->
                                    <div class="bulk-actions d-none me-2" id="bulkActions">
                                        @can('bulk-delete-roles')
                                        <button type="button" class="btn btn-soft-danger btn-sm" onclick="bulkDelete()">
                                            <i class="ti ti-trash me-1"></i> {{ x_('Delete Selected', 'dashboards.admin.pages.user.roles.index') }}</button>
                                        @endcan
                                    </div>
                                    <!-- View Toggle -->
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary active" id="listViewBtn" onclick="toggleView('list')">
                                            <i class="ti ti-list"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="cardViewBtn" onclick="toggleView('card')">
                                            <i class="ti ti-layout-grid"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <!-- List View -->
                                <div id="listView" class="table-responsive">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead class="bg-light-subtle">
                                            <tr>
                                                <th width="10"><input type="checkbox" class="form-check-input" id="selectAll"></th>
                                                <th>{{ x_('Role Name', 'dashboards.admin.pages.user.roles.index') }}</th>
                                                <th>{{ x_('Users', 'dashboards.admin.pages.user.roles.index') }}</th>
                                                <th>{{ x_('Permissions', 'dashboards.admin.pages.user.roles.index') }}</th>
                                                <th>{{ x_('Created', 'dashboards.admin.pages.user.roles.index') }}</th>
                                                <th width="150" class="text-center">{{ x_('Actions', 'dashboards.admin.pages.user.roles.index') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($roles as $role)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input row-checkbox" value="{{ $role->id }}"
                                                            {{ in_array($role->name, ['super-admin', 'admin']) ? 'disabled' : '' }}>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm bg-{{ $role->name == 'super-admin' ? 'danger' : ($role->name == 'admin' ? 'warning' : 'primary') }}-subtle text-{{ $role->name == 'super-admin' ? 'danger' : ($role->name == 'admin' ? 'warning' : 'primary') }} rounded me-2 d-flex align-items-center justify-content-center">
                                                                <i class="ti ti-shield"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</h6>
                                                                <small class="text-muted">{{ $role->guard_name }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info-subtle text-info">{{ $role->users_count }} users</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary-subtle text-secondary">{{ $role->permissions->count() }} permissions</span>
                                                    </td>
                                                    <td>{{ $role->created_at?->format('M d, Y') }}</td>
                                                    <td class="text-center">
                                                        @can('view-roles')
                                                        <button class="btn btn-soft-info btn-sm" onclick="viewRecord({{ $role->id }})" title="{{ x_('View', 'dashboards.admin.pages.user.roles.index') }}"><i class="ti ti-eye"></i></button>
                                                        @endcan
                                                        @can('edit-roles')
                                                        <button class="btn btn-soft-warning btn-sm" onclick="editRecord({{ $role->id }})" title="{{ x_('Edit', 'dashboards.admin.pages.user.roles.index') }}"><i class="ti ti-pencil"></i></button>
                                                        @endcan
                                                        @can('delete-roles')
                                                        @if(!in_array($role->name, ['super-admin', 'admin']))
                                                            <button class="btn btn-soft-danger btn-sm" onclick="deleteRecord({{ $role->id }})" title="{{ x_('Delete', 'dashboards.admin.pages.user.roles.index') }}"><i class="ti ti-trash"></i></button>
                                                        @endif
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ti ti-shield-off fs-1 d-block mb-2"></i>
                                                            No roles found
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Card View -->
                                <div id="cardView" class="d-none p-3">
                                    <div class="row g-3">
                                        @forelse($roles as $role)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="card h-100 border shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                                            <div class="d-flex align-items-center">
                                                                <input type="checkbox" class="form-check-input row-checkbox me-2" value="{{ $role->id }}"
                                                                    {{ in_array($role->name, ['super-admin', 'admin']) ? 'disabled' : '' }}>
                                                                <div class="avatar bg-{{ $role->name == 'super-admin' ? 'danger' : ($role->name == 'admin' ? 'warning' : 'primary') }}-subtle text-{{ $role->name == 'super-admin' ? 'danger' : ($role->name == 'admin' ? 'warning' : 'primary') }} rounded me-2">
                                                                    <i class="ti ti-shield fs-5"></i>
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-0">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</h6>
                                                                    <small class="text-muted">{{ $role->guard_name }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 mb-3">
                                                            <span class="badge bg-info-subtle text-info"><i class="ti ti-users me-1"></i> {{ $role->users_count }} users</span>
                                                            <span class="badge bg-secondary-subtle text-secondary"><i class="ti ti-key me-1"></i> {{ $role->permissions->count() }} permissions</span>
                                                        </div>
                                                        @if($role->permissions->count() > 0)
                                                            <div class="mb-3">
                                                                <small class="text-muted">{{ x_('Permissions:', 'dashboards.admin.pages.user.roles.index') }}</small>
                                                                <div class="mt-1">
                                                                    @foreach($role->permissions->take(3) as $permission)
                                                                        <span class="badge bg-light text-dark me-1 mb-1">{{ $permission->name }}</span>
                                                                    @endforeach
                                                                    @if($role->permissions->count() > 3)
                                                                        <span class="badge bg-light text-dark">+{{ $role->permissions->count() - 3 }} more</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="d-flex gap-1">
                                                            @can('view-roles')
                                                            <button class="btn btn-soft-info btn-sm" onclick="viewRecord({{ $role->id }})" title="{{ x_('View', 'dashboards.admin.pages.user.roles.index') }}"><i class="ti ti-eye"></i></button>
                                                            @endcan
                                                            @can('edit-roles')
                                                            <button class="btn btn-soft-warning btn-sm" onclick="editRecord({{ $role->id }})" title="{{ x_('Edit', 'dashboards.admin.pages.user.roles.index') }}"><i class="ti ti-pencil"></i></button>
                                                            @endcan
                                                            @can('delete-roles')
                                                            @if(!in_array($role->name, ['super-admin', 'admin']))
                                                                <button class="btn btn-soft-danger btn-sm" onclick="deleteRecord({{ $role->id }})" title="{{ x_('Delete', 'dashboards.admin.pages.user.roles.index') }}"><i class="ti ti-trash"></i></button>
                                                            @endif
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ti ti-shield-off fs-1 d-block mb-2"></i>
                                                    No roles found
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            @if($roles->hasPages())
                                <div class="card-footer">{{ $roles->links() }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @include('dashboards.shared.footer')
        </div>
    </div>

    {{-- All Modals --}}
    @can('create-roles')
    @include('dashboards.admin.pages.user.roles.partials.create-modal')
    @endcan
    @can('edit-roles')
    @include('dashboards.admin.pages.user.roles.partials.edit-modal')
    @endcan
    @can('view-roles')
    @include('dashboards.admin.pages.user.roles.partials.show-modal')
    @endcan
    @can('delete-roles')
    @include('dashboards.admin.pages.user.roles.partials.delete-modal')
    @endcan

    @include('dashboards.shared.theme_settings')
    @include('dashboards.shared.scripts')

    {{-- Page-specific Scripts --}}
    @include('dashboards.admin.pages.user.roles.partials.scripts')
</body>
</html>
