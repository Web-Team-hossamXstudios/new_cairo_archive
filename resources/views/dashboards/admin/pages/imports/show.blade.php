<!DOCTYPE html>
@include('dashboards.shared.html')

<head>
    @include('dashboards.shared.meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تفاصيل الاستيراد - نظام أرشيف القاهرة الجديدة</title>
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
                                        <i class="ti ti-file-import me-1"></i> تفاصيل الاستيراد #{{ $import->id }}
                                    </span>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0 mt-1">
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('admin.imports.index') }}">استيراد البيانات</a></li>
                                            <li class="breadcrumb-item active">التفاصيل</li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="d-flex gap-2 mt-2 mt-lg-0">
                                    <a href="{{ route('admin.imports.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-arrow-right me-1"></i> العودة
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Import Status Card -->
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                @switch($import->status)
                                    @case('pending')
                                        <div class="avatar avatar-lg bg-secondary-subtle text-secondary rounded-circle mx-auto mb-2">
                                            <i class="ti ti-clock fs-3"></i>
                                        </div>
                                        <h6 class="text-secondary">معلق</h6>
                                        @break
                                    @case('validating')
                                        <div class="avatar avatar-lg bg-info-subtle text-info rounded-circle mx-auto mb-2">
                                            <i class="ti ti-search fs-3"></i>
                                        </div>
                                        <h6 class="text-info">جاري التحقق</h6>
                                        @break
                                    @case('processing')
                                        <div class="avatar avatar-lg bg-warning-subtle text-warning rounded-circle mx-auto mb-2">
                                            <div class="spinner-border spinner-border-sm"></div>
                                        </div>
                                        <h6 class="text-warning">جاري المعالجة</h6>
                                        @break
                                    @case('completed')
                                        <div class="avatar avatar-lg bg-success-subtle text-success rounded-circle mx-auto mb-2">
                                            <i class="ti ti-check fs-3"></i>
                                        </div>
                                        <h6 class="text-success">مكتمل</h6>
                                        @break
                                    @case('failed')
                                        <div class="avatar avatar-lg bg-danger-subtle text-danger rounded-circle mx-auto mb-2">
                                            <i class="ti ti-x fs-3"></i>
                                        </div>
                                        <h6 class="text-danger">فشل</h6>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <h2 class="mb-0 text-primary">{{ $import->total_rows ?? 0 }}</h2>
                                <small class="text-muted">إجمالي الصفوف</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <h2 class="mb-0 text-success">{{ $import->success_rows ?? 0 }}</h2>
                                <small class="text-muted">صفوف ناجحة</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <h2 class="mb-0 text-danger">{{ $import->failed_rows ?? 0 }}</h2>
                                <small class="text-muted">صفوف فاشلة</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar (for processing status) -->
                @if($import->status === 'processing')
                <div class="card mb-3" id="progressCard">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>التقدم</span>
                            <span id="progressText">{{ $import->progress_percentage ?? 0 }}%</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 id="progressBar"
                                 role="progressbar"
                                 style="width: {{ $import->progress_percentage ?? 0 }}%">
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            تمت معالجة <span id="processedCount">{{ $import->processed_rows ?? 0 }}</span> من {{ $import->total_rows ?? 0 }} صف
                        </small>
                    </div>
                </div>
                @endif

                <!-- Import Details -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="ti ti-info-circle me-1"></i>معلومات الاستيراد</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <th class="text-muted" style="width: 40%;">النوع</th>
                                        <td>
                                            @switch($import->type)
                                                @case('archive') <span class="badge bg-dark">أرشيف</span> @break
                                                @case('full') <span class="badge bg-primary">كامل</span> @break
                                                @case('clients') <span class="badge bg-info">عملاء</span> @break
                                                @case('lands') <span class="badge bg-success">أراضي</span> @break
                                                @case('geographic') <span class="badge bg-warning">جغرافي</span> @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">اسم الملف</th>
                                        <td>{{ $import->original_filename }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">رفع بواسطة</th>
                                        <td>{{ $import->user->name ?? 'غير معروف' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">تاريخ الرفع</th>
                                        <td>{{ $import->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    @if($import->started_at)
                                    <tr>
                                        <th class="text-muted">بدء المعالجة</th>
                                        <td>{{ $import->started_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    @endif
                                    @if($import->completed_at)
                                    <tr>
                                        <th class="text-muted">انتهاء المعالجة</th>
                                        <td>{{ $import->completed_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="ti ti-chart-bar me-1"></i>ملخص النتائج</h6>
                            </div>
                            <div class="card-body">
                                @if($import->summary)
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <h4 class="text-primary">{{ $import->summary['total'] ?? 0 }}</h4>
                                            <small class="text-muted">إجمالي</small>
                                        </div>
                                        <div class="col-4">
                                            <h4 class="text-success">{{ $import->summary['success'] ?? 0 }}</h4>
                                            <small class="text-muted">نجح</small>
                                        </div>
                                        <div class="col-4">
                                            <h4 class="text-danger">{{ $import->summary['failed'] ?? 0 }}</h4>
                                            <small class="text-muted">فشل</small>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted text-center mb-0">لا يوجد ملخص بعد</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Failed Rows Section -->
                @if($import->failed_rows > 0)
                <div class="card mt-3 border-0 shadow-sm">
                    <div class="card-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-white">
                                <i class="ti ti-alert-triangle me-2"></i>
                                الصفوف الفاشلة ({{ $import->failed_rows }})
                            </h5>
                            @if(!empty($import->errors['rows']))
                            <button class="btn btn-sm btn-light" onclick="exportFailedRows()">
                                <i class="ti ti-download me-1"></i>تصدير الأخطاء
                            </button>
                            @endif
                        </div>
                        <small class="text-white-50 d-block mt-1">تفاصيل الصفوف التي فشل استيرادها مع أسباب الفشل</small>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($import->errors['general']))
                            <div class="alert alert-danger m-3 mb-0">
                                <i class="ti ti-info-circle me-2"></i>
                                <strong>خطأ عام:</strong> {{ $import->errors['general'] }}
                            </div>
                        @endif

                        @if(!empty($import->errors['rows']))
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th style="width: 60px;" class="text-center">#</th>
                                            <th style="width: 100px;">رقم الصف</th>
                                            <th>تفاصيل الخطأ</th>
                                            <th style="width: 150px;">البيانات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($import->errors['rows'] as $rowNum => $rowData)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="avatar avatar-sm bg-danger-subtle text-danger rounded">
                                                        <i class="ti ti-x"></i>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger-subtle text-danger">
                                                        <i class="ti ti-file-text me-1"></i>{{ $rowNum }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if(is_array($rowData))
                                                        @if(isset($rowData['errors']))
                                                            <div class="error-list">
                                                                @if(is_array($rowData['errors']))
                                                                    @foreach($rowData['errors'] as $field => $errors)
                                                                        <div class="mb-2">
                                                                            <strong class="text-danger d-block">
                                                                                <i class="ti ti-point-filled"></i>
                                                                                {{ is_numeric($field) ? 'خطأ' : $field }}:
                                                                            </strong>
                                                                            <ul class="mb-0 ps-4">
                                                                                @if(is_array($errors))
                                                                                    @foreach($errors as $error)
                                                                                        <li class="text-muted small">{{ $error }}</li>
                                                                                    @endforeach
                                                                                @else
                                                                                    <li class="text-muted small">{{ $errors }}</li>
                                                                                @endif
                                                                            </ul>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <span class="text-danger">{{ $rowData['errors'] }}</span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-danger">{{ implode(', ', array_filter($rowData, fn($v) => !is_array($v))) }}</span>
                                                        @endif
                                                    @else
                                                        <span class="text-danger">{{ $rowData }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(is_array($rowData) && isset($rowData['data']))
                                                        <button class="btn btn-sm btn-outline-secondary"
                                                                type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#rowData{{ $loop->index }}"
                                                                title="عرض البيانات">
                                                            <i class="ti ti-eye"></i>
                                                        </button>
                                                        <div class="collapse mt-2" id="rowData{{ $loop->index }}">
                                                            <div class="card card-body bg-light small">
                                                                <pre class="mb-0" style="font-size: 0.75rem;">{{ json_encode($rowData['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="ti ti-info-circle me-1"></i>
                                        إجمالي {{ count($import->errors['rows']) }} صف فاشل
                                    </small>
                                    <small class="text-muted">
                                        تم تسجيل جميع الأخطاء في ملف السجل
                                    </small>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning m-3">
                                <i class="ti ti-alert-circle me-2"></i>
                                <strong>تنبيه:</strong> يوجد {{ $import->failed_rows }} صف فاشل ولكن تفاصيل الأخطاء غير متوفرة.
                                <br>
                                <small class="text-muted">قد تكون عملية الاستيراد لم تكتمل بشكل صحيح أو أن تفاصيل الأخطاء لم يتم حفظها.</small>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @include('dashboards.shared.scripts')

    <script>
    // Export failed rows to CSV
    function exportFailedRows() {
        const errors = @json($import->errors ?? []);

        if (!errors.rows || Object.keys(errors.rows).length === 0) {
            alert('لا توجد صفوف فاشلة للتصدير');
            return;
        }

        let csv = 'رقم الصف,الأخطاء,البيانات\n';

        for (const [rowNum, rowData] of Object.entries(errors.rows)) {
            let errorText = '';
            let dataText = '';

            if (typeof rowData === 'object') {
                if (rowData.errors) {
                    if (typeof rowData.errors === 'object') {
                        errorText = Object.entries(rowData.errors)
                            .map(([field, errs]) => {
                                const errList = Array.isArray(errs) ? errs.join('; ') : errs;
                                return `${field}: ${errList}`;
                            })
                            .join(' | ');
                    } else {
                        errorText = rowData.errors;
                    }
                }
                if (rowData.data) {
                    dataText = JSON.stringify(rowData.data);
                }
            } else {
                errorText = rowData;
            }

            csv += `"${rowNum}","${errorText.replace(/"/g, '""')}","${dataText.replace(/"/g, '""')}"\n`;
        }

        const blob = new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `import_${{{ $import->id }}}_failed_rows_${new Date().getTime()}.csv`;
        link.click();
    }
    </script>

    @if($import->status === 'processing')
    <script>
    // Poll for status updates
    const statusInterval = setInterval(function() {
        fetch('{{ route("admin.imports.status", $import) }}', {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('progressBar').style.width = data.progress + '%';
            document.getElementById('progressText').textContent = data.progress + '%';
            document.getElementById('processedCount').textContent = data.processed_rows;

            if (data.status !== 'processing') {
                clearInterval(statusInterval);
                location.reload();
            }
        })
        .catch(err => console.error('Status check failed:', err));
    }, 2000);
    </script>
    @endif
</body>
</html>
