<!DOCTYPE html>
@include('dashboards.shared.html')

<head>
    @include('dashboards.shared.meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة الأراضي - نظام أرشيف القاهرة الجديدة</title>
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
                                            <i class="ti ti-map-2 me-1"></i> إدارة الأراضي
                                        </span>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb mb-0 mt-1">
                                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                                                <li class="breadcrumb-item active">الأراضي</li>
                                            </ol>
                                        </nav>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-2 mt-lg-0">
                                    @can('lands.create')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLandModal">
                                        <i class="ti ti-plus me-1"></i> إضافة أرض
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
                                    <div class="avatar avatar-md bg-success-subtle text-success rounded me-3">
                                        <i class="ti ti-files fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\File::whereNotNull('land_id')->count() }}</h4>
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
                                        <h4 class="mb-0">{{ \App\Models\Land::whereDate('created_at', today())->count() }}</h4>
                                        <small class="text-muted">أراضي اليوم</small>
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
                                        <h4 class="mb-0">{{ \App\Models\Land::onlyTrashed()->count() }}</h4>
                                        <small class="text-muted">المحذوفات</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card" style="border-radius: var(--ins-border-radius);">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.lands.index') }}">
                            <div class="row d-flex align-items-end justify-content-start">
                                <div class="col-md-3 mb-2">
                                    <label class="form-label fw-semibold">بحث</label>
                                    <div class="input-group shadow-sm border border-secondary border-opacity-10 overflow-hidden bg-body"
                                        style="border-radius: var(--ins-border-radius);">
                                        <input type="text" name="search" class="form-control border-0 bg-transparent"
                                            placeholder="رقم الأرض، العنوان..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label fw-semibold">العميل</label>
                                    <select name="client_id" class="form-select">
                                        <option value="">الكل</option>
                                        @foreach($clients ?? [] as $client)
                                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label fw-semibold">المحافظة</label>
                                    <select name="governorate_id" class="form-select" onchange="loadCities(this.value)">
                                        <option value="">الكل</option>
                                        @foreach($governorates ?? [] as $gov)
                                            <option value="{{ $gov->id }}" {{ request('governorate_id') == $gov->id ? 'selected' : '' }}>{{ $gov->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label fw-semibold">المدينة</label>
                                    <select name="city_id" id="citySelect" class="form-select">
                                        <option value="">الكل</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 d-flex align-items-center gap-2">
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="submit" class="btn btn-primary shadow-sm px-3" style="border-radius: var(--ins-border-radius);">
                                            <i class="ti ti-filter me-1"></i> فلترة
                                        </button>
                                        <a href="{{ route('admin.lands.index') }}" style="border-radius: var(--ins-border-radius);"
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
                                    <h5 class="card-title mb-0">الأراضي</h5>
                                    <span class="badge bg-primary-subtle text-primary">{{ $lands->total() ?? 0 }} أرض</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Bulk Actions -->
                                    <div class="bulk-actions d-none me-2" id="bulkActions">
                                        @if(request('trashed') != 'only')
                                            @can('lands.delete')
                                                <button type="button" class="btn btn-soft-danger btn-sm" onclick="bulkDelete()">
                                                    <i class="ti ti-trash me-1"></i> حذف المحدد
                                                </button>
                                            @endcan
                                        @else
                                            @can('lands.delete')
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
                                                <th>رقم الأرض</th>
                                                <th>رقم الوحدة</th>
                                                <th>المحافظة</th>
                                                <th>المدينة</th>
                                                <th>الملفات</th>
                                                <th width="150" class="text-center">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($lands ?? [] as $land)
                                                <tr id="land-row-{{ $land->id }}">
                                                    <td><input type="checkbox" class="form-check-input row-checkbox" value="{{ $land->id }}"></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                                {{ mb_substr($land->client->name ?? '-', 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $land->client->name ?? '-' }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-info-subtle text-info">{{ $land->land_no ?? '-' }}</span></td>
                                                    <td>{{ $land->unit_no ?? '-' }}</td>
                                                    <td>{{ $land->governorate->name ?? '-' }}</td>
                                                    <td>{{ $land->city->name ?? '-' }}</td>
                                                    <td><span class="badge bg-success-subtle text-success">{{ $land->files_count ?? 0 }}</span></td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-1">
                                                            @if(request('trashed') != 'only')
                                                                <button class="btn btn-soft-info btn-sm" onclick="showLand({{ $land->id }})" title="عرض">
                                                                    <i class="ti ti-eye"></i>
                                                                </button>
                                                                @can('lands.edit')
                                                                    <button class="btn btn-soft-warning btn-sm" onclick="editLand({{ $land->id }})" title="تعديل">
                                                                        <i class="ti ti-edit"></i>
                                                                    </button>
                                                                @endcan
                                                                @can('lands.delete')
                                                                    <button class="btn btn-soft-danger btn-sm" onclick="deleteLand({{ $land->id }}, '{{ $land->land_no }}')" title="حذف">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                @endcan
                                                            @else
                                                                <button class="btn btn-soft-success btn-sm" onclick="restoreLand({{ $land->id }})" title="استرجاع">
                                                                    <i class="ti ti-refresh"></i>
                                                                </button>
                                                                <button class="btn btn-danger btn-sm" onclick="forceDeleteLand({{ $land->id }}, '{{ $land->land_no }}')" title="حذف نهائي">
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
                                                            <i class="ti ti-map-off fs-1 d-block mb-2"></i>
                                                            لا توجد أراضي
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
                                        @forelse($lands ?? [] as $land)
                                            <div class="col-md-4 col-lg-3" id="land-card-{{ $land->id }}">
                                                <div class="card border shadow-sm h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="avatar avatar-md bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                                <i class="ti ti-map-2"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0">{{ $land->land_no ?? '-' }}</h6>
                                                                <small class="text-muted">{{ $land->client->name ?? '-' }}</small>
                                                            </div>
                                                            <input type="checkbox" class="form-check-input row-checkbox" value="{{ $land->id }}">
                                                        </div>
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block"><i class="ti ti-building me-1"></i> {{ $land->governorate->name ?? '-' }}</small>
                                                            <small class="text-muted d-block"><i class="ti ti-map-pin me-1"></i> {{ $land->city->name ?? '-' }}</small>
                                                        </div>
                                                        <div class="d-flex gap-2 mb-3">
                                                            <span class="badge bg-info-subtle text-info"><i class="ti ti-hash me-1"></i>{{ $land->unit_no ?? '-' }}</span>
                                                            <span class="badge bg-success-subtle text-success"><i class="ti ti-files me-1"></i>{{ $land->files_count ?? 0 }} ملف</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top-0 pt-0">
                                                        <div class="d-flex justify-content-between">
                                                            @if(request('trashed') != 'only')
                                                                <button class="btn btn-soft-info btn-sm" onclick="showLand({{ $land->id }})"><i class="ti ti-eye"></i></button>
                                                                <button class="btn btn-soft-warning btn-sm" onclick="editLand({{ $land->id }})"><i class="ti ti-edit"></i></button>
                                                                <button class="btn btn-soft-danger btn-sm" onclick="deleteLand({{ $land->id }}, '{{ $land->land_no }}')"><i class="ti ti-trash"></i></button>
                                                            @else
                                                                <button class="btn btn-soft-success btn-sm" onclick="restoreLand({{ $land->id }})"><i class="ti ti-refresh"></i> استرجاع</button>
                                                                <button class="btn btn-danger btn-sm" onclick="forceDeleteLand({{ $land->id }}, '{{ $land->land_no }}')"><i class="ti ti-trash-x"></i> حذف نهائي</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ti ti-map-off fs-1 d-block mb-2"></i>
                                                    لا توجد أراضي
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Pagination -->
                                @if(isset($lands) && $lands->hasPages())
                                    <div class="d-flex justify-content-center p-3">
                                        {{ $lands->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Land Modal -->
    <div class="modal fade" id="createLandModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة أرض جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createLandForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">العميل <span class="text-danger">*</span></label>
                                <select name="client_id" class="form-select" required>
                                    <option value="">اختر العميل</option>
                                    @foreach($clients ?? [] as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->client_code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">رقم الأرض</label>
                                <input type="text" name="land_no" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">رقم الوحدة</label>
                                <input type="text" name="unit_no" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">المحافظة <span class="text-danger">*</span></label>
                                <select name="governorate_id" class="form-select" required onchange="loadModalCities(this.value)">
                                    <option value="">اختر المحافظة</option>
                                    @foreach($governorates ?? [] as $gov)
                                        <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">المدينة</label>
                                <select name="city_id" id="modalCitySelect" class="form-select" onchange="loadModalDistricts(this.value)">
                                    <option value="">اختر المدينة</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الحي</label>
                                <select name="district_id" id="modalDistrictSelect" class="form-select" onchange="loadModalZones(this.value)">
                                    <option value="">اختر الحي</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">المنطقة</label>
                                <select name="zone_id" id="modalZoneSelect" class="form-select" onchange="loadModalAreas(this.value)">
                                    <option value="">اختر المنطقة</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">القطاع</label>
                                <select name="area_id" id="modalAreaSelect" class="form-select">
                                    <option value="">اختر القطاع</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">العنوان</label>
                                <textarea name="address" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">ملاحظات</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
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

    @include('dashboards.admin.pages.lands.partials.show-modal')
    @include('dashboards.admin.pages.lands.partials.edit-modal')
    @include('dashboards.admin.pages.lands.partials.delete-modal')

    @include('dashboards.shared.scripts')

    <script>
    function loadCities(governorateId) {
        const select = document.getElementById('citySelect');
        select.innerHTML = '<option value="">جاري التحميل...</option>';
        if (!governorateId) {
            select.innerHTML = '<option value="">الكل</option>';
            return;
        }
        fetch(`{{ url('admin/geographic-areas/cities/by-governorate') }}/${governorateId}`)
            .then(res => res.json())
            .then(data => {
                select.innerHTML = '<option value="">الكل</option>';
                data.forEach(city => {
                    select.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                });
            });
    }

    function loadModalCities(governorateId) {
        const citySelect = document.getElementById('modalCitySelect');
        const districtSelect = document.getElementById('modalDistrictSelect');
        const zoneSelect = document.getElementById('modalZoneSelect');
        const areaSelect = document.getElementById('modalAreaSelect');

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

    function loadModalDistricts(cityId) {
        const districtSelect = document.getElementById('modalDistrictSelect');
        const zoneSelect = document.getElementById('modalZoneSelect');
        const areaSelect = document.getElementById('modalAreaSelect');

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

    function loadModalZones(districtId) {
        const zoneSelect = document.getElementById('modalZoneSelect');
        const areaSelect = document.getElementById('modalAreaSelect');

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

    function loadModalAreas(zoneId) {
        const areaSelect = document.getElementById('modalAreaSelect');

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

    document.getElementById('createLandForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحفظ...';

        fetch('{{ route("admin.lands.store") }}', {
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
                bootstrap.Modal.getInstance(document.getElementById('createLandModal')).hide();
                location.reload();
            } else {
                alert(data.error || 'حدث خطأ');
                btn.disabled = false;
                btn.innerHTML = 'حفظ';
            }
        })
        .catch(err => {
            console.error(err);
            alert('حدث خطأ في الاتصال');
            btn.disabled = false;
            btn.innerHTML = 'حفظ';
        });
    });

    let currentLandId = null;

    function showLand(id) {
        currentLandId = id;
        fetch(`{{ url('admin/lands') }}/${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.land) {
                    const land = data.land;
                    document.getElementById('showLandClient').textContent = land.client?.name || '-';
                    document.getElementById('showLandNo').textContent = land.land_no || '-';
                    document.getElementById('showUnitNo').textContent = land.unit_no || '-';
                    document.getElementById('showGovernorate').textContent = land.governorate?.name || '-';
                    document.getElementById('showCity').textContent = land.city?.name || '-';
                    document.getElementById('showDistrict').textContent = land.district?.name || '-';
                    document.getElementById('showZone').textContent = land.zone?.name || '-';
                    document.getElementById('showArea').textContent = land.area?.name || '-';
                    document.getElementById('showAddress').textContent = land.address || '-';
                    document.getElementById('showNotes').textContent = land.notes || '-';
                    document.getElementById('showCreatedAt').textContent = new Date(land.created_at).toLocaleString('ar-EG');
                    document.getElementById('showUpdatedAt').textContent = new Date(land.updated_at).toLocaleString('ar-EG');

                    new bootstrap.Modal(document.getElementById('showLandModal')).show();
                } else {
                    alert('حدث خطأ في تحميل البيانات');
                }
            })
            .catch(err => {
                console.error(err);
                alert('حدث خطأ في الاتصال');
            });
    }

    function openEditFromShow() {
        bootstrap.Modal.getInstance(document.getElementById('showLandModal')).hide();
        editLand(currentLandId);
    }

    function editLand(id) {
        currentLandId = id;
        fetch(`{{ url('admin/lands') }}/${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.land) {
                    const land = data.land;
                    document.getElementById('editLandId').value = land.id;
                    document.getElementById('editClientId').value = land.client_id || '';
                    document.getElementById('editLandNo').value = land.land_no || '';
                    document.getElementById('editUnitNo').value = land.unit_no || '';
                    document.getElementById('editAddress').value = land.address || '';
                    document.getElementById('editNotes').value = land.notes || '';

                    // Set governorate and load cities
                    document.getElementById('editGovernorateId').value = land.governorate_id || '';
                    if (land.governorate_id) {
                        loadEditCities(land.governorate_id, land.city_id);
                    }

                    new bootstrap.Modal(document.getElementById('editLandModal')).show();
                } else {
                    alert('حدث خطأ في تحميل البيانات');
                }
            })
            .catch(err => {
                console.error(err);
                alert('حدث خطأ في الاتصال');
            });
    }

    function loadEditCities(governorateId, selectedCityId = null) {
        const citySelect = document.getElementById('editCityId');
        const districtSelect = document.getElementById('editDistrictId');
        const zoneSelect = document.getElementById('editZoneId');
        const areaSelect = document.getElementById('editAreaId');

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
                    const selected = selectedCityId && city.id == selectedCityId ? 'selected' : '';
                    citySelect.innerHTML += `<option value="${city.id}" ${selected}>${city.name}</option>`;
                });

                if (selectedCityId) {
                    // Trigger loading districts
                    fetch(`{{ url('admin/lands') }}/${currentLandId}`)
                        .then(res => res.json())
                        .then(landData => {
                            if (landData.success && landData.land.district_id) {
                                loadEditDistricts(selectedCityId, landData.land.district_id);
                            }
                        });
                }
            });
    }

    function loadEditDistricts(cityId, selectedDistrictId = null) {
        const districtSelect = document.getElementById('editDistrictId');
        const zoneSelect = document.getElementById('editZoneId');
        const areaSelect = document.getElementById('editAreaId');

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
                    const selected = selectedDistrictId && district.id == selectedDistrictId ? 'selected' : '';
                    districtSelect.innerHTML += `<option value="${district.id}" ${selected}>${district.name}</option>`;
                });

                if (selectedDistrictId) {
                    fetch(`{{ url('admin/lands') }}/${currentLandId}`)
                        .then(res => res.json())
                        .then(landData => {
                            if (landData.success && landData.land.zone_id) {
                                loadEditZones(selectedDistrictId, landData.land.zone_id);
                            }
                        });
                }
            });
    }

    function loadEditZones(districtId, selectedZoneId = null) {
        const zoneSelect = document.getElementById('editZoneId');
        const areaSelect = document.getElementById('editAreaId');

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
                    const selected = selectedZoneId && zone.id == selectedZoneId ? 'selected' : '';
                    zoneSelect.innerHTML += `<option value="${zone.id}" ${selected}>${zone.name}</option>`;
                });

                if (selectedZoneId) {
                    fetch(`{{ url('admin/lands') }}/${currentLandId}`)
                        .then(res => res.json())
                        .then(landData => {
                            if (landData.success && landData.land.area_id) {
                                loadEditAreas(selectedZoneId, landData.land.area_id);
                            }
                        });
                }
            });
    }

    function loadEditAreas(zoneId, selectedAreaId = null) {
        const areaSelect = document.getElementById('editAreaId');

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
                    const selected = selectedAreaId && area.id == selectedAreaId ? 'selected' : '';
                    areaSelect.innerHTML += `<option value="${area.id}" ${selected}>${area.name}</option>`;
                });
            });
    }

    // Edit form submission
    document.getElementById('editLandForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const landId = document.getElementById('editLandId').value;
        const btn = this.querySelector('button[type="submit"]');
        const originalBtnText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحفظ...';

        fetch(`{{ url('admin/lands') }}/${landId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(err => Promise.reject(err));
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('editLandModal')).hide();
                location.reload();
            } else {
                alert(data.error || data.message || 'حدث خطأ');
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
            }
        })
        .catch(err => {
            console.error('Error updating land:', err);
            let errorMsg = 'حدث خطأ أثناء حفظ البيانات';
            if (err.message) {
                errorMsg += ': ' + err.message;
            }
            if (err.errors) {
                errorMsg += '\n' + Object.values(err.errors).flat().join('\n');
            }
            alert(errorMsg);
            btn.disabled = false;
            btn.innerHTML = originalBtnText;
        });
    });

    function deleteLand(id, landNo) {
        console.log('deleteLand called', id, landNo);
        const modalElement = document.getElementById('deleteLandModal');
        console.log('Modal element:', modalElement);

        if (!modalElement) {
            alert('خطأ: لم يتم العثور على نافذة الحذف');
            return;
        }

        document.getElementById('deleteLandId').value = id;
        document.getElementById('deleteLandNo').textContent = landNo;

        try {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } catch (error) {
            console.error('Error showing modal:', error);
            alert('خطأ في فتح نافذة الحذف: ' + error.message);
        }
    }

    function confirmLandDelete() {
        const landId = document.getElementById('deleteLandId').value;
        const deleteBtn = event.target;
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحذف...';

        fetch(`{{ url('admin/lands') }}/${landId}/delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('deleteLandModal')).hide();
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
        localStorage.setItem('landsView', view);
    }

    // Initialize view from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('landsView') || 'list';
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
        if (!confirm(`هل أنت متأكد من حذف ${ids.length} أرض؟`)) return;

        fetch('{{ route("admin.lands.bulk-delete") }}', {
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

        fetch('{{ route("admin.lands.bulk-restore") }}', {
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
        if (!confirm(`هل أنت متأكد من الحذف النهائي لـ ${ids.length} أرض؟ لا يمكن التراجع عن هذا الإجراء.`)) return;

        fetch('{{ route("admin.lands.bulk-force-delete") }}', {
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

    // Restore Land
    function restoreLand(id) {
        fetch(`{{ url('admin/lands') }}/${id}/restore`, {
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

    // Force Delete Land
    function forceDeleteLand(id, landNo) {
        if (!confirm(`هل أنت متأكد من الحذف النهائي للأرض "${landNo}"؟ لا يمكن التراجع عن هذا الإجراء.`)) return;

        fetch(`{{ url('admin/lands') }}/${id}/force-delete`, {
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
