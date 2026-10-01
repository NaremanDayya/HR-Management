@extends('layouts.master')

@section('title', 'الكفالة والموظفون')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
<div class="max-w-screen-xl mx-auto py-8 px-4 sm:px-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">ملف الكفالة والموظفون</h1>
            <p class="text-base text-gray-500 mt-1">إدارة بيانات الكفالة، الإقامات، جوازات السفر، وتذاكر السفر</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('leaves.index') }}"
               class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:border-gray-400 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition">
                <i class="fas fa-calendar-alt text-green-500"></i>
                سجل الإجازات
            </a>
            <button onclick="openAddModal()"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition">
                <i class="fas fa-plus"></i>
                إضافة بيانات كفالة
            </button>
        </div>
    </div>

    {{-- Stats --}}
    @php
        $expired      = $employees->filter(fn($e) => $e->id_expiry_date && $e->id_expiry_date->isPast());
        $expiringSoon = $employees->filter(fn($e) => $e->id_expiry_date && !$e->id_expiry_date->isPast() && $e->id_expiry_date->diffInDays(now()) <= 90);
        $over5years   = $employees->filter(fn($e) => $e->joining_date && $e->joining_date->diffInYears(now()) >= 5);
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-users text-blue-500 text-lg"></i>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900">{{ $employees->count() }}</div>
                <div class="text-sm text-gray-500 font-medium">إجمالي الموظفين</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-red-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-red-600">{{ $expired->count() }}</div>
                <div class="text-sm text-gray-500 font-medium">إقامة منتهية</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-amber-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-clock text-amber-500 text-lg"></i>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-amber-600">{{ $expiringSoon->count() }}</div>
                <div class="text-sm text-gray-500 font-medium">تنتهي خلال 3 أشهر</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-green-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-star text-green-500 text-lg"></i>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-green-600">{{ $over5years->count() }}</div>
                <div class="text-sm text-gray-500 font-medium">أكثر من 5 سنوات</div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('sponsorship.index') }}"
          class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 mb-6">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="ابحث بالاسم أو الهوية..."
                   class="col-span-2 md:col-span-1 border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

            <select name="project_id" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500">
                <option value="">كل المشاريع</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}" @selected(request('project_id') == $p->id)>{{ $p->name }}</option>
                @endforeach
            </select>

            <select name="id_expiry" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500">
                <option value="">انتهاء الإقامة / الهوية</option>
                <option value="expired" @selected(request('id_expiry')=='expired')>منتهية الآن</option>
                <option value="1month"  @selected(request('id_expiry')=='1month')>خلال شهر</option>
                <option value="3months" @selected(request('id_expiry')=='3months')>خلال 3 أشهر</option>
                <option value="6months" @selected(request('id_expiry')=='6months')>خلال 6 أشهر</option>
            </select>

            <select name="service_years" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500">
                <option value="">سنوات الخدمة</option>
                <option value="under5" @selected(request('service_years')=='under5')>أقل من 5 سنوات</option>
                <option value="near5"  @selected(request('service_years')=='near5')>قريب من 5 سنوات</option>
                <option value="over5"  @selected(request('service_years')=='over5')>أكثر من 5 سنوات</option>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition">
                    <i class="fas fa-filter ml-1"></i> فلترة
                </button>
                <a href="{{ route('sponsorship.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3.5 py-2.5 rounded-xl text-sm transition">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead>
                    <tr class="bg-gray-50 border-b-2 border-gray-200">
                        <th class="px-5 py-4 font-bold text-gray-700 text-sm whitespace-nowrap">الموظف</th>
                        <th class="px-5 py-4 font-bold text-gray-700 text-sm whitespace-nowrap">رقم الهوية</th>
                        <th class="px-5 py-4 font-bold text-gray-700 text-sm whitespace-nowrap">الجوال</th>
                        <th class="px-5 py-4 font-bold text-gray-700 text-sm whitespace-nowrap">تاريخ الالتحاق</th>
                        <th class="px-5 py-4 font-bold text-gray-700 text-sm whitespace-nowrap">مدة الخدمة</th>
                        <th class="px-5 py-4 font-bold text-gray-700 text-sm whitespace-nowrap">تذكرة السفر</th>
                        <th class="px-5 py-4 font-bold text-gray-700 text-sm whitespace-nowrap">انتهاء الإقامة / الهوية</th>
                        <th class="px-5 py-4 font-bold text-gray-700 text-sm whitespace-nowrap">رقم الجواز</th>
                        <th class="px-5 py-4 font-bold text-gray-700 text-sm whitespace-nowrap">رصيد الإجازة</th>
                        <th class="px-5 py-4 font-bold text-gray-700 text-sm whitespace-nowrap">المشروع</th>
                        <th class="px-5 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($employees as $employee)
                        @php
                            $user         = $employee->user;
                            $expiryDays   = $employee->getIdExpiryDays();
                            $serviceYears = $employee->joining_date ? (int)$employee->joining_date->diffInYears(now()) : null;
                            $ticket       = $employee->getFlightTicketEntitlement();
                            $leaveRem     = $employee->getLeaveDaysRemaining();

                            $expiryCell = match(true) {
                                $expiryDays === null => ['text' => '—',              'badge' => '',           'cls' => 'text-gray-400'],
                                $expiryDays < 0      => ['text' => $employee->id_expiry_date->format('Y/m/d'), 'badge' => 'منتهية', 'cls' => 'text-red-600 font-bold'],
                                $expiryDays <= 30    => ['text' => $employee->id_expiry_date->format('Y/m/d'), 'badge' => "باقي {$expiryDays} يوم", 'cls' => 'text-red-500 font-semibold'],
                                $expiryDays <= 90    => ['text' => $employee->id_expiry_date->format('Y/m/d'), 'badge' => "باقي {$expiryDays} يوم", 'cls' => 'text-amber-600 font-semibold'],
                                $expiryDays <= 180   => ['text' => $employee->id_expiry_date->format('Y/m/d'), 'badge' => '',           'cls' => 'text-yellow-600'],
                                default              => ['text' => $employee->id_expiry_date->format('Y/m/d'), 'badge' => '',           'cls' => 'text-green-600'],
                            };
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition group">
                            <td class="px-5 py-4">
                                <a href="{{ route('sponsorship.profile', $employee) }}"
                                   class="flex items-center gap-3 group/link">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex-shrink-0 overflow-hidden shadow-sm">
                                        @if($user?->personal_image)
                                            <img src="{{ $user->personal_image }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-blue-700 font-extrabold text-base">
                                                {{ mb_substr($employee->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm group-hover/link:text-blue-600 transition">{{ $employee->name }}</div>
                                        <div class="text-xs text-gray-400 font-medium">{{ $employee->job ?? '' }}</div>
                                    </div>
                                </a>
                            </td>
                            <td class="px-5 py-4 font-mono font-semibold text-gray-700 text-sm">{{ $user?->id_card ?? '—' }}</td>
                            <td class="px-5 py-4 font-mono text-gray-600 text-sm">{{ $user?->contact_info['phone_number'] ?? '—' }}</td>
                            <td class="px-5 py-4 text-gray-700 font-medium whitespace-nowrap">{{ $employee->joining_date?->format('Y/m/d') ?? '—' }}</td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($serviceYears !== null)
                                    <span class="font-bold text-gray-900 text-base">{{ $serviceYears }}</span>
                                    <span class="text-gray-400 text-sm font-medium"> سنة</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($ticket['type'] !== 'none')
                                    <div class="inline-flex flex-col">
                                        <span class="text-xs font-bold {{ $ticket['type'] === 'full' ? 'text-green-600' : 'text-amber-600' }}">{{ $ticket['label'] }}</span>
                                        <span class="text-xs text-gray-500 font-semibold">{{ number_format($ticket['amount'], 0) }} ر.س</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($employee->id_expiry_date)
                                    <div class="flex flex-col gap-0.5">
                                        <span class="{{ $expiryCell['cls'] }} text-sm">{{ $expiryCell['text'] }}</span>
                                        @if($expiryCell['badge'])
                                            <span class="text-xs {{ $expiryDays < 0 ? 'text-red-500' : 'text-amber-500' }} font-bold">{{ $expiryCell['badge'] }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 font-medium">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-mono text-gray-600 text-sm">{{ $employee->passport_number ?? '—' }}</td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($employee->joining_date)
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-extrabold text-gray-900 text-base">{{ (int)$leaveRem }}</span>
                                        <span class="text-xs text-gray-400 font-medium">من {{ $employee->getAnnualLeaveEntitlement() }} يوم/سنة</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-600 font-medium whitespace-nowrap">{{ $employee->project?->name ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                                    <a href="{{ route('sponsorship.profile', $employee) }}"
                                       class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition"
                                       title="عرض الملف">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <button onclick="openEditModal({{ $employee->id }}, {{ json_encode($employee->only(['passport_number','passport_issue_date','id_expiry_date','driver_license_number','medical_insurance','wives_count','residential_address','languages'])) }}, {{ json_encode($employee->user?->only(['tshirt_size','trousers_size','shoes_size'])) }})"
                                            class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-500 flex items-center justify-center transition"
                                            title="تعديل بيانات الكفالة">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-5 py-16 text-center">
                                <i class="fas fa-users text-4xl text-gray-300 mb-4 block"></i>
                                <p class="text-gray-500 font-semibold text-base">لا يوجد موظفون مطابقون للبحث</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

{{-- Add / Edit Sponsorship Data Modal --}}
<div id="sponsorshipModal" class="fixed inset-0 z-50 hidden" dir="rtl">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

            <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white rounded-t-2xl z-10">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900">بيانات الكفالة</h2>
                    <p class="text-sm text-gray-500 mt-0.5" id="modalSubtitle">إضافة / تعديل بيانات الكفالة للموظف</p>
                </div>
                <button onclick="closeModal()" class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="sponsorshipForm" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Employee selector (only shown in add mode) --}}
                <div id="employeeSelectorWrap">
                    <label class="block text-sm font-bold text-gray-700 mb-2">الموظف <span class="text-red-500">*</span></label>
                    <select id="modalEmployeeId" name="employee_id_select"
                            class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر موظفاً...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" data-url="{{ route('sponsorship.update-data', $emp) }}">{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Documents Section --}}
                <div>
                    <h3 class="text-sm font-extrabold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fas fa-id-card text-blue-400"></i> الوثائق الرسمية
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">رقم جواز السفر</label>
                            <input type="text" name="passport_number" id="f_passport_number"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="A12345678">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">تاريخ إصدار الجواز</label>
                            <input type="date" name="passport_issue_date" id="f_passport_issue_date"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">انتهاء الإقامة / الهوية / التأشيرة</label>
                            <input type="date" name="id_expiry_date" id="f_id_expiry_date"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">رقم رخصة القيادة</label>
                            <input type="text" name="driver_license_number" id="f_driver_license_number"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Personal Section --}}
                <div>
                    <h3 class="text-sm font-extrabold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fas fa-user text-purple-400"></i> بيانات شخصية
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">التأمين الطبي</label>
                            <input type="text" name="medical_insurance" id="f_medical_insurance"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="اسم شركة التأمين">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">عدد الزوجات</label>
                            <input type="number" name="wives_count" id="f_wives_count" min="0" max="4"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Address Section --}}
                <div>
                    <h3 class="text-sm font-extrabold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-red-400"></i> العنوان السكني
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">المدينة</label>
                            <input type="text" name="residential_address[city]" id="f_city"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">الحي</label>
                            <input type="text" name="residential_address[neighborhood]" id="f_neighborhood"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Sizes Section --}}
                <div>
                    <h3 class="text-sm font-extrabold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fas fa-tshirt text-indigo-400"></i> مقاسات الملابس
                    </h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">قميص</label>
                            <input type="text" name="tshirt_size" id="f_tshirt_size" placeholder="مثال: XL"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">بنطال</label>
                            <input type="text" name="trousers_size" id="f_trousers_size" placeholder="مثال: 32"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">حذاء</label>
                            <input type="text" name="shoes_size" id="f_shoes_size" placeholder="مثال: 42"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Languages Section --}}
                <div>
                    <h3 class="text-sm font-extrabold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fas fa-language text-teal-400"></i> اللغات
                    </h3>
                    <div id="languagesContainer" class="space-y-2"></div>
                    <button type="button" onclick="addLanguageRow()"
                            class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-bold flex items-center gap-1.5 transition">
                        <i class="fas fa-plus-circle"></i> إضافة لغة
                    </button>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl text-sm font-bold transition shadow-sm">
                        <i class="fas fa-save ml-2"></i> حفظ البيانات
                    </button>
                    <button type="button" onclick="closeModal()"
                            class="px-6 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl text-sm font-bold transition">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentEmployeeUrl = null;

function openAddModal() {
    document.getElementById('employeeSelectorWrap').style.display = 'block';
    document.getElementById('modalSubtitle').textContent = 'اختر الموظف وأدخل بيانات الكفالة';
    clearForm();
    document.getElementById('sponsorshipModal').classList.remove('hidden');
}

function openEditModal(id, empData, userData) {
    document.getElementById('employeeSelectorWrap').style.display = 'none';
    const sel = document.getElementById('modalEmployeeId');
    const opt = sel.querySelector(`option[value="${id}"]`);
    currentEmployeeUrl = opt ? opt.dataset.url : null;
    document.getElementById('modalSubtitle').textContent = opt ? opt.textContent.trim() : '';

    // Fill fields
    document.getElementById('f_passport_number').value      = empData.passport_number || '';
    document.getElementById('f_passport_issue_date').value  = empData.passport_issue_date ? empData.passport_issue_date.substring(0,10) : '';
    document.getElementById('f_id_expiry_date').value       = empData.id_expiry_date ? empData.id_expiry_date.substring(0,10) : '';
    document.getElementById('f_driver_license_number').value= empData.driver_license_number || '';
    document.getElementById('f_medical_insurance').value    = empData.medical_insurance || '';
    document.getElementById('f_wives_count').value          = empData.wives_count || '';
    document.getElementById('f_city').value                 = (empData.residential_address && empData.residential_address.city) ? empData.residential_address.city : '';
    document.getElementById('f_neighborhood').value         = (empData.residential_address && empData.residential_address.neighborhood) ? empData.residential_address.neighborhood : '';
    document.getElementById('f_tshirt_size').value          = (userData && userData.tshirt_size) ? userData.tshirt_size : '';
    document.getElementById('f_trousers_size').value        = (userData && userData.trousers_size) ? userData.trousers_size : '';
    document.getElementById('f_shoes_size').value           = (userData && userData.shoes_size) ? userData.shoes_size : '';

    // Languages
    const container = document.getElementById('languagesContainer');
    container.innerHTML = '';
    if (empData.languages && empData.languages.length) {
        empData.languages.forEach(l => addLanguageRow(l.language, l.level));
    }

    document.getElementById('sponsorshipModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('sponsorshipModal').classList.add('hidden');
    currentEmployeeUrl = null;
}

function clearForm() {
    ['f_passport_number','f_passport_issue_date','f_id_expiry_date','f_driver_license_number',
     'f_medical_insurance','f_wives_count','f_city','f_neighborhood',
     'f_tshirt_size','f_trousers_size','f_shoes_size'].forEach(id => {
        document.getElementById(id).value = '';
    });
    document.getElementById('languagesContainer').innerHTML = '';
    document.getElementById('modalEmployeeId').value = '';
    currentEmployeeUrl = null;
}

function addLanguageRow(lang = '', level = '') {
    const div = document.createElement('div');
    div.className = 'flex gap-2 items-center';
    div.innerHTML = `
        <input type="text" name="lang_name[]" value="${lang}" placeholder="اسم اللغة"
               class="flex-1 border-2 border-gray-200 rounded-xl px-3 py-2 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <select name="lang_level[]" class="border-2 border-gray-200 rounded-xl px-3 py-2 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">المستوى</option>
            <option value="مبتدئ" ${level==='مبتدئ'?'selected':''}>مبتدئ</option>
            <option value="متوسط" ${level==='متوسط'?'selected':''}>متوسط</option>
            <option value="جيد" ${level==='جيد'?'selected':''}>جيد</option>
            <option value="متقدم" ${level==='متقدم'?'selected':''}>متقدم</option>
            <option value="طليق" ${level==='طليق'?'selected':''}>طليق</option>
        </select>
        <button type="button" onclick="this.parentElement.remove()"
                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 flex items-center justify-center flex-shrink-0 transition">
            <i class="fas fa-trash text-xs"></i>
        </button>`;
    document.getElementById('languagesContainer').appendChild(div);
}

document.getElementById('sponsorshipForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Resolve URL
    let url = currentEmployeeUrl;
    if (!url) {
        const sel = document.getElementById('modalEmployeeId');
        const opt = sel.options[sel.selectedIndex];
        if (!opt || !opt.value) { alert('يرجى اختيار موظف'); return; }
        url = opt.dataset.url;
    }

    const fd = new FormData(this);
    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-HTTP-Method-Override': 'PUT' },
        body: fd,
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { closeModal(); location.reload(); }
        else { alert(res.message || 'حدث خطأ'); }
    });
});
</script>
@endsection
