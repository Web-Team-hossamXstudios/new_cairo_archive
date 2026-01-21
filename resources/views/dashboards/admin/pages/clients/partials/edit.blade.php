<form id="editClientForm">
    @csrf
    <input type="hidden" name="client_id" id="editClientId" value="{{ $client->id }}">

    <!-- Client Information Section -->
    <div class="form-section">
        <div class="form-section-header">
            <i class="ti ti-user-circle"></i>
            <h6>بيانات العميل</h6>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">اسم العميل <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="ti ti-user text-warning"></i></span>
                    <input type="text" name="name" id="editName" class="form-control" placeholder="اسم العميل" required value="{{ $client->name }}">
                </div>
                <div class="invalid-feedback"></div>
            </div>

            <div class="col-md-6">
                <label class="form-label">الرقم القومي</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="ti ti-id text-warning"></i></span>
                    <input type="text" name="national_id" id="editNationalId" class="form-control" maxlength="14" placeholder="14 رقم" value="{{ $client->national_id }}">
                </div>
                <div class="invalid-feedback"></div>
            </div>

            <div class="col-md-4">
                <label class="form-label">كود العميل</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="ti ti-hash text-warning"></i></span>
                    <input type="text" name="client_code" id="editClientCode" class="form-control bg-light" readonly value="{{ $client->client_code }}">
                </div>
                <div class="invalid-feedback"></div>
            </div>

            <div class="col-md-4">
                <label class="form-label">التليفون</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="ti ti-phone text-warning"></i></span>
                    <input type="text" name="telephone" id="editTelephone" class="form-control" placeholder="التليفون" value="{{ $client->telephone }}">
                </div>
                <div class="invalid-feedback"></div>
            </div>

            <div class="col-md-4">
                <label class="form-label">الموبايل</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="ti ti-device-mobile text-warning"></i></span>
                    <input type="text" name="mobile" id="editMobile" class="form-control" placeholder="الموبايل" value="{{ $client->mobile }}">
                </div>
                <div class="invalid-feedback"></div>
            </div>

            <div class="col-12">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" id="editNotes" class="form-control" rows="3" placeholder="ملاحظات إضافية...">{{ $client->notes }}</textarea>
            </div>
        </div>
    </div>

    <!-- Warning Box -->
    <div class="warning-box">
        <i class="ti ti-alert-triangle"></i>
        <div>
            <strong>تنبيه:</strong> سيتم تحديث بيانات العميل فوراً. تأكد من صحة البيانات قبل الحفظ.
        </div>
    </div>

    <div class="modal-footer mt-3 px-0 pb-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ti ti-x me-1"></i>إلغاء
        </button>
        <button type="submit" class="btn btn-warning">
            <i class="ti ti-check me-1"></i>حفظ التعديلات
        </button>
    </div>
</form>
