<script>
// Toggle View
function toggleView(view) {
    if (view === 'list') {
        document.getElementById('listView').classList.remove('d-none');
        document.getElementById('cardView').classList.add('d-none');
        document.getElementById('listViewBtn').classList.add('active');
        document.getElementById('cardViewBtn').classList.remove('active');
    } else {
        document.getElementById('listView').classList.add('d-none');
        document.getElementById('cardView').classList.remove('d-none');
        document.getElementById('listViewBtn').classList.remove('active');
        document.getElementById('cardViewBtn').classList.add('active');
    }
}

// Select All
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.row-checkbox:not(:disabled)').forEach(cb => cb.checked = this.checked);
    updateBulkActions();
});

document.querySelectorAll('.row-checkbox').forEach(cb => {
    cb.addEventListener('change', updateBulkActions);
});

function updateBulkActions() {
    const checked = document.querySelectorAll('.row-checkbox:checked').length;
    const bulkActions = document.getElementById('bulkActions');
    if (checked > 0) {
        bulkActions.classList.remove('d-none');
    } else {
        bulkActions.classList.add('d-none');
    }
}

function getSelectedIds() {
    return Array.from(document.querySelectorAll('.row-checkbox:checked:not(:disabled)')).map(cb => cb.value);
}

// CRUD Operations
function viewRecord(id) {
    fetch(`{{ url('admin/users') }}/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            document.getElementById('showFullName').textContent = (data.first_name || '') + ' ' + (data.last_name || '');
            document.getElementById('showEmail').textContent = data.email || '';
            document.getElementById('showName').textContent = data.name || '-';
            document.getElementById('showPhone').textContent = data.phone || '-';
            document.getElementById('showEmployeeCode').textContent = data.employee_code || '-';
            document.getElementById('showStatus').innerHTML = data.is_active
                ? '<span class="badge bg-success-subtle text-success">نشط</span>'
                : '<span class="badge bg-danger-subtle text-danger">غير نشط</span>';
            document.getElementById('showRole').innerHTML = data.roles?.length
                ? data.roles.map(r => `<span class="badge bg-primary-subtle text-primary">${r}</span>`).join(' ')
                : '<span class="badge bg-secondary-subtle text-secondary">بدون دور</span>';

            if (data.avatar_url) {
                document.getElementById('showAvatar').innerHTML = `<img src="${data.avatar_url}" class="rounded-circle" width="80" height="80" style="object-fit: cover;">`;
            } else {
                const initial = (data.first_name || data.name || 'U')[0].toUpperCase();
                document.getElementById('showAvatar').innerHTML = `<div class="avatar avatar-lg bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width:80px;height:80px;font-size:28px;">${initial}</div>`;
            }

            new bootstrap.Modal(document.getElementById('showModal')).show();
        })
        .catch(err => {
            console.error('Error fetching user data:', err);
            alert('حدث خطأ في تحميل بيانات المستخدم');
        });
}

function editRecord(id) {
    fetch(`{{ url('admin/users') }}/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            document.getElementById('editId').value = data.id;
            document.getElementById('editFirstName').value = data.first_name || '';
            document.getElementById('editLastName').value = data.last_name || '';
            document.getElementById('editDepartment').value = data.department || '';
            document.getElementById('editEmail').value = data.email || '';
            document.getElementById('editRole').value = data.roles?.[0] || '';
            document.getElementById('editIsActive').checked = data.is_active;

            new bootstrap.Modal(document.getElementById('editModal')).show();
        })
        .catch(err => {
            console.error('Error fetching user data:', err);
            alert('حدث خطأ في تحميل بيانات المستخدم');
        });
}

function deleteRecord(id) {
    document.getElementById('deleteId').value = id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function changePassword(userId, userName) {
    document.getElementById('changePasswordUserId').value = userId;
    document.getElementById('changePasswordUserName').textContent = userName;
    document.getElementById('newPassword').value = '';
    document.getElementById('confirmPassword').value = '';
    new bootstrap.Modal(document.getElementById('changePasswordModal')).show();
}

function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const icon = event.target.closest('button').querySelector('i');
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

document.getElementById('changePasswordForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const userId = document.getElementById('changePasswordUserId').value;
    const formData = new FormData(this);

    fetch(`{{ url('admin/users') }}/${userId}/change-password`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            password: formData.get('password'),
            password_confirmation: formData.get('password_confirmation')
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
            alert('تم تغيير كلمة المرور بنجاح');
            location.reload();
        } else {
            alert(data.message || 'حدث خطأ في تغيير كلمة المرور');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('حدث خطأ في تغيير كلمة المرور');
    });
});

function confirmDelete() {
    const id = document.getElementById('deleteId').value;
    fetch(`{{ url('admin/users') }}/${id}/destroy`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
        else alert(data.message || 'حدث خطأ');
    });
}

function restoreRecord(id) {
    if (confirm('هل تريد استعادة هذا المستخدم؟')) {
        fetch(`{{ url('admin/users') }}/${id}/restore`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.message || 'حدث خطأ');
        });
    }
}

function forceDeleteRecord(id) {
    if (confirm('هل أنت متأكد من الحذف النهائي؟ لا يمكن التراجع عن هذا الإجراء.')) {
        fetch(`{{ url('admin/users') }}/${id}/force-delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.message || 'حدث خطأ');
        });
    }
}

// Bulk Operations
function bulkDelete() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('الرجاء تحديد مستخدمين للحذف');
        return;
    }
    if (confirm(`هل تريد حذف ${ids.length} مستخدم؟`)) {
        fetch('{{ route("admin.users.bulk-delete") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'حدث خطأ');
            }
        })
        .catch(err => {
            console.error('Bulk delete error:', err);
            alert('حدث خطأ في عملية الحذف');
        });
    }
}

function bulkRestore() {
    const ids = getSelectedIds();
    if (ids.length === 0) return;
    if (confirm(`هل تريد استعادة ${ids.length} مستخدم؟`)) {
        fetch('{{ route("admin.users.bulk-restore") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.message || 'حدث خطأ');
        });
    }
}

function bulkForceDelete() {
    const ids = getSelectedIds();
    if (ids.length === 0) return;
    if (confirm(`هل أنت متأكد من الحذف النهائي لـ ${ids.length} مستخدم؟`)) {
        fetch('{{ route("admin.users.bulk-force-delete") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.message || 'حدث خطأ');
        });
    }
}

// Form Submissions
document.getElementById('createForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الإنشاء...';

    fetch('{{ route("admin.users.store") }}', {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'حدث خطأ');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    })
    .catch(err => {
        console.error('Create user error:', err);
        let errorMsg = 'حدث خطأ في إنشاء المستخدم';
        if (err.errors) {
            errorMsg += '\n' + Object.values(err.errors).flat().join('\n');
        }
        alert(errorMsg);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
});

document.getElementById('editForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('editId').value;
    const formData = new FormData(this);

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحفظ...';

    fetch(`{{ url('admin/users') }}/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => {
        if (!res.ok) {
            return res.json().then(err => Promise.reject(err));
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'حدث خطأ');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    })
    .catch(err => {
        console.error('Error updating user:', err);
        let errorMsg = 'حدث خطأ أثناء حفظ البيانات';
        if (err.message) {
            errorMsg += ': ' + err.message;
        }
        if (err.errors) {
            errorMsg += '\n' + Object.values(err.errors).flat().join('\n');
        }
        alert(errorMsg);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
});

document.getElementById('bulkUploadForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الاستيراد...';

    fetch('{{ route("admin.users.bulk-upload") }}', {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('تم بدء عملية الاستيراد بنجاح');
            location.reload();
        } else {
            alert(data.message || 'حدث خطأ');
            btn.disabled = false;
            btn.innerHTML = 'استيراد';
        }
    });
});

// Filter Dropdowns
document.querySelectorAll('.filter-dropdown .dropdown-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        const dropdown = this.closest('.filter-dropdown');
        const hiddenInput = dropdown.previousElementSibling;
        const selectedText = dropdown.querySelector('[data-selected-text]');

        hiddenInput.value = this.dataset.value;
        selectedText.textContent = this.dataset.text;

        dropdown.querySelectorAll('.dropdown-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>
