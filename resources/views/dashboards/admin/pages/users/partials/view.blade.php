<!-- User Details Content -->
<div class="row g-3">
    <!-- Basic Information -->
    <div class="col-12">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
            <i class="ti ti-user me-2"></i>Basic Information
        </h6>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('First Name', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->first_name ?? '-' }}</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Last Name', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->last_name ?? '-' }}</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('National ID', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->national_id ?? '-' }}</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Email', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->email }}</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Phone', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->phone ?? '-' }}</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Gender', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->gender ? ucfirst($user->gender) : '-' }}</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Birth Date', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->birth_date?->format('M d, Y') ?? '-' }}</p>
    </div>
    <div class="col-12">
        <label class="form-label text-muted small mb-1">{{ x_('Address', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->address ?? '-' }}</p>
    </div>

    <!-- Employee Information -->
    <div class="col-12 mt-4">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
            <i class="ti ti-briefcase me-2"></i>Employee Information
        </h6>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Employee Code', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->employee_code ?? '-' }}</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Employee Type', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->employee_type ? ucfirst($user->employee_type) : '-' }}</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Hire Date', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->hire_date?->format('M d, Y') ?? '-' }}</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Basic Salary', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ number_format($user->basic_salary, 2) }} EGP</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Piece Rate', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->piece_rate ? number_format($user->piece_rate, 2) . ' EGP' : '-' }}</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Employment Status', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">
            @if($user->employment_status)
                <span class="badge bg-{{ $user->employment_status === 'active' ? 'success' : ($user->employment_status === 'on_leave' ? 'warning' : 'danger') }}-subtle text-{{ $user->employment_status === 'active' ? 'success' : ($user->employment_status === 'on_leave' ? 'warning' : 'danger') }}">
                    {{ ucwords(str_replace('_', ' ', $user->employment_status)) }}
                </span>
            @else
                -
            @endif
        </p>
    </div>

    <!-- Access Information -->
    <div class="col-12 mt-4">
        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
            <i class="ti ti-shield me-2"></i>Access Information
        </h6>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Role', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">
            @if($user->roles->count() > 0)
                @foreach($user->roles as $role)
                    <span class="badge bg-primary-subtle text-primary">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</span>
                @endforeach
            @else
                <span class="text-muted">No role assigned</span>
            @endif
        </p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Account Status', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">
            <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}-subtle text-{{ $user->is_active ? 'success' : 'danger' }}">
                {{ $user->is_active ? 'Active' : 'Inactive' }}
            </span>
        </p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Email Verified', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->email_verified_at?->format('M d, Y H:i') ?? 'Not verified' }}</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Last Login', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->last_login_at?->format('M d, Y H:i') ?? 'Never' }}</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Created At', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->created_at?->format('M d, Y H:i') ?? '-' }}</p>
    </div>
    <div class="col-md-6">
        <label class="form-label text-muted small mb-1">{{ x_('Updated At', 'dashboards.admin.pages.user.users.partials.view') }}</label>
        <p class="mb-0 fw-medium">{{ $user->updated_at?->format('M d, Y H:i') ?? '-' }}</p>
    </div>
    @if($user->deleted_at)
    <div class="col-12">
        <div class="alert alert-warning mb-0">
            <i class="ti ti-alert-triangle me-2"></i>
            This user was deleted on {{ $user->deleted_at->format('M d, Y H:i') }}
        </div>
    </div>
    @endif
</div>
