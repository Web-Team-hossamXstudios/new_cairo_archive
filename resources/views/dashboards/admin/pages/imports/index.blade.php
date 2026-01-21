<!DOCTYPE html>
@include('dashboards.shared.html')

<head>
    @include('dashboards.shared.meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>استيراد البيانات - نظام أرشيف القاهرة الجديدة</title>
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
                                        <i class="ti ti-file-import me-1"></i> استيراد البيانات
                                    </span>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0 mt-1">
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                                            <li class="breadcrumb-item active">استيراد البيانات</li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="d-flex gap-2 mt-2 mt-lg-0">
                                    @can('import.execute')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newImportModal">
                                        <i class="ti ti-file-import me-1"></i> استيراد جديد
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
                                        <i class="ti ti-file-import fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Import::count() }}</h4>
                                        <small class="text-muted">إجمالي العمليات</small>
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
                                        <i class="ti ti-check fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Import::where('status', 'completed')->count() }}</h4>
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
                                    <div class="avatar avatar-md bg-warning-subtle text-warning rounded me-3">
                                        <i class="ti ti-loader fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Import::where('status', 'processing')->count() }}</h4>
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
                                    <div class="avatar avatar-md bg-danger-subtle text-danger rounded me-3">
                                        <i class="ti ti-x fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ \App\Models\Import::where('status', 'failed')->count() }}</h4>
                                        <small class="text-muted">فشل</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search & Filters Card -->
                @php
                    $hasFilters = request()->filled('search') || request()->filled('type') || request()->filled('status');
                @endphp
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="border-radius: var(--ins-border-radius);">
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.imports.index') }}">
                                    <div class="row d-flex align-items-end justify-content-start">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label fw-semibold">بحث</label>
                                            <div class="input-group shadow-sm border border-secondary border-opacity-10 overflow-hidden bg-body"
                                                style="border-radius: var(--ins-border-radius);">
                                                <input type="text" name="search" class="form-control border-0 bg-transparent"
                                                    placeholder="اسم الملف..." value="{{ request('search') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2 d-flex align-items-center gap-2">
                                            <div class="d-flex flex-wrap gap-1">
                                                <button type="submit" class="btn btn-primary shadow-sm px-3" style="border-radius: var(--ins-border-radius);">
                                                    <i class="ti ti-filter me-1"></i> فلترة
                                                </button>
                                                <a href="{{ route('admin.imports.index') }}" style="border-radius: var(--ins-border-radius);"
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
                                            <label class="form-label fw-semibold">النوع</label>
                                            <select name="type" class="form-select">
                                                <option value="">الكل</option>
                                                <option value="archive" {{ request('type') == 'archive' ? 'selected' : '' }}>أرشيف</option>
                                                <option value="full" {{ request('type') == 'full' ? 'selected' : '' }}>كامل</option>
                                                <option value="clients" {{ request('type') == 'clients' ? 'selected' : '' }}>عملاء</option>
                                                <option value="lands" {{ request('type') == 'lands' ? 'selected' : '' }}>أراضي</option>
                                                <option value="geographic" {{ request('type') == 'geographic' ? 'selected' : '' }}>جغرافي</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">الحالة</label>
                                            <select name="status" class="form-select">
                                                <option value="">الكل</option>
                                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sample Files Download -->
                <div class="card" style="border-radius: var(--ins-border-radius);">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <h5 class="card-title mb-0">تحميل ملفات النموذج</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="{{ route('admin.imports.template', ['type' => 'archive']) }}" class="btn btn-outline-dark w-100">
                                    <i class="ti ti-download me-1"></i> نموذج الأرشيف
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.imports.template', ['type' => 'full']) }}" class="btn btn-outline-primary w-100">
                                    <i class="ti ti-download me-1"></i> نموذج كامل
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.imports.template', ['type' => 'clients']) }}" class="btn btn-outline-info w-100">
                                    <i class="ti ti-download me-1"></i> نموذج العملاء
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.imports.template', ['type' => 'lands']) }}" class="btn btn-outline-success w-100">
                                    <i class="ti ti-download me-1"></i> نموذج الأراضي
                                </a>
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
                                    <h5 class="card-title mb-0">سجل الاستيراد</h5>
                                    <span class="badge bg-primary-subtle text-primary">{{ count($imports ?? []) }} عملية</span>
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
                                                <th>النوع</th>
                                                <th>اسم الملف</th>
                                                <th>الصفوف</th>
                                                <th>نجح</th>
                                                <th>فشل</th>
                                                <th>الحالة</th>
                                                <th>التاريخ</th>
                                                <th width="150" class="text-center">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($imports ?? [] as $import)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        @switch($import->type)
                                                            @case('archive') <span class="badge bg-dark">أرشيف</span> @break
                                                            @case('full') <span class="badge bg-primary">كامل</span> @break
                                                            @case('clients') <span class="badge bg-info">عملاء</span> @break
                                                            @case('lands') <span class="badge bg-success">أراضي</span> @break
                                                            @case('geographic') <span class="badge bg-warning">جغرافي</span> @break
                                                            @default <span class="badge bg-secondary">{{ $import->type }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $import->original_filename ?? '-' }}</td>
                                                    <td>{{ $import->total_rows ?? 0 }}</td>
                                                    <td><span class="badge bg-success-subtle text-success">{{ $import->success_count ?? 0 }}</span></td>
                                                    <td><span class="badge bg-danger-subtle text-danger">{{ $import->failed_count ?? 0 }}</span></td>
                                                    <td>
                                                        @switch($import->status)
                                                            @case('pending') <span class="badge bg-secondary">معلق</span> @break
                                                            @case('processing') <span class="badge bg-warning">قيد المعالجة</span> @break
                                                            @case('completed') <span class="badge bg-success">مكتمل</span> @break
                                                            @case('failed') <span class="badge bg-danger">فشل</span> @break
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $import->created_at->format('Y-m-d H:i') }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-1">
                                                            <button class="btn btn-soft-info btn-sm" onclick="showImport({{ $import->id }})" title="عرض">
                                                                <i class="ti ti-eye"></i>
                                                            </button>
                                                            @if($import->failed_count > 0)
                                                            <a href="{{ route('admin.imports.errors', $import->id) }}" class="btn btn-soft-danger btn-sm" title="تحميل الأخطاء">
                                                                <i class="ti ti-download"></i>
                                                            </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="ti ti-file-off fs-1 d-block mb-2"></i>
                                                            لا توجد عمليات استيراد
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
                                        @forelse($imports ?? [] as $import)
                                            <div class="col-md-4 col-lg-3">
                                                <div class="card border shadow-sm h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="avatar avatar-md bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                                <i class="ti ti-file-import"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0">{{ Str::limit($import->original_filename ?? '-', 20) }}</h6>
                                                                <small class="text-muted">{{ $import->created_at->format('Y-m-d') }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 mb-2">
                                                            @switch($import->type)
                                                                @case('archive') <span class="badge bg-dark">أرشيف</span> @break
                                                                @case('full') <span class="badge bg-primary">كامل</span> @break
                                                                @case('clients') <span class="badge bg-info">عملاء</span> @break
                                                                @case('lands') <span class="badge bg-success">أراضي</span> @break
                                                                @case('geographic') <span class="badge bg-warning">جغرافي</span> @break
                                                            @endswitch
                                                            @switch($import->status)
                                                                @case('pending') <span class="badge bg-secondary">معلق</span> @break
                                                                @case('processing') <span class="badge bg-warning">قيد المعالجة</span> @break
                                                                @case('completed') <span class="badge bg-success">مكتمل</span> @break
                                                                @case('failed') <span class="badge bg-danger">فشل</span> @break
                                                            @endswitch
                                                        </div>
                                                        <div class="d-flex gap-2 mb-3">
                                                            <span class="badge bg-success-subtle text-success"><i class="ti ti-check me-1"></i>{{ $import->success_count ?? 0 }} نجح</span>
                                                            <span class="badge bg-danger-subtle text-danger"><i class="ti ti-x me-1"></i>{{ $import->failed_count ?? 0 }} فشل</span>
                                                        </div>
                                                        <small class="text-muted d-block"><i class="ti ti-stack me-1"></i>{{ $import->total_rows ?? 0 }} صف</small>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top-0 pt-0">
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-soft-info btn-sm" onclick="showImport({{ $import->id }})"><i class="ti ti-eye"></i> عرض</button>
                                                            @if($import->failed_count > 0)
                                                            <a href="{{ route('admin.imports.errors', $import->id) }}" class="btn btn-soft-danger btn-sm"><i class="ti ti-download"></i> الأخطاء</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ti ti-file-off fs-1 d-block mb-2"></i>
                                                    لا توجد عمليات استيراد
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

    <!-- New Import Modal -->
    <div class="modal fade" id="newImportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">استيراد بيانات جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="importForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">نوع البيانات <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="">اختر النوع</option>
                                <option value="archive">أرشيف (بيانات كاملة)</option>
                                <option value="full">استيراد كامل</option>
                                <option value="clients">عملاء فقط</option>
                                <option value="lands">أراضي فقط</option>
                                <option value="geographic">محافظات ومناطق</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ملف Excel <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            <small class="text-muted">الصيغ المدعومة: xlsx, xls, csv</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الوضع</label>
                            <select name="mode" class="form-select">
                                <option value="create">إنشاء جديد فقط</option>
                                <option value="upsert">إنشاء أو تحديث</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">استيراد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('dashboards.shared.scripts')

    <script>
    document.getElementById('importForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الاستيراد...';

        fetch('{{ route("admin.imports.upload") }}', {
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
                btn.disabled = false;
                btn.innerHTML = 'استيراد';
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = 'استيراد';
        });
    });

    function showImport(id) {
        window.location.href = `{{ url('admin/imports') }}/${id}`;
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
        localStorage.setItem('importsView', view);
    }

    // Initialize view from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('importsView') || 'list';
        toggleView(savedView);
    });
    </script>
</body>
</html>
