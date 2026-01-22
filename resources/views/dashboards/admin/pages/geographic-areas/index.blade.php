<!DOCTYPE html>
@include('dashboards.shared.html')

<head>
    @include('dashboards.shared.meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>المناطق الجغرافية - نظام أرشيف القاهرة الجديدة</title>
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
                                        <i class="ti ti-map-pin me-1"></i> المناطق الجغرافية
                                    </span>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0 mt-1">
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة
                                                    التحكم</a></li>
                                            <li class="breadcrumb-item active">المناطق الجغرافية</li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="d-flex gap-2 mt-2 mt-lg-0">
                                    @can('geographic-areas.manage')
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#createGovernorateModal">
                                            <i class="ti ti-plus me-1"></i> إضافة محافظة
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-3 ">
                    <div class="col-md-2">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary-subtle text-primary rounded">
                                        <i class="ti ti-building fs-4"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-0">{{ \App\Models\Governorate::count() }}</h4>
                                        <small class="text-muted">المحافظات</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-success-subtle text-success rounded">
                                        <i class="ti ti-building-community fs-4"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-0">{{ \App\Models\City::count() }}</h4>
                                        <small class="text-muted">المدن</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-info-subtle text-info rounded">
                                        <i class="ti ti-map-2 fs-4"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-0">{{ \App\Models\District::count() }}</h4>
                                        <small class="text-muted">الأحياء</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-warning-subtle text-warning rounded">
                                        <i class="ti ti-map-pin fs-4"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-0">{{ \App\Models\Zone::count() }}</h4>
                                        <small class="text-muted">المناطق</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                       <div class="col-md-2">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-danger-subtle text-danger rounded">
                                        <i class="ti ti-location fs-4"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="mb-0">{{ \App\Models\Area::count() }}</h4>
                                        <small class="text-muted">المجاورات</small>
                                    </div>
                                </div>
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
                                    <h5 class="card-title mb-0">المحافظات والمدن</h5>
                                    <span class="badge bg-primary-subtle text-primary">{{ count($governorates ?? []) }} محافظة</span>
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
                                                <th>#</th>
                                                <th>المحافظة</th>
                                                <th>عدد المدن</th>
                                                <th>عدد الأحياء</th>
                                                <th>عدد المناطق</th>
                                                <th>عدد المجاورات</th>
                                                <th width="180" class="text-center">الإجراءات</th>
                                            </tr>
                                        </thead>
                                <tbody>
                                    @forelse($governorates ?? [] as $governorate)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="fw-medium">{{ $governorate->name }}</span>

                                            </td>
                                            <td><span class="badge bg-success">{{ $governorate->cities_count }}</span>
                                            </td>
                                            <td><span class="badge bg-info">{{ $governorate->districts_total }}</span>
                                            </td>
                                            <td><span class="badge bg-warning">{{ $governorate->zones_total }}</span>
                                            </td>
                                            <td><span class="badge bg-danger">{{ $governorate->areas_total }}</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <button class="btn btn-soft-info btn-sm"
                                                        onclick="showGovernorate({{ $governorate->id }})"
                                                        title="عرض المدن">
                                                        <i class="ti ti-eye"></i>
                                                    </button>
                                                    @can('geographic-areas.manage')
                                                        <button class="btn btn-soft-success btn-sm"
                                                            onclick="addCity({{ $governorate->id }}, '{{ $governorate->name }}')"
                                                            title="إضافة مدينة">
                                                            <i class="ti ti-plus"></i>
                                                        </button>
                                                        <button class="btn btn-soft-warning btn-sm"
                                                            onclick="editGovernorate({{ $governorate->id }}, '{{ $governorate->name }}')"
                                                            title="تعديل">
                                                            <i class="ti ti-edit"></i>
                                                        </button>
                                                        <button class="btn btn-soft-danger btn-sm"
                                                            onclick="deleteGovernorate({{ $governorate->id }})" title="حذف">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ti ti-map-off fs-1 d-block mb-2"></i>
                                                    لا توجد محافظات
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
                                        @forelse($governorates ?? [] as $governorate)
                                            <div class="col-md-4 col-lg-3">
                                                <div class="card border shadow-sm h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="avatar avatar-md bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                                <i class="ti ti-building"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0">{{ $governorate->name }}</h6>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                                            <span class="badge bg-success-subtle text-success"><i class="ti ti-building-community me-1"></i>{{ $governorate->cities_count }} مدينة</span>
                                                            <span class="badge bg-info-subtle text-info"><i class="ti ti-map-2 me-1"></i>{{ $governorate->districts_total }} حي</span>
                                                            <span class="badge bg-warning-subtle text-warning"><i class="ti ti-map-pin me-1"></i>{{ $governorate->zones_total }} منطقة</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top-0 pt-0">
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-soft-info btn-sm" onclick="showGovernorate({{ $governorate->id }})"><i class="ti ti-eye"></i></button>
                                                            @can('geographic-areas.manage')
                                                                <button class="btn btn-soft-success btn-sm" onclick="addCity({{ $governorate->id }}, '{{ $governorate->name }}')"><i class="ti ti-plus"></i></button>
                                                                <button class="btn btn-soft-warning btn-sm" onclick="editGovernorate({{ $governorate->id }}, '{{ $governorate->name }}')"><i class="ti ti-edit"></i></button>
                                                                <button class="btn btn-soft-danger btn-sm" onclick="deleteGovernorate({{ $governorate->id }})"><i class="ti ti-trash"></i></button>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ti ti-map-off fs-1 d-block mb-2"></i>
                                                    لا توجد محافظات
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Governorate Modal -->
    <div class="modal fade" id="createGovernorateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة محافظة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createGovernorateForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم المحافظة <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
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

    <!-- Add City Modal -->
    <div class="modal fade" id="addCityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة مدينة - <span id="govNameForCity"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addCityForm">
                    @csrf
                    <input type="hidden" name="governorate_id" id="cityGovernorateId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم المدينة <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
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

    <!-- Edit Governorate Modal -->
    <div class="modal fade" id="editGovernorateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل المحافظة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editGovernorateForm">
                    @csrf
                    <input type="hidden" name="id" id="editGovId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم المحافظة <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editGovName" class="form-control" required>
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

    <!-- Show Governorate Modal (Cities List) -->
    <div class="modal fade" id="showGovernorateModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-primary-subtle text-primary rounded me-2">
                            <i class="ti ti-map-pin"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-semibold" id="showGovName"></h5>
                            <small class="text-muted">التقسيم الجغرافي</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="refreshGovernorateData()" title="تحديث">
                            <i class="ti ti-refresh"></i>
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body p-0" style="max-height: 70vh; overflow-y: auto;">
                    <div id="showGovBody">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary"></div>
                            <p class="mt-3 text-muted">جاري التحميل...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="currentGovernorateId">

    <!-- Edit City Modal -->
    <div class="modal fade" id="editCityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل المدينة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCityForm">
                    @csrf
                    <input type="hidden" id="editCityId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم المدينة <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editCityName" class="form-control" required>
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

    <!-- Add District Modal -->
    <div class="modal fade" id="addDistrictModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة حي - <span id="districtCityName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addDistrictForm">
                    @csrf
                    <input type="hidden" name="city_id" id="districtCityId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم الحي <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
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

    <!-- Edit District Modal -->
    <div class="modal fade" id="editDistrictModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل الحي</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editDistrictForm">
                    @csrf
                    <input type="hidden" id="editDistrictId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم الحي <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editDistrictName" class="form-control"
                                required>
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

    <!-- Add Zone Modal -->
    <div class="modal fade" id="addZoneModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة منطقة - <span id="zoneDistrictName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addZoneForm">
                    @csrf
                    <input type="hidden" name="district_id" id="zoneDistrictId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم المنطقة <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
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

    <!-- Edit Zone Modal -->
    <div class="modal fade" id="editZoneModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل المنطقة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editZoneForm">
                    @csrf
                    <input type="hidden" id="editZoneId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم المنطقة <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editZoneName" class="form-control" required>
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

    <!-- Add Area Modal -->
    <div class="modal fade" id="addAreaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة مجاورة - <span id="areaZoneName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addAreaForm">
                    @csrf
                    <input type="hidden" name="zone_id" id="areaZoneId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم المجاورة <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
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

    <!-- Edit Area Modal -->
    <div class="modal fade" id="editAreaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل المجاورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editAreaForm">
                    @csrf
                    <input type="hidden" id="editAreaId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم المجاورة <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editAreaName" class="form-control" required>
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

    @include('dashboards.admin.pages.geographic-areas.partials.delete-modal')

    @include('dashboards.shared.modal-styles')
    @include('dashboards.shared.scripts')

    <script>
        const ROUTES = {
            storeGovernorate: '{{ route('admin.geographic-areas.governorates.store') }}',
            updateGovernorate: '{{ url('admin/geographic-areas/governorates') }}',
            deleteGovernorate: '{{ url('admin/geographic-areas/governorates') }}',
            showGovernorate: '{{ url('admin/geographic-areas/governorates') }}',
            storeCity: '{{ route('admin.geographic-areas.cities.store') }}',
            updateCity: '{{ url('admin/geographic-areas/cities') }}',
            deleteCity: '{{ url('admin/geographic-areas/cities') }}',
            storeDistrict: '{{ route('admin.geographic-areas.districts.store') }}',
            updateDistrict: '{{ url('admin/geographic-areas/districts') }}',
            deleteDistrict: '{{ url('admin/geographic-areas/districts') }}',
            showDistrict: '{{ url('admin/geographic-areas/districts') }}',
            storeZone: '{{ route('admin.geographic-areas.zones.store') }}',
            updateZone: '{{ url('admin/geographic-areas/zones') }}',
            deleteZone: '{{ url('admin/geographic-areas/zones') }}',
            showZone: '{{ url('admin/geographic-areas/zones') }}',
            storeArea: '{{ route('admin.geographic-areas.areas.store') }}',
            updateArea: '{{ url('admin/geographic-areas/areas') }}',
            deleteArea: '{{ url('admin/geographic-areas/areas') }}',
            showArea: '{{ url('admin/geographic-areas/areas') }}',
        };

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Create Governorate
        document.getElementById('createGovernorateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch(ROUTES.storeGovernorate, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('createGovernorateModal')).hide();
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

        // Edit Governorate
        function editGovernorate(id, name) {
            document.getElementById('editGovId').value = id;
            document.getElementById('editGovName').value = name;
            new bootstrap.Modal(document.getElementById('editGovernorateModal')).show();
        }

        document.getElementById('editGovernorateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('editGovId').value;
            const formData = new FormData(this);

            fetch(`${ROUTES.updateGovernorate}/${id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('editGovernorateModal')).hide();
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

        // Delete Governorate
        function deleteGovernorate(id) {
            const row = event.target.closest('tr');
            const name = row.querySelector('td:nth-child(2) .fw-medium').textContent;
            document.getElementById('deleteGovId').value = id;
            document.getElementById('deleteGovName').textContent = name;
            new bootstrap.Modal(document.getElementById('deleteGovernorateModal')).show();
        }

        function confirmDeleteGovernorate() {
            const id = document.getElementById('deleteGovId').value;
            fetch(`${ROUTES.deleteGovernorate}/${id}/delete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('deleteGovernorateModal')).hide();
                        location.reload();
                    } else {
                        alert(data.error || 'حدث خطأ');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('حدث خطأ في الاتصال');
                });
        }

        // Show Governorate (Cities)
        function showGovernorate(id) {
            const row = event.target.closest('tr');
            const name = row.querySelector('td:nth-child(2) .fw-medium').textContent;
            document.getElementById('showGovName').textContent = name;
            document.getElementById('currentGovernorateId').value = id;
            document.getElementById('showGovBody').innerHTML =
                '<div class="text-center py-5"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div><p class="mt-3 text-muted">جاري تحميل البيانات...</p></div>';

            new bootstrap.Modal(document.getElementById('showGovernorateModal')).show();
            loadGovernorateData(id);
        }

        // Load governorate data (reusable for refresh)
        function loadGovernorateData(id) {
            fetch(`${ROUTES.showGovernorate}/${id}/show`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        renderCitiesList(data.governorate);
                        showSuccessToast('تم تحديث البيانات');
                    } else {
                        document.getElementById('showGovBody').innerHTML =
                            '<div class="alert alert-danger m-3">حدث خطأ أثناء التحميل</div>';
                    }
                })
                .catch(err => {
                    console.error('Error loading governorate:', err);
                    document.getElementById('showGovBody').innerHTML =
                        '<div class="alert alert-danger m-3">حدث خطأ في الاتصال</div>';
                });
        }

        // Refresh current governorate data without closing modal
        function refreshGovernorateData() {
            const id = document.getElementById('currentGovernorateId').value;
            if (id) {
                document.getElementById('showGovBody').innerHTML =
                    '<div class="text-center py-5"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div><p class="mt-3 text-muted">جاري تحديث البيانات...</p></div>';
                loadGovernorateData(id);
            }
        }

        // Toast notification helper
        function showSuccessToast(message) {
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 p-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
            <div class="toast show align-items-center text-white bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="ti ti-check me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        function showErrorToast(message) {
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 p-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
            <div class="toast show align-items-center text-white bg-danger border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="ti ti-x me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }

        function renderCitiesList(gov) {
            const cities = gov.cities || [];
            if (cities.length === 0) {
                document.getElementById('showGovBody').innerHTML = `
                <div class="text-center py-5">
                    <div class="avatar avatar-lg bg-dark-subtle text-dark rounded-circle mx-auto mb-3">
                        <i class="ti ti-building-community fs-2"></i>
                    </div>
                    <h6 class="text-muted mb-3">لا توجد مدن مضافة</h6>
                    <button class="btn btn-dark" onclick="addCity(${gov.id}, '${gov.name}')">
                        <i class="ti ti-plus me-1"></i>إضافة مدينة
                    </button>
                </div>`;
                return;
            }

            // Stats summary
            let totalDistricts = 0, totalZones = 0, totalAreas = 0;
            cities.forEach(c => {
                const districts = c.districts || [];
                totalDistricts += districts.length;
                districts.forEach(d => {
                    const zones = d.zones || [];
                    totalZones += zones.length;
                    zones.forEach(z => { totalAreas += (z.areas || []).length; });
                });
            });

            let html = `
            <div class="bg-light border-bottom p-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="d-flex flex-wrap gap-2">
                        <div class="bg-white rounded px-3 py-2 shadow-sm">
                            <span class="fs-5 fw-bold text-dark">${cities.length}</span>
                            <span class="text-muted small ms-1">مدينة</span>
                        </div>
                        <div class="bg-white rounded px-3 py-2 shadow-sm">
                            <span class="fs-5 fw-bold text-dark">${totalDistricts}</span>
                            <span class="text-muted small ms-1">حي</span>
                        </div>
                        <div class="bg-white rounded px-3 py-2 shadow-sm">
                            <span class="fs-5 fw-bold text-dark">${totalZones}</span>
                            <span class="text-muted small ms-1">منطقة</span>
                        </div>
                        <div class="bg-white rounded px-3 py-2 shadow-sm">
                            <span class="fs-5 fw-bold text-warning">${totalAreas}</span>
                            <span class="text-muted small ms-1">مجاورة</span>
                        </div>
                    </div>
                    <button class="btn btn-dark" onclick="addCity(${gov.id}, '${gov.name}')">
                        <i class="ti ti-plus me-1"></i>إضافة مدينة
                    </button>
                </div>
            </div>
            <div class="p-3">`;

            cities.forEach((city, idx) => {
                const districts = city.districts || [];
                html += `
                <div class="card mb-2 border shadow-sm">
                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#city${city.id}">
                            <i class="ti ti-chevron-down me-2 text-primary"></i>
                            <i class="ti ti-building-community text-primary me-2 fs-5"></i>
                            <strong class="text-primary">${city.name}</strong>
                            <span class="badge bg-primary ms-2">${districts.length} حي</span>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-dark btn-sm" onclick="addDistrict(${city.id}, '${city.name}')" title="إضافة حي"><i class="ti ti-plus"></i></button>
                            <button class="btn btn-soft-warning btn-sm" onclick="editCity(${city.id}, '${city.name}')" title="تعديل"><i class="ti ti-edit"></i></button>
                            <button class="btn btn-danger btn-sm" onclick="deleteCity(${city.id}, '${city.name}')" title="حذف"><i class="ti ti-trash"></i></button>
                        </div>
                    </div>
                    <div class="collapse ${idx === 0 ? 'show' : ''}" id="city${city.id}">
                        <div class="card-body py-2">
                            ${districts.length > 0 ? districts.map(district => {
                                const zones = district.zones || [];
                                return `
                                <div class="card mb-2 border-0 bg-light">
                                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center border-0">
                                        <div class="d-flex align-items-center" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#district${district.id}">
                                            <i class="ti ti-chevron-down me-2 text-dark"></i>
                                            <i class="ti ti-map-2 text-dark me-2"></i>
                                            <span class="fw-medium text-dark">${district.name}</span>
                                            <span class="badge bg-success ms-2">${zones.length} منطقة</span>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-dark btn-sm" onclick="addZone(${district.id}, '${district.name}')" title="إضافة منطقة"><i class="ti ti-plus"></i></button>
                                            <button class="btn btn-soft-warning btn-sm" onclick="editDistrict(${district.id}, '${district.name}')" title="تعديل"><i class="ti ti-edit"></i></button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteDistrict(${district.id}, '${district.name}')" title="حذف"><i class="ti ti-trash"></i></button>
                                        </div>
                                    </div>
                                    <div class="collapse" id="district${district.id}">
                                        <div class="card-body py-2">
                                            ${zones.length > 0 ? zones.map(zone => {
                                                const areas = zone.areas || [];
                                                return `
                                                <div class="card mb-0 border-0 py-1" style="border-radius: 0">
                                                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center border-0" style="border-radius: 0">
                                                        <div class="d-flex align-items-center" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#zone${zone.id}">
                                                            <i class="ti ti-chevron-down me-2 text-dark"></i>
                                                            <i class="ti ti-map-pin text-dark me-2"></i>
                                                            <span class="fw-medium text-dark">${zone.name}</span>
                                                            <span class="badge bg-info ms-2">${areas.length} مجاورة</span>
                                                        </div>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-dark btn-sm" onclick="addArea(${zone.id}, '${zone.name}')" title="إضافة مجاورة"><i class="ti ti-plus"></i></button>
                                                            <button class="btn btn-soft-warning btn-sm" onclick="editZone(${zone.id}, '${zone.name}')" title="تعديل"><i class="ti ti-edit"></i></button>
                                                            <button class="btn btn-danger btn-sm" onclick="deleteZone(${zone.id}, '${zone.name}')" title="حذف"><i class="ti ti-trash"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="collapse" id="zone${zone.id}">
                                                        <div class="card-body py-2">
                                                            ${areas.length > 0 ? `
                                                                <div class="row g-2">
                                                                    ${areas.map(area => `
                                                                        <div class="col-md-6 col-lg-4">
                                                                            <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                                                                <div class="d-flex align-items-center">
                                                                                    <i class="ti ti-location text-dark me-2"></i>
                                                                                    <span class="fw-medium text-dark">${area.name}</span>
                                                                                </div>
                                                                                <div class="btn-group btn-group-sm">
                                                                                    <button class="btn btn-soft-warning btn-sm" onclick="editArea(${area.id}, '${area.name}')" title="تعديل"><i class="ti ti-edit"></i></button>
                                                                                    <button class="btn btn-danger btn-sm" onclick="deleteArea(${area.id}, '${area.name}')" title="حذف"><i class="ti ti-trash"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    `).join('')}
                                                                </div>
                                                            ` : '<p class="text-muted small mb-0">لا توجد مجاورةات</p>'}
                                                        </div>
                                                    </div>
                                                </div>`;
                                            }).join('') : '<p class="text-muted small mb-0">لا توجد مناطق</p>'}
                                        </div>
                                    </div>
                                </div>`;
                            }).join('') : '<p class="text-muted small mb-0">لا توجد أحياء</p>'}
                        </div>
                    </div>
                </div>`;
            });
            html += '</div>';
            document.getElementById('showGovBody').innerHTML = html;
        }

        // Add City
        function addCity(govId, govName) {
            document.getElementById('cityGovernorateId').value = govId;
            document.getElementById('govNameForCity').textContent = govName;
            new bootstrap.Modal(document.getElementById('addCityModal')).show();
        }

        document.getElementById('addCityForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch(ROUTES.storeCity, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('addCityModal')).hide();
                        refreshGovernorateData();
                        showSuccessToast('تم إضافة المدينة بنجاح');
                    } else {
                        showErrorToast(data.error || 'حدث خطأ');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showErrorToast('حدث خطأ في الاتصال');
                });
        });

        // Edit City
        function editCity(id, name) {
            document.getElementById('editCityId').value = id;
            document.getElementById('editCityName').value = name;
            new bootstrap.Modal(document.getElementById('editCityModal')).show();
        }

        document.getElementById('editCityForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('editCityId').value;
            const formData = new FormData(this);

            fetch(`${ROUTES.updateCity}/${id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('editCityModal')).hide();
                        refreshGovernorateData();
                        showSuccessToast('تم تحديث المدينة بنجاح');
                    } else {
                        showErrorToast(data.error || 'حدث خطأ');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showErrorToast('حدث خطأ في الاتصال');
                });
        });

        // Delete City
        function deleteCity(id, name) {
            document.getElementById('deleteCityId').value = id;
            document.getElementById('deleteCityName').textContent = name;
            new bootstrap.Modal(document.getElementById('deleteCityModal')).show();
        }

        function confirmDeleteCity() {
            const id = document.getElementById('deleteCityId').value;
            fetch(`${ROUTES.deleteCity}/${id}/delete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('deleteCityModal')).hide();
                        refreshGovernorateData();
                        showSuccessToast('تم حذف المدينة بنجاح');
                    } else {
                        showErrorToast(data.error || 'حدث خطأ');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showErrorToast('حدث خطأ في الاتصال');
                });
        }

        // Add District
        function addDistrict(cityId, cityName) {
            document.getElementById('districtCityId').value = cityId;
            document.getElementById('districtCityName').textContent = cityName;
            new bootstrap.Modal(document.getElementById('addDistrictModal')).show();
        }

        document.getElementById('addDistrictForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch(ROUTES.storeDistrict, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('addDistrictModal')).hide();
                        refreshGovernorateData();
                        showSuccessToast('تم إضافة الحي بنجاح');
                    } else {
                        showErrorToast(data.error || 'حدث خطأ');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showErrorToast('حدث خطأ في الاتصال');
                });
        });

        // Edit District
        function editDistrict(id, name) {
            document.getElementById('editDistrictId').value = id;
            document.getElementById('editDistrictName').value = name;
            new bootstrap.Modal(document.getElementById('editDistrictModal')).show();
        }

        document.getElementById('editDistrictForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('editDistrictId').value;
            const formData = new FormData(this);

            fetch(`${ROUTES.updateDistrict}/${id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('editDistrictModal')).hide();
                        refreshGovernorateData();
                        showSuccessToast('تم تحديث الحي بنجاح');
                    } else {
                        showErrorToast(data.error || 'حدث خطأ');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showErrorToast('حدث خطأ في الاتصال');
                });
        });

        // Delete District
        function deleteDistrict(id, name) {
            document.getElementById('deleteDistrictId').value = id;
            document.getElementById('deleteDistrictName').textContent = name;
            new bootstrap.Modal(document.getElementById('deleteDistrictModal')).show();
        }

        function confirmDeleteDistrict() {
            const id = document.getElementById('deleteDistrictId').value;

            fetch(`${ROUTES.deleteDistrict}/${id}/delete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('deleteDistrictModal')).hide();
                        refreshGovernorateData();
                        showSuccessToast('تم حذف الحي بنجاح');
                    } else {
                        showErrorToast(data.error || 'حدث خطأ');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showErrorToast('حدث خطأ في الاتصال');
                });
        }

        // Add Zone
        function addZone(districtId, districtName) {
            document.getElementById('zoneDistrictId').value = districtId;
            document.getElementById('zoneDistrictName').textContent = districtName;
            new bootstrap.Modal(document.getElementById('addZoneModal')).show();
        }

        document.getElementById('addZoneForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            console.log('Submitting zone form with data:', {
                district_id: formData.get('district_id'),
                name: formData.get('name')
            });

            fetch(ROUTES.storeZone, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    console.log('Response status:', res.status);
                    if (!res.ok) {
                        return res.json().then(errData => {
                            console.error('Error response:', errData);
                            throw new Error(errData.error || 'Network response was not ok');
                        });
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Success response:', data);
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('addZoneModal')).hide();
                        refreshGovernorateData();
                        showSuccessToast('تم إضافة المنطقة بنجاح');
                    } else {
                        showErrorToast(data.error || 'حدث خطأ');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showErrorToast('حدث خطأ: ' + err.message);
                });
        });

        // Edit Zone
        function editZone(id, name) {
            document.getElementById('editZoneId').value = id;
            document.getElementById('editZoneName').value = name;
            new bootstrap.Modal(document.getElementById('editZoneModal')).show();
        }

        document.getElementById('editZoneForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('editZoneId').value;
            const formData = new FormData(this);

            fetch(`${ROUTES.updateZone}/${id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('editZoneModal')).hide();
                        refreshGovernorateData();
                        showSuccessToast('تم تحديث المنطقة بنجاح');
                    } else {
                        showErrorToast(data.error || 'حدث خطأ');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showErrorToast('حدث خطأ في الاتصال');
                });
        });

        // Delete Zone
        function deleteZone(id, name) {
            document.getElementById('deleteZoneId').value = id;
            document.getElementById('deleteZoneName').textContent = name;
            new bootstrap.Modal(document.getElementById('deleteZoneModal')).show();
        }

        function confirmDeleteZone() {
            const id = document.getElementById('deleteZoneId').value;

            fetch(`${ROUTES.deleteZone}/${id}/delete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('deleteZoneModal')).hide();
                        refreshGovernorateData();
                        showSuccessToast('تم حذف المنطقة بنجاح');
                    } else {
                        showErrorToast(data.error || 'حدث خطأ');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showErrorToast('حدث خطأ في الاتصال');
                });
        }

        // Add Area
        function addArea(zoneId, zoneName) {
            document.getElementById('areaZoneId').value = zoneId;
            document.getElementById('areaZoneName').textContent = zoneName;
            new bootstrap.Modal(document.getElementById('addAreaModal')).show();
        }

        document.getElementById('addAreaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            console.log('Submitting area form with data:', {
                zone_id: formData.get('zone_id'),
                name: formData.get('name')
            });

            fetch(ROUTES.storeArea, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    console.log('Response status:', res.status);
                    if (!res.ok) {
                        return res.json().then(errData => {
                            console.error('Error response:', errData);
                            throw new Error(errData.error || 'Network response was not ok');
                        });
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Success response:', data);
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('addAreaModal')).hide();
                        refreshGovernorateData();
                        showSuccessToast('تم إضافة المجاورة بنجاح');
                    } else {
                        showErrorToast(data.error || 'حدث خطأ');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showErrorToast('حدث خطأ: ' + err.message);
                });
        });

        // Edit Area
        function editArea(id, name) {
            document.getElementById('editAreaId').value = id;
            document.getElementById('editAreaName').value = name;
            new bootstrap.Modal(document.getElementById('editAreaModal')).show();
        }

        document.getElementById('editAreaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('editAreaId').value;
            const formData = new FormData(this);

            fetch(`${ROUTES.updateArea}/${id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('editAreaModal')).hide();
                        refreshGovernorateData();
                        showSuccessToast('تم تحديث المجاورة بنجاح');
                    } else {
                        showErrorToast(data.error || 'حدث خطأ');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showErrorToast('حدث خطأ في الاتصال');
                });
        });

        // Delete Area
        function deleteArea(id, name) {
            document.getElementById('deleteAreaId').value = id;
            document.getElementById('deleteAreaName').textContent = name;
            new bootstrap.Modal(document.getElementById('deleteAreaModal')).show();
        }

        function confirmDeleteArea() {
            const id = document.getElementById('deleteAreaId').value;

            fetch(`${ROUTES.deleteArea}/${id}/delete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('deleteAreaModal')).hide();
                        refreshGovernorateData();
                        showSuccessToast('تم حذف المجاورة بنجاح');
                    } else {
                        showErrorToast(data.error || 'حدث خطأ');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showErrorToast('حدث خطأ في الاتصال');
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
            localStorage.setItem('geoAreasView', view);
        }

        // Initialize view from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('geoAreasView') || 'list';
            toggleView(savedView);
        });
    </script>
</body>

</html>
