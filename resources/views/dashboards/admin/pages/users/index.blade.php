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
                                            <i class="ti ti-users me-1"></i> إدارة المستخدمين والأدوار
                                        </span>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb mb-0">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                                                <li class="breadcrumb-item active" aria-current="page">المستخدمين</li>
                                            </ol>
                                        </nav>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-2 mt-lg-0">
                                    @can('roles.view')
                                        <a href="{{ route('admin.roles.index') }}"
                                            style="border-radius: var(--ins-border-radius);"
                                            class="btn btn-outline-primary shadow-sm px-3">
                                            <i class="ti ti-shield me-1"></i>
                                            <span>إدارة الأدوار</span>
                                        </a>
                                    @endcan
                                    @can('users.bulk-download')
                                        <a href="{{ route('admin.users.bulk-download') }}?{{ http_build_query(request()->query()) }}"
                                            style="border-radius: var(--ins-border-radius);"
                                            class="btn btn-soft-success shadow-sm px-3">
                                            <i class="ti ti-download me-1"></i>
                                            <span>تصدير</span>
                                        </a>
                                    @endcan
                                    @can('users.bulk-upload')
                                        <button type="button" class="btn btn-outline-primary shadow-sm px-3"
                                            style="border-radius: var(--ins-border-radius);" data-bs-toggle="modal"
                                            data-bs-target="#bulkUploadModal">
                                            <i class="ti ti-upload me-1"></i>
                                            <span>استيراد</span>
                                        </button>
                                    @endcan
                                    @can('users.create')
                                        <button type="button" class="btn btn-primary shadow-sm px-3"
                                            style="border-radius: var(--ins-border-radius);" data-bs-toggle="modal"
                                            data-bs-target="#createModal">
                                            <i class="ti ti-plus me-1"></i>
                                            <span>إضافة مستخدم</span>
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
                                    <div class="avatar avatar-md bg-primary-subtle text-primary rounded me-3">
                                        <i class="ti ti-users fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\User::count() }}</h4>
                                        <small class="text-muted">إجمالي المستخدمين</small>
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
                                        <h4 class="mb-0">{{ \App\Models\User::where('is_active', true)->count() }}</h4>
                                        <small class="text-muted">مستخدمين نشطين</small>
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
                                        <small class="text-muted">الأدوار</small>
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
                                        <h4 class="mb-0">{{ \App\Models\User::where('is_active', false)->count() }}</h4>
                                        <small class="text-muted">مستخدمين غير نشطين</small>
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
                    $roleLabel = $currentRole ? ucfirst(str_replace('-', ' ', $currentRole->name)) : 'كل الأدوار';

                    $departmentsCollection = $departments ?? collect();
                    $departmentValue = request('department_id');
                    $currentDepartment = $departmentsCollection->firstWhere('id', $departmentValue);
                    $departmentLabel = $currentDepartment?->name ?? 'كل الأقسام';

                    $statusValue = request('status');
                    $statusLabel = $statusValue === 'active' ? 'نشط' : ($statusValue === 'inactive' ? 'غير نشط' : 'كل الحالات');

                    $trashedValue = request('trashed');
                    $trashedLabel = $trashedValue === 'only' ? 'المحذوفة فقط' : 'السجلات النشطة';

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
                                            <label class="form-label fw-semibold">بحث</label>
                                            <div class="input-group shadow-sm border border-secondary border-opacity-10 overflow-hidden bg-body"
                                                style="border-radius: var(--ins-border-radius);">
                                                <input type="text" name="search" class="form-control border-0 bg-transparent"
                                                    placeholder="الاسم، البريد، الكود..." value="{{ request('search') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2 d-flex align-items-center gap-2">
                                            <div class="d-flex flex-wrap gap-1">
                                                <button type="submit" class="btn btn-primary shadow-sm px-3"
                                                    style="border-radius: var(--ins-border-radius);">
                                                    <i class="ti ti-filter me-1"></i> تصفية</button>
                                                <a href="{{ route('admin.users.index') }}" style="border-radius: var(--ins-border-radius);"
                                                    class="btn btn-secondary shadow-sm px-3">
                                                    <i class="ti ti-refresh me-1"></i> إعادة تعيين</a>
                                            </div>

                                            <button id="toggleTransferFilters"
                                                class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm"
                                                style="border-radius: var(--ins-border-radius);" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#transferFilters" aria-expanded="{{ $hasFilters ? 'true' : 'false' }}"
                                                aria-controls="transferFilters">
                                                <i class="ti {{ $hasFilters ? 'ti-eye-off' : 'ti-filter' }}"></i>
                                                <span>{{ $hasFilters ? 'إخفاء الفلاتر' : 'إظهار الفلاتر' }}</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="collapse {{ $hasFilters ? 'show' : '' }} row g-3 align-items-end" id="transferFilters">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">الدور</label>

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
                                                            href="#" data-value="" data-text="كل الأدوار">
                                                            <span>كل الأدوار</span>
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
                                            <label class="form-label fw-semibold">القسم</label>

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
                                                            href="#" data-value="" data-text="كل الأقسام">
                                                            <span>كل الأقسام</span>
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
                                            <label class="form-label fw-semibold">إظهار</label>

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
                                                            href="#" data-value="" data-text="السجلات النشطة">
                                                            <i class="ti ti-circle-check fs-16 text-muted"></i>
                                                            <span>السجلات النشطة</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ $trashedValue === 'only' ? 'active' : '' }}"
                                                            href="#" data-value="only" data-text="المحذوفة فقط">
                                                            <i class="ti ti-trash fs-16 text-muted"></i>
                                                            <span>المحذوفة فقط</span>
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
                                    <h5 class="card-title mb-0">المستخدمين</h5>
                                    <span class="badge bg-primary-subtle text-primary">{{ $users->total() }}
                                        مستخدم</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Bulk Actions -->
                                    <div class="bulk-actions d-none me-2" id="bulkActions">
                                        @if (request('trashed') != 'only')
                                            @can('users.bulk-delete')
                                                <button type="button" class="btn btn-soft-danger btn-sm"
                                                    onclick="bulkDelete()">
                                                    <i class="ti ti-trash me-1"></i> حذف المحدد</button>
                                            @endcan
                                        @endif
                                        @if (request('trashed') == 'only')
                                            @can('users.bulk-restore')
                                                <button type="button" class="btn btn-soft-success btn-sm"
                                                    onclick="bulkRestore()">
                                                    <i class="ti ti-refresh me-1"></i> استعادة المحدد</button>
                                            @endcan
                                            @can('users.bulk-force-delete')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="bulkForceDelete()">
                                                    <i class="ti ti-trash-x me-1"></i> حذف نهائي</button>
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
                                                <th>المستخدم</th>
                                                <th>البريد الإلكتروني</th>
                                                <th>الدور</th>
                                                <th>آخر دخول</th>
                                                <th>الحالة</th>
                                                <th width="200" class="text-center">الإجراءات</th>
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
                                                            <span class="badge bg-secondary-subtle text-secondary">بدون دور</span>
                                                        @endforelse
                                                    </td>
                                                    <td>
                                                        @if ($user->last_login_at)
                                                            <span
                                                                title="{{ $user->last_login_at->format('Y-m-d H:i:s') }}">{{ $user->last_login_at->diffForHumans() }}</span>
                                                        @else
                                                            <span class="text-muted">لم يسجل دخول</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($user->trashed())
                                                            <span class="badge bg-warning-subtle text-warning"><i
                                                                    class="ti ti-trash me-1"></i> محذوف</span>
                                                        @elseif($user->is_active)
                                                            <span
                                                                class="badge bg-success-subtle text-success">نشط</span>
                                                        @else
                                                            <span
                                                                class="badge bg-danger-subtle text-danger">غير نشط</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center" width="200">
                                                        @if ($user->trashed())
                                                            @can('users.restore')
                                                                <button class="btn btn-soft-success btn-sm"
                                                                    onclick="restoreRecord({{ $user->id }})"
                                                                    title="استعادة"><i class="ti ti-refresh"></i></button>
                                                            @endcan
                                                            @can('users.force-delete')
                                                                <button class="btn btn-danger btn-sm"
                                                                    onclick="forceDeleteRecord({{ $user->id }})"
                                                                    title="حذف نهائي"><i
                                                                        class="ti ti-trash-x"></i></button>
                                                            @endcan
                                                        @else
                                                            @can('users.view')
                                                                <button class="btn btn-soft-info btn-sm"
                                                                    onclick="viewRecord({{ $user->id }})"
                                                                    title="عرض"><i class="ti ti-eye"></i></button>
                                                            @endcan
                                                            @can('users.edit')
                                                                <button class="btn btn-soft-warning btn-sm"
                                                                    onclick="editRecord({{ $user->id }})"
                                                                    title="تعديل"><i class="ti ti-pencil"></i></button>
                                                            @endcan
                                                            @can('users.delete')
                                                                @if ($user->id !== auth()->id())
                                                                    <button class="btn btn-soft-danger btn-sm"
                                                                        onclick="deleteRecord({{ $user->id }})"
                                                                        title="حذف"><i
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
                                                            لا يوجد مستخدمين
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
                                                                    class="badge bg-success-subtle text-success">نشط</span>
                                                            @else
                                                                <span
                                                                    class="badge bg-danger-subtle text-danger">غير نشط</span>
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
                                                                <span class="badge bg-secondary-subtle text-secondary">بدون دور</span>
                                                            @endforelse
                                                        </div>
                                                        <small class="text-muted d-block mb-3">
                                                            <i class="ti ti-building me-1"></i>
                                                            {{ $user->department?->name ?? 'بدون قسم' }}
                                                        </small>
                                                        <div class="d-flex gap-1 justify-content-center">
                                                            @if ($user->trashed())
                                                                @can('users.restore')
                                                                    <button class="btn btn-soft-success btn-sm"
                                                                        onclick="restoreRecord({{ $user->id }})"><i
                                                                            class="ti ti-refresh"></i></button>
                                                                @endcan
                                                                @can('users.force-delete')
                                                                    <button class="btn btn-danger btn-sm"
                                                                        onclick="forceDeleteRecord({{ $user->id }})"><i
                                                                            class="ti ti-trash-x"></i></button>
                                                                @endcan
                                                            @else
                                                                @can('users.view')
                                                                    <button class="btn btn-soft-info btn-sm"
                                                                        onclick="viewRecord({{ $user->id }})"
                                                                        title="عرض"><i class="ti ti-eye"></i></button>
                                                                @endcan
                                                                @can('users.edit')
                                                                    <button class="btn btn-soft-warning btn-sm"
                                                                        onclick="editRecord({{ $user->id }})"
                                                                        title="تعديل"><i
                                                                            class="ti ti-pencil"></i></button>
                                                                @endcan
                                                                @can('users.delete')
                                                                    @if ($user->id !== auth()->id())
                                                                        <button class="btn btn-soft-danger btn-sm"
                                                                            onclick="deleteRecord({{ $user->id }})"
                                                                            title="حذف"><i
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
                                                    لا يوجد مستخدمين
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
    @can('users.create')
        @include('dashboards.admin.pages.users.partials.create-modal')
    @endcan
    @can('users.edit')
        @include('dashboards.admin.pages.users.partials.edit-modal')
    @endcan
    @can('users.view')
        @include('dashboards.admin.pages.users.partials.show-modal')
    @endcan
    @can('users.delete')
        @include('dashboards.admin.pages.users.partials.delete-modal')
    @endcan
    @can('users.bulk-upload')
        @include('dashboards.admin.pages.users.partials.bulk-upload-modal')
    @endcan

    @include('dashboards.shared.theme_settings')
    @include('dashboards.shared.scripts')

    {{-- Page-specific Scripts --}}
    @include('dashboards.admin.pages.users.partials.scripts')
</body>

</html>
