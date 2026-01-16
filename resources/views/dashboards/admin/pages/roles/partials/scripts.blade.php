<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Initialize view from localStorage
    const savedView = localStorage.getItem('rolesViewMode') || 'list';
    toggleView(savedView);

    // Select All checkbox
    $('#selectAll').on('change', function() {
        $('.row-checkbox:not(:disabled)').prop('checked', $(this).prop('checked'));
        toggleBulkActions();
    });

    $(document).on('change', '.row-checkbox', toggleBulkActions);

    function toggleBulkActions() {
        const visibleView = $('#tableView:not(.d-none), #cardView:not(.d-none)'); const checked = visibleView.find('.row-checkbox:checked').length;
        if (checked > 0) {
            $('#bulkActions').removeClass('d-none');
        } else {
            $('#bulkActions').addClass('d-none');
        }
    }

    // =====================
    // CREATE FORM - Hierarchical Permission Handling
    // =====================

    // Select all permissions for create form
    $('#createSelectAllPermissions').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.create-permission-item').prop('checked', isChecked);
        $('.create-group-check').prop('checked', isChecked);
        $('.create-sub-module-check').prop('checked', isChecked);
        $('.create-main-module-check').prop('checked', isChecked);
        updateCreatePermissionCounts();
    });

    // Main module checkbox toggle for create form
    $(document).on('change', '.create-main-module-check', function() {
        const mainModule = $(this).data('main-module');
        const isChecked = $(this).prop('checked');
        $(`.create-permission-item[data-main-module="${mainModule}"]`).prop('checked', isChecked);
        $(`.create-group-check[data-main-module="${mainModule}"]`).prop('checked', isChecked);
        $(`.create-sub-module-check[data-main-module="${mainModule}"]`).prop('checked', isChecked);
        updateCreatePermissionCounts();
    });

    // Sub-module checkbox toggle for create form
    $(document).on('change', '.create-sub-module-check', function() {
        const subModule = $(this).data('sub-module');
        const mainModule = $(this).data('main-module');
        const isChecked = $(this).prop('checked');
        $(`.create-permission-item[data-sub-module="${subModule}"]`).prop('checked', isChecked);
        $(`.create-group-check[data-sub-module="${subModule}"]`).prop('checked', isChecked);
        updateCreateMainModuleCheckbox(mainModule);
        updateCreatePermissionCounts();
    });

    // Group checkbox toggle for create form
    $(document).on('change', '.create-group-check', function() {
        const group = $(this).data('group');
        const mainModule = $(this).data('main-module');
        const subModule = $(this).data('sub-module');
        const isChecked = $(this).prop('checked');
        $(`.create-permission-item[data-group="${group}"]`).prop('checked', isChecked);
        if (subModule) updateCreateSubModuleCheckbox(subModule);
        updateCreateMainModuleCheckbox(mainModule);
        updateCreatePermissionCounts();
    });

    // Individual permission change for create form
    $(document).on('change', '.create-permission-item', function() {
        const group = $(this).data('group');
        const mainModule = $(this).data('main-module');
        const subModule = $(this).data('sub-module');
        updateCreateGroupCheckbox(group);
        if (subModule) updateCreateSubModuleCheckbox(subModule);
        updateCreateMainModuleCheckbox(mainModule);
        updateCreatePermissionCounts();
    });

    // =====================
    // EDIT FORM - Hierarchical Permission Handling
    // =====================

    // Select all permissions for edit form
    $('#editSelectAllPermissions').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.edit-permission-item').prop('checked', isChecked);
        $('.edit-group-check').prop('checked', isChecked);
        $('.edit-sub-module-check').prop('checked', isChecked);
        $('.edit-main-module-check').prop('checked', isChecked);
        updateEditPermissionCounts();
    });

    // Main module checkbox toggle for edit form
    $(document).on('change', '.edit-main-module-check', function() {
        const mainModule = $(this).data('main-module');
        const isChecked = $(this).prop('checked');
        $(`.edit-permission-item[data-main-module="${mainModule}"]`).prop('checked', isChecked);
        $(`.edit-group-check[data-main-module="${mainModule}"]`).prop('checked', isChecked);
        $(`.edit-sub-module-check[data-main-module="${mainModule}"]`).prop('checked', isChecked);
        updateEditPermissionCounts();
    });

    // Sub-module checkbox toggle for edit form
    $(document).on('change', '.edit-sub-module-check', function() {
        const subModule = $(this).data('sub-module');
        const mainModule = $(this).data('main-module');
        const isChecked = $(this).prop('checked');
        $(`.edit-permission-item[data-sub-module="${subModule}"]`).prop('checked', isChecked);
        $(`.edit-group-check[data-sub-module="${subModule}"]`).prop('checked', isChecked);
        updateEditMainModuleCheckbox(mainModule);
        updateEditPermissionCounts();
    });

    // Group checkbox toggle for edit form
    $(document).on('change', '.edit-group-check', function() {
        const group = $(this).data('group');
        const mainModule = $(this).data('main-module');
        const subModule = $(this).data('sub-module');
        const isChecked = $(this).prop('checked');
        $(`.edit-permission-item[data-group="${group}"]`).prop('checked', isChecked);
        if (subModule) updateEditSubModuleCheckbox(subModule);
        updateEditMainModuleCheckbox(mainModule);
        updateEditPermissionCounts();
    });

    // Individual permission change for edit form
    $(document).on('change', '.edit-permission-item', function() {
        const group = $(this).data('group');
        const mainModule = $(this).data('main-module');
        const subModule = $(this).data('sub-module');
        updateEditGroupCheckbox(group);
        if (subModule) updateEditSubModuleCheckbox(subModule);
        updateEditMainModuleCheckbox(mainModule);
        updateEditPermissionCounts();
    });

    // Create Form Submit
    $('#createForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...');

        $.ajax({
            url: '{{ route("admin.roles.store") }}',
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    showSuccessToast(response.message);
                    hideModal('createModal');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showErrorToast(response.message || 'Failed to create role.');
                    btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Create Role');
                }
            },
            error: function(xhr) {
                showErrorToast(xhr.responseJSON?.message || 'Failed to create role.');
                btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Create Role');
            }
        });
    });

    // Edit Form Submit
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');
        const id = $('#editId').val();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...');

        $.ajax({
            url: `{{ url('admin/roles') }}/${id}`,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    showSuccessToast(response.message);
                    hideModal('editModal');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showErrorToast(response.message || 'Failed to update role.');
                    btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Update Role');
                }
            },
            error: function(xhr) {
                showErrorToast(xhr.responseJSON?.message || 'Failed to update role.');
                btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Update Role');
            }
        });
    });
});

// =====================
// View Toggle
// =====================
function toggleView(mode) {
    localStorage.setItem('rolesViewMode', mode);
    if (mode === 'card') {
        $('#listView').addClass('d-none');
        $('#cardView').removeClass('d-none');
        $('#listViewBtn').removeClass('active');
        $('#cardViewBtn').addClass('active');
    } else {
        $('#listView').removeClass('d-none');
        $('#cardView').addClass('d-none');
        $('#listViewBtn').addClass('active');
        $('#cardViewBtn').removeClass('active');
    }
}

// =====================
// View Record
// =====================
function viewRecord(id) {
    // Show loading, hide data
    $('#showLoading').removeClass('d-none');
    $('#showData').addClass('d-none');

    // Reset all checkboxes
    $('.show-permission-item').prop('checked', false);

    new bootstrap.Modal(document.getElementById('showModal')).show();

    $.ajax({
        url: `{{ url('admin/roles') }}/${id}/edit`,
        type: 'GET',
        success: function(data) {
            // Update role details
            $('#showRoleName').text(data.name.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase()));
            $('#showPermissionCount').text(data.permissions ? data.permissions.length : 0);
            $('#showUserCount').text(data.users_count || 0);
            $('#showTotalPermissionsCount').text((data.permissions ? data.permissions.length : 0) + ' permissions');

            // Check the permissions
            if (data.permissions && data.permissions.length > 0) {
                data.permissions.forEach(function(permName) {
                    $(`.show-permission-item[data-permission="${permName}"]`).prop('checked', true);
                });
            }

            // Update counts for show modal
            updateShowPermissionCounts();

            // Hide loading, show data
            $('#showLoading').addClass('d-none');
            $('#showData').removeClass('d-none');
        },
        error: function(xhr) {
            $('#showLoading').addClass('d-none');
            $('#showData').html('<div class="alert alert-danger">Failed to load role details.</div>').removeClass('d-none');
        }
    });
}

// Update Show Modal Permission Counts
function updateShowPermissionCounts() {
    // Update group counts
    $('.show-group-count').each(function() {
        const group = $(this).data('group');
        const total = $(`.show-permission-item[data-group="${group}"]`).length;
        const checked = $(`.show-permission-item[data-group="${group}"]:checked`).length;
        $(this).text(`${checked} / ${total}`);
        if (checked > 0) {
            $(this).removeClass('bg-light text-muted').addClass('bg-primary-subtle text-primary');
        } else {
            $(this).removeClass('bg-primary-subtle text-primary').addClass('bg-light text-muted');
        }
    });

    // Update sub-module counts
    $('.show-sub-count').each(function() {
        const subModule = $(this).data('sub-module');
        const total = $(`.show-permission-item[data-sub-module="${subModule}"]`).length;
        const checked = $(`.show-permission-item[data-sub-module="${subModule}"]:checked`).length;
        $(this).text(`${checked} / ${total}`);
        if (checked > 0) {
            $(this).removeClass('bg-light text-muted').addClass('bg-primary-subtle text-primary');
        } else {
            $(this).removeClass('bg-primary-subtle text-primary').addClass('bg-light text-muted');
        }
    });

    // Update main module counts
    $('.show-module-count').each(function() {
        const mainModule = $(this).data('main-module');
        const total = $(`.show-permission-item[data-main-module="${mainModule}"]`).length;
        const checked = $(`.show-permission-item[data-main-module="${mainModule}"]:checked`).length;
        $(this).text(`${checked} / ${total}`);
        if (checked > 0) {
            $(this).removeClass('bg-secondary-subtle text-secondary').addClass('bg-primary-subtle text-primary');
        } else {
            $(this).removeClass('bg-primary-subtle text-primary').addClass('bg-secondary-subtle text-secondary');
        }
    });
}

// =====================
// Edit Record
// =====================
function editRecord(id) {
    // Reset all checkboxes
    $('.edit-permission-item').prop('checked', false);
    $('.edit-group-check').prop('checked', false);
    $('.edit-sub-module-check').prop('checked', false);
    $('.edit-main-module-check').prop('checked', false);
    $('#editSelectAllPermissions').prop('checked', false);

    $.ajax({
        url: `{{ url('admin/roles') }}/${id}/edit`,
        type: 'GET',
        success: function(data) {
            $('#editId').val(data.id);
            $('#editName').val(data.name);
            $('#editDescription').val(data.description || '');

            // Check the permissions
            if (data.permissions && data.permissions.length > 0) {
                data.permissions.forEach(function(perm) {
                    $(`.edit-permission-item[value="${perm}"]`).prop('checked', true);
                });

                // Update group checkboxes
                $('.edit-group-check').each(function() {
                    const group = $(this).data('group');
                    const total = $(`.edit-permission-item[data-group="${group}"]`).length;
                    const checked = $(`.edit-permission-item[data-group="${group}"]:checked`).length;
                    $(this).prop('checked', total === checked && total > 0);
                });

                // Update sub-module checkboxes
                $('.edit-sub-module-check').each(function() {
                    const subModule = $(this).data('sub-module');
                    const total = $(`.edit-permission-item[data-sub-module="${subModule}"]`).length;
                    const checked = $(`.edit-permission-item[data-sub-module="${subModule}"]:checked`).length;
                    $(this).prop('checked', total === checked && total > 0);
                });

                // Update main module checkboxes
                $('.edit-main-module-check').each(function() {
                    const mainModule = $(this).data('main-module');
                    const total = $(`.edit-permission-item[data-main-module="${mainModule}"]`).length;
                    const checked = $(`.edit-permission-item[data-main-module="${mainModule}"]:checked`).length;
                    $(this).prop('checked', total === checked && total > 0);
                });
            }

            // Update permission counts
            updateEditPermissionCounts();

            new bootstrap.Modal(document.getElementById('editModal')).show();
        },
        error: function(xhr) {
            showErrorToast('Failed to load role data.');
        }
    });
}

// =====================
// Delete Record
// =====================
function deleteRecord(id) {
    $('#deleteId').val(id);
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function confirmDelete() {
    const id = $('#deleteId').val();
    $.ajax({
        url: `{{ url('admin/roles') }}/${id}/destroy`,
        type: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                showSuccessToast(response.message);
                hideModal('deleteModal');
                setTimeout(() => location.reload(), 1000);
            } else {
                showErrorToast(response.message || 'Failed to delete role.');
            }
        },
        error: function(xhr) {
            showErrorToast(xhr.responseJSON?.message || 'Failed to delete role.');
        }
    });
}

// =====================
// Bulk Delete
// =====================
function bulkDelete() {
    const ids = $('.row-checkbox:checked').map(function() { return $(this).val(); }).get();
    if (ids.length === 0) {
        showErrorToast('Please select at least one role.');
        return;
    }
    if (!confirm(`Are you sure you want to delete ${ids.length} role(s)?`)) return;

    $.ajax({
        url: '{{ route("admin.roles.bulk-delete") }}',
        type: 'POST',
        data: { ids: ids, _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                showSuccessToast(response.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showErrorToast(response.message || 'Failed to delete.');
            }
        },
        error: function(xhr) {
            showErrorToast(xhr.responseJSON?.message || 'Failed to delete.');
        }
    });
}

// =====================
// Helper Functions
// =====================
function hideModal(modalId) {
    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    if (modal) modal.hide();
}

function showSuccessToast(message) {
    const toastEl = document.getElementById('successToast');
    if (toastEl) {
        document.getElementById('successToastMessage').textContent = message;
        new bootstrap.Toast(toastEl).show();
    } else {
        alert(message);
    }
}

function showErrorToast(message) {
    const toastEl = document.getElementById('errorToast');
    if (toastEl) {
        document.getElementById('errorToastMessage').textContent = message;
        new bootstrap.Toast(toastEl).show();
    } else {
        alert(message);
    }
}

// =====================
// CREATE FORM - Permission Count & Checkbox Functions
// =====================
function updateCreatePermissionCounts() {
    const totalPermissions = $('.create-permission-item').length;
    const checkedPermissions = $('.create-permission-item:checked').length;
    $('#createTotalPermissionsCount').text(`${checkedPermissions} selected`);

    // Update each group count
    $('.create-group-check').each(function() {
        const group = $(this).data('group');
        const total = $(`.create-permission-item[data-group="${group}"]`).length;
        const checked = $(`.create-permission-item[data-group="${group}"]:checked`).length;
        $(`.create-group-count[data-group="${group}"]`).text(`${checked} / ${total}`);
    });

    // Update each sub-module count
    $('.create-sub-module-check').each(function() {
        const subModule = $(this).data('sub-module');
        const total = $(`.create-permission-item[data-sub-module="${subModule}"]`).length;
        const checked = $(`.create-permission-item[data-sub-module="${subModule}"]:checked`).length;
        $(`.create-sub-count[data-sub-module="${subModule}"]`).text(`${checked} / ${total}`);
    });

    // Update each main module count
    $('.create-main-module-check').each(function() {
        const mainModule = $(this).data('main-module');
        const total = $(`.create-permission-item[data-main-module="${mainModule}"]`).length;
        const checked = $(`.create-permission-item[data-main-module="${mainModule}"]:checked`).length;
        $(`.create-module-count[data-main-module="${mainModule}"]`).text(`${checked} / ${total}`);
    });

    // Update select all checkbox
    $('#createSelectAllPermissions').prop('checked', checkedPermissions === totalPermissions && totalPermissions > 0);
}

function updateCreateGroupCheckbox(group) {
    const total = $(`.create-permission-item[data-group="${group}"]`).length;
    const checked = $(`.create-permission-item[data-group="${group}"]:checked`).length;
    $(`.create-group-check[data-group="${group}"]`).prop('checked', total === checked && total > 0);
}

function updateCreateSubModuleCheckbox(subModule) {
    const total = $(`.create-permission-item[data-sub-module="${subModule}"]`).length;
    const checked = $(`.create-permission-item[data-sub-module="${subModule}"]:checked`).length;
    $(`.create-sub-module-check[data-sub-module="${subModule}"]`).prop('checked', total === checked && total > 0);
}

function updateCreateMainModuleCheckbox(mainModule) {
    const total = $(`.create-permission-item[data-main-module="${mainModule}"]`).length;
    const checked = $(`.create-permission-item[data-main-module="${mainModule}"]:checked`).length;
    $(`.create-main-module-check[data-main-module="${mainModule}"]`).prop('checked', total === checked && total > 0);
}

// =====================
// EDIT FORM - Permission Count & Checkbox Functions
// =====================
function updateEditPermissionCounts() {
    const totalPermissions = $('.edit-permission-item').length;
    const checkedPermissions = $('.edit-permission-item:checked').length;
    $('#editTotalPermissionsCount').text(`${checkedPermissions} selected`);

    // Update each group count
    $('.edit-group-check').each(function() {
        const group = $(this).data('group');
        const total = $(`.edit-permission-item[data-group="${group}"]`).length;
        const checked = $(`.edit-permission-item[data-group="${group}"]:checked`).length;
        $(`.edit-group-count[data-group="${group}"]`).text(`${checked} / ${total}`);
    });

    // Update each sub-module count
    $('.edit-sub-module-check').each(function() {
        const subModule = $(this).data('sub-module');
        const total = $(`.edit-permission-item[data-sub-module="${subModule}"]`).length;
        const checked = $(`.edit-permission-item[data-sub-module="${subModule}"]:checked`).length;
        $(`.edit-sub-count[data-sub-module="${subModule}"]`).text(`${checked} / ${total}`);
    });

    // Update each main module count
    $('.edit-main-module-check').each(function() {
        const mainModule = $(this).data('main-module');
        const total = $(`.edit-permission-item[data-main-module="${mainModule}"]`).length;
        const checked = $(`.edit-permission-item[data-main-module="${mainModule}"]:checked`).length;
        $(`.edit-module-count[data-main-module="${mainModule}"]`).text(`${checked} / ${total}`);
    });

    // Update select all checkbox
    $('#editSelectAllPermissions').prop('checked', checkedPermissions === totalPermissions && totalPermissions > 0);
}

function updateEditGroupCheckbox(group) {
    const total = $(`.edit-permission-item[data-group="${group}"]`).length;
    const checked = $(`.edit-permission-item[data-group="${group}"]:checked`).length;
    $(`.edit-group-check[data-group="${group}"]`).prop('checked', total === checked && total > 0);
}

function updateEditSubModuleCheckbox(subModule) {
    const total = $(`.edit-permission-item[data-sub-module="${subModule}"]`).length;
    const checked = $(`.edit-permission-item[data-sub-module="${subModule}"]:checked`).length;
    $(`.edit-sub-module-check[data-sub-module="${subModule}"]`).prop('checked', total === checked && total > 0);
}

function updateEditMainModuleCheckbox(mainModule) {
    const total = $(`.edit-permission-item[data-main-module="${mainModule}"]`).length;
    const checked = $(`.edit-permission-item[data-main-module="${mainModule}"]:checked`).length;
    $(`.edit-main-module-check[data-main-module="${mainModule}"]`).prop('checked', total === checked && total > 0);
}
</script>
