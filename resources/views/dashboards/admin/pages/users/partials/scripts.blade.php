<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Initialize view from localStorage
    const savedView = localStorage.getItem('usersViewMode') || 'list';
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

    // Create Form Submit
    $('#createForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...');

        const formData = new FormData(this);
        formData.append('is_active', $('#createIsActive').is(':checked') ? 1 : 0);

        const createGender = form.find('select[name="gender"]').val();
        if (createGender) {
            formData.set('gender', createGender);
        } else {
            formData.delete('gender');
        }

        $.ajax({
            url: '{{ route("admin.users.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showSuccessToast(response.message);
                    hideModal('createModal');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showErrorToast(response.message || 'Failed to create user.');
                    btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Create User');
                }
            },
            error: function(xhr) {
                showErrorToast(xhr.responseJSON?.message || 'Failed to create user.');
                btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Create User');
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

        const formData = new FormData(this);
        formData.append('is_active', $('#editIsActive').is(':checked') ? 1 : 0);

        const editGender = $('#editGender').val();
        if (editGender) {
            formData.set('gender', editGender);
        } else {
            formData.delete('gender');
        }

        $.ajax({
            url: `{{ url('admin/users') }}/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showSuccessToast(response.message);
                    hideModal('editModal');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showErrorToast(response.message || 'Failed to update user.');
                    btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Update User');
                }
            },
            error: function(xhr) {
                showErrorToast(xhr.responseJSON?.message || 'Failed to update user.');
                btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Update User');
            }
        });
    });

    // Bulk Upload Form Submit
    $('#bulkUploadForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Uploading...');

        const formData = new FormData(this);

        $.ajax({
            url: '{{ route("admin.users.bulk-upload") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showSuccessToast(response.message);
                    hideModal('bulkUploadModal');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showErrorToast(response.message || 'Failed to upload.');
                    btn.prop('disabled', false).html('<i class="ti ti-upload me-1"></i> Upload');
                }
            },
            error: function(xhr) {
                showErrorToast(xhr.responseJSON?.message || 'Failed to upload.');
                btn.prop('disabled', false).html('<i class="ti ti-upload me-1"></i> Upload');
            }
        });
    });
});

// =====================
// View Toggle
// =====================
function toggleView(mode) {
    localStorage.setItem('usersViewMode', mode);
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
    $('#showContent').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
    new bootstrap.Modal(document.getElementById('showModal')).show();

    $.ajax({
        url: `{{ url('admin/users') }}/${id}`,
        type: 'GET',
        success: function(response) {
            $('#showContent').html(response);
        },
        error: function(xhr) {
            $('#showContent').html('<div class="alert alert-danger">Failed to load user details.</div>');
        }
    });
}

// =====================
// Edit Record
// =====================
function editRecord(id) {
    $.ajax({
        url: `{{ url('admin/users') }}/${id}/edit`,
        type: 'GET',
        success: function(data) {
            $('#editId').val(data.id);
            $('#editName').val(data.name);
            $('#editEmail').val(data.email);
            $('#editFirstName').val(data.first_name);
            $('#editLastName').val(data.last_name);
            $('#editEmployeeCode').val(data.employee_code);
            $('#editEmployeeType').val(data.employee_type);
            $('#editNationalId').val(data.national_id);
            $('#editPhone').val(data.phone);
            $('#editGender').val(data.gender);
            $('#editBirthDate').val(data.birth_date);
            $('#editAddress').val(data.address);
            $('#editDepartmentId').val(data.department_id);
            $('#editHrJobId').val(data.hr_job_id);
            $('#editHireDate').val(data.hire_date);
            $('#editBasicSalary').val(data.basic_salary);
            $('#editPieceRate').val(data.piece_rate);
            $('#editEmploymentStatus').val(data.employment_status);
            $('#editIsActive').prop('checked', data.is_active);

            if (data.roles && data.roles.length > 0) {
                $('#editRole').val(data.roles[0]);
            }

            if (data.avatar_url) {
                $('#editAvatarPreview').html(`<img src="${data.avatar_url}" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">`);
            } else {
                $('#editAvatarPreview').html('<i class="ti ti-user"></i>');
            }

            new bootstrap.Modal(document.getElementById('editModal')).show();
        },
        error: function(xhr) {
            showErrorToast('Failed to load user data.');
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
        url: `{{ url('admin/users') }}/${id}/destroy`,
        type: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                showSuccessToast(response.message);
                hideModal('deleteModal');
                setTimeout(() => location.reload(), 1000);
            } else {
                showErrorToast(response.message || 'Failed to delete user.');
            }
        },
        error: function(xhr) {
            showErrorToast(xhr.responseJSON?.message || 'Failed to delete user.');
        }
    });
}

// =====================
// Restore Record
// =====================
function restoreRecord(id) {
    if (!confirm('Are you sure you want to restore this user?')) return;
    $.ajax({
        url: `{{ url('admin/users') }}/${id}/restore`,
        type: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                showSuccessToast(response.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showErrorToast(response.message || 'Failed to restore.');
            }
        },
        error: function(xhr) {
            showErrorToast(xhr.responseJSON?.message || 'Failed to restore.');
        }
    });
}

// =====================
// Force Delete Record
// =====================
function forceDeleteRecord(id) {
    if (!confirm('Are you sure you want to PERMANENTLY delete this user? This cannot be undone!')) return;
    $.ajax({
        url: `{{ url('admin/users') }}/${id}/force-delete`,
        type: 'POST',
        data: { _token: '{{ csrf_token() }}' },
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
// Bulk Delete
// =====================
function bulkDelete() {
    const ids = $('.row-checkbox:checked').map(function() { return $(this).val(); }).get();
    if (ids.length === 0) {
        showErrorToast('Please select at least one user.');
        return;
    }
    if (!confirm(`Are you sure you want to delete ${ids.length} user(s)?`)) return;

    $.ajax({
        url: '{{ route("admin.users.bulk-delete") }}',
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
// Bulk Restore
// =====================
function bulkRestore() {
    const ids = $('.row-checkbox:checked').map(function() { return $(this).val(); }).get();
    if (ids.length === 0) {
        showErrorToast('Please select at least one user.');
        return;
    }
    if (!confirm(`Are you sure you want to restore ${ids.length} user(s)?`)) return;

    $.ajax({
        url: '{{ route("admin.users.bulk-restore") }}',
        type: 'POST',
        data: { ids: ids, _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                showSuccessToast(response.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showErrorToast(response.message || 'Failed to restore.');
            }
        },
        error: function(xhr) {
            showErrorToast(xhr.responseJSON?.message || 'Failed to restore.');
        }
    });
}

// =====================
// Bulk Force Delete
// =====================
function bulkForceDelete() {
    const ids = $('.row-checkbox:checked').map(function() { return $(this).val(); }).get();
    if (ids.length === 0) {
        showErrorToast('Please select at least one user.');
        return;
    }
    if (!confirm(`Are you sure you want to PERMANENTLY delete ${ids.length} user(s)? This cannot be undone!`)) return;

    $.ajax({
        url: '{{ route("admin.users.bulk-force-delete") }}',
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

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    input.type = input.type === 'password' ? 'text' : 'password';
}

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#avatarPreview').html(`<img src="${e.target.result}" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">`);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewEditAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#editAvatarPreview').html(`<img src="${e.target.result}" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">`);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Dropdown scripts
document.querySelectorAll('.filter-dropdown').forEach(function(drop) {
    const hidden = drop.parentElement.querySelector('input[type="hidden"][name]');
    const label = drop.querySelector('[data-selected-text]');

    drop.querySelectorAll('.dropdown-item[data-value]').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();

            const value = this.getAttribute('data-value') ?? '';
            const text = this.getAttribute('data-text') ?? '';

            if (hidden) hidden.value = value;
            if (label) label.textContent = text;

            drop.querySelectorAll('.dropdown-item').forEach(function(i) {
                i.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
});

// show or hide filters
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('toggleTransferFilters');
    const collapseEl = document.getElementById('transferFilters');
    if (!btn || !collapseEl) return;

    const icon = btn.querySelector('i');
    const text = btn.querySelector('span');

    function setState(isShown) {
        btn.setAttribute('aria-expanded', isShown ? 'true' : 'false');
        if (icon) icon.className = isShown ? 'ti ti-eye-off' : 'ti ti-filter';
        if (text) text.textContent = isShown ? 'Hide filters' : 'Show filters';
    }

    collapseEl.addEventListener('shown.bs.collapse', () => setState(true));
    collapseEl.addEventListener('hidden.bs.collapse', () => setState(false));
});
</script>
