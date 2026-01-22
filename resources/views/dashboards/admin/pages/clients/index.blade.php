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
                        <div class="mt-3 mb-2 page-title-box">
                            <div class="px-3 py-2 border border-opacity-10 shadow-sm d-flex flex-column flex-lg-row align-items-lg-center justify-content-between bg-body border-secondary"
                                style="border-radius: var(--ins-border-radius);">
                                <div class="d-flex align-items-start align-items-md-center">
                                    <div>
                                        <span
                                            class="px-2 shadow badge badge-default fw-normal fst-italic fs-sm d-inline-flex align-items-center">
                                            <i class="ti ti-users me-1"></i> إدارة العملاء
                                        </span>
                                        <nav aria-label="breadcrumb">
                                            <ol class="mb-0 breadcrumb">
                                                <li class="breadcrumb-item"><a
                                                        href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                                                <li class="breadcrumb-item active" aria-current="page">العملاء</li>
                                            </ol>
                                        </nav>
                                    </div>
                                </div>

                                <div class="flex-wrap gap-2 mt-2 d-flex mt-lg-0">
                                    @can('clients.export')
                                        <a href="#" class="px-3 shadow-sm btn btn-soft-success"
                                            style="border-radius: var(--ins-border-radius);">
                                            <i class="ti ti-download me-1"></i>
                                            <span>تصدير</span>
                                        </a>
                                    @endcan
                                    @can('clients.create')
                                        <button type="button" class="px-3 shadow-sm btn btn-primary"
                                            style="border-radius: var(--ins-border-radius);" onclick="openCreateModal()">
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
                <div class="row">
                    <div class="col-md-4">
                        <div class="border-0 shadow-sm card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="rounded avatar avatar-md bg-primary-subtle text-primary me-3">
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
                    <div class="col-md-4">
                        <div class="border-0 shadow-sm card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="rounded avatar avatar-md bg-success-subtle text-success me-3">
                                        <i class="ti ti-map-2 fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Land::count() }}</h4>
                                        <small class="text-muted">إجمالي القطع</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border-0 shadow-sm card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="rounded avatar avatar-md bg-info-subtle text-info me-3">
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
                </div>

                <!-- Unified Search Card -->
                @php
                    $hasFilters =
                        request()->filled('national_id') ||
                        request()->filled('governorate_id') ||
                        request('trashed') === 'only';
                @endphp
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="border-radius: var(--ins-border-radius);">
                            <div class="card-body">
                                <!-- Unified Search Section -->
                                <form method="GET" action="{{ route('admin.clients.index') }}" id="unifiedSearchForm">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-lg-2 col-md-3">
                                            <label class="mb-2 form-label fw-semibold">نوع البحث</label>
                                            <select id="searchType" name="search_type" class="shadow-sm form-select form-select-lg"
                                                style="border-radius: var(--ins-border-radius); height: 48px;" onchange="updateSearchPlaceholder()">
                                                <option value="name" {{ request('search_type') == 'name' ? 'selected' : '' }}>اسم العميل</option>
                                                <option value="area" {{ request('search_type') == 'area' ? 'selected' : '' }}>المنطقة</option>
                                                <option value="land_no" {{ request('search_type') == 'land_no' ? 'selected' : '' }}>رقم القطعة</option>
                                                <option value="file_no" {{ request('search_type') == 'file_no' ? 'selected' : '' }}>رقم الملف</option>
                                                <option value="barcode" {{ request('search_type') == 'barcode' ? 'selected' : '' }}>كود الباركود</option>
                                                <option value="national_id" {{ request('search_type') == 'national_id' ? 'selected' : '' }}>الرقم القومي</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-7 col-md-6">
                                            <label class="mb-2 form-label fw-semibold">البحث</label>
                                            <div class="overflow-hidden border border-opacity-25 shadow-sm input-group border-primary"
                                                style="border-radius: var(--ins-border-radius); height: 48px;">
                                                <span class="px-3 text-white border-0 input-group-text bg-primary">
                                                    <i class="ti ti-search fs-5"></i>
                                                </span>
                                                <input type="text" id="unifiedSearchInput" name="search"
                                                    class="border-0 form-control"
                                                    placeholder="امسح الباركود أو أدخله يدوياً..."
                                                    value="{{ request('search') }}"
                                                    autocomplete="off"
                                                    autofocus
                                                    style="font-size: 15px;">
                                                <button type="submit" class="px-4 border-0 btn btn-primary">
                                                    <i class="ti ti-search me-1"></i> بحث
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3">
                                            <div class="gap-2 d-flex align-items-center" style="height: 48px;">
                                                <a href="{{ route('admin.clients.index') }}"
                                                    id="resetButton"
                                                    class="shadow-sm btn btn-secondary flex-fill"
                                                    style="border-radius: var(--ins-border-radius); height: 100%;">
                                                    <i class="ti ti-refresh me-1"></i> إعادة
                                                </a>
                                                <button type="button"
                                                    class="shadow-sm btn btn-outline-primary"
                                                    style="border-radius: var(--ins-border-radius); height: 100%; aspect-ratio: 1/1;"
                                                    data-bs-toggle="collapse" data-bs-target="#advancedFilters"
                                                    aria-expanded="{{ $hasFilters ? 'true' : 'false' }}">
                                                    <i class="ti {{ $hasFilters ? 'ti-eye-off' : 'ti-filter' }} fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <small class="mt-2 text-muted d-block">
                                                <i class="ti ti-info-circle me-1"></i>
                                                <span id="searchHint">استخدم جهاز الماسح الضوئي لمسح الباركود أو أدخله يدوياً</span>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="collapse {{ $hasFilters ? 'show' : '' }} row g-3 align-items-end mt-2"
                                        id="advancedFilters">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">الرقم القومي</label>
                                            <input type="text" name="national_id" class="form-control"
                                                placeholder="الرقم القومي" value="{{ request('national_id') }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">المحافظة</label>
                                            <select name="governorate_id" class="form-select">
                                                <option value="">جميع المحافظات</option>
                                                @foreach ($governorates as $governorate)
                                                    <option value="{{ $governorate->id }}"
                                                        {{ request('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                                        {{ $governorate->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">عرض</label>
                                            <select name="trashed" class="form-select">
                                                <option value=""
                                                    {{ request('trashed') !== 'only' ? 'selected' : '' }}>السجلات
                                                    النشطة</option>
                                                <option value="only"
                                                    {{ request('trashed') === 'only' ? 'selected' : '' }}>المحذوفات فقط
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">عدد النتائج</label>
                                            <select name="per_page" class="form-select">
                                                <option value="25"
                                                    {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                                                <option value="50"
                                                    {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                                <option value="100"
                                                    {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
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
                                <div class="gap-3 d-flex align-items-center">
                                    <h5 class="mb-0 card-title">العملاء</h5>
                                    <span class="badge bg-primary-subtle text-primary">{{ $clients->total() }}
                                        عميل</span>
                                </div>
                                <div class="gap-2 d-flex align-items-center">
                                    <!-- Bulk Actions -->
                                    <div class="bulk-actions d-none me-2" id="bulkActions">
                                        @if (request('trashed') != 'only')
                                            <button type="button" class="btn btn-soft-dark btn-sm"
                                                onclick="bulkPrintBarcodes()">
                                                <i class="ti ti-printer me-1"></i> طباعة الباركودات
                                            </button>
                                            @can('clients.delete')
                                                <button type="button" class="btn btn-soft-danger btn-sm"
                                                    onclick="bulkDelete()">
                                                    <i class="ti ti-trash me-1"></i> حذف المحدد
                                                </button>
                                            @endcan
                                        @else
                                            @can('clients.delete')
                                                <button type="button" class="btn btn-soft-success btn-sm"
                                                    onclick="bulkRestore()">
                                                    <i class="ti ti-refresh me-1"></i> استرجاع المحدد
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="bulkForceDelete()">
                                                    <i class="ti ti-trash-x me-1"></i> حذف نهائي
                                                </button>
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
                            <div class="p-0 card-body">
                                <!-- List View -->
                                <div id="listView" class="table-responsive">
                                    <table class="table mb-0 table-hover">
                                        <thead class="bg-light-subtle">
                                            <tr>
                                                <th width="10"><input type="checkbox" class="form-check-input"
                                                        id="selectAll"></th>
                                                <th>بيانات العميل الكاملة</th>
                                                <th width="150" class="text-center">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($clients as $client)
                                                <tr id="client-row-{{ $client->id }}" style="vertical-align: top;">
                                                    <td><input type="checkbox" class="form-check-input row-checkbox"
                                                            value="{{ $client->id }}"></td>
                                                    <td>
                                                        <div class="row g-3">
                                                            {{-- Client Basic Info --}}
                                                            <div class="col-md-2">
                                                                <div class="mb-2 d-flex align-items-start">
                                                                    <div
                                                                        class="avatar avatar-md bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                                        <strong>{{ mb_substr($client->name, 0, 1) }}</strong>
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="mb-1">
                                                                            <strong>{{ $client->name }}</strong></h6>
                                                                        {{-- <small class="text-muted d-block"><i class="ti ti-id me-1"></i>{{ $client->national_id ?? '-' }}</small> --}}
                                                                        {{-- <small class="text-muted d-block"><i class="ti ti-phone me-1"></i>{{ $client->mobile ?? '-' }}</small> --}}
                                                                        @if ($client->excel_row_number)
                                                                            <span
                                                                                class="mt-1 badge bg-secondary-subtle text-secondary">
                                                                                <i
                                                                                    class="ti ti-file-spreadsheet me-1"></i>صف
                                                                                #{{ $client->excel_row_number }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- File Numbers --}}
                                                            <div class="col-md-1">
                                                                <small class="mb-1 text-muted d-block"><strong><i
                                                                            class="ti ti-files me-1"></i>أرقام
                                                                        الملفات:</strong></small>
                                                                @if ($client->mainFiles && $client->mainFiles->count() > 0)
                                                                    @foreach ($client->mainFiles as $file)
                                                                        <div class="mb-1">
                                                                            <span class="badge bg-primary-subtle text-primary">{{ $file->file_name }}</span>
                                                                        </div>
                                                                        @if (!$loop->last)
                                                                            <hr class="my-1">
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <small class="text-muted">-</small>
                                                                @endif
                                                            </div>

                                                            {{-- Land Addresses --}}
                                                            <div class="col-md-3">
                                                                <small class="mb-1 text-muted d-block"><strong><i
                                                                            class="ti ti-map-pin me-1"></i>عناوين
                                                                        القطع:</strong></small>
                                                                @if ($client->lands && $client->lands->count() > 0)
                                                                    @foreach ($client->lands as $land)
                                                                        <div class="mb-1">
                                                                            <small class="d-block fs-5">
                                                                                @if ($land->district)
                                                                                    ({{ $land->district->name }})
                                                                                @endif
                                                                                ->
                                                                                @if ($land->zone)
                                                                                    ({{ $land->zone->name }})
                                                                                @endif
                                                                                ->
                                                                                @if ($land->area)
                                                                                    ({{ $land->area->name }})
                                                                                @endif
-> قطعه
                                                                                <strong>({{ $land->land_no }})</strong>
                                                                            </small>
                                                                        </div>
                                                                        @if (!$loop->last)
                                                                            <hr class="my-1">
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <small class="text-muted">-</small>
                                                                @endif
                                                            </div>

                                                            {{-- Physical Locations & Page Counts --}}
                                                            <div class="col-md-3">
                                                                <small class="mb-1 text-muted d-block"><strong><i
                                                                            class="ti ti-building-warehouse me-1"></i>موقع
                                                                        التخزين:</strong></small>
                                                                @if ($client->mainFiles && $client->mainFiles->count() > 0)
                                                                    @foreach ($client->mainFiles as $file)
                                                                        <div class="mb-1">
                                                                            <small class="d-block fs-5">
                                                                                (غرفة {{ $file->room?->name ?? '-' }})
                                                                                @if ($file->lane)
                                                                                    -> (ممر {{ $file->lane->name }})
                                                                                @endif

                                                                                @if ($file->stand)
                                                                                    -> (ستاند {{ $file->stand->name }})
                                                                                @endif

                                                                                @if ($file->rack)
                                                                                    -> (رف {{ $file->rack->name }})
                                                                                @endif


                                                                            </small>
                                                                        </div>
                                                                        @if (!$loop->last)
                                                                            <hr class="my-1">
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <small class="text-muted">-</small>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-1">
                                                                <small class="mb-1 text-muted d-block"><strong><i
                                                                            class="ti ti-building-warehouse me-1"></i>عدد
                                                                        الصفحات:</strong></small>
                                                                <span class="mt-1 badge bg-info-subtle text-info">
                                                                    <i
                                                                        class="ti ti-file-text me-1"></i>{{ $file->items->count() > 0 ? $file->items->count() : 1 }}
                                                                    صفحة
                                                                </span>
                                                            </div>


                                                            {{-- Barcodes --}}
                                                            <div class="col-md-2">
                                                                <small class="mb-1 text-muted d-block"><strong><i
                                                                            class="ti ti-barcode me-1"></i>الباركود:</strong></small>
                                                                @if ($client->mainFiles && $client->mainFiles->count() > 0)
                                                                    @foreach ($client->mainFiles as $file)
                                                                        <div class="mb-1">
                                                                            <code
                                                                                class="small">{{ $file->barcode }}</code>
                                                                        </div>
                                                                        @if (!$loop->last)
                                                                            <hr class="my-1">
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <small class="text-muted">-</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="gap-1 d-flex flex-column">
                                                            @if (request('trashed') != 'only')
                                                                {{-- Print All Barcodes --}}
                                                                @if ($client->mainFiles && $client->mainFiles->count() > 0)
                                                                    <button class="btn btn-soft-dark btn-sm"
                                                                        onclick="printClientBarcodes({{ $client->id }})"
                                                                        title="طباعة جميع الباركودات">
                                                                        <i class="ti ti-printer"></i>
                                                                    </button>
                                                                @endif
                                                                <button class="btn btn-soft-info btn-sm"
                                                                    onclick="showClient({{ $client->id }})"
                                                                    title="عرض">
                                                                    <i class="ti ti-eye"></i>
                                                                </button>
                                                                @can('clients.edit')
                                                                    <button class="btn btn-soft-warning btn-sm"
                                                                        onclick="editClient({{ $client->id }})"
                                                                        title="تعديل">
                                                                        <i class="ti ti-edit"></i>
                                                                    </button>
                                                                @endcan
                                                                @can('files.upload')
                                                                    {{-- <button class="btn btn-soft-success btn-sm"
                                                                        onclick="uploadFile({{ $client->id }})"
                                                                        title="رفع ملف">
                                                                        <i class="ti ti-upload"></i>
                                                                    </button> --}}
                                                                @endcan
                                                                @can('clients.delete')
                                                                    <button class="btn btn-soft-danger btn-sm"
                                                                        onclick="deleteClient({{ $client->id }}, '{{ $client->name }}')"
                                                                        title="حذف">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                @endcan
                                                            @else
                                                                <button class="btn btn-soft-success btn-sm"
                                                                    onclick="restoreClient({{ $client->id }})"
                                                                    title="استرجاع">
                                                                    <i class="ti ti-refresh"></i>
                                                                </button>
                                                                <button class="btn btn-danger btn-sm"
                                                                    onclick="forceDeleteClient({{ $client->id }}, '{{ $client->name }}')"
                                                                    title="حذف نهائي">
                                                                    <i class="ti ti-trash-x"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="py-5 text-center">
                                                        <div class="text-muted">
                                                            <i class="mb-3 opacity-50 ti ti-search fs-1 d-block"></i>
                                                            <h5 class="mb-2">لا توجد نتائج</h5>
                                                            <p class="mb-0">لم يتم العثور على عملاء مطابقين لمعايير البحث</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Card View -->
                                <div id="cardView" class="p-3 d-none">
                                    <div class="row g-3">
                                        @forelse($clients as $client)
                                            <div class="col-md-4 col-lg-3" id="client-card-{{ $client->id }}">
                                                <div class="border shadow-sm card h-100">
                                                    <div class="card-body">
                                                        <div class="mb-3 d-flex align-items-center">
                                                            <div
                                                                class="avatar avatar-md bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                                {{ mb_substr($client->name, 0, 1) }}
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0">{{ $client->name }}</h6>
                                                                <small
                                                                    class="text-muted">{{ $client->client_code ?? 'بدون كود' }}</small>
                                                            </div>
                                                            <input type="checkbox"
                                                                class="form-check-input row-checkbox"
                                                                value="{{ $client->id }}">
                                                        </div>
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block"><i
                                                                    class="ti ti-id me-1"></i>
                                                                {{ $client->national_id ?? '-' }}</small>
                                                            <small class="text-muted d-block"><i
                                                                    class="ti ti-phone me-1"></i>
                                                                {{ $client->mobile ?? '-' }}</small>
                                                        </div>
                                                        <div class="gap-2 mb-3 d-flex">
                                                            <span class="badge bg-success-subtle text-success"><i
                                                                    class="ti ti-map-2 me-1"></i>{{ $client->lands_count }}
                                                                قطعه</span>
                                                            <span class="badge bg-primary-subtle text-primary"><i
                                                                    class="ti ti-files me-1"></i>{{ $client->files_count }}
                                                                ملف</span>
                                                        </div>
                                                    </div>
                                                    <div class="pt-0 bg-transparent card-footer border-top-0">
                                                        <div class="d-flex justify-content-between">
                                                            @if (request('trashed') != 'only')
                                                                <button class="btn btn-soft-info btn-sm"
                                                                    onclick="showClient({{ $client->id }})"><i
                                                                        class="ti ti-eye"></i></button>
                                                                <button class="btn btn-soft-warning btn-sm"
                                                                    onclick="editClient({{ $client->id }})"><i
                                                                        class="ti ti-edit"></i></button>
                                                                @can('files.upload')
                                                                    <button class="btn btn-soft-success btn-sm"
                                                                        onclick="uploadFile({{ $client->id }})"><i
                                                                            class="ti ti-upload"></i></button>
                                                                @endcan
                                                                <button class="btn btn-soft-danger btn-sm"
                                                                    onclick="deleteClient({{ $client->id }}, '{{ $client->name }}'"><i
                                                                        class="ti ti-trash"></i></button>
                                                            @else
                                                                <button class="btn btn-soft-success btn-sm"
                                                                    onclick="restoreClient({{ $client->id }})"><i
                                                                        class="ti ti-refresh"></i> استرجاع</button>
                                                                <button class="btn btn-danger btn-sm"
                                                                    onclick="forceDeleteClient({{ $client->id }}, '{{ $client->name }}')"><i
                                                                        class="ti ti-trash-x"></i> حذف نهائي</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="py-5 text-center col-12">
                                                <div class="text-muted">
                                                    <i class="mb-3 opacity-50 ti ti-search fs-1 d-block"></i>
                                                    <h5 class="mb-2">لا توجد نتائج</h5>
                                                    <p class="mb-0">لم يتم العثور على عملاء مطابقين لمعايير البحث</p>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Pagination -->
                                @if ($clients->hasPages())
                                    <div class="p-3 d-flex justify-content-center">
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
    @include('dashboards.shared.modal-styles')ة
    @include('dashboards.admin.pages.clients.partials.scripts')
    {{-- @include('dashboards.shared.scripts') --}}

    <script>
        const ROUTES = {
            create: "{{ route('admin.clients.create') }}",
            store: "{{ route('admin.clients.store') }}",
            show: "{{ route('admin.clients.show', ':id') }}",
            edit: "{{ route('admin.clients.edit', ':id') }}",
            update: "{{ route('admin.clients.update', ':id') }}",
            destroy: "{{ route('admin.clients.destroy', ':id') }}",
            restore: "{{ route('admin.clients.restore', ':id') }}",
            forceDelete: "{{ route('admin.clients.force-delete', ':id') }}",
            bulkDelete: "{{ route('admin.clients.bulk-delete') }}",
            bulkRestore: "{{ route('admin.clients.bulk-restore') }}",
            bulkForceDelete: "{{ route('admin.clients.bulk-force-delete') }}",
            uploadFile: "{{ route('admin.files.create') }}",
        };

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
            localStorage.setItem('clientsView', view);
        }

        // Initialize view from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('clientsView') || 'list';
            toggleView(savedView);

            // Auto-focus on search input
            const searchInput = document.getElementById('unifiedSearchInput');
            const searchTypeSelect = document.getElementById('searchType');

            if (searchInput) {
                // Clear URL and input on page load if search type is barcode
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('search_type') === 'barcode' && urlParams.has('search')) {
                    window.history.replaceState({}, '', window.location.pathname);
                    searchInput.value = '';
                }

                searchInput.focus();

                // Barcode scanner variables
                let lastKeyTime = 0;
                let scannerBuffer = '';

                // Detect barcode scanner input
                searchInput.addEventListener('keydown', function(e) {
                    const currentTime = Date.now();
                    const timeDiff = currentTime - lastKeyTime;

                    // Fast typing detected (< 50ms between keys) = barcode scanner
                    if (timeDiff < 50 && lastKeyTime > 0) {
                        // Scanner detected - if there's old content, clear it
                        if (this.value.length > 0 && scannerBuffer.length === 0) {
                            // Clear URL and input before new scan
                            window.history.replaceState({}, '', window.location.pathname);
                            this.value = '';
                            searchTypeSelect.value = 'barcode';
                        }
                        scannerBuffer += e.key;
                    } else if (timeDiff > 100) {
                        // Manual typing or new scan starting
                        scannerBuffer = '';
                    }

                    lastKeyTime = currentTime;
                });

                // Submit on Enter
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();

                        const value = this.value.trim();

                        // Auto-detect barcode pattern
                        if (value.length >= 5 && /^[A-Z0-9-]+$/i.test(value)) {
                            searchTypeSelect.value = 'barcode';
                        }

                        // Submit the form
                        document.getElementById('unifiedSearchForm').submit();

                        // Reset scanner buffer
                        scannerBuffer = '';
                    }
                });

                // Select all on focus
                searchInput.addEventListener('focus', function() {
                    if (this.value) {
                        this.select();
                    }
                });
            }

            // Update placeholder on page load
            updateSearchPlaceholder();
        });

        // Update search placeholder based on search type
        function updateSearchPlaceholder() {
            const searchType = document.getElementById('searchType').value;
            const searchInput = document.getElementById('unifiedSearchInput');
            const searchHint = document.getElementById('searchHint');

            const placeholders = {
                'name': 'أدخل اسم العميل...',
                'area': 'أدخل اسم المنطقة...',
                'land_no': 'أدخل رقم القطعة...',
                'file_no': 'أدخل رقم الملف...',
                'barcode': 'امسح الباركود أو أدخله يدوياً...',
                'national_id': 'أدخل الرقم القومي...'
            };

            const hints = {
                'name': 'ابحث عن العميل باستخدام الاسم',
                'area': 'ابحث عن العملاء في منطقة معينة',
                'land_no': 'ابحث عن القطعة باستخدام رقم القطعة',
                'file_no': 'ابحث عن الملف باستخدام رقم الملف',
                'barcode': 'استخدم جهاز الماسح الضوئي لمسح الباركود أو أدخله يدوياً',
                'national_id': 'ابحث عن العميل باستخدام الرقم القومي'
            };

            searchInput.placeholder = placeholders[searchType] || placeholders['barcode'];
            searchHint.textContent = hints[searchType] || hints['barcode'];

            // Re-focus after type change
            searchInput.focus();
        }

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

        // Open Create Modal - handled by scripts.blade.php

        // Restore Client
        function restoreClient(id) {
            fetch(ROUTES.restore.replace(':id', id), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('client-row-' + id)?.remove();
                    document.getElementById('client-card-' + id)?.remove();
                    showToast('success', data.message);
                } else {
                    showToast('error', data.error || 'حدث خطأ');
                }
            });
        }

        // Force Delete Client
        function forceDeleteClient(id, name) {
            if (!confirm('هل أنت متأكد من الحذف النهائي للعميل "' + name + '"؟ لا يمكن التراجع عن هذا الإجراء!')) return;

            fetch(ROUTES.forceDelete.replace(':id', id), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('client-row-' + id)?.remove();
                    document.getElementById('client-card-' + id)?.remove();
                    showToast('success', data.message);
                } else {
                    showToast('error', data.error || 'حدث خطأ');
                }
            });
        }


        // Bulk Operations
        function getSelectedIds() {
            return Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
        }

        function bulkDelete() {
            const ids = getSelectedIds();
            if (ids.length === 0) return;
            if (!confirm('هل أنت متأكد من حذف ' + ids.length + ' عميل؟')) return;

            fetch(ROUTES.bulkDelete, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    ids.forEach(id => {
                        document.getElementById('client-row-' + id)?.remove();
                        document.getElementById('client-card-' + id)?.remove();
                    });
                    showToast('success', data.message);
                    updateBulkActions();
                }
            });
        }

        function bulkRestore() {
            const ids = getSelectedIds();
            if (ids.length === 0) return;

            fetch(ROUTES.bulkRestore, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    ids.forEach(id => {
                        document.getElementById('client-row-' + id)?.remove();
                        document.getElementById('client-card-' + id)?.remove();
                    });
                    showToast('success', data.message);
                    updateBulkActions();
                }
            });
        }

        function bulkForceDelete() {
            const ids = getSelectedIds();
            if (ids.length === 0) return;
            if (!confirm('هل أنت متأكد من الحذف النهائي لـ ' + ids.length + ' عميل؟ لا يمكن التراجع!')) return;

            fetch(ROUTES.bulkForceDelete, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    ids.forEach(id => {
                        document.getElementById('client-row-' + id)?.remove();
                        document.getElementById('client-card-' + id)?.remove();
                    });
                    showToast('success', data.message);
                    setTimeout(() => location.reload(), 1000);
                }
            });
        }
    </script>


    <!-- PDF.js Library (if not already loaded) -->
    {{-- <script>
    // Only load PDF.js if not already loaded
    if (typeof pdfjsLib === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
        script.onload = function() {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        };
        document.head.appendChild(script);
    } else {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }

    /**
     * Preview first page of uploaded PDF for client upload modal
     */
    function previewClientPDF(input) {
        const file = input.files[0];
        if (!file || file.type !== 'application/pdf') {
            return;
        }

        const placeholder = document.getElementById('clientPdfPlaceholder');
        const canvas = document.getElementById('clientPdfCanvas');
        const loading = document.getElementById('clientPdfLoading');

        // Show loading
        placeholder.classList.add('d-none');
        canvas.style.display = 'none';
        loading.classList.remove('d-none');

        const fileReader = new FileReader();
        fileReader.onload = function() {
            const typedarray = new Uint8Array(this.result);

            // Wait for PDF.js to be loaded
            const renderPDF = function() {
                if (typeof pdfjsLib === 'undefined') {
                    setTimeout(renderPDF, 100);
                    return;
                }

                // Load PDF
                pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                    // Get first page
                    pdf.getPage(1).then(function(page) {
                        const viewport = page.getViewport({ scale: 1.5 });
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
                    placeholder.innerHTML = '<i class="mb-3 ti ti-alert-circle fs-1 d-block text-danger"></i><p class="mb-0 text-danger">خطأ في تحميل الملف</p>';
                });
            };

            renderPDF();
        };

        fileReader.readAsArrayBuffer(file);
    }

    // Reset preview when modal is closed
    document.getElementById('uploadFileModal').addEventListener('hidden.bs.modal', function() {
        const placeholder = document.getElementById('clientPdfPlaceholder');
        const canvas = document.getElementById('clientPdfCanvas');
        const loading = document.getElementById('clientPdfLoading');
        const fileInput = document.getElementById('clientPdfFileInput');

        canvas.style.display = 'none';
        loading.classList.add('d-none');
        placeholder.classList.remove('d-none');
        placeholder.innerHTML = '<i class="mb-3 ti ti-upload fs-1 d-block"></i><p class="mb-0">قم برفع ملف PDF لمعاينة الصفحة الأولى</p>';

        if (fileInput) {
            fileInput.value = '';
        }
    });
</script> --}}

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

    {{-- Bulk Barcode Print Modal --}}
    <div class="modal fade" id="bulkBarcodePrintModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="text-white modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="ti ti-printer me-2"></i>
                        <span id="barcodeModalTitle">طباعة باركودات الملفات</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="bulkBarcodeContent" style="max-height: 70vh; overflow-y: auto; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; padding: 20px;">
                <style>
                    #bulkBarcodeContent .sticker-preview {
                        width: 144px; /* ~38mm at 96dpi */
                        height: 95px; /* ~25mm at 96dpi */
                        border: 1px dashed #ccc;
                        border-radius: 4px;
                        background: #fff;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        padding: 4px;
                        overflow: hidden;
                    }
                    #bulkBarcodeContent .barcode-area {
                        text-align: center;
                        width: 100%;
                    }
                    #bulkBarcodeContent .barcode-area svg {
                        max-width: 136px;
                        height: auto;
                        max-height: 60px;
                    }
                    #bulkBarcodeContent .info-line {
                        font-size: 7px;
                        text-align: center;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        max-width: 136px;
                        margin-top: 1px;
                        color: #333;
                        line-height: 1.1;
                    }
                    #bulkBarcodeContent .info-line.client-name {
                        font-weight: bold;
                        color: #000;
                    }
                    #bulkBarcodeContent .no-barcode {
                        font-size: 10px;
                        color: #721c24;
                        background: #f8d7da;
                        padding: 8px;
                        border-radius: 4px;
                    }
                </style>
                    <div class="py-5 text-center">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                        <p class="mt-3 text-muted fs-5">جاري تحميل بيانات الباركودات...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> إغلاق
                    </button>
                    <button type="button" class="btn btn-primary" onclick="printBarcodeModal()">
                        <i class="ti ti-printer me-1"></i> طباعة
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                    <div class="py-5 text-center">
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

        #showFileModal+.modal-backdrop {
            z-index: 1059 !important;
        }

        #viewClientModal {
            z-index: 1055;
        }

        #viewClientModal+.modal-backdrop {
            z-index: 1054;
        }
    </style>
    @include('dashboards.shared.scripts')
</body>

</html>
