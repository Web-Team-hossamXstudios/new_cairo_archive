<!DOCTYPE html>
@include('dashboards.shared.html')

<head>
    @include('dashboards.shared.meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة الأدوار والصلاحيات - أرشيف القاهرة الجديدة</title>
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
                                <div>
                                    <span class="badge badge-default fw-normal shadow px-2 fst-italic fs-sm d-inline-flex align-items-center">
                                        <i class="ti ti-shield-lock me-1"></i> إدارة الأدوار والصلاحيات
                                    </span>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0 mt-1">
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">المستخدمين</a></li>
                                            <li class="breadcrumb-item active">الأدوار</li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="d-flex flex-wrap gap-2 mt-2 mt-lg-0">
                                    @can('users.view')
                                    <a href="{{ route('admin.users.index') }}" style="border-radius: var(--ins-border-radius);" class="btn btn-outline-secondary shadow-sm px-3">
                                        <i class="ti ti-arrow-left me-1"></i>
                                        <span>العودة للمستخدمين</span>
                                    </a>
                                    @endcan
                                    @can('roles.create')
                                    <button type="button" class="btn btn-primary shadow-sm px-3" style="border-radius: var(--ins-border-radius);" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                                        <i class="ti ti-plus me-1"></i>
                                        <span>إضافة دور جديد</span>
                                    </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary-subtle text-primary rounded me-3">
                                        <i class="ti ti-shield fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \Spatie\Permission\Models\Role::count() }}</h4>
                                        <small class="text-muted">إجمالي الأدوار</small>
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
                                        <small class="text-muted">إجمالي الصلاحيات</small>
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
                                        <small class="text-muted">مستخدمين بأدوار</small>
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
                                        <label class="form-label fw-semibold">بحث</label>
                                        <input type="text" name="search" class="form-control shadow-sm border border-secondary border-opacity-10" style="border-radius: var(--ins-border-radius);" placeholder="بحث باسم الدور..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary shadow-sm" style="border-radius: var(--ins-border-radius);">
                                                <i class="ti ti-filter me-1"></i> تصفية</button>
                                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary shadow-sm" style="border-radius: var(--ins-border-radius);">
                                                <i class="ti ti-refresh"></i> إعادة تعيين</a>
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
                                    <h5 class="card-title mb-0">قائمة الأدوار</h5>
                                    <span class="badge bg-primary-subtle text-primary">{{ $roles->total() ?? 0 }} دور</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
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
                                                <th>اسم الدور</th>
                                                <th>المستخدمين</th>
                                                <th>الصلاحيات</th>
                                                <th>تاريخ الإنشاء</th>
                                                <th width="200" class="text-center">الإجراءات</th>
                                            </tr>
                                </thead>
                                        <tbody>
                                            @forelse($roles ?? [] as $role)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input row-checkbox" value="{{ $role->id }}"
                                                            {{ in_array($role->name, ['super-admin', 'admin', 'Super Admin', 'Admin']) ? 'disabled' : '' }}>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm bg-{{ in_array($role->name, ['Super Admin', 'super-admin']) ? 'danger' : (in_array($role->name, ['Admin', 'admin']) ? 'warning' : 'primary') }}-subtle text-{{ in_array($role->name, ['Super Admin', 'super-admin']) ? 'danger' : (in_array($role->name, ['Admin', 'admin']) ? 'warning' : 'primary') }} rounded me-2 d-flex align-items-center justify-content-center">
                                                                <i class="ti ti-shield"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ translate_role_name($role->name) }}</h6>
                                                                <small class="text-muted">{{ $role->guard_name ?? 'web' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info-subtle text-info">{{ $role->users_count ?? $role->users()->count() }} مستخدم</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary-subtle text-secondary">{{ $role->permissions->count() }} صلاحية</span>
                                                    </td>
                                                    <td>{{ $role->created_at?->format('M d, Y') }}</td>
                                                    <td width="200" class="text-center">
                                                        <button class="btn btn-soft-info btn-sm" onclick="viewRole({{ $role->id }})" title="عرض"><i class="ti ti-eye"></i></button>
                                                        @if(!in_array($role->name, ['Super Admin', 'super-admin', 'Admin', 'admin']))
                                                            @can('roles.edit')
                                                            <button class="btn btn-soft-warning btn-sm" onclick="editRole({{ $role->id }})" title="تعديل"><i class="ti ti-pencil"></i></button>
                                                            @endcan
                                                            @can('roles.delete')
                                                            <button class="btn btn-soft-danger btn-sm" onclick="deleteRole({{ $role->id }}, '{{ $role->name }}')" title="حذف"><i class="ti ti-trash"></i></button>
                                                            @endcan
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ti ti-shield-off fs-1 d-block mb-2"></i>
                                                            لا توجد أدوار
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
                                        @forelse($roles ?? [] as $role)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="card h-100 border shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                                            <div class="d-flex align-items-center">
                                                                <input type="checkbox" class="form-check-input row-checkbox me-2" value="{{ $role->id }}"
                                                                    {{ in_array($role->name, ['super-admin', 'admin', 'Super Admin', 'Admin']) ? 'disabled' : '' }}>
                                                                <div class="avatar bg-{{ in_array($role->name, ['Super Admin', 'super-admin']) ? 'danger' : (in_array($role->name, ['Admin', 'admin']) ? 'warning' : 'primary') }}-subtle text-{{ in_array($role->name, ['Super Admin', 'super-admin']) ? 'danger' : (in_array($role->name, ['Admin', 'admin']) ? 'warning' : 'primary') }} rounded me-2">
                                                                    <i class="ti ti-shield fs-5"></i>
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-0">{{ translate_role_name($role->name) }}</h6>
                                                                    <small class="text-muted">{{ $role->guard_name ?? 'web' }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 mb-3">
                                                            <span class="badge bg-info-subtle text-info"><i class="ti ti-users me-1"></i> {{ $role->users_count ?? $role->users()->count() }} مستخدم</span>
                                                            <span class="badge bg-secondary-subtle text-secondary"><i class="ti ti-key me-1"></i> {{ $role->permissions->count() }} صلاحية</span>
                                                        </div>
                                                        @if($role->permissions->count() > 0)
                                                            <div class="mb-3">
                                                                <small class="text-muted">الصلاحيات:</small>
                                                                <div class="mt-1">
                                                                    @foreach($role->permissions->take(3) as $permission)
                                                                        <span class="badge bg-light text-dark me-1 mb-1">{{ translate_permission($permission->name) }}</span>
                                                                    @endforeach
                                                                    @if($role->permissions->count() > 3)
                                                                        <span class="badge bg-light text-dark">+{{ $role->permissions->count() - 3 }} أخرى</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="d-flex gap-1">
                                                            <button class="btn btn-soft-info btn-sm" onclick="viewRole({{ $role->id }})" title="عرض"><i class="ti ti-eye"></i></button>
                                                            @if(!in_array($role->name, ['Super Admin', 'super-admin', 'Admin', 'admin']))
                                                                @can('roles.edit')
                                                                <button class="btn btn-soft-warning btn-sm" onclick="editRole({{ $role->id }})" title="تعديل"><i class="ti ti-pencil"></i></button>
                                                                @endcan
                                                                @can('roles.delete')
                                                                <button class="btn btn-soft-danger btn-sm" onclick="deleteRole({{ $role->id }}, '{{ $role->name }}')" title="حذف"><i class="ti ti-trash"></i></button>
                                                                @endcan
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ti ti-shield-off fs-1 d-block mb-2"></i>
                                                    لا توجد أدوار
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            @if(isset($roles) && $roles->hasPages())
                                <div class="card-footer">{{ $roles->links() }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @include('dashboards.shared.footer')
        </div>
    </div>

    <!-- Create Role Modal -->
    <div class="modal fade" id="createRoleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-shield-plus me-2"></i>إضافة دور جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createRoleForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم الدور <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required placeholder="مثال: مدير">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الصلاحيات</label>
                            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                @foreach($permissions ?? [] as $module => $perms)
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input module-check" type="checkbox" id="createModule{{ $loop->index }}" data-module="{{ $module }}">
                                            <label class="form-check-label fw-bold" for="createModule{{ $loop->index }}">
                                                {{ translate_module($module) }}
                                            </label>
                                        </div>
                                        <div class="ms-4 mt-1">
                                            @foreach($perms as $perm)
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input perm-check" type="checkbox" name="permissions[]" value="{{ $perm->name }}" id="createPerm{{ $perm->id }}" data-module="{{ $module }}">
                                                    <label class="form-check-label small" for="createPerm{{ $perm->id }}">{{ translate_permission($perm->name) }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-shield-edit me-2"></i>تعديل الدور</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editRoleForm">
                    @csrf
                    <input type="hidden" name="id" id="editRoleId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم الدور <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editRoleName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الصلاحيات</label>
                            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;" id="editPermissionsContainer">
                                @foreach($permissions ?? [] as $module => $perms)
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input edit-module-check" type="checkbox" id="editModule{{ $loop->index }}" data-module="{{ $module }}">
                                            <label class="form-check-label fw-bold" for="editModule{{ $loop->index }}">
                                                {{ translate_module($module) }}
                                            </label>
                                        </div>
                                        <div class="ms-4 mt-1">
                                            @foreach($perms as $perm)
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input edit-perm-check" type="checkbox" name="permissions[]" value="{{ $perm->name }}" id="editPerm{{ $perm->id }}" data-module="{{ $module }}">
                                                    <label class="form-check-label small" for="editPerm{{ $perm->id }}">{{ translate_permission($perm->name) }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Role Modal -->
    <div class="modal fade" id="viewRoleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-shield me-2"></i>تفاصيل الدور</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-lg bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="ti ti-shield fs-1"></i>
                        </div>
                        <h4 id="viewRoleName" class="mt-3 mb-1"></h4>
                        <span id="viewRolePermCount" class="badge bg-info-subtle text-info"></span>
                    </div>
                    <hr>
                    <h6 class="mb-3">الصلاحيات:</h6>
                    <div id="viewRolePermissions" class="row g-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    @include('dashboards.admin.pages.roles.partials.delete-modal')

    @include('dashboards.shared.scripts')

    <script>
    // View Toggle
    function toggleView(view) {
        const listView = document.getElementById('listView');
        const cardView = document.getElementById('cardView');
        const listBtn = document.getElementById('listViewBtn');
        const cardBtn = document.getElementById('cardViewBtn');

        if (view === 'list') {
            listView.classList.remove('d-none');
            cardView.classList.add('d-none');
            listBtn.classList.add('active');
            cardBtn.classList.remove('active');
        } else {
            listView.classList.add('d-none');
            cardView.classList.remove('d-none');
            listBtn.classList.remove('active');
            cardBtn.classList.add('active');
        }
    }

    // Module checkbox toggle
    document.querySelectorAll('.module-check').forEach(moduleCheck => {
        moduleCheck.addEventListener('change', function() {
            const module = this.dataset.module;
            document.querySelectorAll(`.perm-check[data-module="${module}"]`).forEach(permCheck => {
                permCheck.checked = this.checked;
            });
        });
    });

    document.querySelectorAll('.edit-module-check').forEach(moduleCheck => {
        moduleCheck.addEventListener('change', function() {
            const module = this.dataset.module;
            document.querySelectorAll(`.edit-perm-check[data-module="${module}"]`).forEach(permCheck => {
                permCheck.checked = this.checked;
            });
        });
    });

    // Create Role
    document.getElementById('createRoleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('{{ route("admin.roles.store") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.message || 'حدث خطأ');
        });
    });

    // Edit Role
    function editRole(id) {
        fetch(`{{ url('admin/roles') }}/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('editRoleId').value = data.id;
                document.getElementById('editRoleName').value = data.name;

                // Reset all checkboxes
                document.querySelectorAll('.edit-perm-check').forEach(cb => cb.checked = false);
                document.querySelectorAll('.edit-module-check').forEach(cb => cb.checked = false);

                // Check permissions
                (data.permissions || []).forEach(perm => {
                    const checkbox = document.querySelector(`.edit-perm-check[value="${perm}"]`);
                    if (checkbox) checkbox.checked = true;
                });

                new bootstrap.Modal(document.getElementById('editRoleModal')).show();
            });
    }

    document.getElementById('editRoleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('editRoleId').value;
        const formData = new FormData(this);
        fetch(`{{ url('admin/roles') }}/${id}`, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.message || 'حدث خطأ');
        });
    });

    // View Role
    function viewRole(id) {
        fetch(`{{ url('admin/roles') }}/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('viewRoleName').textContent = translate_role_name(data.name);
                document.getElementById('viewRolePermCount').textContent = (data.permissions?.length || 0) + ' صلاحية';

                const permsHtml = (data.permissions || []).map(perm =>
                    `<div class="col-md-4"><span class="badge bg-light text-dark border w-100 py-2">${translate_permission(perm)}</span></div>`
                ).join('');
                document.getElementById('viewRolePermissions').innerHTML = permsHtml || '<div class="col-12 text-muted text-center">لا توجد صلاحيات</div>';

                new bootstrap.Modal(document.getElementById('viewRoleModal')).show();
            });
    }

    // Helper functions for translation
    function translate_role_name(roleName) {
        const translations = {
            'Super Admin': 'المدير العام',
            'super-admin': 'المدير العام',
            'Admin': 'مدير',
            'admin': 'مدير',
            'Manager': 'مدير',
            'manager': 'مدير',
            'Employee': 'موظف',
            'employee': 'موظف',
            'Viewer': 'مشاهد',
            'viewer': 'مشاهد',
        };
        return translations[roleName] || roleName.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    function translate_permission(permission) {
        const translations = {
            // Client Management
            'clients.view': 'عرض العملاء',
            'clients.create': 'إضافة عميل',
            'clients.edit': 'تعديل عميل',
            'clients.delete': 'حذف عميل',
            'clients.restore': 'استعادة عميل',
            'clients.force-delete': 'حذف نهائي للعميل',
            'clients.export': 'تصدير العملاء',
            'clients.bulk-upload': 'استيراد عملاء',
            'clients.bulk-download': 'تصدير عملاء',
            'clients.bulk-delete': 'حذف مجموعة عملاء',
            'clients.bulk-restore': 'استعادة مجموعة عملاء',
            'clients.bulk-force-delete': 'حذف نهائي لمجموعة عملاء',

            // Land Management
            'lands.view': 'عرض القطع',
            'lands.create': 'إضافة قطعة',
            'lands.edit': 'تعديل قطعة',
            'lands.delete': 'حذف قطعة',
            'lands.restore': 'استعادة قطعة',
            'lands.force-delete': 'حذف نهائي للقطعة',

            // File Management
            'files.view': 'عرض الملفات',
            'files.create': 'إضافة ملف',
            'files.upload': 'رفع ملفات',
            'files.edit': 'تعديل ملف',
            'files.delete': 'حذف ملفات',
            'files.download': 'تحميل ملفات',

            // Physical Locations
            'physical_locations.view': 'عرض مواقع التخزين',
            'physical_locations.create': 'إضافة موقع تخزين',
            'physical_locations.edit': 'تعديل موقع تخزين',
            'physical_locations.delete': 'حذف موقع تخزين',
            'physical_locations.manage': 'إدارة مواقع التخزين',

            // Geographic Areas
            'geographic_areas.view': 'عرض المناطق الجغرافية',
            'geographic_areas.create': 'إضافة منطقة جغرافية',
            'geographic_areas.edit': 'تعديل منطقة جغرافية',
            'geographic_areas.delete': 'حذف منطقة جغرافية',
            'geographic_areas.manage': 'إدارة المناطق الجغرافية',
            'geographic-areas.view': 'عرض المناطق الجغرافية',
            'geographic-areas.create': 'إضافة منطقة جغرافية',
            'geographic-areas.edit': 'تعديل منطقة جغرافية',
            'geographic-areas.delete': 'حذف منطقة جغرافية',
            'geographic-areas.manage': 'إدارة المناطق الجغرافية',

            // Items
            'items.view': 'عرض أنواع المحتوى',
            'items.create': 'إضافة نوع محتوى',
            'items.edit': 'تعديل نوع محتوى',
            'items.delete': 'حذف نوع محتوى',
            'items.manage': 'إدارة أنواع المحتوى',

            // Import
            'import.access': 'الوصول للاستيراد',
            'import.view': 'عرض عمليات الاستيراد',
            'import.execute': 'تنفيذ الاستيراد',
            'import.delete': 'حذف عملية استيراد',

            // User Management
            'users.view': 'عرض المستخدمين',
            'users.create': 'إضافة مستخدم',
            'users.edit': 'تعديل مستخدم',
            'users.delete': 'حذف مستخدم',
            'users.restore': 'استعادة مستخدم',
            'users.force-delete': 'حذف نهائي للمستخدم',
            'users.bulk-upload': 'استيراد مستخدمين',
            'users.bulk-download': 'تصدير مستخدمين',
            'users.bulk-delete': 'حذف مجموعة مستخدمين',
            'users.bulk-restore': 'استعادة مجموعة مستخدمين',
            'users.bulk-force-delete': 'حذف نهائي لمجموعة مستخدمين',

            // Roles & Permissions
            'roles.view': 'عرض الأدوار',
            'roles.create': 'إضافة دور',
            'roles.edit': 'تعديل دور',
            'roles.delete': 'حذف دور',
            'roles.restore': 'استعادة دور',
            'roles.force-delete': 'حذف نهائي للدور',
            'roles.manage': 'إدارة الأدوار',
            'roles.bulk-delete': 'حذف مجموعة أدوار',
            'roles.bulk-download': 'تصدير الأدوار',

            // Reports
            'reports.view': 'عرض التقارير',
            'reports.export': 'تصدير التقارير',
            'reports.create': 'إنشاء تقرير',
        };

        return translations[permission] || permission;
    }

    // Delete Role
    function deleteRole(id, name) {
        document.getElementById('deleteRoleId').value = id;
        document.getElementById('deleteRoleName').textContent = translate_role_name(name);
        new bootstrap.Modal(document.getElementById('deleteRoleModal')).show();
    }

    function confirmDeleteRole() {
        const id = document.getElementById('deleteRoleId').value;
        fetch(`{{ url('admin/roles') }}/${id}/destroy`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'حدث خطأ');
            }
        });
    }
    </script>
</body>
</html>
