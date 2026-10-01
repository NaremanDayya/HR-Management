@extends('layouts.master')
@section('title', 'الكفالة والموظفون')

<style>
    #sponsorshipTable th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 10;
        font-weight: 700;
        font-size: 15px;
        white-space: nowrap;
    }
    #sponsorshipTable td {
        font-size: 15px;
        font-weight: 500;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-responsive::-webkit-scrollbar { height: 6px; }
    .table-responsive::-webkit-scrollbar-thumb { background: rgba(0,0,0,.2); border-radius: 3px; }

    .expiry-expired  { color: #dc3545; font-weight: 700; }
    .expiry-soon     { color: #fd7e14; font-weight: 700; }
    .expiry-warning  { color: #ffc107; font-weight: 600; }
    .expiry-ok       { color: #198754; font-weight: 600; }

    .stat-card { border-radius: 12px; padding: 18px 20px; border: 1px solid #e9ecef; background: #fff; }
    .stat-card .stat-number { font-size: 28px; font-weight: 800; line-height: 1; }
    .stat-card .stat-label  { font-size: 13px; font-weight: 600; color: #6c757d; margin-top: 4px; }

    .lang-row { display: flex; gap: 8px; align-items: center; margin-bottom: 8px; }
    .lang-row input, .lang-row select {
        flex: 1; padding: 8px 12px; border: 1px solid #ced4da;
        border-radius: 6px; font-size: 14px;
    }
    #sponsorshipModal .modal-body {
        overflow-y: auto;
        max-height: calc(100vh - 200px);
    }
    .lang-row .remove-lang {
        width: 32px; height: 32px; border-radius: 6px;
        border: 1px solid #f0bcbc; background: #fff5f5;
        color: #dc3545; cursor: pointer; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
    }
</style>

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">

                {{-- Card Header --}}
                <div class="card-header p-2 position-relative z-index-2 d-flex align-items-center justify-content-between w-100">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 flex-grow-1">
                        <h6 class="text-black text-capitalize ps-3 mb-0" style="font-size:25px; font-weight:800;">
                            ملف الكفالة والموظفون
                        </h6>
                    </div>
                    <div class="d-flex align-items-center gap-2 ms-3">
                        <a href="{{ route('leaves.index') }}"
                           class="btn btn-outline-success btn-sm d-flex align-items-center gap-1">
                            <i class="fas fa-calendar-alt"></i> سجل الإجازات
                        </a>
                        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                                data-bs-toggle="modal" data-bs-target="#sponsorshipModal">
                            <i class="fas fa-plus"></i> إضافة بيانات كفالة
                        </button>
                    </div>
                </div>

                {{-- Stats Row --}}
                @php
                    $expired      = $employees->filter(fn($e) => $e->id_expiry_date && $e->id_expiry_date->isPast());
                    $expiringSoon = $employees->filter(fn($e) => $e->id_expiry_date && !$e->id_expiry_date->isPast() && $e->id_expiry_date->diffInDays(now()) <= 90);
                    $over5years   = $employees->filter(fn($e) => $e->joining_date && $e->joining_date->diffInYears(now()) >= 5);
                @endphp
                <div class="d-flex gap-3 px-3 pt-3 pb-1 flex-wrap">
                    <div class="stat-card flex-fill text-center">
                        <div class="stat-number text-primary">{{ $employees->count() }}</div>
                        <div class="stat-label">إجمالي الموظفين</div>
                    </div>
                    <div class="stat-card flex-fill text-center">
                        <div class="stat-number text-danger">{{ $expired->count() }}</div>
                        <div class="stat-label">إقامة منتهية</div>
                    </div>
                    <div class="stat-card flex-fill text-center">
                        <div class="stat-number" style="color:#fd7e14;">{{ $expiringSoon->count() }}</div>
                        <div class="stat-label">تنتهي خلال 3 أشهر</div>
                    </div>
                    <div class="stat-card flex-fill text-center">
                        <div class="stat-number text-success">{{ $over5years->count() }}</div>
                        <div class="stat-label">أكثر من 5 سنوات</div>
                    </div>
                </div>

                {{-- Filters --}}
                <form method="GET" action="{{ route('sponsorship.index') }}" class="px-3 py-3">
                    <div class="d-flex gap-2 flex-wrap align-items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="ابحث بالاسم أو الهوية..."
                               class="form-control form-control-sm" style="max-width:220px;">

                        <select name="project_id" class="form-select form-select-sm" style="max-width:180px;">
                            <option value="">كل المشاريع</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" @selected(request('project_id') == $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>

                        <select name="id_expiry" class="form-select form-select-sm" style="max-width:200px;">
                            <option value="">انتهاء الإقامة / الهوية</option>
                            <option value="expired" @selected(request('id_expiry')=='expired')>منتهية الآن</option>
                            <option value="1month"  @selected(request('id_expiry')=='1month')>خلال شهر</option>
                            <option value="3months" @selected(request('id_expiry')=='3months')>خلال 3 أشهر</option>
                            <option value="6months" @selected(request('id_expiry')=='6months')>خلال 6 أشهر</option>
                        </select>

                        <select name="service_years" class="form-select form-select-sm" style="max-width:200px;">
                            <option value="">سنوات الخدمة</option>
                            <option value="under5" @selected(request('service_years')=='under5')>أقل من 5 سنوات</option>
                            <option value="near5"  @selected(request('service_years')=='near5')>قريب من 5 سنوات</option>
                            <option value="over5"  @selected(request('service_years')=='over5')>أكثر من 5 سنوات</option>
                        </select>

                        <button type="submit" class="btn btn-primary btn-sm px-3">
                            <i class="fas fa-filter me-1"></i> فلترة
                        </button>
                        <a href="{{ route('sponsorship.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>

                {{-- Table --}}
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive px-3">
                        <table class="table align-items-center mb-0 employee-table" id="sponsorshipTable">
                            <thead class="thead">
                                <tr>
                                    <th class="text-end pe-3">الموظف</th>
                                    <th class="text-center">رقم الهوية</th>
                                    <th class="text-center">الجوال</th>
                                    <th class="text-center">تاريخ الالتحاق</th>
                                    <th class="text-center">مدة الخدمة</th>
                                    <th class="text-center">تذكرة السفر</th>
                                    <th class="text-center">انتهاء الإقامة / الهوية</th>
                                    <th class="text-center">رقم الجواز</th>
                                    <th class="text-center">رصيد الإجازة</th>
                                    <th class="text-center">المشروع</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @forelse($employees as $employee)
                                    @php
                                        $user         = $employee->user;
                                        $expiryDays   = $employee->getIdExpiryDays();
                                        $serviceYears = $employee->joining_date ? (int)$employee->joining_date->diffInYears(now()) : null;
                                        $ticket       = $employee->getFlightTicketEntitlement();
                                        $leaveRem     = $employee->getLeaveDaysRemaining();

                                        $expiryCls = match(true) {
                                            $expiryDays === null => '',
                                            $expiryDays < 0      => 'expiry-expired',
                                            $expiryDays <= 30    => 'expiry-expired',
                                            $expiryDays <= 90    => 'expiry-soon',
                                            $expiryDays <= 180   => 'expiry-warning',
                                            default              => 'expiry-ok',
                                        };
                                    @endphp
                                    <tr>
                                        {{-- Employee --}}
                                        <td class="text-end pe-3">
                                            <a href="{{ route('sponsorship.profile', $employee) }}"
                                               class="d-flex align-items-center gap-2 text-decoration-none">
                                                <div style="width:42px;height:42px;border-radius:50%;overflow:hidden;background:#e8eaf6;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                                    @if($user?->personal_image)
                                                        <img src="{{ $user->personal_image }}" style="width:100%;height:100%;object-fit:cover;">
                                                    @else
                                                        <span style="font-weight:800;font-size:16px;color:#3949ab;">{{ mb_substr($employee->name,0,1) }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <div class="employee-name">{{ $employee->name }}</div>
                                                    <div class="text-muted" style="font-size:12px;">{{ $employee->job ?? '' }}</div>
                                                </div>
                                            </a>
                                        </td>

                                        {{-- ID --}}
                                        <td class="text-center employee-detail font-monospace">{{ $user?->id_card ?? '—' }}</td>

                                        {{-- Phone --}}
                                        <td class="text-center employee-detail font-monospace">{{ $user?->contact_info['phone_number'] ?? '—' }}</td>

                                        {{-- Joining date --}}
                                        <td class="text-center employee-detail">{{ $employee->joining_date?->format('Y/m/d') ?? '—' }}</td>

                                        {{-- Service years --}}
                                        <td class="text-center">
                                            @if($serviceYears !== null)
                                                <span style="font-size:18px;font-weight:800;">{{ $serviceYears }}</span>
                                                <span class="text-muted" style="font-size:13px;"> سنة</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Flight ticket --}}
                                        <td class="text-center">
                                            @if($ticket['type'] !== 'none')
                                                <div>
                                                    <span class="badge {{ $ticket['type'] === 'full' ? 'bg-success' : 'bg-warning text-dark' }}" style="font-size:12px;">{{ $ticket['label'] }}</span>
                                                    <div class="text-muted" style="font-size:12px;font-weight:600;">{{ number_format($ticket['amount'],0) }} ر.س</div>
                                                </div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- ID Expiry --}}
                                        <td class="text-center">
                                            @if($employee->id_expiry_date)
                                                <div class="{{ $expiryCls }}">{{ $employee->id_expiry_date->format('Y/m/d') }}</div>
                                                @if($expiryDays !== null && $expiryDays < 0)
                                                    <div style="font-size:11px;font-weight:700;color:#dc3545;">منتهية</div>
                                                @elseif($expiryDays !== null && $expiryDays <= 90)
                                                    <div style="font-size:11px;font-weight:700;color:#fd7e14;">باقي {{ $expiryDays }} يوم</div>
                                                @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Passport --}}
                                        <td class="text-center employee-detail font-monospace">{{ $employee->passport_number ?? '—' }}</td>

                                        {{-- Leave balance --}}
                                        <td class="text-center">
                                            @if($employee->joining_date)
                                                <span style="font-size:20px;font-weight:800;">{{ (int)$leaveRem }}</span>
                                                <span class="text-muted" style="font-size:12px;"> يوم</span>
                                                <div class="text-muted" style="font-size:11px;">من {{ $employee->getAnnualLeaveEntitlement() }} يوم/سنة</div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Project --}}
                                        <td class="text-center employee-detail">{{ $employee->project?->name ?? '—' }}</td>

                                        {{-- Actions --}}
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('sponsorship.profile', $employee) }}"
                                                   class="btn btn-sm btn-outline-primary" title="عرض الملف">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-secondary"
                                                        title="تعديل بيانات الكفالة"
                                                        onclick="openEditModal(
                                                            {{ $employee->id }},
                                                            {{ json_encode(route('sponsorship.update-data', $employee)) }},
                                                            {{ json_encode([
                                                                'passport_number'       => $employee->passport_number,
                                                                'passport_issue_date'   => $employee->passport_issue_date?->format('Y-m-d'),
                                                                'id_expiry_date'        => $employee->id_expiry_date?->format('Y-m-d'),
                                                                'driver_license_number' => $employee->driver_license_number,
                                                                'medical_insurance'     => $employee->medical_insurance,
                                                                'wives_count'           => $employee->wives_count,
                                                                'residential_address'   => $employee->residential_address,
                                                                'languages'             => $employee->languages,
                                                            ]) }},
                                                            {{ json_encode([
                                                                'tshirt_size'   => $employee->user?->tshirt_size,
                                                                'trousers_size' => $employee->user?->trousers_size,
                                                                'shoes_size'    => $employee->user?->shoes_size,
                                                            ]) }}
                                                        )">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5 text-muted">
                                            <i class="fas fa-users fa-3x mb-3 d-block" style="opacity:.2;"></i>
                                            لا يوجد موظفون مطابقون للبحث
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- =================== SPONSORSHIP DATA MODAL =================== --}}
<div class="modal fade" id="sponsorshipModal" tabindex="-1" aria-hidden="true" dir="rtl">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" style="max-width:680px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">

            {{-- Modern Header --}}
            <div style="background:linear-gradient(135deg,#6e48aa,#9d50bb);padding:24px 28px;position:relative;">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        style="position:absolute;top:16px;left:20px;opacity:.8;"></button>
                <div class="d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-passport" style="color:#fff;font-size:20px;"></i>
                    </div>
                    <div>
                        <h5 id="modalTitle" style="color:#fff;font-size:18px;font-weight:800;margin:0;">بيانات الكفالة</h5>
                        <p style="color:rgba(255,255,255,.7);font-size:13px;margin:0;">أدخل البيانات الرسمية والشخصية للموظف</p>
                    </div>
                </div>
            </div>

            <form id="sponsorshipForm">
                @csrf
                <div class="modal-body p-0" style="overflow-y:auto;max-height:calc(100vh - 260px);">
                    <div style="padding:28px;">

                        {{-- ===== Employee Selector (add mode) ===== --}}
                        <div id="employeeSelectorWrap" class="mb-4">
                            <label style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:8px;">
                                <i class="fas fa-user-tie me-1" style="color:#6e48aa;"></i> اختيار الموظف
                            </label>
                            {{-- Custom searchable dropdown --}}
                            <div style="position:relative;" id="empDropdownWrap">
                                <div id="empTrigger"
                                     onclick="toggleEmpDropdown()"
                                     style="display:flex;align-items:center;justify-content:space-between;padding:11px 16px;border:1.5px solid #e5e7eb;border-radius:10px;background:#fff;cursor:pointer;font-size:14px;font-weight:600;color:#374151;transition:border .15s;">
                                    <span id="empTriggerText" style="color:#9ca3af;">اختر موظفاً...</span>
                                    <i class="fas fa-chevron-down" style="font-size:11px;color:#9ca3af;transition:transform .2s;" id="empChevron"></i>
                                </div>
                                <div id="empDropdown"
                                     style="display:none;position:absolute;top:calc(100% + 6px);right:0;left:0;background:#fff;border:1.5px solid #e5e7eb;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.1);z-index:9999;overflow:hidden;">
                                    <div style="padding:10px 12px;border-bottom:1px solid #f3f4f6;">
                                        <input type="text" id="employeeSearch"
                                               placeholder="ابحث بالاسم..."
                                               oninput="filterEmployees(this.value)"
                                               style="width:100%;padding:8px 12px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:13px;outline:none;"
                                               autocomplete="off">
                                    </div>
                                    <ul id="empList"
                                        style="list-style:none;margin:0;padding:6px 0;max-height:200px;overflow-y:auto;">
                                        @foreach($employees as $emp)
                                        <li class="emp-option"
                                            data-id="{{ $emp->id }}"
                                            data-url="{{ route('sponsorship.update-data', $emp) }}"
                                            data-name="{{ $emp->name }}"
                                            data-project="{{ $emp->project?->name ?? 'بدون مشروع' }}"
                                            onclick="selectEmployee(this)"
                                            style="padding:10px 16px;cursor:pointer;font-size:14px;font-weight:600;color:#111827;display:flex;align-items:center;justify-content:space-between;transition:background .1s;"
                                            onmouseover="this.style.background='#f5f3ff'"
                                            onmouseout="this.style.background=''">
                                            <span>{{ $emp->name }}</span>
                                            <span style="font-size:12px;color:#9ca3af;font-weight:500;">{{ $emp->project?->name ?? 'بدون مشروع' }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <input type="hidden" id="modalEmployeeId" name="_employee_id">
                        </div>

                        {{-- ===== SECTION: Documents ===== --}}
                        <div style="background:#f9fafb;border-radius:12px;padding:20px;margin-bottom:16px;">
                            <div style="font-size:11px;font-weight:700;color:#6e48aa;text-transform:uppercase;letter-spacing:.08em;margin-bottom:16px;">
                                <i class="fas fa-id-card me-1"></i> الوثائق الرسمية
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">رقم جواز السفر</label>
                                    <input type="text" name="passport_number" id="f_passport_number"
                                           placeholder="A12345678" class="tw-input">
                                </div>
                                <div class="col-md-6">
                                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">تاريخ إصدار الجواز</label>
                                    <input type="date" name="passport_issue_date" id="f_passport_issue_date" class="tw-input">
                                </div>
                                <div class="col-md-6">
                                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">انتهاء الإقامة / الهوية / التأشيرة</label>
                                    <input type="date" name="id_expiry_date" id="f_id_expiry_date" class="tw-input">
                                </div>
                                <div class="col-md-6">
                                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">رقم رخصة القيادة</label>
                                    <input type="text" name="driver_license_number" id="f_driver_license_number" class="tw-input">
                                </div>
                            </div>
                        </div>

                        {{-- ===== SECTION: Personal ===== --}}
                        <div style="background:#f9fafb;border-radius:12px;padding:20px;margin-bottom:16px;">
                            <div style="font-size:11px;font-weight:700;color:#6e48aa;text-transform:uppercase;letter-spacing:.08em;margin-bottom:16px;">
                                <i class="fas fa-user me-1"></i> بيانات شخصية
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">التأمين الطبي</label>
                                    <input type="text" name="medical_insurance" id="f_medical_insurance"
                                           placeholder="شركة التأمين أو رقم الوثيقة" class="tw-input">
                                </div>
                                <div class="col-md-6">
                                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">عدد الزوجات</label>
                                    <input type="number" name="wives_count" id="f_wives_count"
                                           min="0" max="4" placeholder="0" class="tw-input">
                                </div>
                            </div>
                        </div>

                        {{-- ===== SECTION: Address ===== --}}
                        <div style="background:#f9fafb;border-radius:12px;padding:20px;margin-bottom:16px;">
                            <div style="font-size:11px;font-weight:700;color:#6e48aa;text-transform:uppercase;letter-spacing:.08em;margin-bottom:16px;">
                                <i class="fas fa-map-marker-alt me-1"></i> العنوان السكني
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">المدينة</label>
                                    <input type="text" name="residential_address[city]" id="f_city" placeholder="الرياض" class="tw-input">
                                </div>
                                <div class="col-md-6">
                                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">الحي</label>
                                    <input type="text" name="residential_address[neighborhood]" id="f_neighborhood" placeholder="اسم الحي" class="tw-input">
                                </div>
                            </div>
                        </div>

                        {{-- ===== SECTION: Sizes ===== --}}
                        <div style="background:#f9fafb;border-radius:12px;padding:20px;margin-bottom:16px;">
                            <div style="font-size:11px;font-weight:700;color:#6e48aa;text-transform:uppercase;letter-spacing:.08em;margin-bottom:16px;">
                                <i class="fas fa-tshirt me-1"></i> مقاسات الملابس
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">قميص (T-shirt)</label>
                                    <input type="text" name="tshirt_size" id="f_tshirt_size" placeholder="XL أو 50" class="tw-input">
                                </div>
                                <div class="col-md-4">
                                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">بنطال (Trousers)</label>
                                    <input type="text" name="trousers_size" id="f_trousers_size" placeholder="32 أو M" class="tw-input">
                                </div>
                                <div class="col-md-4">
                                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">حذاء (Shoes)</label>
                                    <input type="text" name="shoes_size" id="f_shoes_size" placeholder="42" class="tw-input">
                                </div>
                            </div>
                        </div>

                        {{-- ===== SECTION: Languages ===== --}}
                        <div style="background:#f9fafb;border-radius:12px;padding:20px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                                <div style="font-size:11px;font-weight:700;color:#6e48aa;text-transform:uppercase;letter-spacing:.08em;">
                                    <i class="fas fa-language me-1"></i> اللغات
                                </div>
                                <button type="button" onclick="addLangRow()"
                                        style="padding:6px 14px;border-radius:8px;border:1.5px solid #6e48aa;background:#f5f3ff;color:#6e48aa;font-size:12px;font-weight:700;cursor:pointer;">
                                    <i class="fas fa-plus me-1"></i> إضافة لغة
                                </button>
                            </div>
                            <div id="languagesContainer"></div>
                            <div id="langEmpty" style="color:#9ca3af;font-size:13px;text-align:center;padding:12px 0;">لا توجد لغات مضافة</div>
                        </div>

                    </div>
                </div>

                {{-- Modern Footer --}}
                <div style="padding:18px 28px;border-top:1px solid #f3f4f6;display:flex;justify-content:flex-end;gap:10px;background:#fff;">
                    <button type="button" data-bs-dismiss="modal"
                            style="padding:10px 22px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;font-size:14px;font-weight:700;color:#6b7280;cursor:pointer;">
                        إلغاء
                    </button>
                    <button type="submit"
                            style="padding:10px 28px;border-radius:10px;border:none;background:linear-gradient(135deg,#6e48aa,#9d50bb);color:#fff;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
                        <i class="fas fa-save"></i> حفظ البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.tw-input {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #111827;
    background: #fff;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
}
.tw-input:focus {
    border-color: #6e48aa;
    box-shadow: 0 0 0 3px rgba(110,72,170,.12);
}
.tw-input::placeholder { color: #d1d5db; }
</style>

<script>
let modalUrl = null;

// ---- Custom dropdown ----
function toggleEmpDropdown() {
    const dd = document.getElementById('empDropdown');
    const chevron = document.getElementById('empChevron');
    const open = dd.style.display === 'block';
    dd.style.display = open ? 'none' : 'block';
    chevron.style.transform = open ? '' : 'rotate(180deg)';
    if (!open) document.getElementById('employeeSearch').focus();
}

function selectEmployee(li) {
    document.getElementById('modalEmployeeId').value = li.dataset.id;
    document.getElementById('empTriggerText').textContent = li.dataset.name + ' — ' + li.dataset.project;
    document.getElementById('empTriggerText').style.color = '#111827';
    document.getElementById('empDropdown').style.display = 'none';
    document.getElementById('empChevron').style.transform = '';
    document.getElementById('empDropdownWrap')._selectedUrl = li.dataset.url;
}

function filterEmployees(query) {
    const q = query.trim().toLowerCase();
    document.querySelectorAll('#empList .emp-option').forEach(li => {
        li.style.display = li.dataset.name.toLowerCase().includes(q) ? '' : 'none';
    });
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('empDropdownWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('empDropdown').style.display = 'none';
        document.getElementById('empChevron').style.transform = '';
    }
});

// ---- Lang empty state ----
function syncLangEmpty() {
    const container = document.getElementById('languagesContainer');
    document.getElementById('langEmpty').style.display =
        container.children.length ? 'none' : 'block';
}

// ---- Open edit modal from table row ----
function openEditModal(id, url, empData, userData) {
    modalUrl = url;
    document.getElementById('employeeSelectorWrap').style.display = 'none';
    document.getElementById('modalTitle').textContent = 'تعديل بيانات الكفالة';
    fillForm(empData, userData);
    const modal = new bootstrap.Modal(document.getElementById('sponsorshipModal'));
    modal.show();
}

function fillForm(empData, userData) {
    document.getElementById('f_passport_number').value       = empData.passport_number || '';
    document.getElementById('f_passport_issue_date').value   = empData.passport_issue_date || '';
    document.getElementById('f_id_expiry_date').value        = empData.id_expiry_date || '';
    document.getElementById('f_driver_license_number').value = empData.driver_license_number || '';
    document.getElementById('f_medical_insurance').value     = empData.medical_insurance || '';
    document.getElementById('f_wives_count').value           = empData.wives_count ?? '';
    document.getElementById('f_city').value                  = empData.residential_address?.city || '';
    document.getElementById('f_neighborhood').value          = empData.residential_address?.neighborhood || '';
    document.getElementById('f_tshirt_size').value           = userData?.tshirt_size || '';
    document.getElementById('f_trousers_size').value         = userData?.trousers_size || '';
    document.getElementById('f_shoes_size').value            = userData?.shoes_size || '';

    const container = document.getElementById('languagesContainer');
    container.innerHTML = '';
    if (empData.languages?.length) {
        empData.languages.forEach(l => addLangRow(l.language, l.level));
    }
    syncLangEmpty();
}

document.getElementById('sponsorshipModal').addEventListener('show.bs.modal', function() {
    if (!modalUrl) {
        document.getElementById('employeeSelectorWrap').style.display = 'block';
        document.getElementById('modalTitle').textContent = 'إضافة بيانات كفالة';
        clearForm();
    }
});
document.getElementById('sponsorshipModal').addEventListener('hidden.bs.modal', function() {
    modalUrl = null;
    clearForm();
    document.getElementById('employeeSelectorWrap').style.display = 'block';
    document.getElementById('empTriggerText').textContent = 'اختر موظفاً...';
    document.getElementById('empTriggerText').style.color = '#9ca3af';
    document.getElementById('modalEmployeeId').value = '';
    document.getElementById('employeeSearch').value = '';
    filterEmployees('');
});

function clearForm() {
    ['f_passport_number','f_passport_issue_date','f_id_expiry_date',
     'f_driver_license_number','f_medical_insurance','f_wives_count',
     'f_city','f_neighborhood','f_tshirt_size','f_trousers_size','f_shoes_size']
        .forEach(id => { document.getElementById(id).value = ''; });
    document.getElementById('languagesContainer').innerHTML = '';
    syncLangEmpty();
}

function addLangRow(lang = '', level = '') {
    const row = document.createElement('div');
    row.style.cssText = 'display:flex;gap:8px;align-items:center;margin-bottom:10px;';
    row.innerHTML = `
        <input type="text" name="lang_name[]" value="${lang}" placeholder="اسم اللغة"
               style="flex:1;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:13px;outline:none;">
        <select name="lang_level[]"
                style="width:130px;padding:9px 10px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:13px;outline:none;background:#fff;">
            <option value="">المستوى</option>
            <option value="مبتدئ"  ${level==='مبتدئ'?'selected':''}>مبتدئ</option>
            <option value="متوسط"  ${level==='متوسط'?'selected':''}>متوسط</option>
            <option value="جيد"    ${level==='جيد'?'selected':''}>جيد</option>
            <option value="متقدم"  ${level==='متقدم'?'selected':''}>متقدم</option>
            <option value="طليق"   ${level==='طليق'?'selected':''}>طليق</option>
        </select>
        <button type="button" onclick="this.parentElement.remove();syncLangEmpty();"
                style="width:34px;height:34px;border-radius:8px;border:1.5px solid #fca5a5;background:#fff5f5;color:#ef4444;cursor:pointer;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-times" style="font-size:11px;"></i>
        </button>`;
    document.getElementById('languagesContainer').appendChild(row);
    syncLangEmpty();
}

document.getElementById('sponsorshipForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let url = modalUrl;
    if (!url) {
        const wrap = document.getElementById('empDropdownWrap');
        url = wrap?._selectedUrl;
        if (!url) { alert('يرجى اختيار موظف'); return; }
    }

    const fd = new FormData(this);
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-HTTP-Method-Override': 'PUT'
        },
        body: fd,
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('sponsorshipModal'))?.hide();
            location.reload();
        } else {
            alert(res.message || 'حدث خطأ');
        }
    })
    .catch(() => alert('حدث خطأ في الاتصال'));
});
</script>
@endsection
