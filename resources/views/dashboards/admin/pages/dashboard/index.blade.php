<!DOCTYPE html>
@include('dashboards.shared.html')

<head>
    @include('dashboards.shared.meta')
    <title>لوحة التحكم - نظام أرشيف القاهرة الجديدة</title>
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
                            <div class="py-2 px-3 bg-body border border-secondary border-opacity-10 shadow-sm"
                                style="border-radius: var(--ins-border-radius);">
                                <h4 class="mb-1"><i class="ti ti-dashboard me-2"></i>لوحة التحكم</h4>
                                <p class="text-muted mb-0">مرحباً {{ auth()->user()->name }}، هذا ملخص لنظام الأرشيف</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-3">
                    <div class="col-md-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-lg bg-primary-subtle text-primary rounded me-3">
                                        <i class="ti ti-users fs-3"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">{{ \App\Models\Client::count() }}</h3>
                                        <span class="text-muted">العملاء</span>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2 border-top">
                                    <small class="text-success">
                                        <i class="ti ti-trending-up me-1"></i>
                                        +{{ \App\Models\Client::whereDate('created_at', today())->count() }} اليوم
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-lg bg-success-subtle text-success rounded me-3">
                                        <i class="ti ti-map-2 fs-3"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">{{ \App\Models\Land::count() }}</h3>
                                        <span class="text-muted">القطع</span>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2 border-top">
                                    <small class="text-success">
                                        <i class="ti ti-trending-up me-1"></i>
                                        +{{ \App\Models\Land::whereDate('created_at', today())->count() }} اليوم
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-lg bg-info-subtle text-info rounded me-3">
                                        <i class="ti ti-files fs-3"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">{{ \App\Models\File::mainFiles()->count() }}</h3>
                                        <span class="text-muted">الملفات</span>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2 border-top">
                                    <small class="text-info">
                                        <i class="ti ti-file-text me-1"></i>
                                        {{ \App\Models\File::pageFiles()->count() }} صفحة
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-lg bg-warning-subtle text-warning rounded me-3">
                                        <i class="ti ti-building-warehouse fs-3"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">{{ \App\Models\Rack::count() }}</h3>
                                        <span class="text-muted">الرفوف</span>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2 border-top">
                                    <small class="text-muted">
                                        <i class="ti ti-building me-1"></i>
                                        {{ \App\Models\Room::count() }} غرفة
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Recent Activity -->
                <div class="row g-3 mt-2">
                    <!-- Quick Actions -->
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent">
                                <h5 class="card-title mb-0"><i class="ti ti-bolt me-2"></i>إجراءات سريعة</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    @can('clients.create')
                                        <a href="{{ route('admin.clients.index') }}" class="btn btn-soft-primary text-start">
                                            <i class="ti ti-user-plus me-2"></i> إضافة عميل جديد
                                        </a>
                                    @endcan
                                    @can('files.upload')
                                        <a href="{{ route('admin.files.index') }}" class="btn btn-soft-success text-start">
                                            <i class="ti ti-upload me-2"></i> رفع ملف PDF
                                        </a>
                                    @endcan
                                    @can('import.access')
                                        <a href="{{ route('admin.imports.create') }}" class="btn btn-soft-info text-start">
                                            <i class="ti ti-file-import me-2"></i> استيراد بيانات Excel
                                        </a>
                                    @endcan
                                    @can('physical_locations.manage')
                                        <a href="{{ route('admin.physical-locations.index') }}" class="btn btn-soft-warning text-start">
                                            <i class="ti ti-building-warehouse me-2"></i> إدارة المواقع
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Clients -->
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0"><i class="ti ti-users me-2"></i>آخر العملاء المضافين</h5>
                                <a href="{{ route('admin.clients.index') }}" class="btn btn-sm btn-soft-primary">عرض الكل</a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="bg-light-subtle">
                                            <tr>
                                                <th>العميل</th>
                                                <th>الرقم القومي</th>
                                                <th>القطع</th>
                                                <th>تاريخ الإضافة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(\App\Models\Client::withCount('lands')->latest()->take(5)->get() as $client)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                                {{ mb_substr($client->name, 0, 1) }}
                                                            </div>
                                                            <span>{{ $client->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $client->national_id ?? '-' }}</td>
                                                    <td><span class="badge bg-success-subtle text-success">{{ $client->lands_count }}</span></td>
                                                    <td><small class="text-muted">{{ $client->created_at->diffForHumans() }}</small></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-3">لا يوجد عملاء بعد</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Processing Status & Geographic Distribution -->
                <div class="row g-3 mt-2">
                    <!-- File Processing Status -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent">
                                <h5 class="card-title mb-0"><i class="ti ti-loader me-2"></i>حالة معالجة الملفات</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $processing = \App\Models\File::mainFiles()->processing()->count();
                                    $completed = \App\Models\File::mainFiles()->completed()->count();
                                    $failed = \App\Models\File::mainFiles()->failed()->count();
                                    $total = $processing + $completed + $failed;
                                @endphp

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted">مكتمل</span>
                                    <span class="badge bg-success">{{ $completed }}</span>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ $total > 0 ? ($completed/$total)*100 : 0 }}%"></div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted">جاري المعالجة</span>
                                    <span class="badge bg-warning">{{ $processing }}</span>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $total > 0 ? ($processing/$total)*100 : 0 }}%"></div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted">فشل</span>
                                    <span class="badge bg-danger">{{ $failed }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-danger" style="width: {{ $total > 0 ? ($failed/$total)*100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Governorates -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent">
                                <h5 class="card-title mb-0"><i class="ti ti-map-pin me-2"></i>أكثر المحافظات</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $topGovernorates = \App\Models\Governorate::withCount('lands')
                                        ->orderByDesc('lands_count')
                                        ->take(5)
                                        ->get();
                                    $maxLands = $topGovernorates->max('lands_count') ?: 1;
                                @endphp

                                @forelse($topGovernorates as $gov)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>{{ $gov->name }}</span>
                                                <span class="badge bg-primary-subtle text-primary">{{ $gov->lands_count }} قطعه</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-primary" style="width: {{ ($gov->lands_count / $maxLands) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-3">
                                        <i class="ti ti-map-off fs-1 d-block mb-2"></i>
                                        لا توجد بيانات جغرافية بعد
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboards.shared.scripts')
</body>
</html>
