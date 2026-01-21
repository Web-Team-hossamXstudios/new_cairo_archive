<!DOCTYPE html>
@include('dashboards.shared.html')

<head>
    @include('dashboards.shared.meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>مواقع التخزين - نظام أرشيف القاهرة الجديدة</title>
    <style>
        .location-card { transition: all 0.2s; border: 1px solid #e5e7eb; }
        .location-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .hierarchy-item { border-right: 2px solid #e5e7eb; padding-right: 1rem; margin-right: 0.5rem; }
        .hierarchy-item:last-child { border-right-color: transparent; }
    </style>
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
                                        <i class="ti ti-building-warehouse me-1"></i> مواقع التخزين
                                    </span>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0">
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                                            <li class="breadcrumb-item active">مواقع التخزين</li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="d-flex flex-wrap gap-2 mt-2 mt-lg-0">
                                    @can('physical_locations.manage')
                                        <button type="button" class="btn btn-primary shadow-sm px-3" style="border-radius: var(--ins-border-radius);" onclick="openRoomModal()">
                                            <i class="ti ti-plus me-1"></i>
                                            <span>إضافة غرفة</span>
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary-subtle text-primary rounded me-3">
                                        <i class="ti ti-building fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Room::count() }}</h4>
                                        <small class="text-muted">الغرف</small>
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
                                        <i class="ti ti-layout-columns fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Lane::count() }}</h4>
                                        <small class="text-muted">الممرات</small>
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
                                        <i class="ti ti-archive fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Stand::count() }}</h4>
                                        <small class="text-muted">الاستاندات</small>
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
                                        <i class="ti ti-folder fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Rack::count() }}</h4>
                                        <small class="text-muted">الرفوف</small>
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
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <h5 class="card-title mb-0">الغرف</h5>
                                    <span class="badge bg-dark-subtle text-dark">{{ $rooms->count() }} غرفة</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- View Toggle -->
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-dark" id="listViewBtn" onclick="switchView('table')">
                                            <i class="ti ti-list"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-dark active" id="cardViewBtn" onclick="switchView('card')">
                                            <i class="ti ti-layout-grid"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <!-- Card View -->
                                <div id="cardView" class="p-3">
                                    <div class="row g-3">
                                        @forelse($rooms as $room)
                                            <div class="col-md-4 col-lg-3">
                                                <div class="card border shadow-sm h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="avatar avatar-md bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                                <i class="ti ti-building"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0">{{ $room->name }}</h6>
                                                                <small class="text-muted">{{ $room->building_name }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 mb-3">
                                                            <span class="badge bg-success-subtle text-success"><i class="ti ti-layout-columns me-1"></i>{{ $room->lanes->count() }} ممر</span>
                                                            <span class="badge bg-info-subtle text-info"><i class="ti ti-archive me-1"></i>{{ $room->lanes->sum(fn($l) => $l->stands->count()) }}</span>
                                                            <span class="badge bg-warning-subtle text-warning"><i class="ti ti-folder me-1"></i>{{ $room->lanes->sum(fn($l) => $l->stands->sum(fn($s) => $s->racks->count())) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top-0 pt-0">
                                                        <div class="d-flex justify-content-between gap-1">
                                                            <button class="btn btn-soft-info btn-sm" onclick="showRoomDetails({{ $room->id }})"><i class="ti ti-eye"></i></button>
                                                            @can('physical_locations.manage')
                                                                <button class="btn btn-soft-success btn-sm" onclick="openLaneModal({{ $room->id }}, '{{ $room->name }}')"><i class="ti ti-plus"></i></button>
                                                                <button class="btn btn-soft-warning btn-sm" onclick="editRoom({{ $room->id }})"><i class="ti ti-edit"></i></button>
                                                                <button class="btn btn-soft-danger btn-sm" onclick="deleteLocation('room', {{ $room->id }}, '{{ $room->name }}')"><i class="ti ti-trash"></i></button>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ti ti-building-warehouse fs-1 d-block mb-2"></i>
                                                    لا توجد غرف
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Table View -->
                                <div id="tableView" class="table-responsive d-none">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead class="bg-light-subtle">
                                            <tr>
                                                <th>الغرفة</th>
                                                <th>المبنى</th>
                                                <th>الممرات</th>
                                                <th>الستاندات</th>
                                                <th>الرفوف</th>
                                                <th width="180" class="text-center">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($rooms as $room)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                                <i class="ti ti-building"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $room->name }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $room->building_name }}</td>
                                                    <td><span class="badge bg-success-subtle text-success">{{ $room->lanes->count() }}</span></td>
                                                    <td><span class="badge bg-info-subtle text-info">{{ $room->lanes->sum(fn($l) => $l->stands->count()) }}</span></td>
                                                    <td><span class="badge bg-warning-subtle text-warning">{{ $room->lanes->sum(fn($l) => $l->stands->sum(fn($s) => $s->racks->count())) }}</span></td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-1">
                                                            <button class="btn btn-soft-info btn-sm" onclick="showRoomDetails({{ $room->id }})" title="عرض">
                                                                <i class="ti ti-eye"></i>
                                                            </button>
                                                            @can('physical_locations.manage')
                                                                <button class="btn btn-soft-success btn-sm" onclick="openLaneModal({{ $room->id }}, '{{ $room->name }}')" title="إضافة ممر">
                                                                    <i class="ti ti-plus"></i>
                                                                </button>
                                                                <button class="btn btn-soft-warning btn-sm" onclick="editRoom({{ $room->id }})" title="تعديل">
                                                                    <i class="ti ti-edit"></i>
                                                                </button>
                                                                <button class="btn btn-soft-danger btn-sm" onclick="deleteLocation('room', {{ $room->id }}, '{{ $room->name }}')" title="حذف">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ti ti-building-warehouse fs-1 d-block mb-2"></i>
                                                            لا توجد غرف
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Modal -->
    <div class="modal fade" id="roomModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-building me-2"></i><span id="roomModalTitle">إضافة غرفة</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="roomForm" onsubmit="return saveRoom(event)">
                    <div class="modal-body">
                        <input type="hidden" name="room_id" id="roomId">
                        <div class="mb-3">
                            <label class="form-label">اسم الغرفة <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="roomName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">اسم المبنى <span class="text-danger">*</span></label>
                            <input type="text" name="building_name" id="buildingName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">وصف</label>
                            <textarea name="description" id="roomDescription" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lane Modal -->
    <div class="modal fade" id="laneModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-layout-columns me-2"></i><span id="laneModalTitle">إضافة ممر</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="laneForm" onsubmit="return saveLane(event)">
                    <div class="modal-body">
                        <input type="hidden" name="room_id" id="laneRoomId">
                        <input type="hidden" name="lane_id" id="laneId">
                        <div class="alert alert-info mb-3">
                            <i class="ti ti-info-circle me-1"></i> إضافة ممر في: <strong id="laneParentName"></strong>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">اسم الممر <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="laneName" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Stand Modal -->
    <div class="modal fade" id="standModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-archive me-2"></i><span id="standModalTitle">إضافة ستاند</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="standForm" onsubmit="return saveStand(event)">
                    <div class="modal-body">
                        <input type="hidden" name="lane_id" id="standLaneId">
                        <input type="hidden" name="stand_id" id="standId">
                        <div class="alert alert-info mb-3">
                            <i class="ti ti-info-circle me-1"></i> إضافة ستاند في: <strong id="standParentName"></strong>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">اسم الستاند <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="standName" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rack Modal -->
    <div class="modal fade" id="rackModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-folder me-2"></i><span id="rackModalTitle">إضافة رف</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="rackForm" onsubmit="return saveRack(event)">
                    <div class="modal-body">
                        <input type="hidden" name="stand_id" id="rackStandId">
                        <input type="hidden" name="rack_id" id="rackId">
                        <div class="alert alert-info mb-3">
                            <i class="ti ti-info-circle me-1"></i> إضافة رف في: <strong id="rackParentName"></strong>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">اسم الرف <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="rackName" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Show Room Details Modal -->
    <div class="modal fade" id="showRoomModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-primary-subtle text-primary rounded me-2">
                            <i class="ti ti-building"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-semibold" id="showRoomName"></h5>
                            <small class="text-muted" id="showRoomBuilding"></small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="refreshRoomData()" title="تحديث">
                            <i class="ti ti-refresh"></i>
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body p-0" style="max-height: 70vh; overflow-y: auto;">
                    <div id="showRoomBody">
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
    <input type="hidden" id="currentRoomId">

    @include('dashboards.admin.pages.physical-locations.partials.delete-modal')

    @include('dashboards.shared.scripts')

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        // View Toggle - matching clients index
        function switchView(view) {
            const cardView = document.getElementById('cardView');
            const tableView = document.getElementById('tableView');
            const cardBtn = document.getElementById('cardViewBtn');
            const listBtn = document.getElementById('listViewBtn');

            if (view === 'card') {
                cardView.classList.remove('d-none');
                tableView.classList.add('d-none');
                cardBtn.classList.add('active');
                listBtn.classList.remove('active');
                localStorage.setItem('physicalLocationsView', 'card');
            } else {
                cardView.classList.add('d-none');
                tableView.classList.remove('d-none');
                cardBtn.classList.remove('active');
                listBtn.classList.add('active');
                localStorage.setItem('physicalLocationsView', 'table');
            }
        }

        // Restore saved view preference
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('physicalLocationsView') || 'card';
            switchView(savedView);
        });

        // Show Room Details in Modal
        function showRoomDetails(roomId) {
            document.getElementById('currentRoomId').value = roomId;
            document.getElementById('showRoomBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-3 text-muted">جاري التحميل...</p></div>';
            new bootstrap.Modal(document.getElementById('showRoomModal')).show();
            loadRoomData(roomId);
        }

        function refreshRoomData() {
            const roomId = document.getElementById('currentRoomId').value;
            if (roomId) {
                document.getElementById('showRoomBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div><p class="mt-3 text-muted">جاري تحديث البيانات...</p></div>';
                loadRoomData(roomId);
            }
        }

        function loadRoomData(roomId) {
            fetch(`{{ url('admin/physical-locations/rooms') }}/${roomId}/show`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderRoomDetails(data.room);
                } else {
                    document.getElementById('showRoomBody').innerHTML = '<div class="alert alert-danger m-3">حدث خطأ</div>';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('showRoomBody').innerHTML = '<div class="alert alert-danger m-3">حدث خطأ في الاتصال</div>';
            });
        }

        function renderRoomDetails(room) {
            document.getElementById('showRoomName').textContent = room.name;
            document.getElementById('showRoomBuilding').textContent = room.building_name;

            const lanes = room.lanes || [];
            if (lanes.length === 0) {
                document.getElementById('showRoomBody').innerHTML = `
                    <div class="text-center py-5">
                        <div class="avatar avatar-lg bg-light text-success rounded-circle mx-auto mb-3">
                            <i class="ti ti-layout-columns fs-2"></i>
                        </div>
                        <h6 class="text-muted mb-3">لا توجد ممرات</h6>
                        <button class="btn btn-dark" onclick="openLaneModal(${room.id}, '${room.name}')">
                            <i class="ti ti-plus me-1"></i>إضافة ممر
                        </button>
                    </div>`;
                return;
            }

            // Calculate totals
            let totalStands = 0, totalRacks = 0;
            lanes.forEach(l => {
                const stands = l.stands || [];
                totalStands += stands.length;
                stands.forEach(s => { totalRacks += (s.racks || []).length; });
            });

            let html = `
            <div class="bg-light border-bottom p-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="d-flex flex-wrap gap-2">
                        <div class="bg-white rounded px-3 py-2 shadow-sm">
                            <span class="fs-5 fw-bold text-success">${lanes.length}</span>
                            <span class="text-muted small ms-1">ممر</span>
                        </div>
                        <div class="bg-white rounded px-3 py-2 shadow-sm">
                            <span class="fs-5 fw-bold text-info">${totalStands}</span>
                            <span class="text-muted small ms-1">ستاند</span>
                        </div>
                        <div class="bg-white rounded px-3 py-2 shadow-sm">
                            <span class="fs-5 fw-bold text-warning">${totalRacks}</span>
                            <span class="text-muted small ms-1">رف</span>
                        </div>
                    </div>
                    <button class="btn btn-dark" onclick="openLaneModal(${room.id}, '${room.name}')">
                        <i class="ti ti-plus me-1"></i>إضافة ممر
                    </button>
                </div>
            </div>
            <div class="p-3">`;

            lanes.forEach((lane, idx) => {
                const stands = lane.stands || [];
                html += `
                <div class="card mb-2 border shadow-sm">
                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#lane${lane.id}">
                            <i class="ti ti-chevron-down me-2 text-dark"></i>
                            <i class="ti ti-layout-columns text-dark me-2 fs-5"></i>
                            <strong class="text-dark">${lane.name}</strong>
                            <span class="badge bg-success ms-2">${stands.length} ستاند</span>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-dark btn-sm" onclick="openStandModal(${lane.id}, '${lane.name}')" title="إضافة ستاند"><i class="ti ti-plus"></i></button>
                            <button class="btn btn-light btn-sm" onclick="editLane(${lane.id})" title="تعديل"><i class="ti ti-edit"></i></button>
                            <button class="btn btn-light btn-sm text-danger" onclick="deleteLocation('lane', ${lane.id}, '${lane.name}')" title="حذف"><i class="ti ti-trash"></i></button>
                        </div>
                    </div>
                    <div class="collapse ${idx === 0 ? 'show' : ''}" id="lane${lane.id}">
                        <div class="card-body py-2">
                            ${stands.length > 0 ? stands.map(stand => {
                                const racks = stand.racks || [];
                                return `
                                <div class="card mb-2 border-0 bg-light">
                                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center border-0">
                                        <div class="d-flex align-items-center" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#stand${stand.id}">
                                            <i class="ti ti-chevron-down me-2 text-dark"></i>
                                            <i class="ti ti-archive text-dark me-2"></i>
                                            <span class="fw-medium text-dark">${stand.name}</span>
                                            <span class="badge bg-info ms-2">${racks.length} رف</span>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-dark btn-sm" onclick="openRackModal(${stand.id}, '${stand.name}')" title="إضافة رف"><i class="ti ti-plus"></i></button>
                                            <button class="btn btn-light btn-sm" onclick="editStand(${stand.id})" title="تعديل"><i class="ti ti-edit"></i></button>
                                            <button class="btn btn-light btn-sm text-danger" onclick="deleteLocation('stand', ${stand.id}, '${stand.name}')" title="حذف"><i class="ti ti-trash"></i></button>
                                        </div>
                                    </div>
                                    <div class="collapse" id="stand${stand.id}">
                                        <div class="card-body py-2">
                                            ${racks.length > 0 ? `
                                                <div class="row g-2">
                                                    ${racks.map(rack => `
                                                        <div class="col-md-6 col-lg-4">
                                                            <div class="d-flex justify-content-between align-items-center p-2 bg-warning-subtle rounded">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="ti ti-folder text-warning me-2"></i>
                                                                    <span class="fw-medium">${rack.name}</span>
                                                                    <span class="badge bg-warning text-dark ms-2">${rack.files_count || 0}</span>
                                                                </div>
                                                                <div class="btn-group btn-group-sm">
                                                                    <button class="btn btn-light btn-sm" onclick="editRack(${rack.id})" title="تعديل"><i class="ti ti-edit"></i></button>
                                                                    <button class="btn btn-light btn-sm text-danger" onclick="deleteLocation('rack', ${rack.id}, '${rack.name}')" title="حذف"><i class="ti ti-trash"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    `).join('')}
                                                </div>
                                            ` : '<p class="text-muted small mb-0">لا توجد رفوف</p>'}
                                        </div>
                                    </div>
                                </div>`;
                            }).join('') : '<p class="text-muted small mb-0">لا توجد ستاندات</p>'}
                        </div>
                    </div>
                </div>`;
            });
            html += '</div>';
            document.getElementById('showRoomBody').innerHTML = html;
        }

        // Room functions
        function openRoomModal(id = null) {
            document.getElementById('roomForm').reset();
            document.getElementById('roomId').value = id || '';
            document.getElementById('roomModalTitle').textContent = id ? 'تعديل غرفة' : 'إضافة غرفة';
            new bootstrap.Modal(document.getElementById('roomModal')).show();
        }

        function editRoom(id) {
            fetch(`{{ url('admin/physical-locations/rooms') }}/${id}/edit`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('roomId').value = data.room.id;
                    document.getElementById('roomName').value = data.room.name;
                    document.getElementById('buildingName').value = data.room.building_name || '';
                    document.getElementById('roomDescription').value = data.room.description || '';
                    document.getElementById('roomModalTitle').textContent = 'تعديل غرفة';
                    new bootstrap.Modal(document.getElementById('roomModal')).show();
                } else {
                    alert(data.error || 'حدث خطأ أثناء تحميل البيانات');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('حدث خطأ في الاتصال');
            });
        }

        function saveRoom(e) {
            e.preventDefault();
            const form = document.getElementById('roomForm');
            const id = document.getElementById('roomId').value;
            const url = id ? `{{ url('admin/physical-locations/rooms') }}/${id}` : `{{ route('admin.physical-locations.rooms.store') }}`;

            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(Object.fromEntries(new FormData(form)))
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('roomModal')).hide();
                    location.reload();
                } else {
                    alert(data.error || 'حدث خطأ');
                }
            });
            return false;
        }

        // Lane functions
        function openLaneModal(roomId, roomName) {
            document.getElementById('laneForm').reset();
            document.getElementById('laneRoomId').value = roomId;
            document.getElementById('laneParentName').textContent = roomName;
            new bootstrap.Modal(document.getElementById('laneModal')).show();
        }

        function editLane(id) {
            fetch(`{{ url('admin/physical-locations/lanes') }}/${id}/edit`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('laneId').value = data.lane.id;
                    document.getElementById('laneName').value = data.lane.name;
                    document.getElementById('laneRoomId').value = data.lane.room_id;
                    document.getElementById('laneDescription').value = data.lane.description || '';
                    document.getElementById('laneModalTitle').textContent = 'تعديل ممر';
                    new bootstrap.Modal(document.getElementById('laneModal')).show();
                } else {
                    alert(data.error || 'حدث خطأ أثناء تحميل البيانات');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('حدث خطأ في الاتصال');
            });
        }

        function saveLane(e) {
            e.preventDefault();
            const form = document.getElementById('laneForm');
            const id = document.getElementById('laneId').value;
            const url = id ? `{{ url('admin/physical-locations/lanes') }}/${id}` : `{{ route('admin.physical-locations.lanes.store') }}`;

            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(Object.fromEntries(new FormData(form)))
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('laneModal')).hide();
                    location.reload();
                }
            });
            return false;
        }

        // Stand functions
        function openStandModal(laneId, laneName) {
            document.getElementById('standForm').reset();
            document.getElementById('standLaneId').value = laneId;
            document.getElementById('standParentName').textContent = laneName;
            new bootstrap.Modal(document.getElementById('standModal')).show();
        }

        function editStand(id) {
            fetch(`{{ url('admin/physical-locations/stands') }}/${id}/edit`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('standId').value = data.stand.id;
                    document.getElementById('standName').value = data.stand.name;
                    document.getElementById('standLaneId').value = data.stand.lane_id;
                    document.getElementById('standDescription').value = data.stand.description || '';
                    document.getElementById('standModalTitle').textContent = 'تعديل ستاند';
                    new bootstrap.Modal(document.getElementById('standModal')).show();
                } else {
                    alert(data.error || 'حدث خطأ أثناء تحميل البيانات');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('حدث خطأ في الاتصال');
            });
        }

        function saveStand(e) {
            e.preventDefault();
            const form = document.getElementById('standForm');
            const id = document.getElementById('standId').value;
            const url = id ? `{{ url('admin/physical-locations/stands') }}/${id}` : `{{ route('admin.physical-locations.stands.store') }}`;

            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(Object.fromEntries(new FormData(form)))
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('standModal')).hide();
                    location.reload();
                }
            });
            return false;
        }

        // Rack functions
        function openRackModal(standId, standName) {
            document.getElementById('rackForm').reset();
            document.getElementById('rackStandId').value = standId;
            document.getElementById('rackParentName').textContent = standName;
            new bootstrap.Modal(document.getElementById('rackModal')).show();
        }

        function editRack(id) {
            fetch(`{{ url('admin/physical-locations/racks') }}/${id}/edit`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('rackId').value = data.rack.id;
                    document.getElementById('rackName').value = data.rack.name;
                    document.getElementById('rackStandId').value = data.rack.stand_id;
                    document.getElementById('rackDescription').value = data.rack.description || '';
                    document.getElementById('rackModalTitle').textContent = 'تعديل رف';
                    new bootstrap.Modal(document.getElementById('rackModal')).show();
                } else {
                    alert(data.error || 'حدث خطأ أثناء تحميل البيانات');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('حدث خطأ في الاتصال');
            });
        }

        function saveRack(e) {
            e.preventDefault();
            const form = document.getElementById('rackForm');
            const id = document.getElementById('rackId').value;
            const url = id ? `{{ url('admin/physical-locations/racks') }}/${id}` : `{{ route('admin.physical-locations.racks.store') }}`;

            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(Object.fromEntries(new FormData(form)))
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('rackModal')).hide();
                    location.reload();
                }
            });
            return false;
        }

        // Delete
        function deleteLocation(type, id, name) {
            const modalIds = {
                room: 'deleteRoomModal',
                lane: 'deleteLaneModal',
                stand: 'deleteStandModal',
                rack: 'deleteRackModal'
            };

            const nameIds = {
                room: 'deleteRoomName',
                lane: 'deleteLaneName',
                stand: 'deleteStandName',
                rack: 'deleteRackName'
            };

            const idInputs = {
                room: 'deleteRoomId',
                lane: 'deleteLaneId',
                stand: 'deleteStandId',
                rack: 'deleteRackId'
            };

            document.getElementById(idInputs[type]).value = id;
            document.getElementById(nameIds[type]).textContent = name;

            const modal = new bootstrap.Modal(document.getElementById(modalIds[type]));
            modal.show();
        }

        function confirmDeleteRoom() {
            confirmDelete('room', document.getElementById('deleteRoomId').value, 'deleteRoomModal');
        }

        function confirmDeleteLane() {
            confirmDelete('lane', document.getElementById('deleteLaneId').value, 'deleteLaneModal');
        }

        function confirmDeleteStand() {
            confirmDelete('stand', document.getElementById('deleteStandId').value, 'deleteStandModal');
        }

        function confirmDeleteRack() {
            confirmDelete('rack', document.getElementById('deleteRackId').value, 'deleteRackModal');
        }

        function confirmDelete(type, id, modalId) {
            const deleteBtn = event.target;
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحذف...';

            const urls = {
                room: `{{ url('admin/physical-locations/rooms') }}/${id}/delete`,
                lane: `{{ url('admin/physical-locations/lanes') }}/${id}/delete`,
                stand: `{{ url('admin/physical-locations/stands') }}/${id}/delete`,
                rack: `{{ url('admin/physical-locations/racks') }}/${id}/delete`
            };

            fetch(urls[type], {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById(modalId)).hide();
                    location.reload();
                } else {
                    alert(data.message || 'حدث خطأ');
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

        function viewRackFiles(rackId) {
            window.location.href = `{{ route('admin.files.index') }}?rack_id=${rackId}`;
        }
    </script>
</body>
</html>
