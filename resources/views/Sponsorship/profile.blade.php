@extends('layouts.master')
@section('title', 'ملف كفالة - ' . $employee->name)

<style>
    :root {
        --primary: #6d28d9;
        --secondary: #7c3aed;
        --accent:   #4776E6;
        --success:  #09d421;
        --danger:   #f72585;
        --warning:  #ff9e00;
        --info:     #00b4d8;
        --dark:     #1e293b;
        --card-bg:  rgba(255,255,255,0.95);
    }

    .profile-container {
        font-family: 'Inter', -apple-system, sans-serif;
        background: linear-gradient(135deg,#f5f7fa 0%,#e4e8f0 100%);
        min-height: 100vh;
    }
    .employee-header {
        padding: 2rem;
        background: linear-gradient(to right, #5b21b6, #7c3aed);
        color: white;
        position: relative;
        overflow: hidden;
    }
    .employee-header::before {
        content:'';
        position:absolute;top:-50%;right:-50%;
        width:200%;height:200%;
        background:radial-gradient(circle,rgba(255,255,255,.1) 0%,rgba(255,255,255,0) 70%);
        transform:rotate(30deg);
    }
    .profile-card {
        display:flex;
        background:var(--card-bg);
        border-radius:16px;
        box-shadow:0 8px 32px rgba(31,38,135,.15);
        backdrop-filter:blur(8px);
        overflow:hidden;
        max-width:1350px;
        margin:0 auto;
        position:relative;z-index:1;
        border:1px solid rgba(255,255,255,.3);
    }
    .profile-avatar {
        padding:2.5rem;
        display:flex;align-items:center;justify-content:center;
        background:linear-gradient(135deg,rgba(255,255,255,.9) 0%,rgba(245,247,250,.8) 100%);
    }
    .profile-content {
        flex:1;padding:2.5rem;
        display:flex;flex-direction:column;gap:1.5rem;
    }
    .profile-title h1 {
        margin:0;font-size:2rem;color:var(--dark);
        font-weight:700;word-break:break-word;
    }
    .role-badge {
        background:linear-gradient(to right,#6d28d9,#7c3aed);
        color:white;padding:.4rem 1rem;border-radius:24px;
        font-size:.85rem;font-weight:600;
        box-shadow:0 2px 8px rgba(109,40,217,.3);
    }
    .profile-meta { display:flex;gap:1.5rem;flex-wrap:wrap; }
    .meta-item { display:flex;align-items:center;gap:.5rem;color:#64748b;font-size:17px;font-weight:700; }
    .meta-item i { color:#7c3aed;font-size:1rem; }
    .profile-actions { display:flex;gap:1rem;flex-wrap:wrap;margin-top:auto; }
    .action-button {
        display:flex;align-items:center;gap:.5rem;
        padding:.7rem 1.5rem;border-radius:10px;font-weight:500;
        text-decoration:none;transition:all .3s;border:none;cursor:pointer;font-size:.9rem;
    }
    .action-button:hover { transform:translateY(-3px);box-shadow:0 6px 16px rgba(0,0,0,.15); }

    .stats-grid-top {
        display:grid;grid-template-columns:repeat(3,1fr);gap:16px;
        padding:0 0 4px;margin-top:4px;border-top:1px solid #e5e7eb;
    }
    .mini-stat { text-align:center;padding:12px 0; }
    .mini-stat-val { font-size:1.4rem;font-weight:800;color:var(--dark); }
    .mini-stat-lbl { font-size:.72rem;font-weight:700;color:#94a3b8;margin-top:2px; }

    .unified-sections {
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(380px,1fr));
        gap:24px;padding:32px;max-width:1400px;margin:0 auto;
    }
    .unified-section {
        background:var(--card-bg);padding:24px 28px;border-radius:16px;
        box-shadow:0 6px 20px rgba(0,0,0,.05);
        transition:all .3s;border:1px solid rgba(0,0,0,.03);
        animation:fadeIn .4s ease forwards;
    }
    .unified-section:hover { transform:translateY(-4px);box-shadow:0 12px 28px rgba(0,0,0,.1); }
    @keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
    .section-header {
        margin-bottom:18px;color:var(--primary);
        border-bottom:1px solid rgba(0,0,0,.07);padding-bottom:10px;
    }
    .section-header h2 { margin:0;font-size:1.1rem;display:flex;align-items:center;gap:.5rem; }
    .section-body { display:flex;flex-direction:column;gap:2px; }
    .info-row { display:flex;margin-bottom:13px;align-items:center; }
    .info-label {
        font-weight:600;width:180px;color:var(--dark);
        font-size:.92rem;display:flex;align-items:center;gap:.5rem;
    }
    .info-value { flex:1;font-size:.92rem;font-weight:600;color:#34373c; }

    /* Document cards */
    .doc-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:4px; }
    .doc-card {
        border-radius:14px;border:2px dashed #ddd6fe;
        background:#faf5ff;overflow:hidden;
        display:flex;flex-direction:column;align-items:center;
        transition:all .25s;
    }
    .doc-card:hover { border-color:#7c3aed;box-shadow:0 4px 14px rgba(124,58,237,.12); }
    .doc-card-preview {
        width:100%;height:100px;
        display:flex;align-items:center;justify-content:center;
        background:#ede9fe;cursor:pointer;overflow:hidden;
    }
    .doc-card-preview img { width:100%;height:100%;object-fit:cover; }
    .doc-card-footer {
        width:100%;padding:8px;text-align:center;
        font-size:.72rem;font-weight:700;color:#6d28d9;
    }

    /* Preview modal */
    #docPreviewModal {
        display:none;position:fixed;inset:0;z-index:9999;
        background:rgba(0,0,0,.75);backdrop-filter:blur(6px);
        align-items:center;justify-content:center;
    }
    #docPreviewModal.open { display:flex; }
    #docPreviewModal .modal-inner {
        background:#fff;border-radius:20px;max-width:90vw;max-height:90vh;
        overflow:hidden;display:flex;flex-direction:column;
        box-shadow:0 20px 60px rgba(0,0,0,.3);
    }
    #docPreviewModal .modal-head {
        display:flex;align-items:center;justify-content:space-between;
        padding:14px 20px;border-bottom:1px solid #e5e7eb;
        font-weight:700;font-size:.95rem;color:#1e293b;
    }
    #docPreviewModal .modal-body-inner {
        flex:1;overflow:auto;padding:16px;
        display:flex;align-items:center;justify-content:center;min-height:300px;
    }
    #docPreviewModal img, #docPreviewModal iframe {
        max-width:100%;max-height:70vh;border-radius:10px;
    }
    #docPreviewModal .modal-actions {
        padding:12px 20px;border-top:1px solid #e5e7eb;
        display:flex;gap:10px;align-items:center;
    }

    /* Upload area */
    .upload-zone {
        border:2px dashed #c4b5fd;border-radius:10px;
        padding:14px;text-align:center;cursor:pointer;
        transition:all .2s;background:#faf5ff;
    }
    .upload-zone:hover { border-color:#7c3aed;background:#ede9fe; }
    .upload-zone input[type=file] { display:none; }
    .upload-zone label { cursor:pointer;font-size:.78rem;font-weight:700;color:#7c3aed; }

    @media(max-width:1024px) {
        .profile-card { flex-direction:column; }
        .profile-avatar { padding:2rem; }
        .doc-grid { grid-template-columns:repeat(2,1fr); }
    }
    @media(max-width:768px) {
        .unified-sections { grid-template-columns:1fr;padding:16px; }
        .info-row { flex-direction:column;align-items:flex-start;gap:.25rem; }
        .info-label { width:100%; }
        .doc-grid { grid-template-columns:1fr; }
    }
</style>

@section('content')
@php
    $user = $employee->user;
    $age  = $user?->birthday ? $user->birthday->age : null;
    $ticket = $flightTicket;

    $expiryStatus = match(true) {
        $idExpiryDays === null => ['label'=>'غير محدد',         'cls'=>'bg-gray-100 text-gray-500',    'dot'=>'bg-gray-400'],
        $idExpiryDays < 0     => ['label'=>'منتهية الصلاحية',   'cls'=>'bg-red-100 text-red-700',      'dot'=>'bg-red-500'],
        $idExpiryDays <= 30   => ['label'=>"تنتهي خلال {$idExpiryDays} يوم", 'cls'=>'bg-red-50 text-red-600',   'dot'=>'bg-red-400'],
        $idExpiryDays <= 90   => ['label'=>"تنتهي خلال {$idExpiryDays} يوم", 'cls'=>'bg-amber-50 text-amber-700','dot'=>'bg-amber-400'],
        $idExpiryDays <= 180  => ['label'=>"تنتهي خلال {$idExpiryDays} يوم", 'cls'=>'bg-yellow-50 text-yellow-700','dot'=>'bg-yellow-400'],
        default               => ['label'=>'ساري المفعول',       'cls'=>'bg-green-50 text-green-700',   'dot'=>'bg-green-400'],
    };

    $docs = $employee->sponsorship_documents ?? [];
    $docMap = [
        'passport'       => ['label'=>'جواز السفر',        'icon'=>'fas fa-passport'],
        'id_card'        => ['label'=>'الهوية / الإقامة',  'icon'=>'fas fa-id-card'],
        'driver_license' => ['label'=>'رخصة القيادة',      'icon'=>'fas fa-car'],
    ];
@endphp

<div class="profile-container" dir="rtl">

    {{-- Header --}}
    <div class="employee-header">
        <div style="max-width:1350px;margin:0 auto 1.5rem;position:relative;z-index:1;">
            <a href="{{ route('sponsorship.index') }}"
               style="color:rgba(255,255,255,.7);font-size:.85rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="fas fa-chevron-right text-xs"></i>
                الكفالة والموظفون
            </a>
            <span style="color:rgba(255,255,255,.4);margin:0 8px;">/</span>
            <span style="color:#fff;font-weight:700;">{{ $employee->name }}</span>
        </div>

        <div class="profile-card">
            {{-- Avatar --}}
            <div class="profile-avatar">
                <div style="position:relative;">
                    @if($user?->personal_image)
                        <img src="{{ $user->personal_image }}" alt="{{ $employee->name }}"
                             class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md">
                    @else
                        <div style="width:128px;height:128px;border-radius:50%;border:4px solid white;
                                    background:linear-gradient(135deg,#ede9fe,#ddd6fe);
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:3rem;font-weight:800;color:#6d28d9;box-shadow:0 8px 24px rgba(0,0,0,.1);">
                            {{ mb_substr($employee->name, 0, 1) }}
                        </div>
                    @endif
                    <div style="position:absolute;bottom:4px;left:4px;width:16px;height:16px;
                                border-radius:50%;border:2px solid white;background:#22c55e;"></div>
                </div>
            </div>

            {{-- Info --}}
            <div class="profile-content">
                <div class="profile-title" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                    <h1>{{ $employee->name }}</h1>
                    <span class="role-badge"><i class="fas fa-passport me-1"></i> موظف كفالة</span>
                    <span class="inline-flex items-center gap-1.5 text-sm font-bold px-3 py-1.5 rounded-full {{ $expiryStatus['cls'] }}">
                        <span style="width:8px;height:8px;border-radius:50%;display:inline-block;background:{{ str_replace('bg-','',$expiryStatus['dot']) === 'green-400' ? '#4ade80' : (str_replace('bg-','',$expiryStatus['dot']) === 'red-500' ? '#ef4444' : '#facc15') }};"></span>
                        {{ $expiryStatus['label'] }}
                    </span>
                </div>

                <div class="profile-meta">
                    <div class="meta-item"><i class="fas fa-briefcase"></i><span>{{ $employee->job ?? 'لا يوجد مسمى وظيفي' }}</span></div>
                    @if($employee->project)
                    <div class="meta-item"><i class="fas fa-building"></i><span>{{ $employee->project->name }}</span></div>
                    @endif
                    @if($user?->nationality)
                    <div class="meta-item"><i class="fas fa-flag"></i><span>{{ $user->nationality }}</span></div>
                    @endif
                </div>

                {{-- Mini stats row --}}
                <div class="stats-grid-top">
                    <div class="mini-stat">
                        <div class="mini-stat-val">{{ $age ?? '—' }}</div>
                        <div class="mini-stat-lbl">العمر</div>
                    </div>
                    <div class="mini-stat" style="border-right:1px solid #e5e7eb;border-left:1px solid #e5e7eb;">
                        <div class="mini-stat-val">{{ $serviceYears ?? '—' }}</div>
                        <div class="mini-stat-lbl">سنوات خدمة</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-val">{{ number_format($remaining) }}</div>
                        <div class="mini-stat-lbl">رصيد الإجازة</div>
                    </div>
                </div>

                <div class="profile-actions">
                    @if($user?->contact_info['phone_number'] ?? null)
                    <a href="https://wa.me/966{{ ltrim($user->contact_info['phone_number'], '0') }}"
                       class="action-button" target="_blank"
                       style="background:linear-gradient(to right,#25D366,#128C7E);color:white;box-shadow:0 2px 10px rgba(37,211,102,.3);">
                        <i class="fab fa-whatsapp"></i> تواصل واتساب
                    </a>
                    @endif
                    <button onclick="document.getElementById('editDataModal').style.display='flex'"
                            class="action-button"
                            style="background:linear-gradient(to right,#6d28d9,#7c3aed);color:white;box-shadow:0 2px 10px rgba(109,40,217,.3);">
                        <i class="fas fa-edit"></i> تعديل البيانات
                    </button>
                    <a href="{{ route('sponsorship.index') }}" class="action-button"
                       style="background:rgba(255,255,255,.9);color:var(--dark);border:1px solid rgba(0,0,0,.1);">
                        <i class="fas fa-arrow-right"></i> رجوع
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Sections --}}
    <div class="unified-sections">

        {{-- Sponsorship / Passport data --}}
        <div class="unified-section">
            <div class="section-header">
                <h2><i class="fas fa-passport" style="color:var(--primary)"></i> بيانات الكفالة</h2>
            </div>
            <div class="section-body">
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-passport" style="color:var(--info)"></i>رقم جواز السفر</div>
                    <div class="info-value" style="font-family:monospace;font-size:1rem;">{{ $employee->passport_number ?? '—' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-calendar-alt" style="color:var(--warning)"></i>انتهاء الإقامة / الهوية</div>
                    <div class="info-value">
                        @if($employee->id_expiry_date)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-bold {{ $expiryStatus['cls'] }}">
                                {{ $employee->id_expiry_date->format('Y/m/d') }}
                                <span class="text-xs opacity-75">({{ $expiryStatus['label'] }})</span>
                            </span>
                        @else <span style="color:#94a3b8;">—</span> @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-car" style="color:var(--secondary)"></i>رقم رخصة القيادة</div>
                    <div class="info-value" style="font-family:monospace;">{{ $employee->driver_license_number ?? '—' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-id-card" style="color:var(--accent)"></i>رقم الهوية / الإقامة</div>
                    <div class="info-value" style="font-family:monospace;">{{ $user?->id_card ?? '—' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-calendar-check" style="color:var(--success)"></i>تاريخ الالتحاق</div>
                    <div class="info-value">{{ $employee->joining_date?->format('Y/m/d') ?? '—' }}</div>
                </div>
                @if($employee->marital_status === 'married' && $employee->wives_count)
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-users" style="color:var(--info)"></i>عدد الزوجات</div>
                    <div class="info-value">{{ $employee->wives_count }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-heart" style="color:var(--danger)"></i>الحالة الاجتماعية</div>
                    <div class="info-value">{{ $employee->getMaritalStatus() }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-plane" style="color:#3b82f6"></i>تذكرة السفر</div>
                    <div class="info-value">
                        @if($employee->flight_ticket)
                            <span style="color:#16a34a;font-weight:700;"><i class="fas fa-check-circle me-1"></i>يستحق</span>
                        @else
                            <span style="color:#94a3b8;font-weight:600;"><i class="fas fa-times-circle me-1"></i>لا يستحق</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Personal & Contact --}}
        <div class="unified-section">
            <div class="section-header">
                <h2><i class="fas fa-user" style="color:var(--primary)"></i> البيانات الشخصية والتواصل</h2>
            </div>
            <div class="section-body">
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-user" style="color:var(--secondary)"></i>الاسم الكامل</div>
                    <div class="info-value">{{ $employee->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-birthday-cake" style="color:var(--warning)"></i>تاريخ الميلاد</div>
                    <div class="info-value">
                        {{ $user?->birthday?->format('Y/m/d') ?? '—' }}
                        @if($age) <span style="color:#94a3b8;font-size:.82rem;">({{ $age }} سنة)</span> @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-flag" style="color:var(--success)"></i>الجنسية</div>
                    <div class="info-value">{{ $user?->nationality ?? '—' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-phone" style="color:var(--info)"></i>رقم الجوال</div>
                    <div class="info-value" dir="ltr" style="text-align:right;">
                        {{ $user?->contact_info['phone_number'] ?? '—' }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-envelope" style="color:var(--accent)"></i>البريد الإلكتروني</div>
                    <div class="info-value" style="word-break:break-all;">{{ $user?->email ?? '—' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-map-marker-alt" style="color:var(--danger)"></i>المدينة السكنية</div>
                    <div class="info-value">{{ $employee->residential_address['city'] ?? '—' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-home" style="color:var(--primary)"></i>الحي السكني</div>
                    <div class="info-value">{{ $employee->residential_address['neighborhood'] ?? '—' }}</div>
                </div>
            </div>
        </div>

        {{-- Leave Balance --}}
        <div class="unified-section">
            <div class="section-header">
                <h2><i class="fas fa-calendar-check" style="color:#22c55e"></i> رصيد الإجازات</h2>
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:16px;">
                <div style="text-align:center;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:16px;padding:18px 8px;">
                    <div style="font-size:2rem;font-weight:800;color:#16a34a;">{{ (int)$remaining }}</div>
                    <div style="font-size:.7rem;font-weight:700;color:#4ade80;margin-top:4px;">متبقي</div>
                </div>
                <div style="text-align:center;background:#fef2f2;border:1px solid #fecaca;border-radius:16px;padding:18px 8px;">
                    <div style="font-size:2rem;font-weight:800;color:#dc2626;">{{ $totalTaken }}</div>
                    <div style="font-size:.7rem;font-weight:700;color:#f87171;margin-top:4px;">مأخوذ</div>
                </div>
                <div style="text-align:center;background:#eff6ff;border:1px solid #bfdbfe;border-radius:16px;padding:18px 8px;">
                    <div style="font-size:2rem;font-weight:800;color:#2563eb;">{{ $entitlement }}</div>
                    <div style="font-size:.7rem;font-weight:700;color:#93c5fd;margin-top:4px;">يوم/سنة</div>
                </div>
            </div>
            <div style="background:#f8fafc;border-radius:12px;padding:12px 16px;text-align:center;">
                <span style="font-size:.85rem;font-weight:600;color:#64748b;">إجمالي المستحق: </span>
                <span style="font-size:.85rem;font-weight:800;color:#1e293b;">{{ number_format($totalAccrued,1) }} يوم</span>
            </div>

            {{-- Add leave --}}
            <div style="margin-top:16px;border-top:1px solid #f1f5f9;padding-top:14px;">
                <button onclick="document.getElementById('leaveFormWrap').style.display=document.getElementById('leaveFormWrap').style.display==='none'?'block':'none'"
                        style="font-size:.82rem;font-weight:700;color:#16a34a;border:1.5px solid #86efac;background:#f0fdf4;padding:7px 16px;border-radius:10px;cursor:pointer;width:100%;">
                    <i class="fas fa-plus me-1"></i> تسجيل إجازة جديدة
                </button>
                <div id="leaveFormWrap" style="display:none;margin-top:12px;">
                    <form id="leaveForm" class="grid grid-cols-2 gap-3">
                        @csrf
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                        <div>
                            <label style="font-size:.75rem;font-weight:700;color:#374151;display:block;margin-bottom:4px;">نوع الإجازة</label>
                            <select name="leave_type" style="width:100%;border:1.5px solid #e5e7eb;border-radius:10px;padding:8px 12px;font-size:.82rem;">
                                <option value="annual">سنوية</option>
                                <option value="sick">مرضية</option>
                                <option value="emergency">طارئة</option>
                                <option value="unpaid">بدون راتب</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size:.75rem;font-weight:700;color:#374151;display:block;margin-bottom:4px;">الحالة</label>
                            <select name="status" style="width:100%;border:1.5px solid #e5e7eb;border-radius:10px;padding:8px 12px;font-size:.82rem;">
                                <option value="approved">موافق</option>
                                <option value="pending">معلقة</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size:.75rem;font-weight:700;color:#374151;display:block;margin-bottom:4px;">تاريخ البداية</label>
                            <input type="date" name="start_date" required style="width:100%;border:1.5px solid #e5e7eb;border-radius:10px;padding:8px 12px;font-size:.82rem;">
                        </div>
                        <div>
                            <label style="font-size:.75rem;font-weight:700;color:#374151;display:block;margin-bottom:4px;">تاريخ النهاية</label>
                            <input type="date" name="end_date" required style="width:100%;border:1.5px solid #e5e7eb;border-radius:10px;padding:8px 12px;font-size:.82rem;">
                        </div>
                        <div style="grid-column:span 2;">
                            <button type="button" onclick="submitLeave()"
                                    style="background:#16a34a;color:white;border:none;border-radius:10px;padding:9px 20px;font-weight:700;font-size:.82rem;cursor:pointer;">
                                <i class="fas fa-save me-1"></i> حفظ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Flight ticket (sponsorship calculated) --}}
        <div class="unified-section">
            <div class="section-header">
                <h2><i class="fas fa-plane" style="color:#3b82f6"></i> تذكرة السفر السنوية</h2>
            </div>
            @if($ticket['type'] !== 'none')
                <div style="display:flex;align-items:center;justify-content:space-between;background:#f8fafc;border-radius:14px;padding:18px 20px;margin-bottom:12px;">
                    <div>
                        <div style="font-size:.75rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;">الاستحقاق الحالي</div>
                        <div style="font-size:1.15rem;font-weight:800;color:{{ $ticket['type']==='full' ? '#16a34a' : '#d97706' }};margin-top:3px;">
                            {{ $ticket['label'] }}
                        </div>
                    </div>
                    <div style="text-align:left;">
                        <div style="font-size:1.7rem;font-weight:900;color:#1e293b;">{{ number_format($ticket['amount'],0) }}</div>
                        <div style="font-size:.75rem;font-weight:700;color:#94a3b8;">ر.س سنوياً</div>
                    </div>
                </div>
                @if($ticket['type']==='half' && $serviceYears !== null && $serviceYears < 5)
                    <div style="text-align:center;font-size:.78rem;font-weight:600;color:#94a3b8;background:#f8fafc;border-radius:10px;padding:8px;">
                        <i class="fas fa-clock me-1 text-amber-500"></i>
                        يستحق تذكرة كاملة بعد {{ 5 - $serviceYears }} سنة
                        {{ $serviceMonths ? "و {$serviceMonths} شهر" : '' }}
                    </div>
                @endif
                <div style="margin-top:12px;padding:12px 16px;border-radius:12px;border:1.5px solid #dbeafe;background:#eff6ff;">
                    <div style="font-size:.72rem;font-weight:700;color:#3b82f6;text-transform:uppercase;margin-bottom:4px;">مدة الخدمة</div>
                    <div style="font-size:.92rem;font-weight:700;color:#1e40af;">
                        {{ $serviceYears ?? '—' }} سنة {{ $serviceMonths ? "و {$serviceMonths} شهر" : '' }}
                        <span style="font-size:.75rem;color:#64748b;">من تاريخ الالتحاق</span>
                    </div>
                </div>
            @else
                <div style="text-align:center;padding:32px 0;color:#94a3b8;">
                    <i class="fas fa-plane" style="font-size:2rem;margin-bottom:10px;display:block;opacity:.3;"></i>
                    <p style="font-weight:600;">لا تتوفر بيانات كافية</p>
                </div>
            @endif
        </div>

        {{-- Document Archive --}}
        <div class="unified-section" style="grid-column:span 2;" x-data="{}">
            <div class="section-header" style="display:flex;align-items:center;justify-content:space-between;">
                <h2><i class="fas fa-folder-open" style="color:var(--primary)"></i> أرشفة الوثائق</h2>
                <span style="font-size:.72rem;color:#94a3b8;font-weight:600;">اضغط على الوثيقة للمعاينة</span>
            </div>
            <div class="doc-grid">
                @foreach($docMap as $key => $meta)
                @php $filePath = $docs[$key] ?? null; @endphp
                <div class="doc-card">
                    {{-- Preview area --}}
                    <div class="doc-card-preview"
                         @if($filePath)
                         onclick="previewDoc('{{ asset('storage/'.$filePath) }}','{{ $meta['label'] }}')"
                         title="معاينة {{ $meta['label'] }}"
                         @endif>
                        @if($filePath)
                            @php $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION)); @endphp
                            @if(in_array($ext, ['jpg','jpeg','png','webp']))
                                <img src="{{ asset('storage/'.$filePath) }}" alt="{{ $meta['label'] }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <div style="display:flex;flex-direction:column;align-items:center;gap:6px;">
                                    <i class="fas fa-file-pdf" style="font-size:2.2rem;color:#dc2626;"></i>
                                    <span style="font-size:.7rem;font-weight:700;color:#7c3aed;">PDF</span>
                                </div>
                            @endif
                        @else
                            <div style="display:flex;flex-direction:column;align-items:center;gap:6px;opacity:.4;">
                                <i class="{{ $meta['icon'] }}" style="font-size:2rem;color:#7c3aed;"></i>
                                <span style="font-size:.7rem;font-weight:700;color:#7c3aed;">لا توجد وثيقة</span>
                            </div>
                        @endif
                    </div>

                    <div class="doc-card-footer">
                        <div style="margin-bottom:6px;">{{ $meta['label'] }}</div>

                        {{-- Upload form --}}
                        <form action="{{ route('sponsorship.upload-doc', $employee) }}"
                              method="POST" enctype="multipart/form-data"
                              onsubmit="submitDocUpload(event,this)">
                            @csrf
                            <input type="hidden" name="doc_key" value="{{ $key }}">
                            <div class="upload-zone" onclick="this.querySelector('input').click()">
                                <input type="file" name="doc_file" accept="image/*,application/pdf"
                                       onchange="this.closest('form').dispatchEvent(new Event('autosubmit'))">
                                <label><i class="fas fa-upload me-1"></i> {{ $filePath ? 'تحديث' : 'رفع وثيقة' }}</label>
                            </div>
                        </form>
                        @if($filePath)
                        <a href="{{ asset('storage/'.$filePath) }}" download
                           style="display:inline-flex;align-items:center;gap:4px;margin-top:6px;font-size:.7rem;color:#6d28d9;text-decoration:none;font-weight:600;">
                            <i class="fas fa-download"></i> تحميل
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Languages --}}
        @if($employee->languages && count($employee->languages))
        <div class="unified-section">
            <div class="section-header">
                <h2><i class="fas fa-language" style="color:var(--primary)"></i> اللغات</h2>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                @foreach($employee->languages as $lang)
                <div style="display:inline-flex;align-items:center;gap:8px;background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;font-size:.85rem;font-weight:700;padding:8px 16px;border-radius:12px;">
                    {{ $lang['language'] ?? $lang }}
                    @if(!empty($lang['level']))
                    <span style="font-size:.7rem;color:#3b82f6;background:#dbeafe;padding:2px 8px;border-radius:20px;">{{ $lang['level'] }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Leave History --}}
        <div class="unified-section">
            <div class="section-header">
                <h2><i class="fas fa-calendar-alt" style="color:var(--primary)"></i> سجل الإجازات</h2>
            </div>
            @forelse($employee->leaveRequests as $leave)
            @php
                $lv = [
                    'approved'=>['bg'=>'#f0fdf4','border'=>'#bbf7d0','badge'=>'#dcfce7 text-green-700'],
                    'pending' =>['bg'=>'#fefce8','border'=>'#fef08a','badge'=>'#fef9c3 text-yellow-700'],
                    'rejected'=>['bg'=>'#fef2f2','border'=>'#fecaca','badge'=>'#fee2e2 text-red-600'],
                ][$leave->status] ?? ['bg'=>'#f8fafc','border'=>'#e5e7eb','badge'=>'#f1f5f9 text-gray-600'];
            @endphp
            <div style="display:flex;align-items:center;justify-content:space-between;border-radius:14px;border:1.5px solid {{ $lv['border'] }};background:{{ $lv['bg'] }};padding:14px 18px;margin-bottom:10px;">
                <div>
                    <div style="font-weight:700;color:#1e293b;font-size:.88rem;">{{ $leave->getLeaveTypeLabel() }}</div>
                    <div style="font-size:.75rem;color:#64748b;margin-top:3px;">
                        {{ $leave->start_date->format('Y/m/d') }} → {{ $leave->end_date->format('Y/m/d') }}
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="text-align:center;">
                        <div style="font-size:1.5rem;font-weight:900;color:#1e293b;">{{ $leave->days_count }}</div>
                        <div style="font-size:.7rem;font-weight:700;color:#94a3b8;">يوم</div>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:28px 0;color:#94a3b8;">
                <i class="fas fa-calendar-times" style="font-size:2rem;margin-bottom:8px;display:block;opacity:.3;"></i>
                <p style="font-weight:600;">لا يوجد سجل إجازات</p>
            </div>
            @endforelse
        </div>

    </div>
</div>

{{-- ===== Edit Data Modal ===== --}}
<div id="editDataModal" style="display:none;position:fixed;inset:0;z-index:9998;background:rgba(0,0,0,.6);backdrop-filter:blur(5px);align-items:center;justify-content:center;overflow-y:auto;padding:20px;">
    <div style="background:#fff;border-radius:20px;max-width:640px;width:100%;margin:auto;box-shadow:0 20px 60px rgba(0,0,0,.25);">
        <div style="padding:20px 24px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;">
            <h3 style="margin:0;font-size:1.1rem;font-weight:800;color:#1e293b;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-edit" style="color:#7c3aed;"></i> تعديل بيانات الكفالة
            </h3>
            <button onclick="document.getElementById('editDataModal').style.display='none'"
                    style="background:none;border:none;font-size:1.2rem;color:#94a3b8;cursor:pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editDataForm" action="{{ route('sponsorship.update-data', $employee) }}" method="POST">
            @csrf @method('PUT')
            <div style="padding:20px 24px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <label style="font-size:.78rem;font-weight:700;color:#374151;display:block;margin-bottom:5px;">رقم جواز السفر</label>
                    <input type="text" name="passport_number" value="{{ $employee->passport_number }}"
                           style="width:100%;border:1.5px solid #e5e7eb;border-radius:10px;padding:9px 12px;font-size:.88rem;">
                </div>
                <div>
                    <label style="font-size:.78rem;font-weight:700;color:#374151;display:block;margin-bottom:5px;">تاريخ انتهاء الإقامة / الهوية</label>
                    <input type="date" name="id_expiry_date" value="{{ $employee->id_expiry_date?->format('Y-m-d') }}"
                           style="width:100%;border:1.5px solid #e5e7eb;border-radius:10px;padding:9px 12px;font-size:.88rem;">
                </div>
                <div>
                    <label style="font-size:.78rem;font-weight:700;color:#374151;display:block;margin-bottom:5px;">رقم رخصة القيادة</label>
                    <input type="text" name="driver_license_number" value="{{ $employee->driver_license_number }}"
                           style="width:100%;border:1.5px solid #e5e7eb;border-radius:10px;padding:9px 12px;font-size:.88rem;">
                </div>
                <div>
                    <label style="font-size:.78rem;font-weight:700;color:#374151;display:block;margin-bottom:5px;">عدد الزوجات</label>
                    <input type="number" name="wives_count" value="{{ $employee->wives_count }}" min="1" max="4"
                           style="width:100%;border:1.5px solid #e5e7eb;border-radius:10px;padding:9px 12px;font-size:.88rem;">
                </div>
                <div>
                    <label style="font-size:.78rem;font-weight:700;color:#374151;display:block;margin-bottom:5px;">المدينة السكنية</label>
                    <input type="text" name="residential_address[city]" value="{{ $employee->residential_address['city'] ?? '' }}"
                           style="width:100%;border:1.5px solid #e5e7eb;border-radius:10px;padding:9px 12px;font-size:.88rem;">
                </div>
                <div>
                    <label style="font-size:.78rem;font-weight:700;color:#374151;display:block;margin-bottom:5px;">الحي السكني</label>
                    <input type="text" name="residential_address[neighborhood]" value="{{ $employee->residential_address['neighborhood'] ?? '' }}"
                           style="width:100%;border:1.5px solid #e5e7eb;border-radius:10px;padding:9px 12px;font-size:.88rem;">
                </div>
            </div>
            <div style="padding:16px 24px;border-top:1px solid #e5e7eb;display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="document.getElementById('editDataModal').style.display='none'"
                        style="padding:9px 20px;background:#f1f5f9;border:none;border-radius:10px;font-weight:700;cursor:pointer;color:#475569;">إلغاء</button>
                <button type="submit"
                        style="padding:9px 24px;background:linear-gradient(to right,#6d28d9,#7c3aed);color:white;border:none;border-radius:10px;font-weight:700;cursor:pointer;">
                    <i class="fas fa-save me-1"></i> حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== Document Preview Modal ===== --}}
<div id="docPreviewModal">
    <div class="modal-inner" style="min-width:320px;">
        <div class="modal-head">
            <span id="docPreviewTitle">معاينة الوثيقة</span>
            <button onclick="closeDocPreview()" style="background:none;border:none;font-size:1.3rem;color:#94a3b8;cursor:pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body-inner" id="docPreviewContent">
            <div style="color:#94a3b8;font-size:.9rem;">جاري التحميل...</div>
        </div>
        <div class="modal-actions">
            <a id="docDownloadBtn" href="#" download
               style="display:inline-flex;align-items:center;gap:6px;background:#6d28d9;color:white;padding:8px 18px;border-radius:10px;font-weight:700;font-size:.82rem;text-decoration:none;">
                <i class="fas fa-download"></i> تحميل
            </a>
            <button onclick="closeDocPreview()"
                    style="padding:8px 16px;background:#f1f5f9;border:none;border-radius:10px;font-weight:700;color:#475569;cursor:pointer;font-size:.82rem;">
                إغلاق
            </button>
        </div>
    </div>
</div>

<script>
// ── Leave submit
function submitLeave() {
    const form = document.getElementById('leaveForm');
    fetch('{{ route('leaves.store') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: new FormData(form),
    })
    .then(r => r.json())
    .then(res => { if (res.success) location.reload(); else alert(res.message || 'حدث خطأ'); });
}

// ── Document upload (auto-submit on file select)
document.querySelectorAll('form[action*="upload-doc"]').forEach(form => {
    form.addEventListener('autosubmit', () => submitDocUpload(null, form));
});

function submitDocUpload(e, form) {
    if (e) e.preventDefault();
    const fd = new FormData(form);
    const file = form.querySelector('input[type=file]').files[0];
    if (!file) return;

    const label = form.querySelector('label');
    const original = label.innerHTML;
    label.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري الرفع...';

    fetch(form.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: fd,
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) location.reload();
        else { label.innerHTML = original; alert(res.message || 'فشل الرفع'); }
    })
    .catch(() => { label.innerHTML = original; alert('حدث خطأ في الاتصال'); });
}

// ── Doc preview modal
function previewDoc(url, title) {
    document.getElementById('docPreviewTitle').textContent = title;
    document.getElementById('docDownloadBtn').href = url;
    const ext = url.split('.').pop().toLowerCase();
    const container = document.getElementById('docPreviewContent');
    if (['jpg','jpeg','png','webp'].includes(ext)) {
        container.innerHTML = `<img src="${url}" alt="${title}" style="max-width:100%;max-height:70vh;border-radius:10px;object-fit:contain;">`;
    } else {
        container.innerHTML = `<iframe src="${url}" style="width:70vw;height:70vh;border:none;border-radius:10px;"></iframe>`;
    }
    document.getElementById('docPreviewModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeDocPreview() {
    document.getElementById('docPreviewModal').classList.remove('open');
    document.getElementById('docPreviewContent').innerHTML = '';
    document.body.style.overflow = '';
}
document.getElementById('docPreviewModal').addEventListener('click', function(e) {
    if (e.target === this) closeDocPreview();
});

// ── Edit modal AJAX
document.getElementById('editDataForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type=submit]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري الحفظ...';
    fetch(this.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: new FormData(this),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) location.reload();
        else { alert(res.message || 'حدث خطأ'); btn.disabled=false; btn.innerHTML='<i class="fas fa-save me-1"></i> حفظ التغييرات'; }
    });
});
</script>
@endsection
