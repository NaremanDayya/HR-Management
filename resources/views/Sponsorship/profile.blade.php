@extends('layouts.master')

@section('title', 'ملف الموظف - ' . $employee->name)

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
<div class="max-w-screen-xl mx-auto py-8 px-4 sm:px-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm font-medium text-gray-500 mb-6">
        <a href="{{ route('sponsorship.index') }}" class="hover:text-blue-600 transition flex items-center gap-1.5">
            <i class="fas fa-passport text-xs"></i>
            الكفالة والموظفون
        </a>
        <i class="fas fa-chevron-left text-xs text-gray-400"></i>
        <span class="text-gray-800 font-bold">{{ $employee->name }}</span>
    </div>

    @php
        $user = $employee->user;
        $expiryDays = $employee->getIdExpiryDays();
        $expiryStatus = match(true) {
            $expiryDays === null => ['label' => 'غير محدد',            'cls' => 'bg-gray-100 text-gray-500',   'dot' => 'bg-gray-400'],
            $expiryDays < 0     => ['label' => 'منتهية الصلاحية',      'cls' => 'bg-red-100 text-red-700',     'dot' => 'bg-red-500'],
            $expiryDays <= 30   => ['label' => "تنتهي خلال {$expiryDays} يوم", 'cls' => 'bg-red-50 text-red-600',      'dot' => 'bg-red-400'],
            $expiryDays <= 90   => ['label' => "تنتهي خلال {$expiryDays} يوم", 'cls' => 'bg-amber-50 text-amber-700',  'dot' => 'bg-amber-400'],
            $expiryDays <= 180  => ['label' => "تنتهي خلال {$expiryDays} يوم", 'cls' => 'bg-yellow-50 text-yellow-700','dot' => 'bg-yellow-400'],
            default             => ['label' => 'ساري المفعول',          'cls' => 'bg-green-50 text-green-700',  'dot' => 'bg-green-400'],
        };
        $age = $user?->birthday ? $user->birthday->age : null;
        $ticket = $flightTicket;
    @endphp

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

        {{-- SIDEBAR --}}
        <div class="xl:col-span-4 space-y-5">

            {{-- Identity Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                {{-- Cover --}}
                <div class="h-28 bg-gradient-to-l from-blue-700 to-blue-500 relative">
                    <div class="absolute bottom-0 right-0 left-0 h-10 bg-gradient-to-t from-black/20 to-transparent"></div>
                </div>
                {{-- Avatar --}}
                <div class="-mt-12 px-6 pb-6">
                    <div class="w-24 h-24 rounded-2xl border-4 border-white overflow-hidden bg-blue-100 shadow-lg mb-4">
                        @if($user?->personal_image)
                            <img src="{{ $user->personal_image }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-blue-700 text-3xl font-extrabold">
                                {{ mb_substr($employee->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h1 class="text-xl font-extrabold text-gray-900 leading-tight">{{ $employee->name }}</h1>
                    <p class="text-sm font-semibold text-gray-500 mt-0.5">{{ $employee->job ?? 'لا يوجد مسمى وظيفي' }}</p>

                    @if($employee->project)
                        <div class="mt-3 inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-sm font-bold px-3 py-1.5 rounded-full">
                            <i class="fas fa-building text-xs"></i>
                            {{ $employee->project->name }}
                        </div>
                    @endif

                    <div class="mt-3">
                        <span class="inline-flex items-center gap-2 text-sm font-bold px-3 py-2 rounded-xl {{ $expiryStatus['cls'] }}">
                            <span class="w-2 h-2 rounded-full {{ $expiryStatus['dot'] }}"></span>
                            {{ $expiryStatus['label'] }}
                        </span>
                    </div>

                    {{-- Quick info row --}}
                    <div class="mt-5 grid grid-cols-3 gap-3 pt-4 border-t border-gray-100">
                        <div class="text-center">
                            <div class="text-lg font-extrabold text-gray-900">{{ $age ?? '—' }}</div>
                            <div class="text-xs font-semibold text-gray-400 mt-0.5">العمر</div>
                        </div>
                        <div class="text-center border-x border-gray-100">
                            <div class="text-lg font-extrabold text-gray-900">{{ $serviceYears ?? '—' }}</div>
                            <div class="text-xs font-semibold text-gray-400 mt-0.5">سنوات خدمة</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-extrabold text-gray-900">{{ $user?->nationality ?? '—' }}</div>
                            <div class="text-xs font-semibold text-gray-400 mt-0.5">الجنسية</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Leave Balance --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-base font-extrabold text-gray-800 mb-5 flex items-center gap-2">
                    <i class="fas fa-calendar-check text-green-500"></i>
                    رصيد الإجازات
                </h3>
                <div class="grid grid-cols-3 gap-3">
                    <div class="text-center bg-green-50 border border-green-100 rounded-2xl p-4">
                        <div class="text-3xl font-extrabold text-green-600">{{ (int)$remaining }}</div>
                        <div class="text-xs font-bold text-green-500 mt-1">متبقي</div>
                    </div>
                    <div class="text-center bg-red-50 border border-red-100 rounded-2xl p-4">
                        <div class="text-3xl font-extrabold text-red-500">{{ $totalTaken }}</div>
                        <div class="text-xs font-bold text-red-400 mt-1">مأخوذ</div>
                    </div>
                    <div class="text-center bg-blue-50 border border-blue-100 rounded-2xl p-4">
                        <div class="text-3xl font-extrabold text-blue-600">{{ $entitlement }}</div>
                        <div class="text-xs font-bold text-blue-400 mt-1">يوم/سنة</div>
                    </div>
                </div>
                @if($employee->joining_date)
                    <div class="mt-4 bg-gray-50 rounded-xl p-3 text-center">
                        <span class="text-sm font-semibold text-gray-600">إجمالي المستحق: </span>
                        <span class="text-sm font-extrabold text-gray-800">{{ number_format($totalAccrued, 1) }} يوم</span>
                    </div>
                @endif
            </div>

            {{-- Flight Ticket --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-base font-extrabold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-plane text-blue-500"></i>
                    تذكرة السفر السنوية
                </h3>
                @if($ticket['type'] !== 'none')
                    <div class="flex items-center justify-between bg-gray-50 rounded-xl p-4">
                        <div>
                            <div class="text-sm font-bold text-gray-500">الاستحقاق الحالي</div>
                            <div class="text-lg font-extrabold {{ $ticket['type'] === 'full' ? 'text-green-600' : 'text-amber-600' }} mt-0.5">
                                {{ $ticket['label'] }}
                            </div>
                        </div>
                        <div class="text-left">
                            <div class="text-2xl font-extrabold text-gray-900">{{ number_format($ticket['amount'], 0) }}</div>
                            <div class="text-sm font-bold text-gray-400">ر.س سنوياً</div>
                        </div>
                    </div>
                    @if($ticket['type'] === 'half' && $serviceYears !== null && $serviceYears < 5)
                        <p class="text-xs font-semibold text-gray-500 mt-3 text-center">
                            يستحق تذكرة كاملة بعد {{ 5 - $serviceYears }} سنة {{ $serviceMonths ? "و {$serviceMonths} شهر" : '' }}
                        </p>
                    @endif
                @else
                    <p class="text-sm font-semibold text-gray-400 text-center py-2">لا تتوفر بيانات كافية</p>
                @endif
            </div>

        </div>

        {{-- MAIN CONTENT --}}
        <div class="xl:col-span-8" x-data="{ tab: 'personal' }">

            {{-- Tab Navigation --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto border-b border-gray-200">
                    <div class="flex min-w-max">
                        @foreach([
                            ['personal',  'fas fa-user',           'البيانات الشخصية'],
                            ['documents', 'fas fa-id-card',        'الوثائق والإقامة'],
                            ['contact',   'fas fa-map-marker-alt', 'العنوان والتواصل'],
                            ['vehicle',   'fas fa-car',            'المركبة والرخصة'],
                            ['education', 'fas fa-graduation-cap', 'التعليم واللغات'],
                            ['sizes',     'fas fa-tshirt',         'المقاسات'],
                            ['leaves',    'fas fa-calendar-alt',   'الإجازات'],
                        ] as [$key, $icon, $label])
                        <button @click="tab = '{{ $key }}'"
                                :class="tab === '{{ $key }}'
                                    ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50/60'
                                    : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50'"
                                class="flex items-center gap-2 px-5 py-4 text-sm font-bold transition whitespace-nowrap">
                            <i class="{{ $icon }} text-xs" :class="tab === '{{ $key }}' ? 'text-blue-500' : 'text-gray-400'"></i>
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Tab Content wrapper --}}
                <div class="p-7">

                    {{-- PERSONAL --}}
                    <div x-show="tab === 'personal'" x-transition>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <x-profile-field label="الاسم الكامل"       :value="$employee->name" />
                            <x-profile-field label="تاريخ الميلاد"      :value="$user?->birthday?->format('Y/m/d')" :suffix="$age ? '(العمر: '.$age.' سنة)' : ''" />
                            <x-profile-field label="الجنس"              :value="$user?->gender === 'male' ? 'ذكر' : ($user?->gender === 'female' ? 'أنثى' : null)" />
                            <x-profile-field label="الجنسية"            :value="$user?->nationality" />
                            <x-profile-field label="تاريخ الالتحاق"     :value="$employee->joining_date?->format('Y/m/d')" />
                            <x-profile-field label="مدة الخدمة"         :value="$serviceYears !== null ? $serviceYears.' سنة '.($serviceMonths ? 'و '.$serviceMonths.' شهر' : '') : null" />
                            <x-profile-field label="الحالة الاجتماعية"  :value="$employee->getMaritalStatus()" />
                            @if($employee->marital_status === 'married' && $employee->wives_count)
                                <x-profile-field label="عدد الزوجات"    :value="$employee->wives_count" />
                            @endif
                            <x-profile-field label="عدد أفراد الأسرة"  :value="$employee->members_number" />
                            <x-profile-field label="التأمين الطبي"      :value="$employee->medical_insurance" />
                        </div>
                    </div>

                    {{-- DOCUMENTS --}}
                    <div x-show="tab === 'documents'" x-transition>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <x-profile-field label="رقم الهوية / الإقامة" :value="$user?->id_card" :mono="true" />
                            <div>
                                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">انتهاء الإقامة / الهوية / التأشيرة</div>
                                @if($employee->id_expiry_date)
                                    <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold {{ $expiryStatus['cls'] }}">
                                        <span class="w-2 h-2 rounded-full {{ $expiryStatus['dot'] }}"></span>
                                        {{ $employee->id_expiry_date->format('Y/m/d') }}
                                        <span class="opacity-70 font-semibold">({{ $expiryStatus['label'] }})</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 font-semibold text-sm">—</span>
                                @endif
                            </div>
                            <x-profile-field label="رقم جواز السفر"     :value="$employee->passport_number" :mono="true" />
                            <x-profile-field label="تاريخ إصدار الجواز" :value="$employee->passport_issue_date?->format('Y/m/d')" />
                            @if($employee->passport_issue_date)
                                @php
                                    $passportExpiry  = $employee->passport_issue_date->copy()->addYears(5);
                                    $passportExpired = $passportExpiry->isPast();
                                    $passportDays    = (int)now()->diffInDays($passportExpiry, false);
                                @endphp
                                <div class="sm:col-span-2">
                                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">تاريخ انتهاء الجواز (تقديري – 5 سنوات من الإصدار)</div>
                                    <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold
                                        {{ $passportExpired ? 'bg-red-50 text-red-700' : ($passportDays <= 90 ? 'bg-amber-50 text-amber-700' : 'bg-green-50 text-green-700') }}">
                                        {{ $passportExpiry->format('Y/m/d') }}
                                        @if($passportExpired)
                                            <span class="text-xs font-semibold opacity-70">منتهي</span>
                                        @elseif($passportDays <= 90)
                                            <span class="text-xs font-semibold opacity-70">باقي {{ $passportDays }} يوم</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- CONTACT --}}
                    <div x-show="tab === 'contact'" x-transition>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <x-profile-field label="رقم الجوال"         :value="$user?->contact_info['phone_number'] ?? null" :mono="true" />
                            <x-profile-field label="البريد الإلكتروني"  :value="$user?->email" />
                            <x-profile-field label="المدينة"             :value="$employee->residential_address['city'] ?? null" />
                            <x-profile-field label="الحي"                :value="$employee->residential_address['neighborhood'] ?? null" />
                        </div>
                    </div>

                    {{-- VEHICLE --}}
                    <div x-show="tab === 'vehicle'" x-transition>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <x-profile-field label="نوع المركبة"        :value="$employee->vehicle_info['type'] ?? null" />
                            <x-profile-field label="رقم اللوحة"         :value="$employee->vehicle_info['plate'] ?? ($employee->vehicle_info['plate_number'] ?? null)" :mono="true" />
                            <x-profile-field label="رقم رخصة القيادة"   :value="$employee->driver_license_number" :mono="true" />
                        </div>
                    </div>

                    {{-- EDUCATION --}}
                    <div x-show="tab === 'education'" x-transition>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                            <x-profile-field label="المؤهل التعليمي"   :value="$employee->getCertificateType()" />
                            <x-profile-field label="مستوى الإنجليزية" :value="$employee->getEnglishLevel()" />
                        </div>
                        <div class="border-t border-gray-100 pt-5">
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">اللغات المتحدثة</div>
                            @if($employee->languages && count($employee->languages))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($employee->languages as $lang)
                                        <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-100 text-blue-800 text-sm font-bold px-4 py-2 rounded-xl">
                                            {{ $lang['language'] ?? $lang }}
                                            @if(!empty($lang['level']))
                                                <span class="text-xs text-blue-400 font-semibold bg-blue-100 px-2 py-0.5 rounded-full">{{ $lang['level'] }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm font-semibold text-gray-400">لم تُحدد لغات إضافية</p>
                            @endif
                        </div>
                    </div>

                    {{-- SIZES --}}
                    <div x-show="tab === 'sizes'" x-transition>
                        <div class="grid grid-cols-3 gap-5">
                            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 text-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-tshirt text-blue-500 text-xl"></i>
                                </div>
                                <div class="text-3xl font-extrabold text-gray-900">{{ $user?->tshirt_size ?? '—' }}</div>
                                <div class="text-sm font-bold text-gray-400 mt-2">قميص</div>
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 text-center">
                                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-male text-indigo-500 text-xl"></i>
                                </div>
                                <div class="text-3xl font-extrabold text-gray-900">{{ $user?->trousers_size ?? '—' }}</div>
                                <div class="text-sm font-bold text-gray-400 mt-2">بنطال</div>
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 text-center">
                                <div class="w-12 h-12 bg-gray-200 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-shoe-prints text-gray-500 text-xl"></i>
                                </div>
                                <div class="text-3xl font-extrabold text-gray-900">{{ $user?->shoes_size ?? '—' }}</div>
                                <div class="text-sm font-bold text-gray-400 mt-2">حذاء</div>
                            </div>
                        </div>
                    </div>

                    {{-- LEAVES --}}
                    <div x-show="tab === 'leaves'" x-transition x-data="{ showForm: false }">
                        {{-- Add leave toggle --}}
                        <button @click="showForm = !showForm"
                                class="mb-5 inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition shadow-sm">
                            <i class="fas fa-plus"></i>
                            تسجيل إجازة جديدة
                        </button>

                        {{-- Add form --}}
                        <div x-show="showForm" x-transition class="bg-gray-50 border border-gray-200 rounded-2xl p-5 mb-6">
                            <form id="leaveForm" class="grid grid-cols-2 gap-4">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">نوع الإجازة</label>
                                    <select name="leave_type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="annual">سنوية</option>
                                        <option value="sick">مرضية</option>
                                        <option value="emergency">طارئة</option>
                                        <option value="maternity">أمومة</option>
                                        <option value="unpaid">بدون راتب</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">الحالة</label>
                                    <select name="status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="approved">موافق</option>
                                        <option value="pending">معلقة</option>
                                        <option value="rejected">مرفوضة</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">تاريخ البداية</label>
                                    <input type="date" name="start_date" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">تاريخ النهاية</label>
                                    <input type="date" name="end_date" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">ملاحظات</label>
                                    <textarea name="notes" rows="2" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                                <div class="col-span-2 flex gap-2">
                                    <button type="button" onclick="submitLeave()"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition shadow-sm">
                                        <i class="fas fa-save ml-1"></i> حفظ
                                    </button>
                                    <button type="button" @click="showForm = false"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-bold transition">
                                        إلغاء
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Leave history --}}
                        @if($employee->leaveRequests->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($employee->leaveRequests as $leave)
                                    @php
                                        $lv = [
                                            'approved' => ['bg' => 'bg-green-50 border-green-200', 'badge' => 'bg-green-100 text-green-700'],
                                            'pending'  => ['bg' => 'bg-yellow-50 border-yellow-200', 'badge' => 'bg-yellow-100 text-yellow-700'],
                                            'rejected' => ['bg' => 'bg-red-50 border-red-200', 'badge' => 'bg-red-100 text-red-600'],
                                        ][$leave->status] ?? ['bg' => 'bg-gray-50 border-gray-200', 'badge' => 'bg-gray-100 text-gray-600'];
                                    @endphp
                                    <div class="flex items-center justify-between border rounded-2xl px-5 py-4 {{ $lv['bg'] }}">
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">{{ $leave->getLeaveTypeLabel() }}</div>
                                            <div class="text-xs font-semibold text-gray-500 mt-0.5">
                                                {{ $leave->start_date->format('Y/m/d') }} → {{ $leave->end_date->format('Y/m/d') }}
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="text-center">
                                                <div class="text-2xl font-extrabold text-gray-900">{{ $leave->days_count }}</div>
                                                <div class="text-xs font-bold text-gray-400">يوم</div>
                                            </div>
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $lv['badge'] }}">{{ $leave->getStatusLabel() }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10">
                                <i class="fas fa-calendar-times text-3xl text-gray-300 mb-3 block"></i>
                                <p class="text-gray-500 font-semibold">لا يوجد سجل إجازات بعد</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script>
function submitLeave() {
    const form = document.getElementById('leaveForm');
    const data = new FormData(form);
    fetch('{{ route('leaves.store') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: data,
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { location.reload(); }
        else { alert(res.message || 'حدث خطأ'); }
    });
}
</script>
@endsection
