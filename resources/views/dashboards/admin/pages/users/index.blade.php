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
                            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between py-2 px-3 bg-body border border-secondary border-opacity-10 shadow-sm"
                                style="border-radius: var(--ins-border-radius);">
                                <div class="d-flex align-items-start align-items-md-center">
                                    <div>
                                        <span
                                            class="badge badge-default fw-normal shadow px-2 fst-italic fs-sm d-inline-flex align-items-center">
                                            <i class="ti ti-users me-1"></i> Users & Roles Management
                                        </span>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb mb-0">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ route('admin.dashboard') }}">{{ x_('Dashboard', 'dashboards.admin.pages.user.users.index') }}</a></li>
                                                <li class="breadcrumb-item active" aria-current="page">Users</li>
                                            </ol>
                                        </nav>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-2 mt-lg-0">
                                    @can('view-roles')
                                        <a href="{{ route('admin.roles.index') }}"
                                            style="border-radius: var(--ins-border-radius);"
                                            class="btn btn-outline-primary shadow-sm px-3">
                                            <i class="ti ti-shield me-1"></i>
                                            <span>Manage Roles</span>
                                        </a>
                                    @endcan
                                    @can('bulk-download-users')
                                        <a href="{{ route('admin.users.bulk-download') }}?{{ http_build_query(request()->query()) }}"
                                            style="border-radius: var(--ins-border-radius);"
                                            class="btn btn-soft-success shadow-sm px-3">
                                            <i class="ti ti-download me-1"></i>
                                            <span>{{ x_('Export', 'dashboards.admin.pages.user.users.index') }}</span>
                                        </a>
                                    @endcan
                                    @can('bulk-upload-users')
                                        <button type="button" class="btn btn-outline-primary shadow-sm px-3"
                                            style="border-radius: var(--ins-border-radius);" data-bs-toggle="modal"
                                            data-bs-target="#bulkUploadModal">
                                            <i class="ti ti-upload me-1"></i>
                                            <span>{{ x_('Import', 'dashboards.admin.pages.user.users.index') }}</span>
                                        </button>
                                    @endcan
                                    @can('create-users')
                                        <button type="button" class="btn btn-primary shadow-sm px-3"
                                            style="border-radius: var(--ins-border-radius);" data-bs-toggle="modal"
                                            data-bs-target="#createModal">
                                            <i class="ti ti-plus me-1"></i>
                                            <span>Add User</span>
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-mdbg-primary-subtle text-primary rounded me-3">
                                        <i class="ti ti-users fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\User::count() }}</h4>
                                        <small class="text-muted">{{ x_('Total Users', 'dashboards.admin.pages.user.users.index') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-success-subtle text-success rounded me-3">
                                        <i class="ti ti-user-check fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\User::where('is_active', true)->count() }}
                                        </h4>
                                        <small class="text-muted">{{ x_('Active Users', 'dashboards.admin.pages.user.users.index') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-warning-subtle text-warning rounded me-3">
                                        <i class="ti ti-shield fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \Spatie\Permission\Models\Role::count() }}</h4>
                                        <small class="text-muted">{{ x_('Roles', 'dashboards.admin.pages.user.users.index') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-danger-subtle text-danger rounded me-3">
                                        <i class="ti ti-user-off fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\User::where('is_active', false)->count() }}
                                        </h4>
                                        <small class="text-muted">{{ x_('Inactive Users', 'dashboards.admin.pages.user.users.index') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Card -->
                @php
                    $rolesCollection = $roles ?? collect();
                    $roleValue = request('role');
                    $currentRole = $rolesCollection->firstWhere('name', $roleValue);
                    $roleLabel = $currentRole ? ucfirst(str_replace('-', ' ', $currentRole->name)) : 'All Roles';

                    $departmentsCollection = $departments ?? collect();
                    $departmentValue = request('department_id');
                    $currentDepartment = $departmentsCollection->firstWhere('id', $departmentValue);
                    $departmentLabel = $currentDepartment?->name ?? 'All Departments';

                    $statusValue = request('status');
                    $statusLabel = $statusValue === 'active' ? 'Active' : ($statusValue === 'inactive' ? 'Inactive' : 'All Status');

                    $trashedValue = request('trashed');
                    $trashedLabel = $trashedValue === 'only' ? 'Deleted Only' : 'Active Records';

                    $hasFilters =
                        request()->filled('search') ||
                        request()->filled('role') ||
                        request()->filled('department_id') ||
                        request()->filled('status') ||
                        $trashedValue === 'only';
                @endphp
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="border-radius: var(--ins-border-radius);">
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.users.index') }}">
                                    <div class="row d-flex align-items-end justify-content-start">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label fw-semibold">{{ x_('Search', 'dashboards.admin.pages.user.users.index') }}</label>
                                            <div class="input-group shadow-sm border border-secondary border-opacity-10 overflow-hidden bg-body"
                                                style="border-radius: var(--ins-border-radius);">
                                                <input type="text" name="search" class="form-control border-0 bg-transparent"
                                                    placeholder="{{ x_('Name, email, code...', 'dashboards.admin.pages.user.users.index') }}" value="{{ request('search') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2 d-flex align-items-center gap-2">
                                            <div class="d-flex flex-wrap gap-1">
                                                <button type="submit" class="btn btn-primary shadow-sm px-3"
                                                    style="border-radius: var(--ins-border-radius);">
                                                    <i class="ti ti-filter me-1"></i> {{ x_('Filter', 'dashboards.admin.pages.user.users.index') }}</button>
                                                <a href="{{ route('admin.users.index') }}" style="border-radius: var(--ins-border-radius);"
                                                    class="btn btn-secondary shadow-sm px-3">
                                                    <i class="ti ti-refresh me-1"></i> {{ x_('Reset', 'dashboards.admin.pages.user.users.index') }}</a>
                                            </div>

                                            <button id="toggleTransferFilters"
                                                class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm"
                                                style="border-radius: var(--ins-border-radius);" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#transferFilters" aria-expanded="{{ $hasFilters ? 'true' : 'false' }}"
                                                aria-controls="transferFilters">
                                                <i class="ti {{ $hasFilters ? 'ti-eye-off' : 'ti-filter' }}"></i>
                                                <span>{{ $hasFilters ? 'Hide filters' : 'Show filters' }}</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="collapse {{ $hasFilters ? 'show' : '' }} row g-3 align-items-end" id="transferFilters">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">{{ x_('Role', 'dashboards.admin.pages.user.users.index') }}</label>

                                            <input type="hidden" name="role" value="{{ request('role') }}">

                                            <div class="dropdown filter-dropdown w-100">
                                                <button
                                                    class="btn w-100 text-start d-flex align-items-center justify-content-between bg-body border border-secondary border-opacity-10 shadow-sm px-3 py-2"
                                                    style="border-radius: var(--ins-border-radius);" type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span class="d-inline-flex align-items-center gap-2">
                                                        <span data-selected-text>{{ $roleLabel }}</span>
                                                    </span>
                                                    <i class="ti ti-chevron-down text-muted"></i>
                                                </button>

                                                <ul class="dropdown-menu w-100 shadow-sm border border-secondary border-opacity-10 py-2 overflow-auto dropdown-scroll"
                                                    style="border-radius: var(--ins-border-radius);">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ $roleValue === null ? 'active' : '' }}"
                                                            href="#" data-value="" data-text="All Roles">
                                                            <span>All Roles</span>
                                                        </a>
                                                    </li>
                                                    @foreach ($rolesCollection as $role)
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ (string) $roleValue === (string) $role->name ? 'active' : '' }}"
                                                                href="#" data-value="{{ $role->name }}"
                                                                data-text="{{ ucfirst(str_replace('-', ' ', $role->name)) }}">
                                                                <span>{{ ucfirst(str_replace('-', ' ', $role->name)) }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">{{ x_('Department', 'dashboards.admin.pages.user.users.index') }}</label>

                                            <input type="hidden" name="department_id" value="{{ request('department_id') }}">

                                            <div class="dropdown filter-dropdown w-100">
                                                <button
                                                    class="btn w-100 text-start d-flex align-items-center justify-content-between bg-body border border-secondary border-opacity-10 shadow-sm px-3 py-2"
                                                    style="border-radius: var(--ins-border-radius);" type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span class="d-inline-flex align-items-center gap-2">
                                                        <span data-selected-text>{{ $departmentLabel }}</span>
                                                    </span>
                                                    <i class="ti ti-chevron-down text-muted"></i>
                                                </button>

                                                <ul class="dropdown-menu w-100 shadow-sm border border-secondary border-opacity-10 py-2 overflow-auto dropdown-scroll"
                                                    style="border-radius: var(--ins-border-radius);">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ $departmentValue === null ? 'active' : '' }}"
                                                            href="#" data-value="" data-text="All Departments">
                                                            <span>All Departments</span>
                                                        </a>
                                                    </li>
                                                    @foreach ($departmentsCollection as $dept)
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ (string) $departmentValue === (string) $dept->id ? 'active' : '' }}"
                                                                href="#" data-value="{{ $dept->id }}" data-text="{{ $dept->name }}">
                                                                <span>{{ $dept->name }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">{{ x_('Status', 'dashboards.admin.pages.user.users.index') }}</label>

                                            <input type="hidden" name="status" value="{{ request('status') }}">

                                            <div class="dropdown filter-dropdown w-100">
                                                <button
                                                    class="btn w-100 text-start d-flex align-items-center justify-content-between bg-body border border-secondary border-opacity-10 shadow-sm px-3 py-2"
                                                    style="border-radius: var(--ins-border-radius);" type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span class="d-inline-flex align-items-center gap-2">
                                                        <span data-selected-text>{{ $statusLabel }}</span>
                                                    </span>
                                                    <i class="ti ti-chevron-down text-muted"></i>
                                                </button>

                                                <ul class="dropdown-menu w-100 shadow-sm border border-secondary border-opacity-10 py-2 overflow-auto dropdown-scroll"
                                                    style="border-radius: var(--ins-border-radius);">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ $statusValue === null ? 'active' : '' }}"
                                                            href="#" data-value="" data-text="All Status">
                                                            <i class="ti ti-adjustments fs-16 text-muted"></i>
                                                            <span>All Status</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ $statusValue === 'active' ? 'active' : '' }}"
                                                            href="#" data-value="active" data-text="Active">
                                                            <i class="ti ti-circle-check fs-16 text-muted"></i>
                                                            <span>{{ x_('Active', 'dashboards.admin.pages.user.users.index') }}</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ $statusValue === 'inactive' ? 'active' : '' }}"
                                                            href="#" data-value="inactive" data-text="Inactive">
                                                            <i class="ti ti-circle-x fs-16 text-muted"></i>
                                                            <span>{{ x_('Inactive', 'dashboards.admin.pages.user.users.index') }}</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">{{ x_('Show', 'dashboards.admin.pages.user.users.index') }}</label>

                                            <input type="hidden" name="trashed" value="{{ request('trashed') }}">

                                            <div class="dropdown filter-dropdown w-100">
                                                <button
                                                    class="btn w-100 text-start d-flex align-items-center justify-content-between bg-body border border-secondary border-opacity-10 shadow-sm px-3 py-2"
                                                    style="border-radius: var(--ins-border-radius);" type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span class="d-inline-flex align-items-center gap-2">
                                                        <span data-selected-text>{{ $trashedLabel }}</span>
                                                    </span>
                                                    <i class="ti ti-chevron-down text-muted"></i>
                                                </button>

                                                <ul class="dropdown-menu w-100 shadow-sm border border-secondary border-opacity-10 py-2 overflow-auto dropdown-scroll"
                                                    style="border-radius: var(--ins-border-radius);">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ $trashedValue !== 'only' ? 'active' : '' }}"
                                                            href="#" data-value="" data-text="Active Records">
                                                            <i class="ti ti-circle-check fs-16 text-muted"></i>
                                                            <span>Active Records</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ $trashedValue === 'only' ? 'active' : '' }}"
                                                            href="#" data-value="only" data-text="Deleted Only">
                                                            <i class="ti ti-trash fs-16 text-muted"></i>
                                                            <span>Deleted Only</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
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
                                    <h5 class="card-title mb-0">{{ x_('Users', 'dashboards.admin.pages.user.users.index') }}</h5>
                                    <span class="badge bg-primary-subtle text-primary">{{ $users->total() }}
                                        Users</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Bulk Actions -->
                                    <div class="bulk-actions d-none me-2" id="bulkActions">
                                        @if (request('trashed') != 'only')
                                            @can('bulk-delete-users')
                                                <button type="button" class="btn btn-soft-danger btn-sm"
                                                    onclick="bulkDelete()">
                                                    <i class="ti ti-trash me-1"></i> {{ x_('Delete Selected', 'dashboards.admin.pages.user.users.index') }}</button>
                                            @endcan
                                        @endif
                                        @if (request('trashed') == 'only')
                                            @can('bulk-restore-users')
                                                <button type="button" class="btn btn-soft-success btn-sm"
                                                    onclick="bulkRestore()">
                                                    <i class="ti ti-refresh me-1"></i> {{ x_('Restore Selected', 'dashboards.admin.pages.user.users.index') }}</button>
                                            @endcan
                                            @can('bulk-force-delete-users')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="bulkForceDelete()">
                                                    <i class="ti ti-trash-x me-1"></i> {{ x_('Permanently Delete', 'dashboards.admin.pages.user.users.index') }}</button>
                                            @endcan
                                        @endif
                                    </div>
                                    <!-- View Toggle -->
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary active"
                                            id="listViewBtn" onclick="toggleView('list')">
                                            <i class="ti ti-list"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            id="cardViewBtn" onclick="toggleView('card')">
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
                                                <th width="10"><input type="checkbox" class="form-check-input"
                                                        id="selectAll"></th>
                                                <th>{{ x_('User', 'dashboards.admin.pages.user.users.index') }}</th>
                                                <th>{{ x_('Email', 'dashboards.admin.pages.user.users.index') }}</th>
                                                <th>{{ x_('Role', 'dashboards.admin.pages.user.users.index') }}</th>
                                                <th>{{ x_('Department', 'dashboards.admin.pages.user.users.index') }}</th>
                                                <th>{{ x_('Last Login', 'dashboards.admin.pages.user.users.index') }}</th>
                                                <th>{{ x_('Status', 'dashboards.admin.pages.user.users.index') }}</th>
                                                <th width="150" class="text-center">{{ x_('Actions', 'dashboards.admin.pages.user.users.index') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($users as $user)
                                                <tr>
                                                    <td><input type="checkbox" class="form-check-input row-checkbox"
                                                            value="{{ $user->id }}"
                                                            {{ $user->id == auth()->id() ? 'disabled' : '' }}></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if ($user->getFirstMediaUrl('avatar'))
                                                                <img src="{{ $user->getFirstMediaUrl('avatar') }}"
                                                                    class="avatar avatar-sm rounded-circle me-2"
                                                                    alt="">
                                                            @else
                                                                <div
                                                                    class="avatar avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <h6 class="mb-0">
                                                                    {{ $user->full_name ?? $user->name }}</h6>
                                                                <small
                                                                    class="text-muted">{{ $user->employee_code ?? '' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        @forelse($user->roles as $role)
                                                            <span
                                                                class="badge bg-{{ $role->name == 'super-admin' ? 'danger' : ($role->name == 'admin' ? 'warning' : 'primary') }}-subtle text-{{ $role->name == 'super-admin' ? 'danger' : ($role->name == 'admin' ? 'warning' : 'primary') }}">
                                                                {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                                            </span>
                                                        @empty
                                                            <span class="badge bg-secondary-subtle text-secondary">No
                                                                Role</span>
                                                        @endforelse
                                                    </td>
                                                    <td>{{ $user->department?->name ?? '-' }}</td>
                                                    <td>
                                                        @if ($user->last_login_at)
                                                            <span
                                                                title="{{ $user->last_login_at->format('Y-m-d H:i:s') }}">{{ $user->last_login_at->diffForHumans() }}</span>
                                                        @else
                                                            <span class="text-muted">Never</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($user->trashed())
                                                            <span class="badge bg-warning-subtle text-warning"><i
                                                                    class="ti ti-trash me-1"></i> {{ x_('Trashed', 'dashboards.admin.pages.user.users.index') }}</span>
                                                        @elseif($user->is_active)
                                                            <span
                                                                class="badge bg-success-subtle text-success">{{ x_('Active', 'dashboards.admin.pages.user.users.index') }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-danger-subtle text-danger">{{ x_('Inactive', 'dashboards.admin.pages.user.users.index') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($user->trashed())
                                                            @can('restore-users')
                                                                <button class="btn btn-soft-success btn-sm"
                                                                    onclick="restoreRecord({{ $user->id }})"
                                                                    title="{{ x_('Restore', 'dashboards.admin.pages.user.users.index') }}"><i class="ti ti-refresh"></i></button>
                                                            @endcan
                                                            @can('force-delete-users')
                                                                <button class="btn btn-danger btn-sm"
                                                                    onclick="forceDeleteRecord({{ $user->id }})"
                                                                    title="{{ x_('Permanently Delete', 'dashboards.admin.pages.user.users.index') }}"><i
                                                                        class="ti ti-trash-x"></i></button>
                                                            @endcan
                                                        @else
                                                            @can('view-users')
                                                                <button class="btn btn-soft-info btn-sm"
                                                                    onclick="viewRecord({{ $user->id }})"
                                                                    title="{{ x_('View', 'dashboards.admin.pages.user.users.index') }}"><i class="ti ti-eye"></i></button>
                                                            @endcan
                                                            @can('edit-users')
                                                                <button class="btn btn-soft-warning btn-sm"
                                                                    onclick="editRecord({{ $user->id }})"
                                                                    title="{{ x_('Edit', 'dashboards.admin.pages.user.users.index') }}"><i class="ti ti-pencil"></i></button>
                                                            @endcan
                                                            @can('delete-users')
                                                                @if ($user->id !== auth()->id())
                                                                    <button class="btn btn-soft-danger btn-sm"
                                                                        onclick="deleteRecord({{ $user->id }})"
                                                                        title="{{ x_('Delete', 'dashboards.admin.pages.user.users.index') }}"><i
                                                                            class="ti ti-trash"></i></button>
                                                                @endif
                                                            @endcan
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ti ti-users-off fs-1 d-block mb-2"></i>
                                                            No users found
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
                                        @forelse($users as $user)
                                            <div class="col-md-6 col-lg-4 col-xl-3">
                                                <div class="card h-100 border shadow-sm">
                                                    <div class="card-body text-center">
                                                        <div class="position-absolute top-0 start-0 p-2">
                                                            <input type="checkbox"
                                                                class="form-check-input row-checkbox"
                                                                value="{{ $user->id }}"
                                                                {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                                        </div>
                                                        <div class="position-absolute top-0 end-0 p-2">
                                                            @if ($user->trashed())
                                                                <span class="badge bg-warning-subtle text-warning"><i
                                                                        class="ti ti-trash"></i></span>
                                                            @elseif($user->is_active)
                                                                <span
                                                                    class="badge bg-success-subtle text-success">{{ x_('Active', 'dashboards.admin.pages.user.users.index') }}</span>
                                                            @else
                                                                <span
                                                                    class="badge bg-danger-subtle text-danger">{{ x_('Inactive', 'dashboards.admin.pages.user.users.index') }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="mb-3 mt-2">
                                                            @if ($user->getFirstMediaUrl('avatar'))
                                                                <img src="{{ $user->getFirstMediaUrl('avatar') }}"
                                                                    class="avatar avatar-lg rounded-circle"
                                                                    alt="">
                                                            @else
                                                                <div class="avatar avatar-lg bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center"
                                                                    style="width: 64px; height: 64px; font-size: 24px;">
                                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <h6 class="mb-1">{{ $user->full_name ?? $user->name }}</h6>
                                                        <small
                                                            class="text-muted d-block mb-2">{{ $user->email }}</small>
                                                        <div class="mb-2">
                                                            @forelse($user->roles as $role)
                                                                <span
                                                                    class="badge bg-{{ $role->name == 'super-admin' ? 'danger' : ($role->name == 'admin' ? 'warning' : 'primary') }}-subtle text-{{ $role->name == 'super-admin' ? 'danger' : ($role->name == 'admin' ? 'warning' : 'primary') }}">
                                                                    {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                                                </span>
                                                            @empty
                                                                <span
                                                                    class="badge bg-secondary-subtle text-secondary">No
                                                                    Role</span>
                                                            @endforelse
                                                        </div>
                                                        <small class="text-muted d-block mb-3">
                                                            <i class="ti ti-building me-1"></i>
                                                            {{ $user->department?->name ?? 'No Department' }}
                                                        </small>
                                                        <div class="d-flex gap-1 justify-content-center">
                                                            @if ($user->trashed())
                                                                @can('restore-users')
                                                                    <button class="btn btn-soft-success btn-sm"
                                                                        onclick="restoreRecord({{ $user->id }})"><i
                                                                            class="ti ti-refresh"></i></button>
                                                                @endcan
                                                                @can('force-delete-users')
                                                                    <button class="btn btn-danger btn-sm"
                                                                        onclick="forceDeleteRecord({{ $user->id }})"><i
                                                                            class="ti ti-trash-x"></i></button>
                                                                @endcan
                                                            @else
                                                                @can('view-users')
                                                                    <button class="btn btn-soft-info btn-sm"
                                                                        onclick="viewRecord({{ $user->id }})"
                                                                        title="{{ x_('View', 'dashboards.admin.pages.user.users.index') }}"><i class="ti ti-eye"></i></button>
                                                                @endcan
                                                                @can('edit-users')
                                                                    <button class="btn btn-soft-warning btn-sm"
                                                                        onclick="editRecord({{ $user->id }})"
                                                                        title="{{ x_('Edit', 'dashboards.admin.pages.user.users.index') }}"><i
                                                                            class="ti ti-pencil"></i></button>
                                                                @endcan
                                                                @can('delete-users')
                                                                    @if ($user->id !== auth()->id())
                                                                        <button class="btn btn-soft-danger btn-sm"
                                                                            onclick="deleteRecord({{ $user->id }})"
                                                                            title="{{ x_('Delete', 'dashboards.admin.pages.user.users.index') }}"><i
                                                                                class="ti ti-trash"></i></button>
                                                                    @endif
                                                                @endcan
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ti ti-users-off fs-1 d-block mb-2"></i>
                                                    No users found
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            @if ($users->hasPages())
                                <div class="card-footer">{{ $users->appends(request()->query())->links() }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @include('dashboards.shared.footer')
        </div>
    </div>

    {{-- All Modals --}}
    @can('create-users')
        @include('dashboards.admin.pages.user.users.partials.create-modal')
    @endcan
    @can('edit-users')
        @include('dashboards.admin.pages.user.users.partials.edit-modal')
    @endcan
    @can('view-users')
        @include('dashboards.admin.pages.user.users.partials.show-modal')
    @endcan
    @can('delete-users')
        @include('dashboards.admin.pages.user.users.partials.delete-modal')
    @endcan
    @can('bulk-upload-users')
        @include('dashboards.admin.pages.user.users.partials.bulk-upload-modal')
    @endcan

    @include('dashboards.shared.theme_settings')
    @include('dashboards.shared.scripts')

    {{-- Page-specific Scripts --}}
    @include('dashboards.admin.pages.user.users.partials.scripts')
</body>

</html>
