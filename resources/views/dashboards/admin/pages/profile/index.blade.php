<!DOCTYPE html>
@include('dashboards.shared.html')

<head>
    @include('dashboards.shared.meta')
    <title>الملف الشخصي - نظام أرشيف القاهرة الجديدة</title>
    <style>
        .profile-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 16px;
            overflow: hidden;
            position: relative;
        }
        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }
        .avatar-upload {
            position: relative;
            display: inline-block;
        }
        .avatar-upload .avatar-preview {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 5px solid rgba(255,255,255,0.3);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
        }
        .avatar-upload .avatar-edit {
            position: absolute;
            bottom: 5px;
            right: 5px;
            z-index: 1;
        }
        .avatar-upload .avatar-edit input {
            display: none;
        }
        .avatar-upload .avatar-edit label {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            color: #334155;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .avatar-upload .avatar-edit label:hover {
            background: #f1f5f9;
            transform: scale(1.1);
        }
        .profile-stat-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }
        .profile-stat-card:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }
        .nav-profile-tabs .nav-link {
            border: none;
            color: #64748b;
            font-weight: 500;
            padding: 1rem 1.5rem;
            border-radius: 10px 10px 0 0;
            transition: all 0.3s ease;
        }
        .nav-profile-tabs .nav-link:hover {
            color: #334155;
            background: #f8fafc;
        }
        .nav-profile-tabs .nav-link.active {
            color: #3b82f6;
            background: #fff;
            box-shadow: 0 -2px 10px rgba(59, 130, 246, 0.1);
        }
        .form-floating-custom {
            position: relative;
        }
        .form-floating-custom label {
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.5rem;
        }
        .form-floating-custom .form-control {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        .form-floating-custom .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .info-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 12px;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }
        .info-item:hover {
            background: #f1f5f9;
        }
        .info-item .icon-box {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 1rem;
            flex-shrink: 0;
        }
        .permission-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            margin: 0.25rem;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
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
                        <div class="page-title-box mb-3 mt-3">
                            <div class="py-2 px-3 bg-body border border-secondary border-opacity-10 shadow-sm"
                                style="border-radius: var(--ins-border-radius);">
                                <h4 class="mb-1"><i class="ti ti-user-circle me-2"></i>الملف الشخصي</h4>
                                <p class="text-muted mb-0">إدارة معلوماتك الشخصية وإعدادات الحساب</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ti ti-alert-circle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Profile Header Card -->
                <div class="card border-0 shadow-lg mb-4 profile-header">
                    <div class="card-body p-4 position-relative" style="z-index: 1;">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-upload">
                                    <div class="avatar-preview">
                                        @if($user->avatar_url)
                                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-100 h-100 rounded-circle" style="object-fit: cover;">
                                        @else
                                            {{ $user->initials }}
                                        @endif
                                    </div>
                                    <div class="avatar-edit">
                                        <input type="file" id="avatarUpload" accept="image/*" form="profileForm" name="avatar">
                                        <label for="avatarUpload" title="تغيير الصورة">
                                            <i class="ti ti-camera"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <h2 class="text-white mb-1 fw-bold">{{ $user->name }}</h2>
                                <p class="text-white-50 mb-2">
                                    <i class="ti ti-mail me-1"></i>{{ $user->email }}
                                </p>
                                @if($user->job_title || $user->department)
                                    <p class="text-white-50 mb-3">
                                        @if($user->job_title)
                                            <span class="me-3"><i class="ti ti-briefcase me-1"></i>{{ $user->job_title }}</span>
                                        @endif
                                        @if($user->department)
                                            <span><i class="ti ti-building me-1"></i>{{ $user->department }}</span>
                                        @endif
                                    </p>
                                @endif
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($user->roles as $role)
                                        <span class="role-badge">
                                            <i class="ti ti-shield-check me-1"></i>{{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="profile-stat-card">
                                            <div class="text-white-50 small mb-1">تاريخ الانضمام</div>
                                            <div class="text-white fw-bold">{{ $user->created_at->format('Y/m/d') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="profile-stat-card">
                                            <div class="text-white-50 small mb-1">آخر تسجيل دخول</div>
                                            <div class="text-white fw-bold">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'غير متوفر' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="profile-stat-card">
                                            <div class="text-white-50 small mb-1">الحالة</div>
                                            <div class="text-white fw-bold">
                                                @if($user->is_active)
                                                    <span class="badge bg-success">نشط</span>
                                                @else
                                                    <span class="badge bg-danger">غير نشط</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="profile-stat-card">
                                            <div class="text-white-50 small mb-1">الصلاحيات</div>
                                            <div class="text-white fw-bold">{{ $user->getAllPermissions()->count() }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="row g-4">
                    <!-- Left Column - Edit Forms -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                            <!-- Tabs Navigation -->
                            <ul class="nav nav-profile-tabs bg-light border-bottom" id="profileTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">
                                        <i class="ti ti-user-edit me-2"></i>المعلومات الشخصية
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button">
                                        <i class="ti ti-lock me-2"></i>الأمان
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button">
                                        <i class="ti ti-shield me-2"></i>الصلاحيات
                                    </button>
                                </li>
                            </ul>

                            <!-- Tabs Content -->
                            <div class="tab-content p-4" id="profileTabsContent">
                                <!-- Personal Info Tab -->
                                <div class="tab-pane fade show active" id="info" role="tabpanel">
                                    <form id="profileForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="form-floating-custom">
                                                    <label for="first_name">
                                                        <i class="ti ti-user text-primary me-1"></i>الاسم الأول <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                                           id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                                                    @error('first_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating-custom">
                                                    <label for="last_name">
                                                        <i class="ti ti-user text-primary me-1"></i>الاسم الأخير <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                                           id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                                                    @error('last_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating-custom">
                                                    <label for="email">
                                                        <i class="ti ti-mail text-info me-1"></i>البريد الإلكتروني <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating-custom">
                                                    <label for="phone">
                                                        <i class="ti ti-phone text-success me-1"></i>رقم الهاتف
                                                    </label>
                                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}" dir="ltr">
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating-custom">
                                                    <label for="job_title">
                                                        <i class="ti ti-briefcase text-warning me-1"></i>المسمى الوظيفي
                                                    </label>
                                                    <input type="text" class="form-control @error('job_title') is-invalid @enderror"
                                                           id="job_title" name="job_title" value="{{ old('job_title', $user->job_title) }}">
                                                    @error('job_title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating-custom">
                                                    <label for="department">
                                                        <i class="ti ti-building text-secondary me-1"></i>القسم
                                                    </label>
                                                    <input type="text" class="form-control @error('department') is-invalid @enderror"
                                                           id="department" name="department" value="{{ old('department', $user->department) }}">
                                                    @error('department')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-floating-custom">
                                                    <label for="bio">
                                                        <i class="ti ti-notes text-purple me-1"></i>نبذة شخصية
                                                    </label>
                                                    <textarea class="form-control @error('bio') is-invalid @enderror"
                                                              id="bio" name="bio" rows="4" maxlength="1000">{{ old('bio', $user->bio) }}</textarea>
                                                    <small class="text-muted">
                                                        <span id="bioCount">{{ strlen($user->bio ?? '') }}</span>/1000 حرف
                                                    </small>
                                                    @error('bio')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <hr class="my-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    @if($user->avatar_url)
                                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#removeAvatarModal">
                                                            <i class="ti ti-trash me-1"></i>حذف الصورة
                                                        </button>
                                                    @else
                                                        <div></div>
                                                    @endif
                                                    <button type="submit" class="btn btn-primary px-4">
                                                        <i class="ti ti-device-floppy me-1"></i>حفظ التغييرات
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Security Tab -->
                                <div class="tab-pane fade" id="security" role="tabpanel">
                                    <div class="mb-4">
                                        <h5 class="fw-bold mb-1"><i class="ti ti-key me-2 text-warning"></i>تغيير كلمة المرور</h5>
                                        <p class="text-muted small">قم بتحديث كلمة المرور الخاصة بك بشكل دوري للحفاظ على أمان حسابك</p>
                                    </div>
                                    <form action="{{ route('admin.profile.password') }}" method="POST">
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <div class="form-floating-custom">
                                                    <label for="current_password">
                                                        <i class="ti ti-lock text-secondary me-1"></i>كلمة المرور الحالية <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                                               id="current_password" name="current_password" required>
                                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                                            <i class="ti ti-eye"></i>
                                                        </button>
                                                    </div>
                                                    @error('current_password')
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating-custom">
                                                    <label for="password">
                                                        <i class="ti ti-lock-plus text-primary me-1"></i>كلمة المرور الجديدة <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                               id="password" name="password" required>
                                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                            <i class="ti ti-eye"></i>
                                                        </button>
                                                    </div>
                                                    @error('password')
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating-custom">
                                                    <label for="password_confirmation">
                                                        <i class="ti ti-lock-check text-success me-1"></i>تأكيد كلمة المرور <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control"
                                                               id="password_confirmation" name="password_confirmation" required>
                                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                                            <i class="ti ti-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="alert alert-info d-flex align-items-start">
                                                    <i class="ti ti-info-circle fs-4 me-2 mt-1"></i>
                                                    <div>
                                                        <strong>متطلبات كلمة المرور:</strong>
                                                        <ul class="mb-0 mt-1 small">
                                                            <li>يجب أن تحتوي على 8 أحرف على الأقل</li>
                                                            <li>يُفضل استخدام أحرف كبيرة وصغيرة وأرقام ورموز</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <hr class="my-3">
                                                <div class="d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-warning px-4">
                                                        <i class="ti ti-key me-1"></i>تغيير كلمة المرور
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Permissions Tab -->
                                <div class="tab-pane fade" id="permissions" role="tabpanel">
                                    <div class="mb-4">
                                        <h5 class="fw-bold mb-1"><i class="ti ti-shield-check me-2 text-primary"></i>الأدوار والصلاحيات</h5>
                                        <p class="text-muted small">عرض الأدوار والصلاحيات المُسندة إليك في النظام</p>
                                    </div>

                                    <!-- Roles -->
                                    <div class="mb-4">
                                        <h6 class="fw-bold text-secondary mb-3">
                                            <i class="ti ti-crown me-1"></i>الأدوار ({{ $user->roles->count() }})
                                        </h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @forelse($user->roles as $role)
                                                <span class="role-badge">
                                                    <i class="ti ti-shield-check me-1"></i>{{ $role->name }}
                                                </span>
                                            @empty
                                                <span class="text-muted">لا توجد أدوار مُسندة</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <!-- Permissions -->
                                    <div>
                                        <h6 class="fw-bold text-secondary mb-3">
                                            <i class="ti ti-key me-1"></i>الصلاحيات ({{ $user->getAllPermissions()->count() }})
                                        </h6>
                                        @php
                                            $permissions = $user->getAllPermissions()->groupBy(function($permission) {
                                                return explode('.', $permission->name)[0] ?? 'أخرى';
                                            });
                                        @endphp

                                        @forelse($permissions as $group => $groupPermissions)
                                            <div class="mb-3">
                                                <h6 class="text-uppercase text-muted small fw-bold mb-2">{{ translate_module($group) }}</h6>
                                                <div class="d-flex flex-wrap">
                                                    @foreach($groupPermissions as $permission)
                                                        <span class="permission-badge">
                                                            <i class="ti ti-check text-success me-1"></i>
                                                            {{ translate_permission($permission->name) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-4">
                                                <i class="ti ti-lock-off fs-1 text-muted opacity-50 d-block mb-2"></i>
                                                <span class="text-muted">لا توجد صلاحيات مُسندة</span>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Quick Info -->
                    <div class="col-lg-4">
                        <!-- Account Info Card -->
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h5 class="card-title mb-0 fw-bold">
                                    <i class="ti ti-info-circle text-primary me-2"></i>معلومات الحساب
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="info-item">
                                    <div class="icon-box bg-primary-subtle text-primary">
                                        <i class="ti ti-id"></i>
                                    </div>
                                    <div class="mx-1">
                                        <small class="text-muted d-block">رقم المستخدم</small>
                                        <strong>#{{ $user->id }}</strong>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="icon-box bg-success-subtle text-success">
                                        <i class="ti ti-calendar-plus"></i>
                                    </div>
                                    <div class="mx-1">
                                        <small class="text-muted d-block">تاريخ الإنشاء</small>
                                        <strong>{{ $user->created_at->format('Y/m/d - h:i A') }}</strong>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="icon-box bg-info-subtle text-info">
                                        <i class="ti ti-refresh"></i>
                                    </div>
                                    <div class="mx-1">
                                        <small class="text-muted d-block">آخر تحديث</small>
                                        <strong>{{ $user->updated_at->format('Y/m/d - h:i A') }}</strong>
                                    </div>
                                </div>
                                @if($user->last_login_at)
                                    <div class="info-item">
                                        <div class="icon-box bg-warning-subtle text-warning">
                                            <i class="ti ti-login"></i>
                                        </div>
                                        <div class="mx-1">
                                            <small class="text-muted d-block">آخر تسجيل دخول</small>
                                            <strong>{{ $user->last_login_at->format('Y/m/d - h:i A') }}</strong>
                                        </div>
                                    </div>
                                @endif
                                @if($user->last_login_ip)
                                    <div class="info-item">
                                        <div class="icon-box bg-secondary-subtle text-secondary">
                                            <i class="ti ti-world"></i>
                                        </div>
                                        <div class="mx-1">
                                            <small class="text-muted d-block">آخر IP</small>
                                            <strong dir="ltr">{{ $user->last_login_ip }}</strong>
                                        </div>
                                    </div>
                                @endif
                                @if($user->email_verified_at)
                                    <div class="info-item">
                                        <div class="icon-box bg-success-subtle text-success">
                                            <i class="ti ti-mail-check"></i>
                                        </div>
                                        <div class="mx-1">
                                            <small class="text-muted d-block">تأكيد البريد</small>
                                            <strong class="text-success">تم التأكيد</strong>
                                        </div>
                                    </div>
                                @else
                                    <div class="info-item">
                                        <div class="icon-box bg-danger-subtle text-danger">
                                            <i class="ti ti-mail-x"></i>
                                        </div>
                                        <div class="mx-1">
                                            <small class="text-muted d-block">تأكيد البريد</small>
                                            <strong class="text-danger">غير مؤكد</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Quick Actions Card -->
                        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h5 class="card-title mb-0 fw-bold">
                                    <i class="ti ti-bolt text-warning me-2"></i>إجراءات سريعة
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-soft-primary text-start">
                                        <i class="ti ti-dashboard me-2"></i>الذهاب للوحة التحكم
                                    </a>
                                    <button type="button" class="btn btn-soft-warning text-start" onclick="document.getElementById('security-tab').click();">
                                        <i class="ti ti-key me-2"></i>تغيير كلمة المرور
                                    </button>
                                    <form action="{{ route('logout') }}" method="POST" class="d-grid">
                                        @csrf
                                        <button type="submit" class="btn btn-soft-danger text-start">
                                            <i class="ti ti-logout me-2"></i>تسجيل الخروج
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Remove Avatar Modal -->
    <div class="modal fade" id="removeAvatarModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">
                        <i class="ti ti-trash text-danger me-2"></i>حذف الصورة الشخصية
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="ti ti-photo-off text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <p class="mb-0">هل أنت متأكد من حذف صورتك الشخصية؟</p>
                    <p class="text-muted small">سيتم استخدام الأحرف الأولى من اسمك بدلاً منها</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">إلغاء</button>
                    <form action="{{ route('admin.profile.remove-avatar') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="ti ti-trash me-1"></i>حذف الصورة
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('dashboards.shared.scripts')

    <script>
        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                input.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        }

        // Bio character counter
        document.getElementById('bio').addEventListener('input', function() {
            document.getElementById('bioCount').textContent = this.value.length;
        });

        // Avatar preview
        document.getElementById('avatarUpload').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.avatar-preview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-100 h-100 rounded-circle" style="object-fit: cover;">`;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
</body>
</html>
