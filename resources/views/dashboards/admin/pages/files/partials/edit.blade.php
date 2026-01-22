<!-- Edit File Modal Content -->
<div class="modal-header modal-header-primary">
    <h5 class="modal-title">
        <i class="ti ti-edit"></i>
        تعديل الملف: {{ $file->file_name }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<form id="editFileForm" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row g-3">
            <!-- File Info -->
            <div class="col-12">
                <div class="alert alert-info mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="ti ti-barcode fs-3"></i>
                        <div>
                            <strong>باركود الملف:</strong>
                            <span class="font-monospace fs-5">{{ $file->barcode }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Name -->
            <div class="col-md-6">
                <label class="form-label">رقم الملف <span class="text-danger">*</span></label>
                <input type="text" name="file_name" class="form-control"
                    value="{{ $file->file_name }}" required>
            </div>

            <!-- Client (Read Only) -->
            <div class="col-md-6">
                <label class="form-label">العميل</label>
                <input type="text" class="form-control" value="{{ $file->client?->name ?? 'غير محدد' }}" readonly>
            </div>

            <!-- Land (Read Only) -->
            <div class="col-md-6">
                <label class="form-label">القطعة</label>
                <input type="text" class="form-control" value="{{ $file->land?->land_no ?? 'غير محدد' }}" readonly>
            </div>

            <!-- Status -->
            <div class="col-md-6">
                <label class="form-label">الحالة</label>
                <input type="text" class="form-control" value="{{ $file->status_badge['text'] }}" readonly>
            </div>

            <!-- Physical Location Section -->
            <div class="col-12">
                <hr class="my-3">
                <h6 class="text-muted mb-3"><i class="ti ti-map-pin me-2"></i>الموقع الفعلي</h6>
            </div>

            <div class="col-md-3">
                <label class="form-label">الغرفة</label>
                <select name="room_id" id="editRoomId" class="form-select" onchange="loadEditLanes(this.value)">
                    <option value="">اختر الغرفة</option>
                    @foreach ($rooms ?? [] as $room)
                        <option value="{{ $room->id }}" {{ $file->room_id == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">الممر</label>
                <select name="lane_id" id="editLaneId" class="form-select" onchange="loadEditStands(this.value)">
                    <option value="">اختر الممر</option>
                    @if($file->room)
                        @foreach ($file->room->lanes ?? [] as $lane)
                            <option value="{{ $lane->id }}" {{ $file->lane_id == $lane->id ? 'selected' : '' }}>
                                {{ $lane->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">الاستند</label>
                <select name="stand_id" id="editStandId" class="form-select" onchange="loadEditRacks(this.value)">
                    <option value="">اختر الاستند</option>
                    @if($file->lane)
                        @foreach ($file->lane->stands ?? [] as $stand)
                            <option value="{{ $stand->id }}" {{ $file->stand_id == $stand->id ? 'selected' : '' }}>
                                {{ $stand->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">الرف</label>
                <select name="rack_id" id="editRackId" class="form-select">
                    <option value="">اختر الرف</option>
                    @if($file->stand)
                        @foreach ($file->stand->racks ?? [] as $rack)
                            <option value="{{ $rack->id }}" {{ $file->rack_id == $rack->id ? 'selected' : '' }}>
                                {{ $rack->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Document Upload Section -->
            <div class="col-12">
                <hr class="my-3">
                <h6 class="text-muted mb-3"><i class="ti ti-file-upload me-2"></i>رفع مستند</h6>
            </div>

            @if($file->hasMedia('documents'))
                <div class="col-12">
                    <div class="alert alert-success">
                        <i class="ti ti-check me-2"></i>
                        يوجد مستند مرفق: <strong>{{ $file->original_name ?? 'document.pdf' }}</strong>
                        ({{ $file->pages_count }} صفحة)
                        <a href="{{ route('admin.files.view', $file) }}" target="_blank" class="btn btn-sm btn-outline-success ms-2">
                            <i class="ti ti-eye"></i> عرض
                        </a>
                    </div>
                </div>
            @else
                <div class="col-12">
                    <div class="alert alert-warning">
                        <i class="ti ti-alert-triangle me-2"></i>
                        هذا الملف لا يحتوي على مستند. يمكنك رفع ملف PDF الآن.
                    </div>
                </div>
            @endif

            <div class="col-12">
                <label class="form-label">
                    {{ $file->hasMedia('documents') ? 'استبدال المستند (اختياري)' : 'رفع مستند PDF' }}
                </label>
                <input type="file" name="document" class="form-control" accept=".pdf">
                <small class="text-muted">الحد الأقصى: 50 ميجابايت، صيغة PDF فقط</small>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ti ti-x me-1"></i> إلغاء
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-check me-1"></i> حفظ التغييرات
        </button>
    </div>
</form>

<script>
    // Load lanes when room changes
    function loadEditLanes(roomId) {
        const laneSelect = document.getElementById('editLaneId');
        const standSelect = document.getElementById('editStandId');
        const rackSelect = document.getElementById('editRackId');

        laneSelect.innerHTML = '<option value="">اختر الممر</option>';
        standSelect.innerHTML = '<option value="">اختر الاستند</option>';
        rackSelect.innerHTML = '<option value="">اختر الرف</option>';

        if (!roomId) return;

        fetch(`{{ url('admin/physical-locations/rooms') }}/${roomId}/lanes`)
            .then(response => response.json())
            .then(data => {
                data.lanes.forEach(lane => {
                    laneSelect.innerHTML += `<option value="${lane.id}">${lane.name}</option>`;
                });
            });
    }

    // Load stands when lane changes
    function loadEditStands(laneId) {
        const standSelect = document.getElementById('editStandId');
        const rackSelect = document.getElementById('editRackId');

        standSelect.innerHTML = '<option value="">اختر الاستند</option>';
        rackSelect.innerHTML = '<option value="">اختر الرف</option>';

        if (!laneId) return;

        fetch(`{{ url('admin/physical-locations/lanes') }}/${laneId}/stands`)
            .then(response => response.json())
            .then(data => {
                data.stands.forEach(stand => {
                    standSelect.innerHTML += `<option value="${stand.id}">${stand.name}</option>`;
                });
            });
    }

    // Load racks when stand changes
    function loadEditRacks(standId) {
        const rackSelect = document.getElementById('editRackId');

        rackSelect.innerHTML = '<option value="">اختر الرف</option>';

        if (!standId) return;

        fetch(`{{ url('admin/physical-locations/stands') }}/${standId}/racks`)
            .then(response => response.json())
            .then(data => {
                data.racks.forEach(rack => {
                    rackSelect.innerHTML += `<option value="${rack.id}">${rack.name}</option>`;
                });
            });
    }

    // Handle form submission
    document.getElementById('editFileForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الحفظ...';

        fetch('{{ route("admin.files.update", $file) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('editFileModal')).hide();
                location.reload();
            } else {
                let errorMsg = data.error || data.message || 'حدث خطأ أثناء التحديث';
                if (data.errors) {
                    errorMsg += '\n' + Object.values(data.errors).flat().join('\n');
                }
                alert(errorMsg);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error updating file:', error);
            let errorMsg = 'حدث خطأ أثناء حفظ البيانات';
            if (error.message) {
                errorMsg += ': ' + error.message;
            }
            if (error.errors) {
                errorMsg += '\n' + Object.values(error.errors).flat().join('\n');
            }
            alert(errorMsg);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
</script>
