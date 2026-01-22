<!DOCTYPE html>
@include('dashboards.shared.html')

<head>
    @include('dashboards.shared.meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة الملفات - نظام أرشيف القاهرة الجديدة</title>
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
                                    <span
                                        class="badge badge-default fw-normal shadow px-2 fst-italic fs-sm d-inline-flex align-items-center">
                                        <i class="ti ti-files me-1"></i> إدارة الملفات
                                    </span>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0 mt-1">
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة
                                                    التحكم</a></li>
                                            <li class="breadcrumb-item active">الملفات</li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="d-flex gap-2 mt-2 mt-lg-0">
                                    @can('files.upload')
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#uploadFileModal">
                                            <i class="ti ti-upload me-1"></i> رفع ملف PDF
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-3 ">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary-subtle text-primary rounded">
                                        <i class="ti ti-files fs-4"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-0">{{ \App\Models\File::mainFiles()->count() }}</h4>
                                        <small class="text-muted">إجمالي الملفات</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-warning-subtle text-warning rounded">
                                        <i class="ti ti-loader fs-4"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-0">{{ \App\Models\File::mainFiles()->processing()->count() }}
                                        </h4>
                                        <small class="text-muted">قيد المعالجة</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-success-subtle text-success rounded">
                                        <i class="ti ti-check fs-4"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-0">{{ \App\Models\File::mainFiles()->completed()->count() }}
                                        </h4>
                                        <small class="text-muted">مكتمل</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-danger-subtle text-danger rounded">
                                        <i class="ti ti-alert-triangle fs-4"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-0">{{ \App\Models\File::mainFiles()->failed()->count() }}</h4>
                                        <small class="text-muted">فشل</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card" style="border-radius: var(--ins-border-radius);">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.files.index') }}">
                            <div class="row d-flex align-items-end justify-content-start">
                                <div class="col-md-3 mb-2">
                                    <label class="form-label fw-semibold">بحث</label>
                                    <div class="input-group shadow-sm border border-secondary border-opacity-10 overflow-hidden bg-body"
                                        style="border-radius: var(--ins-border-radius);">
                                        <input type="text" name="search"
                                            class="form-control border-0 bg-transparent"
                                            placeholder="اسم الملف، الباركود..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label fw-semibold">العميل</label>
                                    <select name="client_id" class="form-select">
                                        <option value="">الكل</option>
                                        @foreach ($clients ?? [] as $client)
                                            <option value="{{ $client->id }}"
                                                {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label fw-semibold">الحالة</label>
                                    <select name="status" class="form-select">
                                        <option value="">الكل</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            معلق</option>
                                        <option value="processing"
                                            {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة
                                        </option>
                                        <option value="completed"
                                            {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>
                                            فشل</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label fw-semibold">الغرفة</label>
                                    <select name="room_id" class="form-select">
                                        <option value="">الكل</option>
                                        @foreach ($rooms ?? [] as $room)
                                            <option value="{{ $room->id }}"
                                                {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                                {{ $room->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 d-flex align-items-center gap-2">
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="submit" class="btn btn-primary shadow-sm px-3"
                                            style="border-radius: var(--ins-border-radius);">
                                            <i class="ti ti-filter me-1"></i> فلترة
                                        </button>
                                        <a href="{{ route('admin.files.index') }}"
                                            style="border-radius: var(--ins-border-radius);"
                                            class="btn btn-secondary shadow-sm px-3">
                                            <i class="ti ti-refresh me-1"></i> إعادة تعيين
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Data Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="border-radius: var(--ins-border-radius);">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <h5 class="card-title mb-0">الملفات</h5>
                                    <span class="badge bg-primary-subtle text-primary">{{ $files->total() ?? 0 }}
                                        ملف</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Bulk Actions -->
                                    <div class="bulk-actions d-none me-2" id="bulkActions">
                                        @can('files.delete')
                                            <button type="button" class="btn btn-soft-danger btn-sm"
                                                onclick="bulkDelete()">
                                                <i class="ti ti-trash me-1"></i> حذف المحدد
                                            </button>
                                        @endcan
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
                                                <th>الباركود</th>
                                                <th>الملف</th>
                                                <th>العميل</th>
                                                <th>القطعة</th>
                                                <th>الصفحات</th>
                                                <th>الحالة</th>
                                                <th width="180" class="text-center">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($files ?? [] as $file)
                                                <tr id="file-row-{{ $file->id }}">
                                                    <td><input type="checkbox" class="form-check-input row-checkbox"
                                                            value="{{ $file->id }}"></td>
                                                    <td>
                                                        <span
                                                            class="badge bg-dark font-monospace">{{ $file->barcode ?? '-' }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar avatar-sm bg-danger-subtle text-danger rounded me-2">
                                                                <i class="ti ti-file-type-pdf"></i>
                                                            </div>
                                                            <span>{{ Str::limit($file->original_name ?? $file->file_name, 25) }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $file->client->name ?? '-' }}</td>
                                                    <td>{{ $file->land->land_no ?? '-' }}</td>
                                                    <td><span
                                                            class="badge bg-secondary">{{ $file->pages_count ?? 0 }}</span>
                                                    </td>
                                                    <td>
                                                        @switch($file->status)
                                                            @case('pending')
                                                                <span class="badge bg-secondary">معلق</span>
                                                            @break

                                                            @case('processing')
                                                                <span class="badge bg-warning">قيد المعالجة</span>
                                                            @break

                                                            @case('completed')
                                                                <span class="badge bg-success">مكتمل</span>
                                                            @break

                                                            @case('failed')
                                                                <span class="badge bg-danger">فشل</span>
                                                            @break
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-1">
                                                            @php
                                                                $hasDocument =
                                                                    $file->getFirstMedia('documents') !== null;
                                                            @endphp

                                                            @if ($hasDocument)
                                                                <button class="btn btn-soft-info btn-sm"
                                                                    onclick="showFile({{ $file->id }})"
                                                                    title="عرض">
                                                                    <i class="ti ti-eye"></i>
                                                                </button>
                                                            @else
                                                                @can('files.upload')
                                                                    <button class="btn btn-soft-primary btn-sm"
                                                                        onclick="openUploadForFile({{ $file->id }}, '{{ $file->file_name }}')"
                                                                        title="رفع ملف">
                                                                        <i class="ti ti-upload"></i>
                                                                    </button>
                                                                @endcan
                                                            @endif

                                                            @if ($file->barcode)
                                                                <button class="btn btn-soft-dark btn-sm"
                                                                    onclick="printBarcode('{{ $file->barcode }}', '{{ $file->file_name }}')"
                                                                    title="طباعة الباركود">
                                                                    <i class="ti ti-barcode"></i>
                                                                </button>
                                                            @endif

                                                            @can('files.upload')
                                                                <button class="btn btn-soft-warning btn-sm"
                                                                    onclick="editFile({{ $file->id }})"
                                                                    title="تعديل">
                                                                    <i class="ti ti-edit"></i>
                                                                </button>
                                                            @endcan
                                                            @can('files.delete')
                                                                <button class="btn btn-soft-danger btn-sm"
                                                                    onclick="deleteFile({{ $file->id }}, '{{ $file->file_name }}')"
                                                                    title="حذف">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center py-4">
                                                            <div class="text-muted">
                                                                <i class="ti ti-files-off fs-1 d-block mb-2"></i>
                                                                لا توجد ملفات
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
                                            @forelse($files ?? [] as $file)
                                                <div class="col-md-4 col-lg-3" id="file-card-{{ $file->id }}">
                                                    <div class="card border shadow-sm h-100">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div
                                                                    class="avatar avatar-md bg-danger-subtle text-danger rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                                    <i class="ti ti-file-type-pdf"></i>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-0">
                                                                        {{ Str::limit($file->original_name ?? $file->file_name, 18) }}
                                                                    </h6>
                                                                    <small
                                                                        class="text-muted">{{ $file->barcode ?? '-' }}</small>
                                                                </div>
                                                                <input type="checkbox"
                                                                    class="form-check-input row-checkbox"
                                                                    value="{{ $file->id }}">
                                                            </div>
                                                            <div class="mb-2">
                                                                <small class="text-muted d-block"><i
                                                                        class="ti ti-user me-1"></i>
                                                                    {{ $file->client->name ?? '-' }}</small>
                                                                <small class="text-muted d-block"><i
                                                                        class="ti ti-map-2 me-1"></i>
                                                                    {{ $file->land->land_no ?? '-' }}</small>
                                                            </div>
                                                            <div class="d-flex gap-2 mb-3">
                                                                <span class="badge bg-secondary"><i
                                                                        class="ti ti-file me-1"></i>{{ $file->pages_count ?? 0 }}
                                                                    صفحة</span>
                                                                @switch($file->status)
                                                                    @case('pending')
                                                                        <span class="badge bg-secondary">معلق</span>
                                                                    @break

                                                                    @case('processing')
                                                                        <span class="badge bg-warning">قيد المعالجة</span>
                                                                    @break

                                                                    @case('completed')
                                                                        <span class="badge bg-success">مكتمل</span>
                                                                    @break

                                                                    @case('failed')
                                                                        <span class="badge bg-danger">فشل</span>
                                                                    @break
                                                                @endswitch
                                                            </div>
                                                        </div>
                                                        <div class="card-footer bg-transparent border-top-0 pt-0">
                                                            <div class="d-flex justify-content-between">
                                                                @php $hasDocument = $file->getFirstMedia('documents') !== null; @endphp
                                                                @if ($hasDocument)
                                                                    <button class="btn btn-soft-info btn-sm"
                                                                        onclick="showFile({{ $file->id }})"><i
                                                                            class="ti ti-eye"></i></button>
                                                                @else
                                                                    <button class="btn btn-soft-primary btn-sm"
                                                                        onclick="openUploadForFile({{ $file->id }}, '{{ $file->file_name }}')"><i
                                                                            class="ti ti-upload"></i></button>
                                                                @endif
                                                                <button class="btn btn-soft-warning btn-sm"
                                                                    onclick="editFile({{ $file->id }})"><i
                                                                        class="ti ti-edit"></i></button>
                                                                <button class="btn btn-soft-danger btn-sm"
                                                                    onclick="deleteFile({{ $file->id }}, '{{ $file->file_name }}')"><i
                                                                        class="ti ti-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @empty
                                                    <div class="col-12 text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ti ti-files-off fs-1 d-block mb-2"></i>
                                                            لا توجد ملفات
                                                        </div>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>

                                        <!-- Pagination -->
                                        @if (isset($files) && $files->hasPages())
                                            <div class="d-flex justify-content-center p-3">
                                                {{ $files->links() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('dashboards.admin.pages.files.partials.show')
            <!-- Delete File Modal -->
            <div class="modal fade" id="deleteFileModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header modal-header-danger">
                            <h5 class="modal-title">
                                <i class="ti ti-alert-triangle"></i>
                                تأكيد الحذف
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <div class="avatar avatar-xl bg-danger-subtle text-danger rounded-circle mx-auto mb-3">
                                <i class="ti ti-trash fs-1"></i>
                            </div>
                            <h5 class="mb-2">هل أنت متأكد من حذف هذا الملف؟</h5>
                            <p class="text-muted mb-0" id="deleteFileName"></p>
                            <input type="hidden" id="deleteFileId">
                            <div class="alert alert-warning mt-3 text-start">
                                <i class="ti ti-info-circle me-1"></i>
                                <small>سيتم حذف الملف وجميع الملفات الفرعية المرتبطة به بشكل نهائي</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="button" class="btn btn-danger" onclick="confirmFileDelete()">
                                <i class="ti ti-trash me-1"></i>حذف
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit File Modal -->
            <div class="modal fade" id="editFileModal" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content" id="editFileModalContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">جاري التحميل...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('dashboards.admin.pages.files.partials.upload')





            @include('dashboards.shared.modal-styles')
            @include('dashboards.shared.scripts')

            <script>
                // Toggle page range fields when checkbox is checked
                function togglePageRange(itemId) {
                    const checkbox = document.getElementById('item' + itemId);
                    const fromPageInput = document.getElementById('fromPage' + itemId);
                    const toPageInput = document.getElementById('toPage' + itemId);

                    if (checkbox.checked) {
                        fromPageInput.classList.remove('d-none');
                        toPageInput.classList.remove('d-none');
                        // Set focus on from_page input
                        fromPageInput.focus();
                    } else {
                        fromPageInput.classList.add('d-none');
                        toPageInput.classList.add('d-none');
                        // Clear the inputs when unchecked
                        fromPageInput.value = '';
                        toPageInput.value = '';
                    }

                    // Update selected count
                    updateSelectedItemsCount();
                }

                // Filter items based on search input
                function filterItems() {
                    const searchInput = document.getElementById('itemSearchInput').value.toLowerCase();
                    const itemRows = document.querySelectorAll('.item-row');
                    let visibleCount = 0;

                    itemRows.forEach(row => {
                        const itemName = row.getAttribute('data-item-name');
                        if (itemName.includes(searchInput)) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Show no results message
                    const container = document.getElementById('itemsContainer');
                    let noResultsMsg = document.getElementById('noResultsMessage');

                    if (visibleCount === 0) {
                        if (!noResultsMsg) {
                            noResultsMsg = document.createElement('tr');
                            noResultsMsg.id = 'noResultsMessage';
                            noResultsMsg.innerHTML = `
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="ti ti-search-off fs-1 d-block mb-2"></i>
                        <p>لا توجد نتائج للبحث "${searchInput}"</p>
                    </td>
                `;
                            container.appendChild(noResultsMsg);
                        }
                    } else {
                        if (noResultsMsg) {
                            noResultsMsg.remove();
                        }
                    }
                }

                // Clear search input
                function clearItemSearch() {
                    document.getElementById('itemSearchInput').value = '';
                    filterItems();
                }

                // Select all visible items
                function selectAllItems() {
                    const itemRows = document.querySelectorAll('.item-row');
                    itemRows.forEach(row => {
                        if (row.style.display !== 'none') {
                            const checkboxes = row.querySelectorAll('.item-checkbox');
                            checkboxes.forEach(checkbox => {
                                if (!checkbox.checked) {
                                    checkbox.checked = true;
                                    togglePageRange(checkbox.getAttribute('data-item-id'));
                                }
                            });
                        }
                    });
                }

                // Deselect all items
                function deselectAllItems() {
                    const checkboxes = document.querySelectorAll('.item-checkbox');
                    checkboxes.forEach(checkbox => {
                        if (checkbox.checked) {
                            checkbox.checked = false;
                            togglePageRange(checkbox.getAttribute('data-item-id'));
                        }
                    });
                }

                // Update selected items count badge
                function updateSelectedItemsCount() {
                    const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
                    const badge = document.getElementById('selectedItemsCount');
                    badge.textContent = checkedCount + ' محدد';

                    if (checkedCount > 0) {
                        badge.classList.remove('bg-primary-subtle', 'text-primary');
                        badge.classList.add('bg-success', 'text-white');
                    } else {
                        badge.classList.remove('bg-success', 'text-white');
                        badge.classList.add('bg-primary-subtle', 'text-primary');
                    }
                }

                function toggleNewLandForm() {
                    const newLandForm = document.getElementById('newLandForm');
                    const landSelect = document.getElementById('landSelect');

                    if (newLandForm.classList.contains('d-none')) {
                        newLandForm.classList.remove('d-none');
                        landSelect.removeAttribute('required');
                        landSelect.disabled = true;
                        document.getElementById('newGovernorateId').setAttribute('required', 'required');
                    } else {
                        newLandForm.classList.add('d-none');
                        landSelect.setAttribute('required', 'required');
                        landSelect.disabled = false;
                        document.getElementById('newGovernorateId').removeAttribute('required');
                        // Clear new land form
                        document.getElementById('newLandNo').value = '';
                        document.getElementById('newUnitNo').value = '';
                        document.getElementById('newGovernorateId').value = '';
                        document.getElementById('newCityId').innerHTML = '<option value="">اختر المدينة</option>';
                        document.getElementById('newDistrictId').innerHTML = '<option value="">اختر الحي</option>';
                        document.getElementById('newZoneId').innerHTML = '<option value="">اختر المنطقة</option>';
                        document.getElementById('newAreaId').innerHTML = '<option value="">اختر القطاع</option>';
                        document.getElementById('newAddress').value = '';
                        document.getElementById('newNotes').value = '';
                    }
                }

                function loadClientLands(clientId) {
                    const select = document.getElementById('landSelect');
                    select.innerHTML = '<option value="">جاري التحميل...</option>';
                    if (!clientId) {
                        select.innerHTML = '<option value="">اختر القطعة</option>';
                        return;
                    }
                    fetch(`{{ url('admin/clients') }}/${clientId}/lands`)
                        .then(res => res.json())
                        .then(data => {
                            select.innerHTML = '<option value="">اختر القطعة</option>';
                            (data.lands || data).forEach(land => {
                                select.innerHTML +=
                                    `<option value="${land.id}">${land.land_no || ''} - ${land.address || ''}</option>`;
                            });
                        });
                }

                function loadNewLandCities(governorateId) {
                    const citySelect = document.getElementById('newCityId');
                    const districtSelect = document.getElementById('newDistrictId');
                    const zoneSelect = document.getElementById('newZoneId');
                    const areaSelect = document.getElementById('newAreaId');

                    citySelect.innerHTML = '<option value="">جاري التحميل...</option>';
                    districtSelect.innerHTML = '<option value="">اختر الحي</option>';
                    zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
                    areaSelect.innerHTML = '<option value="">اختر القطاع</option>';

                    if (!governorateId) {
                        citySelect.innerHTML = '<option value="">اختر المدينة</option>';
                        return;
                    }

                    fetch(`{{ url('admin/geographic-areas/cities/by-governorate') }}/${governorateId}`)
                        .then(res => res.json())
                        .then(data => {
                            citySelect.innerHTML = '<option value="">اختر المدينة</option>';
                            data.forEach(city => {
                                citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                            });
                        });
                }

                function loadNewLandDistricts(cityId) {
                    const districtSelect = document.getElementById('newDistrictId');
                    const zoneSelect = document.getElementById('newZoneId');
                    const areaSelect = document.getElementById('newAreaId');

                    districtSelect.innerHTML = '<option value="">جاري التحميل...</option>';
                    zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
                    areaSelect.innerHTML = '<option value="">اختر القطاع</option>';

                    if (!cityId) {
                        districtSelect.innerHTML = '<option value="">اختر الحي</option>';
                        return;
                    }

                    fetch(`{{ url('admin/geographic-areas/districts/by-city') }}/${cityId}`)
                        .then(res => res.json())
                        .then(data => {
                            districtSelect.innerHTML = '<option value="">اختر الحي</option>';
                            data.forEach(district => {
                                districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                            });
                        });
                }

                function loadNewLandZones(districtId) {
                    const zoneSelect = document.getElementById('newZoneId');
                    const areaSelect = document.getElementById('newAreaId');

                    zoneSelect.innerHTML = '<option value="">جاري التحميل...</option>';
                    areaSelect.innerHTML = '<option value="">اختر القطاع</option>';

                    if (!districtId) {
                        zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
                        return;
                    }

                    fetch(`{{ url('admin/geographic-areas/zones/by-district') }}/${districtId}`)
                        .then(res => res.json())
                        .then(data => {
                            zoneSelect.innerHTML = '<option value="">اختر المنطقة</option>';
                            data.forEach(zone => {
                                zoneSelect.innerHTML += `<option value="${zone.id}">${zone.name}</option>`;
                            });
                        });
                }

                function loadNewLandAreas(zoneId) {
                    const areaSelect = document.getElementById('newAreaId');

                    areaSelect.innerHTML = '<option value="">جاري التحميل...</option>';

                    if (!zoneId) {
                        areaSelect.innerHTML = '<option value="">اختر القطاع</option>';
                        return;
                    }

                    fetch(`{{ url('admin/geographic-areas/areas/by-zone') }}/${zoneId}`)
                        .then(res => res.json())
                        .then(data => {
                            areaSelect.innerHTML = '<option value="">اختر القطاع</option>';
                            data.forEach(area => {
                                areaSelect.innerHTML += `<option value="${area.id}">${area.name}</option>`;
                            });
                        });
                }

                function loadLanes(roomId) {
                    const laneSelect = document.getElementById('laneSelect');
                    const standSelect = document.getElementById('standSelect');
                    const rackSelect = document.getElementById('rackSelect');

                    laneSelect.innerHTML = '<option value="">جاري التحميل...</option>';
                    standSelect.innerHTML = '<option value="">اختر الستاند</option>';
                    rackSelect.innerHTML = '<option value="">اختر الرف</option>';

                    if (!roomId) {
                        laneSelect.innerHTML = '<option value="">اختر الممر</option>';
                        return;
                    }

                    fetch(`{{ url('admin/physical-locations/rooms') }}/${roomId}/lanes`)
                        .then(res => res.json())
                        .then(data => {
                            laneSelect.innerHTML = '<option value="">اختر الممر</option>';
                            (data.lanes || data).forEach(lane => {
                                laneSelect.innerHTML += `<option value="${lane.id}">${lane.name}</option>`;
                            });
                        })
                        .catch(() => {
                            laneSelect.innerHTML = '<option value="">اختر الممر</option>';
                        });
                }

                function loadStands(laneId) {
                    const standSelect = document.getElementById('standSelect');
                    const rackSelect = document.getElementById('rackSelect');

                    standSelect.innerHTML = '<option value="">جاري التحميل...</option>';
                    rackSelect.innerHTML = '<option value="">اختر الرف</option>';

                    if (!laneId) {
                        standSelect.innerHTML = '<option value="">اختر الستاند</option>';
                        return;
                    }

                    fetch(`{{ url('admin/physical-locations/lanes') }}/${laneId}/stands`)
                        .then(res => res.json())
                        .then(data => {
                            standSelect.innerHTML = '<option value="">اختر الستاند</option>';
                            (data.stands || data).forEach(stand => {
                                standSelect.innerHTML += `<option value="${stand.id}">${stand.name}</option>`;
                            });
                        })
                        .catch(() => {
                            standSelect.innerHTML = '<option value="">اختر الستاند</option>';
                        });
                }

                function loadRacks(standId) {
                    const rackSelect = document.getElementById('rackSelect');

                    rackSelect.innerHTML = '<option value="">جاري التحميل...</option>';

                    if (!standId) {
                        rackSelect.innerHTML = '<option value="">اختر الرف</option>';
                        return;
                    }

                    fetch(`{{ url('admin/physical-locations/stands') }}/${standId}/racks`)
                        .then(res => res.json())
                        .then(data => {
                            rackSelect.innerHTML = '<option value="">اختر الرف</option>';
                            (data.racks || data).forEach(rack => {
                                rackSelect.innerHTML += `<option value="${rack.id}">${rack.name}</option>`;
                            });
                        })
                        .catch(() => {
                            rackSelect.innerHTML = '<option value="">اختر الرف</option>';
                        });
                }

                document.getElementById('uploadFileForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    // Collect selected items with page ranges
                    const selectedItems = [];
                    document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
                        const itemId = checkbox.dataset.itemId;
                        const fromPageInput = document.getElementById('fromPage' + itemId);
                        const toPageInput = document.getElementById('toPage' + itemId);
                        const fromPage = fromPageInput ? fromPageInput.value : '';
                        const toPage = toPageInput ? toPageInput.value : '';

                        selectedItems.push({
                            item_id: itemId,
                            from_page: fromPage || null,
                            to_page: toPage || null
                        });
                    });

                    // Add items as JSON
                    formData.delete('items'); // Remove old format
                    formData.append('items_json', JSON.stringify(selectedItems));

                    const submitBtn = this.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الرفع...';

                    fetch('{{ route('admin.files.store') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.error || 'حدث خطأ');
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = 'رفع ومعالجة';
                            }
                        })
                        .catch(err => {
                            alert('حدث خطأ في الاتصال');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = 'رفع ومعالجة';
                        });
                });

                // Copy barcode to clipboard
                function copyFileBarcode(barcode) {
                    navigator.clipboard.writeText(barcode).then(() => {
                        iziToast.success({
                            title: 'نجح',
                            message: 'تم نسخ الباركود',
                            position: 'topRight',
                            rtl: true
                        });
                    }).catch(() => {
                        iziToast.error({
                            title: 'خطأ',
                            message: 'فشل نسخ الباركود',
                            position: 'topRight',
                            rtl: true
                        });
                    });
                }

                function showFile(id) {
                    const modal = new bootstrap.Modal(document.getElementById('showFileModal'));
                    modal.show();

                    fetch(`{{ url('admin/files') }}/${id}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                renderFileDetails(data.file, data.pdf_url);
                            } else {
                                document.getElementById('fileModalBody').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="ti ti-alert-circle me-2"></i>
                            ${data.error || 'حدث خطأ أثناء تحميل البيانات'}
                        </div>
                    `;
                            }
                        })
                        .catch(err => {
                            document.getElementById('fileModalBody').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i>
                        حدث خطأ في الاتصال
                    </div>
                `;
                        });
                }

                function renderFileDetails(file, pdfUrl) {
                    document.getElementById('fileModalTitle').textContent = file.file_name;

                    const modalBody = document.getElementById('fileModalBody');

                    let html = `
            <!-- PDF Viewer Section (Hidden by default) -->
            <div id="pdfViewerSection" class="d-none mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="ti ti-file-type-pdf text-danger me-2"></i>
                            <span id="currentPdfTitle">معاينة الملف</span>
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="closePdfViewer()">
                            <i class="ti ti-x me-1"></i>إغلاق المعاينة
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <iframe id="pdfIframe" style="width: 100%; height: 600px; border: none;" src=""></iframe>
                    </div>
                </div>
            </div>

            <!-- File Info Section -->
            <div id="fileInfoSection">
            <div class="row g-4 mb-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="ti ti-info-circle me-2"></i>معلومات الملف</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12 mb-2">
                                    <div class="d-flex align-items-center justify-content-between p-3 bg-dark text-white rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-white text-dark rounded me-3">
                                                <i class="ti ti-barcode"></i>
                                            </div>
                                            <div>
                                                <small class="text-white-50 d-block">الباركود</small>
                                                <strong class="font-monospace fs-5">${file.barcode || 'غير متوفر'}</strong>
                                            </div>
                                        </div>
                                        ${file.barcode ? '<button class="btn btn-sm btn-outline-light text-white" onclick="copyFileBarcode(\'' + file.barcode + '\')"><i class="ti ti-copy"></i></button>' : ''}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-primary-subtle text-primary rounded me-3">
                                            <i class="ti ti-user"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">العميل</small>
                                            <strong>${file.client?.name || 'غير محدد'}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-success-subtle text-success rounded me-3">
                                            <i class="ti ti-map-pin"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">القطعة</small>
                                            <strong>${file.land?.land_no || 'غير محدد'}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-info-subtle text-info rounded me-3">
                                            <i class="ti ti-building"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">موقع التخزين</small>
                                            <strong>${file.room?.name || 'غير محدد'} - ${file.rack?.name || ''}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-warning-subtle text-warning rounded me-3">
                                            <i class="ti ti-file"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">عدد الصفحات</small>
                                            <strong>${file.pages_count} صفحة</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="ti ti-download me-2"></i>تحميل الملف</h6>
                        </div>
                        <div class="card-body text-center">
                            ${pdfUrl ? `
                                            <div class="mb-3">
                                                <i class="ti ti-file-type-pdf text-danger" style="font-size: 3rem;"></i>
                                            </div>
                                            <button type="button" class="btn btn-primary w-100 mb-2" onclick="viewPdfInModal('${pdfUrl}', '${file.file_name}')">
                                                <i class="ti ti-eye me-2"></i>عرض في المعاينة
                                            </button>
                                            <a href="${pdfUrl}" target="_blank" class="btn btn-outline-secondary w-100 mb-2">
                                                <i class="ti ti-external-link me-2"></i>فتح في تبويب جديد
                                            </a>
                                            <a href="${pdfUrl}" download class="btn btn-outline-primary w-100">
                                                <i class="ti ti-download me-2"></i>تحميل
                                            </a>
                                        ` : '<p class="text-muted">الملف غير متوفر</p>'}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sub-Files Section -->
            ${file.subFiles && file.subFiles.length > 0 ? `
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <h5 class="mb-0 text-white">
                                        <i class="ti ti-folders me-2"></i>
                                        الملفات الفرعية المستخرجة (${file.subFiles.length})
                                    </h5>
                                    <small class="text-white-50">الملفات التي تم إنشاؤها بناءً على أنواع المحتوى المحددة</small>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50">#</th>
                                                    <th>نوع المحتوى</th>
                                                    <th width="150">نطاق الصفحات</th>
                                                    <th width="100">عدد الصفحات</th>
                                                    <th width="150" class="text-center">الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${file.subFiles.map((subFile, index) => {
                                                    const item = subFile.items && subFile.items[0];
                                                    const fileItem = subFile.file_items && subFile.file_items[0];
                                                    const fromPage = fileItem?.from_page || '-';
                                                    const toPage = fileItem?.to_page || '-';
                                                    const pdfUrl = subFile.media && subFile.media[0] ? subFile.media[0].original_url : null;

                                                    return `
                                            <tr>
                                                <td>
                                                    <div class="avatar avatar-sm bg-primary-subtle text-primary rounded">
                                                        <i class="ti ti-file-text"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong class="d-block">${item?.name || 'غير محدد'}</strong>
                                                        <small class="text-muted">${item?.description || ''}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info-subtle text-info">
                                                        ${fromPage} - ${toPage}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success">
                                                        ${subFile.pages_count} صفحة
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    ${pdfUrl ? `
                                                                    <button type="button" class="btn btn-sm btn-info" title="عرض في المعاينة" onclick="viewPdfInModal('${pdfUrl}', '${item?.name || 'ملف فرعي'}')">
                                                                        <i class="ti ti-eye"></i>
                                                                    </button>
                                                                    <a href="${pdfUrl}" target="_blank" class="btn btn-sm btn-secondary" title="فتح في تبويب جديد">
                                                                        <i class="ti ti-external-link"></i>
                                                                    </a>
                                                                    <a href="${pdfUrl}" download class="btn btn-sm btn-primary" title="تحميل">
                                                                        <i class="ti ti-download"></i>
                                                                    </a>
                                                                ` : '<span class="text-muted">غير متوفر</span>'}
                                                </td>
                                            </tr>
                                        `;
                                                }).join('')}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        ` : ''}

            <!-- Pages by Items Section -->
            ${file.items && file.items.length > 0 && file.pages && file.pages.length > 0 ? `
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header" style="background: black;">
                                    <h5 class="mb-0 text-white">
                                        <i class="ti ti-files me-2"></i>
                                        الصفحات المستخرجة حسب نوع المحتوى
                                    </h5>
                                    <small class="text-white-50">جميع الصفحات الفردية مرتبة حسب أنواع المحتوى المحددة</small>
                                </div>
                                <div class="card-body">
                                    ${file.items.map(item => {
                                        const fromPage = item.pivot?.from_page || 0;
                                        const toPage = item.pivot?.to_page || 0;

                                        // Get pages in this range
                                        const itemPages = file.pages.filter(page =>
                                            page.page_number >= Math.min(fromPage, toPage) &&
                                            page.page_number <= Math.max(fromPage, toPage)
                                        );

                                        if (itemPages.length === 0) return '';

                                        return `
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                                        <div class="avatar avatar-md bg-primary text-white rounded me-3">
                                            <i class="ti ti-file-text"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">${item.name}</h6>
                                            <small class="text-muted">${item.description || ''}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-info-subtle text-info">
                                                صفحات ${fromPage} - ${toPage}
                                            </span>
                                            <div class="text-muted small mt-1">${itemPages.length} صفحة</div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        ${itemPages.map(page => {
                                            const pageMedia = page.media && page.media[0];
                                            const thumbnailUrl = pageMedia ? pageMedia.original_url : null;
                                            const pagePdfUrl = pageMedia ? pageMedia.original_url : null;

                                            return `
                                                            <div class="col-md-2 col-sm-3 col-4">
                                                                <div class="card border shadow-sm h-100">
                                                                    <div class="card-body p-2 text-center">
                                                                        ${thumbnailUrl ? `
                                                                <div class="position-relative mb-2" style="cursor: pointer; height: 150px; overflow: hidden; border-radius: 8px;" onclick="viewPdfInModal('${pagePdfUrl}', 'صفحة ${page.page_number}')">
                                                                    <iframe src="${thumbnailUrl}" style="width: 100%; height: 200px; border: none; pointer-events: none; transform: scale(0.75); transform-origin: top left;"></iframe>
                                                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0); transition: all 0.3s;" onmouseover="this.style.background='rgba(0,0,0,0.5)'" onmouseout="this.style.background='rgba(0,0,0,0)'">
                                                                        <i class="ti ti-eye text-white" style="font-size: 2rem; opacity: 0; transition: all 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'"></i>
                                                                    </div>
                                                                </div>
                                                            ` : `
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center mb-2" style="height: 150px;">
                                                                    <i class="ti ti-file-text text-muted" style="font-size: 3rem;"></i>
                                                                </div>
                                                            `}
                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                            <small class="text-muted fw-bold">صفحة ${page.page_number}</small>
                                                                            ${pagePdfUrl ? `
                                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewPdfInModal('${pagePdfUrl}', 'صفحة ${page.page_number}')" title="عرض">
                                                                        <i class="ti ti-eye"></i>
                                                                    </button>
                                                                ` : ''}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `;
                                        }).join('')}
                                    </div>
                                </div>
                            `;
                                    }).join('')}
                                </div>
                            </div>
                        ` : ''}

            <!-- All Items Summary Section -->
            ${file.items && file.items.length > 0 ? `
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="ti ti-list me-2"></i>ملخص أنواع المحتوى (${file.items.length})</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        ${file.items.map(item => `
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-2 border rounded">
                                        <i class="ti ti-file-text text-primary me-2"></i>
                                        <div class="flex-grow-1">
                                            <strong class="d-block">${item.name}</strong>
                                            ${item.pivot ? `
                                                            <small class="text-muted">
                                                                صفحات ${item.pivot.from_page} - ${item.pivot.to_page}
                                                            </small>
                                                        ` : ''}
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                                    </div>
                                </div>
                            </div>
                        ` : ''}
            </div>
        `;

                    modalBody.innerHTML = html;
                }

                function viewPdfInModal(pdfUrl, title) {
                    // Hide file info section
                    document.getElementById('fileInfoSection').classList.add('d-none');

                    // Show PDF viewer section
                    const pdfViewerSection = document.getElementById('pdfViewerSection');
                    pdfViewerSection.classList.remove('d-none');

                    // Update title and load PDF
                    document.getElementById('currentPdfTitle').textContent = title;
                    document.getElementById('pdfIframe').src = pdfUrl;

                    // Scroll to top of modal
                    document.getElementById('fileModalBody').scrollTop = 0;
                }

                function closePdfViewer() {
                    // Hide PDF viewer section
                    document.getElementById('pdfViewerSection').classList.add('d-none');

                    // Show file info section
                    document.getElementById('fileInfoSection').classList.remove('d-none');

                    // Clear iframe
                    document.getElementById('pdfIframe').src = '';
                }

                function editFile(id) {
                    const modalElement = document.getElementById('editFileModal');
                    const modalContent = document.getElementById('editFileModalContent');

                    // Show loading
                    modalContent.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
                <p class="mt-2 text-muted">جاري تحميل بيانات الملف...</p>
            </div>
        `;

                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    fetch(`{{ url('admin/files') }}/${id}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                modalContent.innerHTML = data.html;

                                // Execute scripts in the loaded HTML
                                const scripts = modalContent.querySelectorAll('script');
                                scripts.forEach(script => {
                                    const newScript = document.createElement('script');
                                    if (script.src) {
                                        newScript.src = script.src;
                                    } else {
                                        newScript.textContent = script.textContent;
                                    }
                                    script.parentNode.replaceChild(newScript, script);
                                });
                            } else {
                                modalContent.innerHTML = `
                        <div class="modal-header modal-header-danger">
                            <h5 class="modal-title"><i class="ti ti-alert-triangle"></i> خطأ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <p class="text-danger">${data.error || 'حدث خطأ أثناء تحميل بيانات الملف'}</p>
                        </div>
                    `;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            modalContent.innerHTML = `
                    <div class="modal-header modal-header-danger">
                        <h5 class="modal-title"><i class="ti ti-alert-triangle"></i> خطأ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <p class="text-danger">حدث خطأ في الاتصال بالخادم</p>
                    </div>
                `;
                        });
                }

                function deleteFile(id, name) {
                    console.log('deleteFile called', id, name);
                    const modalElement = document.getElementById('deleteFileModal');
                    console.log('Modal element:', modalElement);

                    if (!modalElement) {
                        alert('خطأ: لم يتم العثور على نافذة الحذف');
                        return;
                    }

                    document.getElementById('deleteFileId').value = id;
                    document.getElementById('deleteFileName').textContent = name;

                    try {
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    } catch (error) {
                        console.error('Error showing modal:', error);
                        alert('خطأ في فتح نافذة الحذف: ' + error.message);
                    }
                }

                function confirmFileDelete() {
                    const fileId = document.getElementById('deleteFileId').value;
                    const deleteBtn = event.target;
                    deleteBtn.disabled = true;
                    deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحذف...';

                    fetch(`{{ url('admin/files') }}/${fileId}/delete`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                bootstrap.Modal.getInstance(document.getElementById('deleteFileModal')).hide();
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

                // Convert Arabic numbers to English in page range inputs
                function convertArabicToEnglish(input) {
                    const arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
                    const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

                    let value = input.value;
                    for (let i = 0; i < arabicNumbers.length; i++) {
                        value = value.replace(new RegExp(arabicNumbers[i], 'g'), englishNumbers[i]);
                    }
                    input.value = value;
                }

                // Add event listeners to all page range number inputs
                document.addEventListener('DOMContentLoaded', function() {
                    const pageInputs = document.querySelectorAll('input[name*="from_page"], input[name*="to_page"]');
                    pageInputs.forEach(input => {
                        input.addEventListener('input', function() {
                            convertArabicToEnglish(this);
                        });
                    });
                });
            </script>

            <!-- Barcode Print Modal -->
            <div class="modal fade" id="barcodePrintModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title">
                                <i class="ti ti-barcode me-2"></i>طباعة الباركود
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center p-4">
                            <div class="mb-3">
                                <h6 id="barcodeFileName" class="text-muted mb-3"></h6>
                                <div id="barcodeContainer" class="d-flex justify-content-center align-items-center"
                                    style="min-height: 150px;">
                                    <!-- Barcode will be generated here -->
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ti ti-x me-1"></i>إغلاق
                            </button>
                            <button type="button" class="btn btn-dark" onclick="printBarcodeContent()">
                                <i class="ti ti-printer me-1"></i>طباعة
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Upload Modal -->
            <div class="modal fade" id="fileUploadModal" tabindex="-1" data-bs-backdrop="static">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content" style="height: 100vh;">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="ti ti-upload me-2"></i>رفع ملف PDF
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="fileUploadForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="file_id" id="uploadFileId">
                            <div class="modal-body p-0" style="height: calc(100vh - 140px); overflow: hidden;">
                                <div class="row g-0" style="height: 100%;">
                                    <!-- Left Side: PDF Preview -->
                                    <div class="col-md-5 border-end"
                                        style="height: 100%; overflow-y: auto; background: #f8f9fa;">
                                        <div class="p-4">
                                            <h6 class="mb-3 text-muted">
                                                <i class="ti ti-file-text me-2"></i>
                                                معاينة الصفحة الأولى من الملف
                                            </h6>
                                            <div id="uploadPdfPreviewContainer" class="text-center">
                                                <div class="alert alert-info" id="uploadPdfPlaceholder">
                                                    <i class="ti ti-upload fs-1 mb-3 d-block"></i>
                                                    <p class="mb-0">قم برفع ملف PDF لمعاينة الصفحة الأولى</p>
                                                </div>
                                                <canvas id="uploadPdfCanvas" class="border rounded shadow-sm"
                                                    style="max-width: 100%; display: none;"></canvas>
                                                <div id="uploadPdfLoading" class="d-none">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">جاري التحميل...</span>
                                                    </div>
                                                    <p class="mt-2 text-muted">جاري تحميل المعاينة...</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Side: Form -->
                                    <div class="col-md-7" style="height: 100%; overflow-y: auto;">
                                        <div class="p-4">
                                            <div class="row g-3">
                                                <!-- File Info Alert -->
                                                <div class="col-12">
                                                    <div class="alert alert-info d-flex align-items-center mb-3">
                                                        <i class="ti ti-info-circle fs-4 me-3"></i>
                                                        <div>
                                                            <strong>رقم الملف:</strong> <span id="uploadFileName"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- PDF File Upload -->
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">
                                                        <i class="ti ti-file-upload me-2 text-primary"></i>
                                                        ملف PDF <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="file" name="document" id="uploadPdfFileInput"
                                                        class="form-control form-control-lg" accept=".pdf" required
                                                        onchange="previewUploadPDF(this)">
                                                    <small class="text-muted">
                                                        <i class="ti ti-info-circle me-1"></i>
                                                        الحد الأقصى: 50 ميجابايت
                                                    </small>
                                                </div>

                                                <!-- Content Types Section -->
                                                <div class="col-12">
                                                    <label
                                                        class="form-label d-flex align-items-center justify-content-between">
                                                        <span class="fw-semibold">
                                                            <i class="ti ti-checkbox me-2 text-primary"></i>
                                                            أنواع المحتوى (حدد نوع المحتوى ونطاق الصفحات)
                                                        </span>
                                                        <span class="badge bg-primary" id="uploadSelectedItemsCount">0
                                                            محدد</span>
                                                    </label>

                                                    <!-- Search Box -->
                                                    <div class="mb-3">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light border-end-0">
                                                                <i class="ti ti-search text-muted"></i>
                                                            </span>
                                                            <input type="text" id="uploadItemSearchInput"
                                                                class="form-control border-start-0 ps-0"
                                                                placeholder="ابحث عن نوع المحتوى..."
                                                                onkeyup="filterUploadItems()">
                                                            <button class="btn btn-outline-secondary" type="button"
                                                                onclick="clearUploadItemSearch()">
                                                                <i class="ti ti-x"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Quick Actions -->
                                                    <div class="mb-3 d-flex gap-2">
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            onclick="selectAllUploadItems()">
                                                            <i class="ti ti-checkbox me-1"></i>تحديد الكل
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                            onclick="deselectAllUploadItems()">
                                                            <i class="ti ti-square me-1"></i>إلغاء الكل
                                                        </button>
                                                    </div>

                                                    <!-- Items Table -->
                                                    <div class="border rounded"
                                                        style="max-height: 400px; overflow-y: auto; background: white;">
                                                        <table class="table table-bordered table-hover mb-0"
                                                            id="uploadItemsTable" style="direction: rtl">
                                                            <thead class="table-light"
                                                                style="position: sticky; top: 0; z-index: 10;">
                                                                <tr>
                                                                    <th class="text-center" style="width: 30%;">توصيف المستند
                                                                    </th>
                                                                    <th class="text-center" style="width: 10%;">من</th>
                                                                    <th class="text-center" style="width: 10%;">إلى</th>
                                                                    <th class="text-center" style="width: 30%;">توصيف المستند
                                                                    </th>
                                                                    <th class="text-center" style="width: 10%;">من</th>
                                                                    <th class="text-center" style="width: 10%;">إلى</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="uploadItemsContainer">
                                                                @php
                                                                    $allItems = \App\Models\Item::orderBy(
                                                                        'order',
                                                                    )->get();
                                                                    $rightItems = $allItems
                                                                        ->where('order', '<=', 37)
                                                                        ->values();
                                                                    $leftItems = $allItems
                                                                        ->where('order', '>', 37)
                                                                        ->values();
                                                                    $maxRows = max(
                                                                        $rightItems->count(),
                                                                        $leftItems->count(),
                                                                    );
                                                                @endphp
                                                                @for ($i = 0; $i < $maxRows; $i++)
                                                                    @php
                                                                        $rightItem = $rightItems->get($i);
                                                                        $leftItem = $leftItems->get($i);
                                                                        $searchName = strtolower(
                                                                            ($rightItem->name ?? '') .
                                                                                ' ' .
                                                                                ($leftItem->name ?? ''),
                                                                        );
                                                                    @endphp
                                                                    <tr class="upload-item-row"
                                                                        data-item-name="{{ $searchName }}">
                                                                        @if ($rightItem)
                                                                            <td class="align-middle">
                                                                                <div class="form-check mb-0">
                                                                                    <input
                                                                                        class="form-check-input upload-item-checkbox"
                                                                                        type="checkbox"
                                                                                        data-item-id="{{ $rightItem->id }}"
                                                                                        id="uploadItem{{ $rightItem->id }}"
                                                                                        onchange="toggleUploadPageRange({{ $rightItem->id }})">
                                                                                    <label
                                                                                        class="form-check-label cursor-pointer"
                                                                                        for="uploadItem{{ $rightItem->id }}">
                                                                                        {{ $rightItem->name }}
                                                                                    </label>
                                                                                </div>
                                                                                <input type="hidden"
                                                                                    name="items[{{ $rightItem->id }}][item_id]"
                                                                                    value="{{ $rightItem->id }}">
                                                                            </td>
                                                                            <td class="text-center align-middle">
                                                                                <input type="number"
                                                                                    name="items[{{ $rightItem->id }}][from_page]"
                                                                                    class="form-control form-control-sm text-center d-none page-input"
                                                                                    id="uploadFromPage{{ $rightItem->id }}"
                                                                                    min="1" placeholder="من">
                                                                            </td>
                                                                            <td class="text-center align-middle">
                                                                                <input type="number"
                                                                                    name="items[{{ $rightItem->id }}][to_page]"
                                                                                    class="form-control form-control-sm text-center d-none page-input"
                                                                                    id="uploadToPage{{ $rightItem->id }}"
                                                                                    min="1" placeholder="إلى">
                                                                            </td>
                                                                        @else
                                                                            <td class="align-middle"></td>
                                                                            <td class="text-center align-middle"></td>
                                                                            <td class="text-center align-middle"></td>
                                                                        @endif
                                                                        @if ($leftItem)
                                                                            <td class="align-middle">
                                                                                <div class="form-check mb-0">
                                                                                    <input
                                                                                        class="form-check-input upload-item-checkbox"
                                                                                        type="checkbox"
                                                                                        data-item-id="{{ $leftItem->id }}"
                                                                                        id="uploadItem{{ $leftItem->id }}"
                                                                                        onchange="toggleUploadPageRange({{ $leftItem->id }})">
                                                                                    <label
                                                                                        class="form-check-label cursor-pointer"
                                                                                        for="uploadItem{{ $leftItem->id }}">
                                                                                        {{ $leftItem->name }}
                                                                                    </label>
                                                                                </div>
                                                                                <input type="hidden"
                                                                                    name="items[{{ $leftItem->id }}][item_id]"
                                                                                    value="{{ $leftItem->id }}">
                                                                            </td>
                                                                            <td class="text-center align-middle">
                                                                                <input type="number"
                                                                                    name="items[{{ $leftItem->id }}][from_page]"
                                                                                    class="form-control form-control-sm text-center d-none page-input"
                                                                                    id="uploadFromPage{{ $leftItem->id }}"
                                                                                    min="1" placeholder="من">
                                                                            </td>
                                                                            <td class="text-center align-middle">
                                                                                <input type="number"
                                                                                    name="items[{{ $leftItem->id }}][to_page]"
                                                                                    class="form-control form-control-sm text-center d-none page-input"
                                                                                    id="uploadToPage{{ $leftItem->id }}"
                                                                                    min="1" placeholder="إلى">
                                                                            </td>
                                                                        @else
                                                                            <td class="align-middle"></td>
                                                                            <td class="text-center align-middle"></td>
                                                                            <td class="text-center align-middle"></td>
                                                                        @endif
                                                                    </tr>
                                                                @endfor
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <small class="text-muted mt-2 d-block">
                                                        <i class="ti ti-bulb text-warning me-1"></i>
                                                        عند تحديد نطاق الصفحات، سيتم قص الصفحات المحددة وإنشاء ملف فرعي جديد لكل
                                                        نوع محتوى
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="ti ti-x me-1"></i>إلغاء
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-upload me-1"></i>رفع ومعالجة
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- <!-- PDF.js Library for PDF Preview -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

    <!-- JsBarcode Library -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script> --}}

            <script src="{{ asset('dashboard/assets/vendor/pdfjs/pdf.min.js') }}"></script>
            <script src="{{ asset('dashboard/assets/vendor/jsbarcode/JsBarcode.all.min.js') }}"></script>
            <script>
                // Configure PDF.js worker
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                // Barcode Print Functions
                function printBarcode(barcode, fileName) {
                    document.getElementById('barcodeFileName').textContent = fileName;
                    const container = document.getElementById('barcodeContainer');
                    container.innerHTML = '<svg id="barcodeSvg"></svg>';

                    try {
                        JsBarcode("#barcodeSvg", barcode, {
                            format: "CODE128",
                            width: 2,
                            height: 100,
                            displayValue: true,
                            fontSize: 20,
                            margin: 10
                        });

                        const modal = new bootstrap.Modal(document.getElementById('barcodePrintModal'));
                        modal.show();
                    } catch (error) {
                        console.error('Barcode generation error:', error);
                        alert('فشل إنشاء الباركود');
                    }
                }

                function printBarcodeContent() {
                    const printWindow = window.open('', '', 'width=600,height=400');
                    const barcodeHtml = document.getElementById('barcodeContainer').innerHTML;
                    const fileName = document.getElementById('barcodeFileName').textContent;

                    printWindow.document.write(`
            <html dir="rtl">
            <head>
                <title>طباعة الباركود</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        padding: 20px;
                    }
                    h3 {
                        margin-bottom: 20px;
                        text-align: center;
                    }
                    @media print {
                        @page {
                            margin: 1cm;
                        }
                    }
                </style>
            </head>
            <body>
                <h3>${fileName}</h3>
                ${barcodeHtml}
                <script>
                    window.onload = function() {
                        window.print();
                        window.onafterprint = function() {
                            window.close();
                        };
                    };
                <\/script>
            </body>
            </html>
        `);
                    printWindow.document.close();
                }

                // Upload Modal Functions
                function openUploadForFile(fileId, fileName) {
                    document.getElementById('uploadFileId').value = fileId;
                    document.getElementById('uploadFileName').textContent = fileName;
                    document.getElementById('fileUploadForm').reset();
                    document.getElementById('uploadFileId').value = fileId;

                    // Reset all checkboxes and page inputs
                    document.querySelectorAll('.upload-item-checkbox').forEach(cb => {
                        cb.checked = false;
                    });
                    document.querySelectorAll('#uploadItemsTable .page-input').forEach(input => {
                        input.classList.add('d-none');
                        input.value = '';
                    });
                    updateUploadItemsCount();

                    const modal = new bootstrap.Modal(document.getElementById('fileUploadModal'));
                    modal.show();
                }

                function toggleUploadPageRange(itemId) {
                    const checkbox = document.getElementById(`uploadItem${itemId}`);
                    const fromPage = document.getElementById(`uploadFromPage${itemId}`);
                    const toPage = document.getElementById(`uploadToPage${itemId}`);

                    if (checkbox.checked) {
                        fromPage.classList.remove('d-none');
                        toPage.classList.remove('d-none');
                    } else {
                        fromPage.classList.add('d-none');
                        toPage.classList.add('d-none');
                        fromPage.value = '';
                        toPage.value = '';
                    }
                    updateUploadItemsCount();
                }

                function updateUploadItemsCount() {
                    const count = document.querySelectorAll('.upload-item-checkbox:checked').length;
                    document.getElementById('uploadSelectedItemsCount').textContent = count + ' محدد';
                }

                function filterUploadItems() {
                    const searchValue = document.getElementById('uploadItemSearchInput').value.toLowerCase();
                    const rows = document.querySelectorAll('.upload-item-row');

                    rows.forEach(row => {
                        const itemName = row.getAttribute('data-item-name');
                        if (itemName.includes(searchValue)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }

                function clearUploadItemSearch() {
                    document.getElementById('uploadItemSearchInput').value = '';
                    filterUploadItems();
                }

                function selectAllUploadItems() {
                    document.querySelectorAll('.upload-item-checkbox').forEach(checkbox => {
                        if (!checkbox.checked) {
                            checkbox.checked = true;
                            toggleUploadPageRange(checkbox.getAttribute('data-item-id'));
                        }
                    });
                }

                function deselectAllUploadItems() {
                    document.querySelectorAll('.upload-item-checkbox').forEach(checkbox => {
                        if (checkbox.checked) {
                            checkbox.checked = false;
                            toggleUploadPageRange(checkbox.getAttribute('data-item-id'));
                        }
                    });
                }

                function previewUploadPDF(input) {
                    const file = input.files[0];
                    if (!file || file.type !== 'application/pdf') {
                        return;
                    }

                    const fileSize = file.size / 1024 / 1024;
                    if (fileSize > 50) {
                        alert('حجم الملف يتجاوز 50 ميجابايت');
                        input.value = '';
                        return;
                    }

                    const placeholder = document.getElementById('uploadPdfPlaceholder');
                    const canvas = document.getElementById('uploadPdfCanvas');
                    const loading = document.getElementById('uploadPdfLoading');

                    // Show loading
                    placeholder.classList.add('d-none');
                    canvas.style.display = 'none';
                    loading.classList.remove('d-none');

                    const fileReader = new FileReader();
                    fileReader.onload = function() {
                        const typedarray = new Uint8Array(this.result);

                        // Load PDF
                        pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                            // Get first page
                            pdf.getPage(1).then(function(page) {
                                const viewport = page.getViewport({
                                    scale: 1.5
                                });
                                const context = canvas.getContext('2d');

                                canvas.height = viewport.height;
                                canvas.width = viewport.width;

                                // Render PDF page
                                const renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };

                                page.render(renderContext).promise.then(function() {
                                    loading.classList.add('d-none');
                                    canvas.style.display = 'block';
                                });
                            });
                        }).catch(function(error) {
                            console.error('Error loading PDF:', error);
                            loading.classList.add('d-none');
                            placeholder.classList.remove('d-none');
                            placeholder.innerHTML =
                                '<i class="ti ti-alert-circle fs-1 mb-3 d-block text-danger"></i><p class="mb-0 text-danger">خطأ في تحميل الملف</p>';
                        });
                    };

                    fileReader.readAsArrayBuffer(file);
                }

                // Reset preview when modal is closed
                document.getElementById('fileUploadModal')?.addEventListener('hidden.bs.modal', function() {
                    const placeholder = document.getElementById('uploadPdfPlaceholder');
                    const canvas = document.getElementById('uploadPdfCanvas');
                    const loading = document.getElementById('uploadPdfLoading');
                    const fileInput = document.getElementById('uploadPdfFileInput');

                    if (canvas) canvas.style.display = 'none';
                    if (loading) loading.classList.add('d-none');
                    if (placeholder) {
                        placeholder.classList.remove('d-none');
                        placeholder.innerHTML =
                            '<i class="ti ti-upload fs-1 mb-3 d-block"></i><p class="mb-0">قم برفع ملف PDF لمعاينة الصفحة الأولى</p>';
                    }
                    if (fileInput) fileInput.value = '';
                });

                // Handle Upload Form Submission
                document.getElementById('fileUploadForm')?.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const fileId = document.getElementById('uploadFileId').value;
                    const submitBtn = this.querySelector('button[type="submit"]');

                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الرفع...';

                    fetch(`/admin/files/${fileId}/upload-document`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message || 'تم رفع الملف بنجاح');
                                bootstrap.Modal.getInstance(document.getElementById('fileUploadModal')).hide();
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                alert(data.message || 'حدث خطأ أثناء رفع الملف');
                            }
                        })
                        .catch(error => {
                            console.error('Upload error:', error);
                            alert('حدث خطأ في الاتصال بالخادم');
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="ti ti-upload me-1"></i>رفع ومعالجة';
                        });
                });

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
                    localStorage.setItem('filesView', view);
                }

                // Initialize view from localStorage
                document.addEventListener('DOMContentLoaded', function() {
                    const savedView = localStorage.getItem('filesView') || 'list';
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
                    if (!confirm(`هل أنت متأكد من حذف ${ids.length} ملف؟`)) return;

                    fetch('{{ route('admin.files.bulk-delete') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                ids
                            })
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
