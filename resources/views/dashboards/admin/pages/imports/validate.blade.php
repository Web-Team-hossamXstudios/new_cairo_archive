<!DOCTYPE html>
@include('dashboards.shared.html')

<head>
    @include('dashboards.shared.meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>التحقق من الاستيراد - نظام أرشيف القاهرة الجديدة</title>
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
                                        <i class="ti ti-check me-1"></i> التحقق من البيانات
                                    </span>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0 mt-1">
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('admin.imports.index') }}">استيراد البيانات</a></li>
                                            <li class="breadcrumb-item active">التحقق</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Validation Summary -->
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm bg-primary text-white">
                            <div class="card-body text-center">
                                <h2 class="mb-0">{{ $validationResults['total'] ?? 0 }}</h2>
                                <small>إجمالي الصفوف</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm bg-success text-white">
                            <div class="card-body text-center">
                                <h2 class="mb-0">{{ $validationResults['valid'] ?? 0 }}</h2>
                                <small>صفوف صالحة</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm bg-danger text-white">
                            <div class="card-body text-center">
                                <h2 class="mb-0">{{ $validationResults['invalid'] ?? 0 }}</h2>
                                <small>صفوف بها أخطاء</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-1">{{ $import->original_filename }}</h6>
                                <small class="text-muted">
                                    @switch($import->type)
                                        @case('archive') أرشيف @break
                                        @case('full') كامل @break
                                        @case('clients') عملاء @break
                                        @case('lands') أراضي @break
                                        @case('geographic') جغرافي @break
                                    @endswitch
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                @if(!empty($validationResults['errors']['columns']))
                    <div class="alert alert-danger">
                        <h6><i class="ti ti-alert-triangle me-1"></i>أخطاء في الأعمدة:</h6>
                        <ul class="mb-0">
                            @foreach($validationResults['errors']['columns'] as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(!empty($validationResults['errors']['general']))
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-triangle me-1"></i>{{ $validationResults['errors']['general'] }}
                    </div>
                @endif

                <!-- Data Preview -->
                @if(!empty($validationResults['rows']))
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">معاينة البيانات</h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="toggleFilter('all')">الكل</button>
                            <button class="btn btn-sm btn-outline-success" onclick="toggleFilter('valid')">صالح</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="toggleFilter('invalid')">أخطاء</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>#</th>
                                        <th>الحالة</th>
                                        @if($import->type === 'archive')
                                            <th>رقم الملف</th>
                                            <th>المالك</th>
                                            <th>القطعة</th>
                                            <th>الحي</th>
                                            <th>المنطقة</th>
                                            <th>الغرفة</th>
                                        @else
                                            <th>البيانات</th>
                                        @endif
                                        <th>الأخطاء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($validationResults['rows'] as $rowNum => $row)
                                        <tr class="row-{{ $row['status'] }}" data-status="{{ $row['status'] }}">
                                            <td>{{ $rowNum }}</td>
                                            <td>
                                                @if($row['status'] === 'valid')
                                                    <span class="badge bg-success"><i class="ti ti-check"></i></span>
                                                @else
                                                    <span class="badge bg-danger"><i class="ti ti-x"></i></span>
                                                @endif
                                            </td>
                                            @if($import->type === 'archive')
                                                <td>{{ $row['data']['file_number'] ?? $row['data']['رقم الملف'] ?? '-' }}</td>
                                                <td>{{ $row['data']['owner_name'] ?? $row['data']['المالك'] ?? '-' }}</td>
                                                <td>{{ $row['data']['land_no'] ?? $row['data']['القطعه'] ?? '-' }}</td>
                                                <td>{{ $row['data']['district'] ?? $row['data']['الحي'] ?? '-' }}</td>
                                                <td>{{ $row['data']['zone'] ?? $row['data']['المنطقة'] ?? '-' }}</td>
                                                <td>{{ $row['data']['room'] ?? $row['data']['الاوضه'] ?? '-' }}</td>
                                            @else
                                                <td><small>{{ json_encode($row['data'], JSON_UNESCAPED_UNICODE) }}</small></td>
                                            @endif
                                            <td>
                                                @if(!empty($row['errors']))
                                                    <small class="text-danger">{{ implode(', ', $row['errors']) }}</small>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="skipErrors" checked>
                                    <label class="form-check-label" for="skipErrors">
                                        تخطي الصفوف التي بها أخطاء والمتابعة
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="updateExisting">
                                    <label class="form-check-label" for="updateExisting">
                                        تحديث البيانات الموجودة
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('admin.imports.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="ti ti-arrow-right me-1"></i>إلغاء
                                </a>
                                @if(($validationResults['valid'] ?? 0) > 0)
                                <button type="button" class="btn btn-success btn-lg" onclick="executeImport()">
                                    <i class="ti ti-player-play me-1"></i>
                                    تنفيذ الاستيراد ({{ $validationResults['valid'] ?? 0 }} صف)
                                </button>
                                @else
                                <button type="button" class="btn btn-secondary btn-lg" disabled>
                                    <i class="ti ti-x me-1"></i>لا توجد بيانات صالحة
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboards.shared.scripts')

    <script>
    function toggleFilter(status) {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            if (status === 'all') {
                row.style.display = '';
            } else if (row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function executeImport() {
        const skipErrors = document.getElementById('skipErrors').checked;
        const updateExisting = document.getElementById('updateExisting').checked;
        const btn = event.target;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري التنفيذ...';

        fetch('{{ route("admin.imports.execute", $import) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                skip_errors: skipErrors,
                update_existing: updateExisting
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.redirect) {
                window.location.href = data.redirect;
            } else {
                alert(data.error || 'حدث خطأ');
                btn.disabled = false;
                btn.innerHTML = '<i class="ti ti-player-play me-1"></i> تنفيذ الاستيراد';
            }
        })
        .catch(err => {
            console.error(err);
            alert('حدث خطأ في الاتصال');
            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-player-play me-1"></i> تنفيذ الاستيراد';
        });
    }
    </script>
</body>
</html>
