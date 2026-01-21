<!DOCTYPE html>
@include('dashboards.shared.html')

<head>
    @include('dashboards.shared.meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة العملاء - نظام أرشيف القاهرة الجديدة</title>
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
                                        <span class="badge badge-default fw-normal shadow px-2 fst-italic fs-sm d-inline-flex align-items-center">
                                            <i class="ti ti-users me-1"></i> إدارة العملاء
                                        </span>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb mb-0">
                                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                                                <li class="breadcrumb-item active" aria-current="page">العملاء</li>
                                            </ol>
                                        </nav>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-2 mt-lg-0">
                                    @can('clients.export')
                                        <a href="#" class="btn btn-soft-success shadow-sm px-3" style="border-radius: var(--ins-border-radius);">
                                            <i class="ti ti-download me-1"></i>
                                            <span>تصدير</span>
                                        </a>
                                    @endcan
                                    @can('clients.create')
                                        <button type="button" class="btn btn-primary shadow-sm px-3" style="border-radius: var(--ins-border-radius);"
                                            onclick="openCreateModal()">
                                            <i class="ti ti-plus me-1"></i>
                                            <span>إضافة عميل</span>
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row ">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary-subtle text-primary rounded me-3">
                                        <i class="ti ti-users fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Client::count() }}</h4>
                                        <small class="text-muted">إجمالي العملاء</small>
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
                                        <i class="ti ti-map-2 fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Land::count() }}</h4>
                                        <small class="text-muted">إجمالي الأراضي</small>
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
                                        <i class="ti ti-files fs-4"></i>
                                    </div>
                                    <div>
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
                                    <div class="avatar avatar-md bg-warning-subtle text-warning rounded me-3">
                                        <i class="ti ti-clock fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Client::whereDate('created_at', today())->count() }}</h4>
                                        <small class="text-muted">عملاء اليوم</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search & Filters Card -->
                @php
                    $hasFilters = request()->filled('search') || request()->filled('national_id') ||
                                  request()->filled('governorate_id') || request('trashed') === 'only';
                @endphp
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="border-radius: var(--ins-border-radius);">
                            <div class="card-body">
                                <!-- Barcode Scanner Section -->
                                <div class="mb-1 pb-3 border-bottom">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="ti ti-barcode fs-4 me-2 text-dark"></i>
                                        <h6 class="mb-0 fw-semibold">البحث بالباركود (الماسح الضوئي)</h6>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="input-group input-group-lg shadow-sm border border-dark border-opacity-25 overflow-hidden"
                                                style="border-radius: var(--ins-border-radius);">
                                                <span class="input-group-text bg-dark text-white border-0">
                                                    <i class="ti ti-scan"></i>
                                                </span>
                                                <input type="text" id="barcodeSearchInput" class="form-control border-0 fs-5"
                                                    placeholder="امسح الباركود أو أدخله يدوياً..." autocomplete="off" autofocus>
                                                <button type="button" class="btn btn-dark border-0 fs-5" onclick="searchByBarcode()">
                                                    <i class="ti ti-search me-1"></i> بحث
                                                </button>
                                            </div>
                                            <small class="text-muted mt-1 d-block">
                                                <i class="ti ti-info-circle me-1"></i>
                                                استخدم جهاز الماسح الضوئي لمسح الباركود أو أدخل رقم الباركود يدوياً ثم اضغط Enter
                                            </small>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div id="barcodeScannerStatus" class="d-flex align-items-center justify-content-center gap-2">
                                                <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                                                    <i class="ti ti-device-desktop-analytics me-1"></i>
                                                    جاهز للمسح
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Client Search & Filters Section -->
                                <form method="GET" action="{{ route('admin.clients.index') }}">
                                    <div class="row d-flex align-items-end justify-content-start">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label fw-semibold">بحث</label>
                                            <div class="input-group shadow-sm border border-secondary border-opacity-10 overflow-hidden bg-body"
                                                style="border-radius: var(--ins-border-radius);">
                                                <input type="text" name="search" class="form-control border-0 bg-transparent"
                                                    placeholder="الاسم، الرقم القومي، كود العميل، الموبايل..." value="{{ request('search') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2 d-flex align-items-center gap-2">
                                            <div class="d-flex flex-wrap gap-1">
                                                <button type="submit" class="btn btn-primary shadow-sm px-3" style="border-radius: var(--ins-border-radius);">
                                                    <i class="ti ti-filter me-1"></i> فلترة
                                                </button>
                                                <a href="{{ route('admin.clients.index') }}" style="border-radius: var(--ins-border-radius);"
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
                                            <label class="form-label fw-semibold">الرقم القومي</label>
                                            <input type="text" name="national_id" class="form-control" placeholder="الرقم القومي" value="{{ request('national_id') }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">المحافظة</label>
                                            <select name="governorate_id" class="form-select">
                                                <option value="">جميع المحافظات</option>
                                                @foreach($governorates as $governorate)
                                                    <option value="{{ $governorate->id }}" {{ request('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                                        {{ $governorate->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

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
                <div class="row ">
                    <div class="col-12">
                        <div class="card" style="border-radius: var(--ins-border-radius);">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <h5 class="card-title mb-0">العملاء</h5>
                                    <span class="badge bg-primary-subtle text-primary">{{ $clients->total() }} عميل</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Bulk Actions -->
                                    <div class="bulk-actions d-none me-2" id="bulkActions">
                                        @if(request('trashed') != 'only')
                                            @can('clients.delete')
                                                <button type="button" class="btn btn-soft-danger btn-sm" onclick="bulkDelete()">
                                                    <i class="ti ti-trash me-1"></i> حذف المحدد
                                                </button>
                                            @endcan
                                        @else
                                            @can('clients.delete')
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
                                                <th>العميل</th>
                                                <th>الرقم القومي</th>
                                                <th>كود العميل</th>
                                                <th>الموبايل</th>
                                                <th>الأراضي</th>
                                                <th>الملفات</th>
                                                <th width="180" class="text-center">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($clients as $client)
                                                <tr id="client-row-{{ $client->id }}">
                                                    <td><input type="checkbox" class="form-check-input row-checkbox" value="{{ $client->id }}"></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                                {{ mb_substr($client->name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $client->name }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $client->national_id ?? '-' }}</td>
                                                    <td><span class="badge bg-info-subtle text-info">{{ $client->client_code ?? '-' }}</span></td>
                                                    <td>{{ $client->mobile ?? '-' }}</td>
                                                    <td><span class="badge bg-success-subtle text-success">{{ $client->lands_count }}</span></td>
                                                    <td><span class="badge bg-primary-subtle text-primary">{{ $client->files_count }}</span></td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-1">
                                                            @if(request('trashed') != 'only')
                                                                <button class="btn btn-soft-info btn-sm" onclick="showClient({{ $client->id }})" title="عرض">
                                                                    <i class="ti ti-eye"></i>
                                                                </button>
                                                                @can('files.upload')
                                                                <button class="btn btn-soft-success btn-sm" onclick="uploadFile({{ $client->id }})" title="رفع ملف">
                                                                    <i class="ti ti-upload"></i>
                                                                </button>
                                                                @endcan
                                                                @can('clients.edit')
                                                                    <button class="btn btn-soft-warning btn-sm" onclick="editClient({{ $client->id }})" title="تعديل">
                                                                        <i class="ti ti-edit"></i>
                                                                    </button>
                                                                @endcan
                                                                @can('clients.delete')
                                                                    <button class="btn btn-soft-danger btn-sm" onclick="deleteClient({{ $client->id }}, '{{ $client->name }}')" title="حذف">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                @endcan
                                                            @else
                                                                <button class="btn btn-soft-success btn-sm" onclick="restoreClient({{ $client->id }})" title="استرجاع">
                                                                    <i class="ti ti-refresh"></i>
                                                                </button>
                                                                <button class="btn btn-danger btn-sm" onclick="forceDeleteClient({{ $client->id }}, '{{ $client->name }}')" title="حذف نهائي">
                                                                    <i class="ti ti-trash-x"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ti ti-users-minus fs-1 d-block mb-2"></i>
                                                            لا يوجد عملاء
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
                                        @forelse($clients as $client)
                                            <div class="col-md-4 col-lg-3" id="client-card-{{ $client->id }}">
                                                <div class="card border shadow-sm h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="avatar avatar-md bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                                {{ mb_substr($client->name, 0, 1) }}
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0">{{ $client->name }}</h6>
                                                                <small class="text-muted">{{ $client->client_code ?? 'بدون كود' }}</small>
                                                            </div>
                                                            <input type="checkbox" class="form-check-input row-checkbox" value="{{ $client->id }}">
                                                        </div>
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block"><i class="ti ti-id me-1"></i> {{ $client->national_id ?? '-' }}</small>
                                                            <small class="text-muted d-block"><i class="ti ti-phone me-1"></i> {{ $client->mobile ?? '-' }}</small>
                                                        </div>
                                                        <div class="d-flex gap-2 mb-3">
                                                            <span class="badge bg-success-subtle text-success"><i class="ti ti-map-2 me-1"></i>{{ $client->lands_count }} أرض</span>
                                                            <span class="badge bg-primary-subtle text-primary"><i class="ti ti-files me-1"></i>{{ $client->files_count }} ملف</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top-0 pt-0">
                                                        <div class="d-flex justify-content-between">
                                                            @if(request('trashed') != 'only')
                                                                <button class="btn btn-soft-info btn-sm" onclick="showClient({{ $client->id }})"><i class="ti ti-eye"></i></button>
                                                                <button class="btn btn-soft-warning btn-sm" onclick="editClient({{ $client->id }})"><i class="ti ti-edit"></i></button>
                                                                <button class="btn btn-soft-success btn-sm" onclick="uploadFile({{ $client->id }})"><i class="ti ti-upload"></i></button>
                                                                <button class="btn btn-soft-danger btn-sm" onclick="deleteClient({{ $client->id }}, '{{ $client->name }}')"><i class="ti ti-trash"></i></button>
                                                            @else
                                                                <button class="btn btn-soft-success btn-sm" onclick="restoreClient({{ $client->id }})"><i class="ti ti-refresh"></i> استرجاع</button>
                                                                <button class="btn btn-danger btn-sm" onclick="forceDeleteClient({{ $client->id }}, '{{ $client->name }}')"><i class="ti ti-trash-x"></i> حذف نهائي</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ti ti-users-minus fs-1 d-block mb-2"></i>
                                                    لا يوجد عملاء
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Pagination -->
                                @if($clients->hasPages())
                                    <div class="d-flex justify-content-center p-3">
                                        {{ $clients->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('dashboards.shared.modal-styles')


    {{-- All Modals --}}
    @can('clients.create')
        @include('dashboards.admin.pages.clients.partials.create-modal')
    @endcan
    @can('clients.edit')
        @include('dashboards.admin.pages.clients.partials.edit-modal')
    @endcan
    @can('clients.delete')
        @include('dashboards.admin.pages.clients.partials.delete-modal')
        @include('dashboards.admin.pages.clients.partials.bulk-modals')
    @endcan
    @include('dashboards.admin.pages.clients.partials.view-modal')
    @can('files.upload')
        @include('dashboards.admin.pages.clients.partials.upload-modal')
    @endcan

@include('dashboards.admin.pages.clients.partials.scripts')


    {{-- Show File Modal for viewing files from client list --}}
    <div class="modal fade" id="showFileModal" tabindex="-1" style="z-index: 1060;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header modal-header-dark">
                    <h5 class="modal-title">
                        <i class="ti ti-file-text"></i>
                        <span id="fileModalTitle">تفاصيل الملف</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="fileModalBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                        <p class="mt-3 text-muted">جاري تحميل بيانات الملف...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal backdrop for showFileModal with higher z-index --}}
    <style>
        #showFileModal.show {
            z-index: 1060 !important;
        }
        #showFileModal + .modal-backdrop {
            z-index: 1059 !important;
        }
        #viewClientModal {
            z-index: 1055;
        }
        #viewClientModal + .modal-backdrop {
            z-index: 1054;
        }
    </style>

    {{-- @include('dashboards.shared.theme_settings') --}}
    @include('dashboards.shared.scripts')
</body>
</html>
