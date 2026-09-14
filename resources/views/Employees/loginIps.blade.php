@extends('layouts.master')
@section('title', 'إدارة أجهزة تسجيل الدخول')

@push('styles')
<style>
    :root { --brand: #740e0e; }

    .ip-page { background: #f4f6fb; min-height: 100vh; padding: 28px 20px; }

    /* ── Stats bar ── */
    .stats-bar { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 28px; }
    .stat-card {
        background: #fff; border-radius: 14px; padding: 20px 24px;
        display: flex; align-items: center; gap: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,.06);
    }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .stat-label { font-size: 12px; color: #6b7280; margin-bottom: 2px; }
    .stat-value { font-size: 26px; font-weight: 700; line-height: 1; }

    /* ── Section headers ── */
    .section-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px;
    }
    .section-title { font-size: 18px; font-weight: 700; color: #111827; display: flex; align-items: center; gap: 8px; }
    .section-title .dot { width: 10px; height: 10px; border-radius: 50%; }

    /* ── Pending cards ── */
    .pending-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px; margin-bottom: 32px; }
    .pending-card {
        background: #fff; border-radius: 14px; padding: 20px;
        border-right: 4px solid #f59e0b;
        box-shadow: 0 2px 12px rgba(245,158,11,.15);
        transition: transform .15s;
    }
    .pending-card:hover { transform: translateY(-2px); }
    .pending-card .emp-name { font-size: 15px; font-weight: 700; color: #111827; margin-bottom: 4px; }
    .pending-card .emp-role { font-size: 12px; color: #6b7280; margin-bottom: 12px; }
    .pending-card .ip-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: #fef3c7; color: #92400e; border-radius: 8px;
        padding: 6px 12px; font-size: 13px; font-weight: 600; font-family: monospace;
        margin-bottom: 10px;
    }
    .pending-card .meta { font-size: 12px; color: #9ca3af; margin-bottom: 16px; }
    .pending-card .actions { display: flex; gap: 10px; }
    .btn-approve {
        flex: 1; background: #10b981; color: #fff; border: none; border-radius: 8px;
        padding: 8px 0; font-size: 13px; font-weight: 600; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        transition: background .15s;
    }
    .btn-approve:hover { background: #059669; }
    .btn-reject {
        flex: 1; background: #fff; color: #ef4444; border: 2px solid #ef4444; border-radius: 8px;
        padding: 8px 0; font-size: 13px; font-weight: 600; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        transition: all .15s;
    }
    .btn-reject:hover { background: #ef4444; color: #fff; }

    /* ── Employee IP tables ── */
    .emp-section { background: #fff; border-radius: 14px; margin-bottom: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .emp-section-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; cursor: pointer; user-select: none;
        border-bottom: 1px solid #f3f4f6;
    }
    .emp-section-header:hover { background: #fafafa; }
    .emp-info { display: flex; align-items: center; gap: 12px; }
    .emp-avatar {
        width: 40px; height: 40px; border-radius: 10px;
        background: linear-gradient(135deg, var(--brand), #b91c1c);
        color: #fff; font-weight: 700; font-size: 16px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .emp-section-name { font-size: 15px; font-weight: 600; color: #111827; }
    .emp-section-sub { font-size: 12px; color: #9ca3af; }
    .ip-count-badge { background: #f3f4f6; color: #374151; border-radius: 20px; padding: 3px 10px; font-size: 12px; font-weight: 600; }
    .chevron { transition: transform .2s; color: #9ca3af; }
    .chevron.open { transform: rotate(180deg); }

    .emp-section-body { padding: 0 20px 16px; }

    .ip-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    .ip-table th { text-align: right; font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase; padding: 8px 12px; background: #f9fafb; }
    .ip-table td { padding: 10px 12px; font-size: 13px; color: #374151; border-bottom: 1px solid #f3f4f6; }
    .ip-table tr:last-child td { border-bottom: none; }
    .ip-table tr:hover td { background: #fafafa; }
    .ip-mono { font-family: monospace; font-size: 13px; font-weight: 600; color: #1f2937; }

    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .badge-green  { background: #d1fae5; color: #065f46; }
    .badge-red    { background: #fee2e2; color: #991b1b; }
    .badge-yellow { background: #fef3c7; color: #92400e; }
    .badge-gray   { background: #f3f4f6; color: #6b7280; }
    .badge-blue   { background: #dbeafe; color: #1e40af; }

    .action-link { background: none; border: none; font-size: 12px; font-weight: 600; cursor: pointer; padding: 4px 10px; border-radius: 6px; transition: all .15s; }
    .action-link.block-btn   { color: #ef4444; }
    .action-link.block-btn:hover   { background: #fee2e2; }
    .action-link.unblock-btn { color: #10b981; }
    .action-link.unblock-btn:hover { background: #d1fae5; }

    /* ── Add temp IP form ── */
    .add-ip-form { display: flex; gap: 10px; margin-top: 14px; flex-wrap: wrap; align-items: flex-end; }
    .add-ip-form input { flex: 1; min-width: 160px; border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 8px 12px; font-size: 13px; outline: none; }
    .add-ip-form input:focus { border-color: var(--brand); }
    .btn-add-ip { background: var(--brand); color: #fff; border: none; border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; }
    .btn-add-ip:hover { opacity: .9; }

    /* ── Empty & search ── */
    .empty-state { text-align: center; padding: 48px 0; color: #9ca3af; }
    .empty-state i { font-size: 40px; margin-bottom: 12px; display: block; }
    .search-bar { display: flex; gap: 10px; }
    .search-bar input { flex: 1; border: 1.5px solid #e5e7eb; border-radius: 10px; padding: 10px 16px; font-size: 14px; outline: none; }
    .search-bar input:focus { border-color: var(--brand); }
    .btn-search { background: var(--brand); color: #fff; border: none; border-radius: 10px; padding: 10px 20px; font-size: 14px; font-weight: 600; cursor: pointer; }
    .btn-clear  { background: #f3f4f6; color: #374151; border: none; border-radius: 10px; padding: 10px 16px; font-size: 14px; cursor: pointer; }

    @media (max-width: 768px) {
        .stats-bar { grid-template-columns: repeat(2,1fr); }
        .pending-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="ip-page">

    {{-- ── Page header ── --}}
    <div class="section-header mb-6">
        <div>
            <h1 style="font-size:22px;font-weight:800;color:#111827;margin:0">إدارة أجهزة تسجيل الدخول</h1>
            <p style="font-size:13px;color:#9ca3af;margin:4px 0 0">راقب طلبات الدخول الجديدة وأجهزة الموظفين المسجلة</p>
        </div>
        <form method="GET" class="search-bar">
            <input type="text" name="search" value="{{ $search }}" placeholder="بحث باسم الموظف...">
            <button type="submit" class="btn-search"><i class="fas fa-search me-1"></i> بحث</button>
            @if($search)
                <a href="{{ route('admin.employee-ips.index') }}" class="btn-clear">مسح</a>
            @endif
        </form>
    </div>

    {{-- ── Stats ── --}}
    <div class="stats-bar">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7"><i class="fas fa-clock" style="color:#d97706"></i></div>
            <div>
                <div class="stat-label">بانتظار المراجعة</div>
                <div class="stat-value" style="color:#d97706">{{ $stats['pending'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5"><i class="fas fa-check-circle" style="color:#10b981"></i></div>
            <div>
                <div class="stat-label">موافق عليها</div>
                <div class="stat-value" style="color:#10b981">{{ $stats['approved'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2"><i class="fas fa-ban" style="color:#ef4444"></i></div>
            <div>
                <div class="stat-label">مرفوضة</div>
                <div class="stat-value" style="color:#ef4444">{{ $stats['rejected'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#ede9fe"><i class="fas fa-users" style="color:#7c3aed"></i></div>
            <div>
                <div class="stat-label">موظفون مسجلون</div>
                <div class="stat-value" style="color:#7c3aed">{{ $stats['employees'] }}</div>
            </div>
        </div>
    </div>

    {{-- ── Pending requests ── --}}
    @if($pendingAttempts->isNotEmpty())
    <div class="mb-6">
        <div class="section-header">
            <div class="section-title">
                <div class="dot" style="background:#f59e0b"></div>
                طلبات دخول بانتظار المراجعة
                <span style="background:#f59e0b;color:#fff;border-radius:20px;padding:2px 10px;font-size:12px">{{ $pendingAttempts->count() }}</span>
            </div>
        </div>
        <div class="pending-grid">
            @foreach($pendingAttempts as $attempt)
            <div class="pending-card" id="pending-card-{{ $attempt->id }}">
                <div class="emp-name">{{ $attempt->employee->user->name ?? '—' }}</div>
                <div class="emp-role">{{ __($attempt->employee->user->role ?? '') }} &bull; {{ $attempt->employee->project->name ?? 'بدون مشروع' }}</div>
                <div class="ip-badge"><i class="fas fa-wifi"></i> {{ $attempt->ip_address }}</div>
                <div class="meta">
                    <i class="fas fa-clock me-1"></i>
                    {{ $attempt->attempted_at->diffForHumans() }} &mdash; {{ $attempt->attempted_at->format('Y/m/d H:i') }}
                </div>
                <div class="actions">
                    <button class="btn-approve"
                        onclick="reviewAttempt({{ $attempt->id }}, 'approve', this)">
                        <i class="fas fa-check"></i> قبول الجهاز
                    </button>
                    <button class="btn-reject"
                        onclick="reviewAttempt({{ $attempt->id }}, 'reject', this)">
                        <i class="fas fa-times"></i> رفض
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div style="background:#fff;border-radius:14px;padding:20px 24px;margin-bottom:24px;display:flex;align-items:center;gap:12px;box-shadow:0 2px 8px rgba(0,0,0,.06)">
        <i class="fas fa-check-circle" style="font-size:22px;color:#10b981"></i>
        <span style="color:#374151;font-weight:600">لا توجد طلبات دخول بانتظار المراجعة</span>
    </div>
    @endif

    {{-- ── Registered devices per employee ── --}}
    <div class="section-header">
        <div class="section-title">
            <div class="dot" style="background:#7c3aed"></div>
            أجهزة الموظفين المسجلة
        </div>
    </div>

    @forelse($employees as $employee)
    @php
        $blockedCount  = $employee->loginIps->where('blocked_at', '!=', null)->count();
        $allowedCount  = $employee->loginIps->where('blocked_at', null)->count();
        $initial       = mb_substr($employee->user->name ?? '?', 0, 1);
    @endphp
    <div class="emp-section">
        <div class="emp-section-header" onclick="toggleSection({{ $employee->id }})">
            <div class="emp-info">
                <div class="emp-avatar">{{ $initial }}</div>
                <div>
                    <div class="emp-section-name">{{ $employee->user->name ?? '—' }}</div>
                    <div class="emp-section-sub">{{ __($employee->user->role ?? '') }}</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                @if($blockedCount)
                    <span class="badge badge-red"><i class="fas fa-ban"></i> {{ $blockedCount }} محظور</span>
                @endif
                <span class="badge badge-gray">{{ $employee->loginIps->count() }} جهاز</span>
                <i class="fas fa-chevron-down chevron" id="chevron-{{ $employee->id }}"></i>
            </div>
        </div>

        <div class="emp-section-body" id="section-body-{{ $employee->id }}" style="display:none">
            <table class="ip-table">
                <thead>
                    <tr>
                        <th>عنوان IP</th>
                        <th>النوع</th>
                        <th>الحالة</th>
                        <th>ينتهي في</th>
                        <th>آخر تحديث</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employee->loginIps as $ip)
                    <tr id="ip-row-{{ $ip->id }}">
                        <td><span class="ip-mono">{{ $ip->ip_address }}</span></td>
                        <td>
                            @if($ip->is_temporary)
                                <span class="badge badge-yellow"><i class="fas fa-hourglass-half"></i> مؤقت</span>
                            @else
                                <span class="badge badge-blue"><i class="fas fa-shield-alt"></i> رئيسي</span>
                            @endif
                        </td>
                        <td>
                            @if($ip->blocked_at)
                                <span class="badge badge-red"><i class="fas fa-ban"></i> محظور</span>
                            @elseif($ip->isExpiredTemporary())
                                <span class="badge badge-gray"><i class="fas fa-clock"></i> منتهي</span>
                            @else
                                <span class="badge badge-green"><i class="fas fa-check"></i> مسموح</span>
                            @endif
                        </td>
                        <td style="color:#9ca3af;font-size:12px">
                            {{ $ip->allowed_until ? $ip->allowed_until->format('Y/m/d H:i') : '—' }}
                        </td>
                        <td style="color:#9ca3af;font-size:12px">{{ $ip->updated_at->diffForHumans() }}</td>
                        <td>
                            @if($ip->blocked_at)
                                <button class="action-link unblock-btn" onclick="toggleBlock({{ $ip->id }}, 'unblock', this)">
                                    <i class="fas fa-unlock-alt me-1"></i>رفع الحظر
                                </button>
                            @else
                                <button class="action-link block-btn" onclick="toggleBlock({{ $ip->id }}, 'block', this)">
                                    <i class="fas fa-ban me-1"></i>حظر
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Add temp IP ─ collapsed under a toggle --}}
            <div style="margin-top:14px">
                <button onclick="toggleAddForm({{ $employee->id }})"
                    style="background:none;border:1.5px dashed #d1d5db;color:#6b7280;border-radius:8px;padding:6px 14px;font-size:12px;cursor:pointer;font-weight:600">
                    <i class="fas fa-plus me-1"></i> إضافة جهاز مؤقت
                </button>
                <div id="add-form-{{ $employee->id }}" style="display:none;margin-top:10px">
                    <div class="add-ip-form">
                        <input type="text" placeholder="عنوان IP" id="ip-input-{{ $employee->id }}">
                        <input type="text" placeholder="صالح لغاية (اختياري)" id="until-input-{{ $employee->id }}" class="flatpickr-input">
                        <button class="btn-add-ip" onclick="addTempIp({{ $employee->id }})">إضافة</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-laptop"></i>
        <p>لا يوجد موظفون مسجلون بأجهزة حتى الآن</p>
    </div>
    @endforelse

</div>
@endsection

@push('scripts')
<script>
const _csrf = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

function toggleSection(empId) {
    const body    = document.getElementById('section-body-' + empId);
    const chevron = document.getElementById('chevron-' + empId);
    const open    = body.style.display !== 'none';
    body.style.display    = open ? 'none' : 'block';
    chevron.classList.toggle('open', !open);
}

function toggleAddForm(empId) {
    const el = document.getElementById('add-form-' + empId);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

async function post(url) {
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _csrf, 'Accept': 'application/json' },
    });
    return res.json();
}

function reviewAttempt(id, action, btn) {
    btn.disabled = true;
    const card = document.getElementById('pending-card-' + id);
    post(`/admin/pending-login-attempts/${id}/${action}`).then(data => {
        if (data.success) {
            Swal.fire({
                toast: true, position: 'top-end', timer: 2500, timerProgressBar: true,
                showConfirmButton: false, icon: action === 'approve' ? 'success' : 'info',
                title: data.message,
            });
            card.style.transition = 'opacity .4s, transform .4s';
            card.style.opacity    = '0';
            card.style.transform  = 'scale(.95)';
            setTimeout(() => card.remove(), 420);

            // Update pending stat counter
            const counter = document.querySelector('.stat-value[style*="d97706"]');
            if (counter) { const n = parseInt(counter.textContent) - 1; counter.textContent = n < 0 ? 0 : n; }
        }
    }).catch(() => { btn.disabled = false; });
}

function toggleBlock(ipId, action, btn) {
    btn.disabled = true;
    post(`/admin/employee-ips/${ipId}/${action}`).then(data => {
        if (data.success) {
            Swal.fire({
                toast: true, position: 'top-end', timer: 2500, timerProgressBar: true,
                showConfirmButton: false, icon: 'success', title: data.message,
            });
            // Swap button label without reload
            if (action === 'block') {
                btn.className   = 'action-link unblock-btn';
                btn.innerHTML   = '<i class="fas fa-unlock-alt me-1"></i>رفع الحظر';
                btn.onclick     = () => toggleBlock(ipId, 'unblock', btn);
                const td        = btn.closest('tr').querySelector('td:nth-child(3)');
                if (td) td.innerHTML = '<span class="badge badge-red"><i class="fas fa-ban"></i> محظور</span>';
            } else {
                btn.className   = 'action-link block-btn';
                btn.innerHTML   = '<i class="fas fa-ban me-1"></i>حظر';
                btn.onclick     = () => toggleBlock(ipId, 'block', btn);
                const td        = btn.closest('tr').querySelector('td:nth-child(3)');
                if (td) td.innerHTML = '<span class="badge badge-green"><i class="fas fa-check"></i> مسموح</span>';
            }
        }
        btn.disabled = false;
    }).catch(() => { btn.disabled = false; });
}

async function addTempIp(empId) {
    const ip    = document.getElementById('ip-input-'    + empId).value.trim();
    const until = document.getElementById('until-input-' + empId).value.trim();
    if (!ip) { Swal.fire({ icon: 'warning', title: 'أدخل عنوان IP', timer: 1500, showConfirmButton: false }); return; }

    const fd = new FormData();
    fd.append('_token', _csrf);
    fd.append('ip_address', ip);
    if (until) fd.append('allowed_until', until);

    const res  = await fetch(`/admin/employee-ips/${empId}/add-temp-ip`, { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
        Swal.fire({ toast: true, position: 'top-end', timer: 2500, timerProgressBar: true, showConfirmButton: false, icon: 'success', title: data.message });
        document.getElementById('ip-input-'    + empId).value = '';
        document.getElementById('until-input-' + empId).value = '';
        // Reload section body content after short delay
        setTimeout(() => location.reload(), 800);
    }
}

// Flatpickr for date/time inputs
document.querySelectorAll('.flatpickr-input').forEach(el => {
    if (typeof flatpickr !== 'undefined') {
        flatpickr(el, { enableTime: true, dateFormat: 'Y-m-d H:i', minDate: 'today' });
    }
});
</script>
@endpush
