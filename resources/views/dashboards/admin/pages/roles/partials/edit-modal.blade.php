<!-- Edit Role Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow" style="border-radius: var(--ins-border-radius);">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h4 class="modal-title fw-bold" id="editModalLabel">
                        <i class="ti ti-shield-cog me-2 text-primary"></i>Edit Role
                    </h4>
                    <p class="text-muted mb-0">Modify role permissions and settings</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ x_('Close', 'dashboards.admin.pages.user.roles.partials.edit-modal') }}"></button>
            </div>
            <form id="editForm">
                @csrf
                <input type="hidden" name="id" id="editId">
                <div class="modal-body pt-3" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Role Details Card -->
                    <div class="card border border-secondary border-opacity-10 shadow-sm mb-4"
                        style="border-radius: var(--ins-border-radius);">
                        <div class="card-header bg-light-subtle py-2">
                            <h6 class="mb-0 fw-semibold"><i class="ti ti-info-circle me-2 text-primary"></i>Role Details
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Role Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" id="editName"
                                        class="form-control shadow-sm border border-secondary border-opacity-10"
                                        style="border-radius: var(--ins-border-radius);"
                                        placeholder="e.g., sales-manager" required pattern="[a-z0-9\-]+">
                                    <small class="text-muted">{{ x_('Use lowercase letters, numbers, and hyphens only', 'dashboards.admin.pages.user.roles.partials.edit-modal') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">{{ x_('Description', 'dashboards.admin.pages.user.roles.partials.edit-modal') }}</label>
                                    <input type="text" name="description" id="editDescription"
                                        class="form-control shadow-sm border border-secondary border-opacity-10"
                                        style="border-radius: var(--ins-border-radius);"
                                        placeholder="{{ x_('Brief description of this role', 'dashboards.admin.pages.user.roles.partials.edit-modal') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Section -->
                    <div class="card border border-secondary border-opacity-10 shadow-sm"
                        style="border-radius: var(--ins-border-radius);">
                        <div class="card-header bg-light-subtle py-2 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold"><i class="ti ti-lock me-2 text-primary"></i>Role Permissions
                            </h6>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-primary shadow-sm" id="editTotalPermissionsCount">0
                                    selected</span>
                                <div class="form-check mb-0">
                                    <input type="checkbox" class="form-check-input" id="editSelectAllPermissions"
                                        style="width: 18px; height: 18px;">
                                    <label class="form-check-label fw-medium" for="editSelectAllPermissions">{{ x_('Select
                                        All', 'dashboards.admin.pages.user.roles.partials.edit-modal') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <!-- Permissions Accordion -->
                            <div class="accordion accordion-flush" id="editPermissionsAccordion">
                                @foreach ($hierarchicalPermissions ?? [] as $mainModule => $moduleData)
                                    @php $mainModuleId = Str::slug($mainModule); @endphp
                                    <div class="accordion-item border-0 mb-2">
                                        <!-- Main Module Header -->
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed py-2 px-3 shadow-sm"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#edit_{{ $mainModuleId }}" aria-expanded="false"
                                                aria-controls="edit_{{ $mainModuleId }}"
                                                style="border-radius: var(--ins-border-radius); background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                                <div
                                                    class="d-flex align-items-center justify-content-between w-100 me-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="form-check mb-0 me-3"
                                                            onclick="event.stopPropagation();">
                                                            <input type="checkbox"
                                                                class="form-check-input edit-main-module-check"
                                                                data-main-module="{{ $mainModuleId }}"
                                                                id="edit_module_{{ $mainModuleId }}"
                                                                style="width: 20px; height: 20px; border-radius: 4px;">
                                                        </div>
                                                        <div
                                                            class="avatar avatar-sm bg-primary-subtle text-primary rounded me-2 d-flex align-items-center justify-content-center">
                                                            <i class="{{ $moduleData['icon'] }}"></i>
                                                        </div>
                                                        <span class="fw-semibold text-dark">{{ $mainModule }}</span>
                                                    </div>
                                                    <span
                                                        class="badge bg-secondary-subtle text-secondary edit-module-count"
                                                        data-main-module="{{ $mainModuleId }}">
                                                        0 / {{ $moduleData['total_count'] }}
                                                    </span>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="edit_{{ $mainModuleId }}" class="accordion-collapse collapse"
                                            data-bs-parent="#editPermissionsAccordion">
                                            <div class="accordion-body py-2 px-3">
                                                @if (!empty($moduleData['submodules']))
                                                    <!-- Nested Submodules -->
                                                    @foreach ($moduleData['submodules'] as $subCategory => $subData)
                                                        @php $subCategoryId = $mainModuleId . '_' . Str::slug($subCategory); @endphp
                                                        <div class="card border border-secondary border-opacity-10 mb-2"
                                                            style="border-radius: var(--ins-border-radius);">
                                                            <div class="card-header py-2 px-3 bg-white d-flex justify-content-between align-items-center"
                                                                style="border-radius: var(--ins-border-radius) var(--ins-border-radius) 0 0;">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="form-check mb-0 me-2">
                                                                        <input type="checkbox"
                                                                            class="form-check-input edit-sub-module-check"
                                                                            data-main-module="{{ $mainModuleId }}"
                                                                            data-sub-module="{{ $subCategoryId }}"
                                                                            id="edit_sub_{{ $subCategoryId }}"
                                                                            style="width: 16px; height: 16px;">
                                                                    </div>
                                                                    <a class="text-decoration-none text-dark fw-medium d-flex align-items-center"
                                                                        data-bs-toggle="collapse"
                                                                        href="#edit_sub_content_{{ $subCategoryId }}"
                                                                        role="button">
                                                                        <i
                                                                            class="ti ti-chevron-right me-1 transition-transform collapse-chevron"></i>
                                                                        <span
                                                                            class="text-primary">{{ $subCategory }}</span>
                                                                    </a>
                                                                </div>
                                                                <span
                                                                    class="badge bg-light text-muted border edit-sub-count"
                                                                    data-sub-module="{{ $subCategoryId }}">
                                                                    0 / {{ $subData['total_count'] }}
                                                                </span>
                                                            </div>
                                                            <div class="collapse"
                                                                id="edit_sub_content_{{ $subCategoryId }}">
                                                                <div class="card-body py-2 px-3 bg-light-subtle">
                                                                    @foreach ($subData['modules'] as $moduleName => $modulePermissions)
                                                                        @php $moduleId = $mainModuleId . '_' . Str::slug($moduleName); @endphp
                                                                        <div
                                                                            class="mb-3 pb-2 border-bottom border-secondary border-opacity-10">
                                                                            <div
                                                                                class="d-flex align-items-center justify-content-between mb-2">
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="form-check mb-0 me-2">
                                                                                        <input type="checkbox"
                                                                                            class="form-check-input edit-group-check"
                                                                                            data-main-module="{{ $mainModuleId }}"
                                                                                            data-sub-module="{{ $subCategoryId }}"
                                                                                            data-group="{{ $moduleId }}"
                                                                                            id="edit_group_{{ $moduleId }}"
                                                                                            style="width: 14px; height: 14px;">
                                                                                    </div>
                                                                                    <span
                                                                                        class="fw-medium text-secondary">{{ ucwords(str_replace(['-', '_'], ' ', $moduleName)) }}</span>
                                                                                </div>
                                                                                <span
                                                                                    class="badge bg-white text-muted border small edit-group-count"
                                                                                    data-group="{{ $moduleId }}">
                                                                                    0 /
                                                                                    {{ $modulePermissions->count() }}
                                                                                </span>
                                                                            </div>
                                                                            <div class="row g-1 ms-4">
                                                                                @foreach ($modulePermissions as $permission)
                                                                                    <div
                                                                                        class="col-6 col-md-4 col-lg-3">
                                                                                        <div class="form-check mb-1">
                                                                                            <input type="checkbox"
                                                                                                name="permissions[]"
                                                                                                value="{{ $permission->name }}"
                                                                                                class="form-check-input edit-permission-item"
                                                                                                data-main-module="{{ $mainModuleId }}"
                                                                                                data-sub-module="{{ $subCategoryId }}"
                                                                                                data-group="{{ $moduleId }}"
                                                                                                id="edit_perm_{{ $permission->id }}"
                                                                                                style="width: 14px; height: 14px;">
                                                                                            <label
                                                                                                class="form-check-label small"
                                                                                                for="edit_perm_{{ $permission->id }}">
                                                                                                @php
                                                                                                    $label =
                                                                                                        $permission->name;

                                                                                                    $labelOverrides = [
                                                                                                        'access-employee-portal' =>
                                                                                                            'Access Employee Portal',
                                                                                                        'access-daily-worker-portal' =>
                                                                                                            'Access Daily Worker Portal',
                                                                                                    ];
                                                                                                    if (
                                                                                                        array_key_exists(
                                                                                                            $label,
                                                                                                            $labelOverrides,
                                                                                                        )
                                                                                                    ) {
                                                                                                        $label =
                                                                                                            $labelOverrides[
                                                                                                                $label
                                                                                                            ];
                                                                                                    }

                                                                                                    if (
                                                                                                        str_contains(
                                                                                                            $label,
                                                                                                            '-',
                                                                                                        ) &&
                                                                                                        !array_key_exists(
                                                                                                            $permission->name,
                                                                                                            $labelOverrides,
                                                                                                        )
                                                                                                    ) {
                                                                                                        $prefixMap = [
                                                                                                            'bulk-force-delete-' =>
                                                                                                                'Bulk Force Delete',
                                                                                                            'force-delete-' =>
                                                                                                                'Force Delete',
                                                                                                            'bulk-upload-' =>
                                                                                                                'Bulk Upload',
                                                                                                            'bulk-download-' =>
                                                                                                                'Bulk Download',
                                                                                                            'bulk-delete-' =>
                                                                                                                'Bulk Delete',
                                                                                                            'bulk-restore-' =>
                                                                                                                'Bulk Restore',
                                                                                                            'create-' =>
                                                                                                                'Create',
                                                                                                            'view-' =>
                                                                                                                'View',
                                                                                                            'edit-' =>
                                                                                                                'Edit',
                                                                                                            'delete-' =>
                                                                                                                'Delete',
                                                                                                            'restore-' =>
                                                                                                                'Restore',
                                                                                                            'update-' =>
                                                                                                                'Update',
                                                                                                            'approve-' =>
                                                                                                                'Approve',
                                                                                                            'reject-' =>
                                                                                                                'Reject',
                                                                                                            'cancel-' =>
                                                                                                                'Cancel',
                                                                                                            'calculate-' =>
                                                                                                                'Calculate',
                                                                                                            'process-' =>
                                                                                                                'Process',
                                                                                                            'finalize-' =>
                                                                                                                'Finalize',
                                                                                                            'pay-' =>
                                                                                                                'Pay',
                                                                                                            'print-' =>
                                                                                                                'Print',
                                                                                                            'receive-' =>
                                                                                                                'Receive',
                                                                                                            'return-' =>
                                                                                                                'Return',
                                                                                                            'assign-' =>
                                                                                                                'Assign',
                                                                                                            'initialize-' =>
                                                                                                                'Initialize',
                                                                                                            'set-active-' =>
                                                                                                                'Set Active',
                                                                                                            'check-in-' =>
                                                                                                                'Check In',
                                                                                                            'check-out-' =>
                                                                                                                'Check Out',
                                                                                                            'access-' =>
                                                                                                                'Access',
                                                                                                            'download-' =>
                                                                                                                'Download',
                                                                                                            'escalate-' =>
                                                                                                                'Escalate',
                                                                                                            'resolve-' =>
                                                                                                                'Resolve',
                                                                                                            'complete-' =>
                                                                                                                'Complete',
                                                                                                        ];

                                                                                                        foreach (
                                                                                                            $prefixMap
                                                                                                            as $prefix =>
                                                                                                                $title
                                                                                                        ) {
                                                                                                            if (
                                                                                                                str_starts_with(
                                                                                                                    $label,
                                                                                                                    $prefix,
                                                                                                                )
                                                                                                            ) {
                                                                                                                $rest = substr(
                                                                                                                    $label,
                                                                                                                    strlen(
                                                                                                                        $prefix,
                                                                                                                    ),
                                                                                                                );
                                                                                                                $rest = trim(
                                                                                                                    str_replace(
                                                                                                                        [
                                                                                                                            '-',
                                                                                                                            '_',
                                                                                                                            '.',
                                                                                                                        ],
                                                                                                                        ' ',
                                                                                                                        $rest,
                                                                                                                    ),
                                                                                                                );
                                                                                                                $label =
                                                                                                                    $title .
                                                                                                                    ($rest !==
                                                                                                                    ''
                                                                                                                        ? ' ' .
                                                                                                                            ucwords(
                                                                                                                                $rest,
                                                                                                                            )
                                                                                                                        : '');
                                                                                                                break;
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                @endphp
                                                                                                {{ $label }}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <!-- Flat Permissions -->
                                                    @foreach ($moduleData['permissions'] as $moduleName => $modulePermissions)
                                                        @php $moduleId = $mainModuleId . '_' . Str::slug($moduleName); @endphp
                                                        <div class="card border border-secondary border-opacity-10 mb-2"
                                                            style="border-radius: var(--ins-border-radius);">
                                                            <div class="card-body py-2 px-3">
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between mb-2">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="form-check mb-0 me-2">
                                                                            <input type="checkbox"
                                                                                class="form-check-input edit-group-check"
                                                                                data-main-module="{{ $mainModuleId }}"
                                                                                data-group="{{ $moduleId }}"
                                                                                id="edit_group_{{ $moduleId }}"
                                                                                style="width: 16px; height: 16px;">
                                                                        </div>
                                                                        <span
                                                                            class="fw-medium text-primary">{{ ucwords(str_replace(['-', '_'], ' ', $moduleName)) }}</span>
                                                                    </div>
                                                                    <span
                                                                        class="badge bg-light text-muted border edit-group-count"
                                                                        data-group="{{ $moduleId }}">
                                                                        0 / {{ $modulePermissions->count() }}
                                                                    </span>
                                                                </div>
                                                                <div class="row g-1 ms-4">
                                                                    @foreach ($modulePermissions as $permission)
                                                                        <div class="col-6 col-md-4 col-lg-3">
                                                                            <div class="form-check mb-1">
                                                                                <input type="checkbox"
                                                                                    name="permissions[]"
                                                                                    value="{{ $permission->name }}"
                                                                                    class="form-check-input edit-permission-item"
                                                                                    data-main-module="{{ $mainModuleId }}"
                                                                                    data-group="{{ $moduleId }}"
                                                                                    id="edit_perm_{{ $permission->id }}"
                                                                                    style="width: 14px; height: 14px;">
                                                                                <label class="form-check-label small"
                                                                                    for="edit_perm_{{ $permission->id }}">
                                                                                    @php
                                                                                        $label = $permission->name;

                                                                                        $labelOverrides = [
                                                                                            'access-employee-portal' =>
                                                                                                'Access Employee Portal',
                                                                                            'access-daily-worker-portal' =>
                                                                                                'Access Daily Worker Portal',
                                                                                        ];
                                                                                        if (
                                                                                            array_key_exists(
                                                                                                $label,
                                                                                                $labelOverrides,
                                                                                            )
                                                                                        ) {
                                                                                            $label =
                                                                                                $labelOverrides[$label];
                                                                                        }

                                                                                        if (
                                                                                            str_contains($label, '-') &&
                                                                                            !array_key_exists(
                                                                                                $permission->name,
                                                                                                $labelOverrides,
                                                                                            )
                                                                                        ) {
                                                                                            $prefixMap = [
                                                                                                'bulk-force-delete-' =>
                                                                                                    'Bulk Force Delete',
                                                                                                'force-delete-' =>
                                                                                                    'Force Delete',
                                                                                                'bulk-upload-' =>
                                                                                                    'Bulk Upload',
                                                                                                'bulk-download-' =>
                                                                                                    'Bulk Download',
                                                                                                'bulk-delete-' =>
                                                                                                    'Bulk Delete',
                                                                                                'bulk-restore-' =>
                                                                                                    'Bulk Restore',
                                                                                                'create-' => 'Create',
                                                                                                'view-' => 'View',
                                                                                                'edit-' => 'Edit',
                                                                                                'delete-' => 'Delete',
                                                                                                'restore-' => 'Restore',
                                                                                                'update-' => 'Update',
                                                                                                'approve-' => 'Approve',
                                                                                                'reject-' => 'Reject',
                                                                                                'cancel-' => 'Cancel',
                                                                                                'calculate-' =>
                                                                                                    'Calculate',
                                                                                                'process-' => 'Process',
                                                                                                'finalize-' =>
                                                                                                    'Finalize',
                                                                                                'pay-' => 'Pay',
                                                                                                'print-' => 'Print',
                                                                                                'receive-' => 'Receive',
                                                                                                'return-' => 'Return',
                                                                                                'assign-' => 'Assign',
                                                                                                'initialize-' =>
                                                                                                    'Initialize',
                                                                                                'set-active-' =>
                                                                                                    'Set Active',
                                                                                                'check-in-' =>
                                                                                                    'Check In',
                                                                                                'check-out-' =>
                                                                                                    'Check Out',
                                                                                                'access-' => 'Access',
                                                                                                'download-' =>
                                                                                                    'Download',
                                                                                                'escalate-' =>
                                                                                                    'Escalate',
                                                                                                'resolve-' => 'Resolve',
                                                                                                'complete-' =>
                                                                                                    'Complete',
                                                                                            ];

                                                                                            foreach (
                                                                                                $prefixMap
                                                                                                as $prefix => $title
                                                                                            ) {
                                                                                                if (
                                                                                                    str_starts_with(
                                                                                                        $label,
                                                                                                        $prefix,
                                                                                                    )
                                                                                                ) {
                                                                                                    $rest = substr(
                                                                                                        $label,
                                                                                                        strlen($prefix),
                                                                                                    );
                                                                                                    $rest = trim(
                                                                                                        str_replace(
                                                                                                            [
                                                                                                                '-',
                                                                                                                '_',
                                                                                                                '.',
                                                                                                            ],
                                                                                                            ' ',
                                                                                                            $rest,
                                                                                                        ),
                                                                                                    );
                                                                                                    $label =
                                                                                                        $title .
                                                                                                        ($rest !== ''
                                                                                                            ? ' ' .
                                                                                                                ucwords(
                                                                                                                    $rest,
                                                                                                                )
                                                                                                            : '');
                                                                                                    break;
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    @endphp
                                                                                    {{ $label }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light-subtle"
                    style="border-radius: 0 0 var(--ins-border-radius) var(--ins-border-radius);">
                    <button type="button" class="btn btn-light shadow-sm px-4"
                        style="border-radius: var(--ins-border-radius);" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>{{ x_('Cancel', 'dashboards.admin.pages.user.roles.partials.edit-modal') }}</button>
                    <button type="submit" class="btn btn-primary shadow-sm px-4"
                        style="border-radius: var(--ins-border-radius);">
                        <i class="ti ti-check me-1"></i>{{ x_('Save Changes', 'dashboards.admin.pages.user.roles.partials.edit-modal') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #editModal .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%) !important;
        color: #1976d2;
    }

    #editModal .accordion-button:focus {
        box-shadow: none;
    }

    #editModal .form-check-input:checked {
        background-color: black;
        border-color: #212529 !important;
    }

    #editModal .form-check-input {
        border: 2px solid #6c757d;
    }

    #editModal .edit-permission-item:checked+label {
        color: var(--bs-primary);
        font-weight: 500;
    }
</style>
