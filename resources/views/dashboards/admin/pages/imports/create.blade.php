<!DOCTYPE html>
@include('dashboards.shared.html')

<head>
    @include('dashboards.shared.meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>استيراد جديد - نظام أرشيف القاهرة الجديدة</title>
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
                                        <i class="ti ti-file-import me-1"></i> استيراد جديد
                                    </span>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0 mt-1">
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('admin.imports.index') }}">استيراد البيانات</a></li>
                                            <li class="breadcrumb-item active">استيراد جديد</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0"><i class="ti ti-file-import me-2"></i>رفع ملف الاستيراد</h5>
                            </div>
                            <div class="card-body">
                                <form id="uploadForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">نوع الاستيراد <span class="text-danger">*</span></label>
                                        <select name="type" class="form-select form-select-lg" required>
                                            <option value="">اختر نوع البيانات</option>
                                            <option value="archive">أرشيف (البيانات الكاملة مع موقع التخزين)</option>
                                            <option value="full">استيراد كامل (عملاء + قطع + مناطق)</option>
                                            <option value="clients">عملاء فقط</option>
                                            <option value="lands">قطع فقط</option>
                                            <option value="geographic">مناطق جغرافية فقط</option>
                                        </select>
                                        <div class="form-text">
                                            <strong>أرشيف:</strong> يتضمن رقم الملف، المالك، القطعة، الحي، المنطقة، المجاورة، الغرفة، الممر، الستاند، الرف
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">ملف Excel <span class="text-danger">*</span></label>
                                        <input type="file" name="file" class="form-control form-control-lg" accept=".xlsx,.xls" required>
                                        <div class="form-text">الصيغ المدعومة: xlsx, xls - الحد الأقصى: 10 ميجابايت</div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="skip_errors" id="skipErrors" value="1" checked>
                                                <label class="form-check-label" for="skipErrors">
                                                    تخطي الصفوف التي بها أخطاء
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="update_existing" id="updateExisting" value="1">
                                                <label class="form-check-label" for="updateExisting">
                                                    تحديث البيانات الموجودة
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="ti ti-upload me-1"></i> رفع والتحقق
                                        </button>
                                        <a href="{{ route('admin.imports.index') }}" class="btn btn-outline-secondary">
                                            <i class="ti ti-arrow-right me-1"></i> العودة
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Archive Headers Reference -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="ti ti-info-circle me-1"></i>أعمدة ملف الأرشيف</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>العمود</th>
                                                <th>الوصف</th>
                                                <th>مطلوب</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>رقم الملف</td><td>رقم ملف العميل</td><td>-</td></tr>
                                            <tr><td>المالك</td><td>اسم المالك/العميل</td><td><span class="badge bg-danger">مطلوب</span></td></tr>
                                            <tr><td>القطعه</td><td>رقم قطعة القطعة</td><td><span class="badge bg-danger">مطلوب</span></td></tr>
                                            <tr><td>الحي</td><td>اسم الحي</td><td>-</td></tr>
                                            <tr><td>المنطقة</td><td>اسم المنطقة</td><td>-</td></tr>
                                            <tr><td>المجاورة</td><td>اسم القطاع/المجاورة</td><td>-</td></tr>
                                            <tr><td>الاوضه</td><td>رقم/اسم الغرفة</td><td>-</td></tr>
                                            <tr><td>الممر</td><td>رقم/اسم الممر</td><td>-</td></tr>
                                            <tr><td>الاستند</td><td>رقم/اسم الستاند</td><td>-</td></tr>
                                            <tr><td>الرف</td><td>رقم/اسم الرف</td><td>-</td></tr>
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

    @include('dashboards.shared.scripts')

    <script>
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الرفع...';

        fetch('{{ route("admin.imports.upload") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.redirect) {
                window.location.href = data.redirect;
            } else {
                alert(data.error || 'حدث خطأ');
                btn.disabled = false;
                btn.innerHTML = '<i class="ti ti-upload me-1"></i> رفع والتحقق';
            }
        })
        .catch(err => {
            console.error(err);
            alert('حدث خطأ في الاتصال');
            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-upload me-1"></i> رفع والتحقق';
        });
    });
    </script>
</body>
</html>
