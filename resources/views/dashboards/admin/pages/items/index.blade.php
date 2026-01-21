<!DOCTYPE html>
@include('dashboards.shared.html')

<head>
    @include('dashboards.shared.meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>أنواع المحتوى - نظام أرشيف القاهرة الجديدة</title>
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
                                        <i class="ti ti-tags me-1"></i> أنواع المحتوى
                                    </span>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0 mt-1">
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                                            <li class="breadcrumb-item active">أنواع المحتوى</li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="d-flex gap-2 mt-2 mt-lg-0">
                                    @can('items.manage')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createItemModal">
                                        <i class="ti ti-plus me-1"></i> إضافة نوع جديد
                                    </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary-subtle text-primary rounded me-3">
                                        <i class="ti ti-tags fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Item::count() }}</h4>
                                        <small class="text-muted">إجمالي الأنواع</small>
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
                                        <i class="ti ti-file-text fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\File::whereHas('items')->count() }}</h4>
                                        <small class="text-muted">ملفات مرتبطة</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-info-subtle text-info rounded me-3">
                                        <i class="ti ti-clock fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Item::whereDate('created_at', today())->count() }}</h4>
                                        <small class="text-muted">أنواع اليوم</small>
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
                                        <i class="ti ti-trash fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Item::onlyTrashed()->count() }}</h4>
                                        <small class="text-muted">المحذوفات</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search & Filters Card -->
                @php
                    $hasFilters = request()->filled('search') || request('trashed') === 'only';
                @endphp
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="border-radius: var(--ins-border-radius);">
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.items.index') }}">
                                    <div class="row d-flex align-items-end justify-content-start">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label fw-semibold">بحث</label>
                                            <div class="input-group shadow-sm border border-secondary border-opacity-10 overflow-hidden bg-body"
                                                style="border-radius: var(--ins-border-radius);">
                                                <input type="text" name="search" class="form-control border-0 bg-transparent"
                                                    placeholder="اسم نوع المحتوى..." value="{{ request('search') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2 d-flex align-items-center gap-2">
                                            <div class="d-flex flex-wrap gap-1">
                                                <button type="submit" class="btn btn-primary shadow-sm px-3" style="border-radius: var(--ins-border-radius);">
                                                    <i class="ti ti-filter me-1"></i> فلترة
                                                </button>
                                                <a href="{{ route('admin.items.index') }}" style="border-radius: var(--ins-border-radius);"
                                                    class="btn btn-secondary shadow-sm px-3">
                                                    <i class="ti ti-refresh me-1"></i> إعادة تعيين
                                                </a>
                                            </div>

                                            <button class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm"
                                                style="border-radius: var(--ins-border-radius);" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#advancedFilters" aria-expanded="{{ $hasFilters ? 'true' : 'false' }}">
                                                <i class="ti {{ $hasFilters ? 'ti-eye-off' : 'ti-filter' }}"></i>
                                                <span>{{ $hasFilters ? 'إخفاء الفلاتر' : 'فلاتر متقدمة' }}</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="collapse {{ $hasFilters ? 'show' : '' }} row g-3 align-items-end mt-2" id="advancedFilters">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">عرض</label>
                                            <select name="trashed" class="form-select">
                                                <option value="" {{ request('trashed') !== 'only' ? 'selected' : '' }}>السجلات النشطة</option>
                                                <option value="only" {{ request('trashed') === 'only' ? 'selected' : '' }}>المحذوفات فقط</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">عدد النتائج</label>
                                            <select name="per_page" class="form-select">
                                                <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                            </select>
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
                                    <h5 class="card-title mb-0">أنواع المحتوى</h5>
                                    <span class="badge bg-primary-subtle text-primary">{{ $items->total() ?? 0 }} نوع</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Bulk Actions -->
                                    <div class="bulk-actions d-none me-2" id="bulkActions">
                                        @if(request('trashed') != 'only')
                                            @can('items.manage')
                                                <button type="button" class="btn btn-soft-danger btn-sm" onclick="bulkDelete()">
                                                    <i class="ti ti-trash me-1"></i> حذف المحدد
                                                </button>
                                            @endcan
                                        @else
                                            @can('items.manage')
                                                <button type="button" class="btn btn-soft-success btn-sm" onclick="bulkRestore()">
                                                    <i class="ti ti-refresh me-1"></i> استرجاع المحدد
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="bulkForceDelete()">
                                                    <i class="ti ti-trash-x me-1"></i> حذف نهائي
                                                </button>
                                            @endcan
                                        @endif
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
                                                <th>الاسم</th>
                                                <th>الترتيب</th>
                                                <th>الملفات المرتبطة</th>
                                                <th>تاريخ الإنشاء</th>
                                                <th width="150" class="text-center">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($items ?? [] as $item)
                                                <tr id="item-row-{{ $item->id }}">
                                                    <td><input type="checkbox" class="form-check-input row-checkbox" value="{{ $item->id }}"></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                                <i class="ti ti-tag"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $item->name }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-info-subtle text-info">{{ $item->order }}</span></td>
                                                    <td><span class="badge bg-success-subtle text-success">{{ $item->files_count ?? 0 }}</span></td>
                                                    <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-1">
                                                            @if(request('trashed') != 'only')
                                                                @can('items.manage')
                                                                    <button class="btn btn-soft-warning btn-sm" onclick="editItem({{ $item->id }}, '{{ $item->name }}', {{ $item->order }})" title="تعديل">
                                                                        <i class="ti ti-edit"></i>
                                                                    </button>
                                                                    <button class="btn btn-soft-danger btn-sm" onclick="deleteItem({{ $item->id }}, '{{ $item->name }}')" title="حذف">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                @endcan
                                                            @else
                                                                <button class="btn btn-soft-success btn-sm" onclick="restoreItem({{ $item->id }})" title="استرجاع">
                                                                    <i class="ti ti-refresh"></i>
                                                                </button>
                                                                <button class="btn btn-danger btn-sm" onclick="forceDeleteItem({{ $item->id }}, '{{ $item->name }}')" title="حذف نهائي">
                                                                    <i class="ti ti-trash-x"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ti ti-tags-off fs-1 d-block mb-2"></i>
                                                            لا توجد أنواع محتوى
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
                                        @forelse($items ?? [] as $item)
                                            <div class="col-md-4 col-lg-3" id="item-card-{{ $item->id }}">
                                                <div class="card border shadow-sm h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="avatar avatar-md bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                                <i class="ti ti-tag"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0">{{ $item->name }}</h6>
                                                                <small class="text-muted">ترتيب: {{ $item->order }}</small>
                                                            </div>
                                                            <input type="checkbox" class="form-check-input row-checkbox" value="{{ $item->id }}">
                                                        </div>
                                                        <div class="d-flex gap-2 mb-3">
                                                            <span class="badge bg-success-subtle text-success"><i class="ti ti-files me-1"></i>{{ $item->files_count ?? 0 }} ملف</span>
                                                            <span class="badge bg-info-subtle text-info"><i class="ti ti-sort-ascending me-1"></i>{{ $item->order }}</span>
                                                        </div>
                                                        <small class="text-muted d-block"><i class="ti ti-calendar me-1"></i>{{ $item->created_at->format('Y-m-d') }}</small>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top-0 pt-0">
                                                        <div class="d-flex justify-content-between">
                                                            @if(request('trashed') != 'only')
                                                                <button class="btn btn-soft-warning btn-sm" onclick="editItem({{ $item->id }}, '{{ $item->name }}', {{ $item->order }})"><i class="ti ti-edit"></i></button>
                                                                <button class="btn btn-soft-danger btn-sm" onclick="deleteItem({{ $item->id }}, '{{ $item->name }}')"><i class="ti ti-trash"></i></button>
                                                            @else
                                                                <button class="btn btn-soft-success btn-sm" onclick="restoreItem({{ $item->id }})"><i class="ti ti-refresh"></i> استرجاع</button>
                                                                <button class="btn btn-danger btn-sm" onclick="forceDeleteItem({{ $item->id }}, '{{ $item->name }}')"><i class="ti ti-trash-x"></i> حذف نهائي</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ti ti-tags-off fs-1 d-block mb-2"></i>
                                                    لا توجد أنواع محتوى
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Pagination -->
                                @if(isset($items) && $items->hasPages())
                                    <div class="d-flex justify-content-center p-3">
                                        {{ $items->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Item Modal -->
    <div class="modal fade" id="createItemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة نوع محتوى جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createItemForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">الاسم <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الترتيب <span class="text-danger">*</span></label>
                            <input type="number" name="order" class="form-control" value="0" required min="0">
                            <small class="text-muted">يتم ترتيب العناصر حسب هذا الرقم (الأصغر أولاً)</small>
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

    <!-- Edit Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل نوع المحتوى</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editItemForm">
                    @csrf
                    <input type="hidden" name="id" id="editItemId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">الاسم <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editItemName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الترتيب <span class="text-danger">*</span></label>
                            <input type="number" name="order" id="editItemOrder" class="form-control" required min="0">
                            <small class="text-muted">يتم ترتيب العناصر حسب هذا الرقم (الأصغر أولاً)</small>
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

    @include('dashboards.admin.pages.items.partials.delete-modal')

    @include('dashboards.shared.scripts')

    <script>
    // Toggle View
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
        localStorage.setItem('itemsView', view);
    }

    // Initialize view from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('itemsView') || 'list';
        toggleView(savedView);
    });

    // Select All Checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateBulkActions();
    });

    document.querySelectorAll('.row-checkbox').forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        const bulkActions = document.getElementById('bulkActions');
        if (checked > 0) {
            bulkActions.classList.remove('d-none');
        } else {
            bulkActions.classList.add('d-none');
        }
    }

    function getSelectedIds() {
        return Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
    }

    // Bulk Delete
    function bulkDelete() {
        const ids = getSelectedIds();
        if (ids.length === 0) return;
        if (!confirm(`هل أنت متأكد من حذف ${ids.length} عنصر؟`)) return;

        fetch('{{ route("admin.items.bulk-delete") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.error || 'حدث خطأ');
        });
    }

    // Bulk Restore
    function bulkRestore() {
        const ids = getSelectedIds();
        if (ids.length === 0) return;

        fetch('{{ route("admin.items.bulk-restore") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.error || 'حدث خطأ');
        });
    }

    // Bulk Force Delete
    function bulkForceDelete() {
        const ids = getSelectedIds();
        if (ids.length === 0) return;
        if (!confirm(`هل أنت متأكد من الحذف النهائي لـ ${ids.length} عنصر؟ لا يمكن التراجع عن هذا الإجراء.`)) return;

        fetch('{{ route("admin.items.bulk-force-delete") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.error || 'حدث خطأ');
        });
    }

    // Create Item
    document.getElementById('createItemForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('{{ route("admin.items.store") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'حدث خطأ');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('حدث خطأ في الاتصال');
        });
    });

    // Edit Item
    function editItem(id, name, order) {
        document.getElementById('editItemId').value = id;
        document.getElementById('editItemName').value = name;
        document.getElementById('editItemOrder').value = order || 0;
        new bootstrap.Modal(document.getElementById('editItemModal')).show();
    }

    document.getElementById('editItemForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('editItemId').value;
        const formData = new FormData(this);
        fetch(`{{ url('admin/items') }}/${id}`, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'حدث خطأ');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('حدث خطأ في الاتصال');
        });
    });

    // Delete Item
    function deleteItem(id, name) {
        document.getElementById('deleteItemId').value = id;
        document.getElementById('deleteItemName').textContent = name;
        new bootstrap.Modal(document.getElementById('deleteItemModal')).show();
    }

    function confirmDeleteItem() {
        const id = document.getElementById('deleteItemId').value;
        const deleteBtn = event.target;
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحذف...';

        fetch(`{{ url('admin/items') }}/${id}/delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('deleteItemModal')).hide();
                location.reload();
            } else {
                alert(data.error || 'حدث خطأ');
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = '<i class="ti ti-trash me-1"></i>حذف';
            }
        })
        .catch(err => {
            alert('حدث خطأ في الاتصال');
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = '<i class="ti ti-trash me-1"></i>حذف';
        });
    }

    // Restore Item
    function restoreItem(id) {
        fetch(`{{ url('admin/items') }}/${id}/restore`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.error || 'حدث خطأ');
        });
    }

    // Force Delete Item
    function forceDeleteItem(id, name) {
        if (!confirm(`هل أنت متأكد من الحذف النهائي لـ "${name}"؟ لا يمكن التراجع عن هذا الإجراء.`)) return;

        fetch(`{{ url('admin/items') }}/${id}/force-delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.error || 'حدث خطأ');
        });
    }
    </script>
</body>
</html>
