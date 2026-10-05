{{--
    Sponsorship employee section — drop this inside any Bootstrap form.
    Requires: Bootstrap 5 RTL + Font Awesome 6.
    Plain JS only (no Alpine) so it works in the public self-registration pages.
--}}

{{-- Flight ticket checkbox --}}
<div class="mt-4">
    <label class="d-flex align-items-center gap-2" style="cursor:pointer;padding:12px 14px;border-radius:10px;border:1.5px solid #e5e7eb;background:#f9fafb;">
        <input type="checkbox" name="flight_ticket" value="1"
               {{ old('flight_ticket') ? 'checked' : '' }}
               style="width:18px;height:18px;accent-color:#2563eb;cursor:pointer;flex-shrink:0;">
        <div>
            <p style="margin:0;font-weight:700;font-size:14px;color:#1e40af;"><i class="fas fa-plane me-1"></i> يستحق تذكرة سفر</p>
            <p style="margin:0;font-size:12px;color:#6b7280;">تفعيل إذا كان الموظف يستحق تذكرة سفر سنوية</p>
        </div>
    </label>
</div>

<div class="mt-4" id="sponsorshipToggleSection">
    <div class="d-flex align-items-center gap-3 p-3 rounded-3"
         style="background:#f5f3ff;border:1.5px solid #ddd6fe;cursor:pointer;"
         onclick="toggleSponsorshipSection()">
        {{-- Custom toggle --}}
        <div style="position:relative;width:44px;height:24px;flex-shrink:0;">
            <input type="checkbox" name="is_sponsorship_employee" value="1"
                   id="isSponsorCheckbox"
                   {{ old('is_sponsorship_employee') ? 'checked' : '' }}
                   onchange="syncSponsorToggle(this.checked)"
                   onclick="event.stopPropagation()"
                   style="opacity:0;width:0;height:0;position:absolute;">
            <span id="sponsorTrack"
                  style="position:absolute;inset:0;border-radius:12px;background:#d1d5db;transition:background .2s;"></span>
            <span id="sponsorThumb"
                  style="position:absolute;top:2px;right:2px;width:20px;height:20px;border-radius:50%;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.3);transition:transform .2s;"></span>
        </div>
        <div>
            <p style="margin:0;font-weight:700;font-size:15px;color:#5b21b6;">موظف كفالة</p>
            <p style="margin:0;font-size:12px;color:#7c3aed;opacity:.8;">تفعيل لإدخال بيانات الكفالة الإضافية</p>
        </div>
    </div>

    {{-- Extra sponsorship fields (hidden by default) --}}
    <div id="sponsorshipExtraFields" style="display:none;margin-top:16px;">
        <div style="background:#faf5ff;border:1.5px solid #ddd6fe;border-radius:12px;padding:24px;">
            <h6 style="color:#6d28d9;font-weight:700;font-size:13px;text-transform:uppercase;letter-spacing:.06em;margin-bottom:20px;">
                <i class="fas fa-passport me-1"></i> بيانات الكفالة
            </h6>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">رقم جواز السفر</label>
                    <input type="text" name="passport_number" value="{{ old('passport_number') }}"
                           class="form-control" placeholder="A12345678"
                           style="border-color:#c4b5fd;">
                </div>
                <div class="col-md-6">
                    <label class="form-label">تاريخ انتهاء الجواز / الإقامة</label>
                    <input type="date" name="id_expiry_date" value="{{ old('id_expiry_date') }}"
                           class="form-control" style="border-color:#c4b5fd;">
                </div>
                <div class="col-md-6">
                    <label class="form-label">رقم رخصة القيادة</label>
                    <input type="text" name="driver_license_number" value="{{ old('driver_license_number') }}"
                           class="form-control" style="border-color:#c4b5fd;">
                </div>
                {{-- Wives count — only when married --}}
                <div class="col-md-6" id="wivesCountWrap" style="display:none;">
                    <label class="form-label">عدد الزوجات</label>
                    <input type="number" name="wives_count" value="{{ old('wives_count', 1) }}"
                           min="1" max="4" class="form-control" style="border-color:#c4b5fd;">
                </div>
                <div class="col-md-6">
                    <label class="form-label">المدينة السكنية</label>
                    <input type="text" name="residential_city" value="{{ old('residential_city') }}"
                           class="form-control" placeholder="الرياض" style="border-color:#c4b5fd;">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الحي السكني</label>
                    <input type="text" name="residential_neighborhood" value="{{ old('residential_neighborhood') }}"
                           class="form-control" placeholder="اسم الحي" style="border-color:#c4b5fd;">
                </div>

                {{-- Document uploads --}}
                <div class="col-12 mt-2">
                    <p style="font-size:13px;font-weight:700;color:#6d28d9;margin-bottom:10px;">
                        <i class="fas fa-folder-open me-1"></i> أرشفة الوثائق
                    </p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">صورة جواز السفر</label>
                            <input type="file" name="doc_passport" accept="image/*,application/pdf"
                                   class="form-control" style="border-color:#c4b5fd;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">صورة الهوية / الإقامة</label>
                            <input type="file" name="doc_id_card" accept="image/*,application/pdf"
                                   class="form-control" style="border-color:#c4b5fd;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">صورة رخصة القيادة</label>
                            <input type="file" name="doc_driver_license" accept="image/*,application/pdf"
                                   class="form-control" style="border-color:#c4b5fd;">
                        </div>
                    </div>
                </div>

                {{-- Languages --}}
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-label mb-0">اللغات</label>
                        <button type="button" onclick="addSponsorLangRow()"
                                style="font-size:12px;font-weight:700;color:#7c3aed;border:1.5px solid #c4b5fd;background:#f5f3ff;padding:4px 14px;border-radius:8px;">
                            <i class="fas fa-plus me-1"></i> إضافة لغة
                        </button>
                    </div>
                    <div id="sponsorLangsContainer" style="display:flex;flex-direction:column;gap:8px;"></div>
                    <p id="sponsorLangsEmpty" style="font-size:12px;color:#9ca3af;text-align:center;padding:8px 0;margin:0;">
                        لا توجد لغات مضافة
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var checkbox  = document.getElementById('isSponsorCheckbox');
    var track     = document.getElementById('sponsorTrack');
    var thumb     = document.getElementById('sponsorThumb');
    var fields    = document.getElementById('sponsorshipExtraFields');

    function applyState(checked) {
        track.style.background = checked ? '#7c3aed' : '#d1d5db';
        thumb.style.transform  = checked ? 'translateX(-20px)' : 'none';
        fields.style.display   = checked ? 'block' : 'none';
    }

    // expose so the wrapping div's onclick can call it
    window.toggleSponsorshipSection = function () {
        checkbox.checked = !checkbox.checked;
        applyState(checkbox.checked);
    };

    window.syncSponsorToggle = function (checked) {
        applyState(checked);
    };

    // Auto-enable when ?sponsorship=1 is in the URL (link generated by admin)
    if (!checkbox.checked && new URLSearchParams(window.location.search).get('sponsorship') === '1') {
        checkbox.checked = true;
    }

    // restore old() state on page load (validation failure redirect)
    applyState(checkbox.checked);

    // Wives count: show only when marital_status === 'married'
    function syncWivesCount() {
        var ms  = document.querySelector('select[name="marital_status"]');
        var wrap = document.getElementById('wivesCountWrap');
        if (wrap) wrap.style.display = (ms && ms.value === 'married') ? '' : 'none';
    }
    var ms = document.querySelector('select[name="marital_status"]');
    if (ms) ms.addEventListener('change', syncWivesCount);
    syncWivesCount();
})();

function addSponsorLangRow(lang, level) {
    lang  = lang  || '';
    level = level || '';
    var container = document.getElementById('sponsorLangsContainer');
    var empty     = document.getElementById('sponsorLangsEmpty');
    empty.style.display = 'none';

    var row = document.createElement('div');
    row.style.cssText = 'display:flex;gap:8px;align-items:center;';
    row.innerHTML =
        '<input type="text" name="lang_name[]" value="' + lang + '" placeholder="اسم اللغة"' +
        '       class="form-control form-control-sm" style="border-color:#c4b5fd;">' +
        '<select name="lang_level[]" class="form-select form-select-sm" style="width:130px;border-color:#c4b5fd;">' +
        '  <option value="">المستوى</option>' +
        '  <option value="مبتدئ"' + (level==='مبتدئ'?' selected':'') + '>مبتدئ</option>' +
        '  <option value="متوسط"' + (level==='متوسط'?' selected':'') + '>متوسط</option>' +
        '  <option value="جيد"'   + (level==='جيد'  ?' selected':'') + '>جيد</option>' +
        '  <option value="متقدم"' + (level==='متقدم'?' selected':'') + '>متقدم</option>' +
        '  <option value="طليق"'  + (level==='طليق' ?' selected':'') + '>طليق</option>' +
        '</select>' +
        '<button type="button" onclick="removeSponsorLangRow(this)"' +
        '        style="width:32px;height:32px;border-radius:8px;border:1.5px solid #fca5a5;background:#fff5f5;color:#ef4444;flex-shrink:0;display:flex;align-items:center;justify-content:center;">' +
        '  <i class="fas fa-times" style="font-size:11px;pointer-events:none;"></i>' +
        '</button>';
    container.appendChild(row);
}

function removeSponsorLangRow(btn) {
    btn.parentElement.remove();
    var container = document.getElementById('sponsorLangsContainer');
    var empty     = document.getElementById('sponsorLangsEmpty');
    if (!container.children.length) empty.style.display = '';
}
</script>
