@extends('layouts.master')

@section('title', 'ملف الموظف - ' . $employee->name)

@section('content')
<div class="container-fluid py-6 px-4" dir="rtl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('sponsorship.index') }}" class="hover:text-blue-600 transition">الكفالة والموظفون</a>
        <i class="fas fa-chevron-left text-xs"></i>
        <span class="text-gray-800 font-medium">{{ $employee->name }}</span>
    </div>

    @php
        $user = $employee->user;
        $expiryDays = $employee->getIdExpiryDays();
        $expiryStatus = match(true) {
            $expiryDays === null => ['label' => 'غير محدد', 'class' => 'bg-gray-100 text-gray-500'],
            $expiryDays < 0     => ['label' => 'منتهية الصلاحية', 'class' => 'bg-red-100 text-red-700'],
            $expiryDays <= 30   => ['label' => "تنتهي خلال {$expiryDays} يوم", 'class' => 'bg-red-50 text-red-600'],
            $expiryDays <= 90   => ['label' => "تنتهي خلال {$expiryDays} يوم", 'class' => 'bg-amber-50 text-amber-600'],
            $expiryDays <= 180  => ['label' => "تنتهي خلال {$expiryDays} يوم", 'class' => 'bg-yellow-50 text-yellow-600'],
            default             => ['label' => "صالحة ({$expiryDays} يوم)", 'class' => 'bg-green-50 text-green-600'],
        };
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Identity Card --}}
        <div class="lg:col-span-1 space-y-5">

            {{-- Photo + name card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 h-24 relative">
                    <div class="absolute -bottom-10 right-6">
                        <div class="w-20 h-20 rounded-2xl border-4 border-white overflow-hidden bg-blue-100 shadow">
                            @if($user?->personal_image)
                                <img src="{{ $user->personal_image }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-blue-600 text-2xl font-bold">
                                    {{ mb_substr($employee->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="pt-12 pb-5 px-6">
                    <h2 class="text-lg font-bold text-gray-800">{{ $employee->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $employee->job ?? 'لا يوجد مسمى وظيفي' }}</p>
                    @if($employee->project)
                        <span class="inline-flex items-center gap-1 mt-2 text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded-full">
                            <i class="fas fa-building text-xs"></i>
                            {{ $employee->project->name }}
                        </span>
                    @endif

                    {{-- ID expiry badge --}}
                    <div class="mt-3">
                        <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full {{ $expiryStatus['class'] }}">
                            <i class="fas fa-id-card text-xs"></i>
                            {{ $expiryStatus['label'] }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Leave Balance Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-calendar-check text-green-500"></i>
                    رصيد الإجازات
                </h3>
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div class="text-center bg-green-50 rounded-xl p-3">
                        <div class="text-xl font-bold text-green-600">{{ (int)$remaining }}</div>
                        <div class="text-xs text-green-500 mt-0.5">متبقي</div>
                    </div>
                    <div class="text-center bg-red-50 rounded-xl p-3">
                        <div class="text-xl font-bold text-red-500">{{ $totalTaken }}</div>
                        <div class="text-xs text-red-400 mt-0.5">مأخوذ</div>
                    </div>
                    <div class="text-center bg-blue-50 rounded-xl p-3">
                        <div class="text-xl font-bold text-blue-600">{{ $entitlement }}</div>
                        <div class="text-xs text-blue-400 mt-0.5">يوم/سنة</div>
                    </div>
                </div>
                @if($employee->joining_date)
                    <div class="text-xs text-gray-500 text-center">
                        إجمالي المستحق: {{ number_format($totalAccrued, 1) }} يوم
                    </div>
                @endif
            </div>

            {{-- Flight Ticket --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-plane text-blue-500"></i>
                    تذكرة السفر السنوية
                </h3>
                @php $ticket = $flightTicket; @endphp
                @if($ticket['type'] !== 'none')
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ $ticket['label'] }}</span>
                        <span class="font-bold {{ $ticket['type'] === 'full' ? 'text-green-600' : 'text-amber-600' }}">
                            {{ number_format($ticket['amount'], 0) }} ر.س
                        </span>
                    </div>
                    <div class="mt-2 text-xs text-gray-400">
                        @if($serviceYears !== null)
                            مدة الخدمة: {{ $serviceYears }} سنة
                            @if($serviceMonths) و {{ $serviceMonths }} شهر @endif
                            @if($ticket['type'] === 'half' && $serviceYears < 5)
                                — يستحق الراتب الكامل بعد {{ 5 - $serviceYears }} سنة
                            @endif
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-400">لا تتوفر بيانات كافية</p>
                @endif
            </div>

        </div>

        {{-- RIGHT: Details tabs --}}
        <div class="lg:col-span-2 space-y-5" x-data="{ tab: 'personal' }">

            {{-- Tab nav --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="flex border-b border-gray-200 overflow-x-auto">
                    @foreach([
                        ['personal',   'fas fa-user',        'البيانات الشخصية'],
                        ['documents',  'fas fa-id-card',     'الوثائق والإقامة'],
                        ['contact',    'fas fa-map-marker-alt','العنوان والتواصل'],
                        ['vehicle',    'fas fa-car',         'المركبة والرخصة'],
                        ['education',  'fas fa-graduation-cap','التعليم واللغات'],
                        ['sizes',      'fas fa-tshirt',      'المقاسات'],
                        ['leaves',     'fas fa-calendar-alt', 'الإجازات'],
                    ] as [$key, $icon, $label])
                    <button @click="tab = '{{ $key }}'"
                            :class="tab === '{{ $key }}' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700'"
                            class="flex-shrink-0 flex items-center gap-1.5 px-4 py-3 text-sm font-medium transition whitespace-nowrap">
                        <i class="{{ $icon }} text-xs"></i>
                        {{ $label }}
                    </button>
                    @endforeach
                </div>

                {{-- Tab: Personal --}}
                <div x-show="tab === 'personal'" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $age = $user?->birthday ? $user->birthday->age : null;
                        @endphp
                        <x-profile-field label="الاسم الكامل"    value="{{ $employee->name }}" />
                        <x-profile-field label="تاريخ الميلاد"   value="{{ $user?->birthday?->format('Y/m/d') }}" suffix="{{ $age ? '(العمر: '.$age.' سنة)' : '' }}" />
                        <x-profile-field label="الجنس"           value="{{ $user?->gender === 'male' ? 'ذكر' : ($user?->gender === 'female' ? 'أنثى' : null) }}" />
                        <x-profile-field label="الجنسية"         value="{{ $user?->nationality }}" />
                        <x-profile-field label="تاريخ الالتحاق"  value="{{ $employee->joining_date?->format('Y/m/d') }}" />
                        <x-profile-field label="مدة الخدمة"      value="{{ $serviceYears !== null ? $serviceYears.' سنة '.($serviceMonths ? 'و '.$serviceMonths.' شهر' : '') : null }}" />
                        <x-profile-field label="الحالة الاجتماعية" value="{{ $employee->getMaritalStatus() }}" />
                        @if($employee->marital_status === 'married' && $employee->wives_count)
                            <x-profile-field label="عدد الزوجات" value="{{ $employee->wives_count }}" />
                        @endif
                        <x-profile-field label="عدد أفراد الأسرة" value="{{ $employee->members_number }}" />
                        <x-profile-field label="التأمين الطبي"   value="{{ $employee->medical_insurance }}" />
                    </div>
                </div>

                {{-- Tab: Documents --}}
                <div x-show="tab === 'documents'" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-profile-field label="رقم الهوية / الإقامة" value="{{ $user?->id_card }}" mono />
                        <div>
                            <div class="text-xs text-gray-500 mb-1">انتهاء الإقامة / الهوية / التأشيرة</div>
                            @if($employee->id_expiry_date)
                                <div class="font-medium {{ $expiryStatus['class'] }} inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm">
                                    {{ $employee->id_expiry_date->format('Y/m/d') }}
                                    <span class="text-xs opacity-75">({{ $expiryStatus['label'] }})</span>
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </div>
                        <x-profile-field label="رقم جواز السفر"    value="{{ $employee->passport_number }}" mono />
                        <x-profile-field label="تاريخ إصدار الجواز" value="{{ $employee->passport_issue_date?->format('Y/m/d') }}" />
                        @if($employee->passport_issue_date)
                            <div>
                                <div class="text-xs text-gray-500 mb-1">تاريخ انتهاء الجواز (تقديري)</div>
                                @php
                                    $passportExpiry = $employee->passport_issue_date->copy()->addYears(5);
                                    $passportExpired = $passportExpiry->isPast();
                                    $passportDays = (int)now()->diffInDays($passportExpiry, false);
                                @endphp
                                <div class="{{ $passportExpired ? 'text-red-600 font-semibold' : ($passportDays <= 90 ? 'text-amber-600' : 'text-green-600') }} text-sm">
                                    {{ $passportExpiry->format('Y/m/d') }}
                                    <span class="text-xs text-gray-400">(5 سنوات من الإصدار)</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tab: Contact & Address --}}
                <div x-show="tab === 'contact'" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-profile-field label="رقم الجوال"  value="{{ $user?->contact_info['phone_number'] ?? null }}" mono />
                        <x-profile-field label="البريد الإلكتروني" value="{{ $user?->email }}" />
                        <x-profile-field label="المدينة"      value="{{ $employee->residential_address['city'] ?? null }}" />
                        <x-profile-field label="الحي"         value="{{ $employee->residential_address['neighborhood'] ?? null }}" />
                    </div>
                </div>

                {{-- Tab: Vehicle --}}
                <div x-show="tab === 'vehicle'" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-profile-field label="نوع المركبة"        value="{{ $employee->vehicle_info['type'] ?? null }}" />
                        <x-profile-field label="رقم اللوحة"         value="{{ $employee->vehicle_info['plate'] ?? ($employee->vehicle_info['plate_number'] ?? null) }}" mono />
                        <x-profile-field label="رقم رخصة القيادة"   value="{{ $employee->driver_license_number }}" mono />
                    </div>
                </div>

                {{-- Tab: Education & Languages --}}
                <div x-show="tab === 'education'" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-profile-field label="المؤهل التعليمي" value="{{ $employee->getCertificateType() }}" />
                        <x-profile-field label="مستوى الإنجليزية" value="{{ $employee->getEnglishLevel() }}" />
                    </div>
                    @if($employee->languages && count($employee->languages))
                        <div class="mt-4">
                            <div class="text-xs text-gray-500 mb-2">اللغات</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($employee->languages as $lang)
                                    <span class="bg-blue-50 text-blue-700 text-sm px-3 py-1.5 rounded-full">
                                        {{ $lang['language'] ?? $lang }}
                                        @if(!empty($lang['level']))
                                            <span class="text-blue-400">({{ $lang['level'] }})</span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mt-4 text-sm text-gray-400">لم تُحدد لغات إضافية</div>
                    @endif
                </div>

                {{-- Tab: Clothing Sizes --}}
                <div x-show="tab === 'sizes'" class="p-6">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-xl p-4 text-center">
                            <i class="fas fa-tshirt text-2xl text-blue-400 mb-2"></i>
                            <div class="text-lg font-bold text-gray-800">{{ $user?->tshirt_size ?? '—' }}</div>
                            <div class="text-xs text-gray-500 mt-1">قميص</div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 text-center">
                            <i class="fas fa-male text-2xl text-indigo-400 mb-2"></i>
                            <div class="text-lg font-bold text-gray-800">{{ $user?->trousers_size ?? '—' }}</div>
                            <div class="text-xs text-gray-500 mt-1">بنطال</div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 text-center">
                            <i class="fas fa-shoe-prints text-2xl text-gray-400 mb-2"></i>
                            <div class="text-lg font-bold text-gray-800">{{ $user?->shoes_size ?? '—' }}</div>
                            <div class="text-xs text-gray-500 mt-1">حذاء</div>
                        </div>
                    </div>
                </div>

                {{-- Tab: Leaves --}}
                <div x-show="tab === 'leaves'" class="p-6">

                    {{-- Add leave form --}}
                    <div class="bg-gray-50 rounded-xl p-4 mb-5" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                            <i class="fas fa-plus-circle"></i>
                            تسجيل إجازة جديدة
                        </button>
                        <form x-show="open" x-transition @submit.prevent="submitLeave()" class="mt-4 grid grid-cols-2 gap-3" id="leaveForm">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">نوع الإجازة</label>
                                <select name="leave_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                                    <option value="annual">سنوية</option>
                                    <option value="sick">مرضية</option>
                                    <option value="emergency">طارئة</option>
                                    <option value="maternity">أمومة</option>
                                    <option value="unpaid">بدون راتب</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">الحالة</label>
                                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                    <option value="approved">موافق</option>
                                    <option value="pending">معلقة</option>
                                    <option value="rejected">مرفوضة</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">تاريخ البداية</label>
                                <input type="date" name="start_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">تاريخ النهاية</label>
                                <input type="date" name="end_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs text-gray-600 mb-1">ملاحظات</label>
                                <textarea name="notes" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                            </div>
                            <div class="col-span-2 flex gap-2">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                    حفظ
                                </button>
                                <button type="button" @click="open = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
                                    إلغاء
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Leave history --}}
                    @if($employee->leaveRequests->isNotEmpty())
                        <div class="space-y-2">
                            @foreach($employee->leaveRequests as $leave)
                                @php
                                    $statusColors = [
                                        'approved' => 'bg-green-50 border-green-200 text-green-700',
                                        'pending'  => 'bg-yellow-50 border-yellow-200 text-yellow-700',
                                        'rejected' => 'bg-red-50 border-red-200 text-red-600',
                                    ];
                                @endphp
                                <div class="flex items-center justify-between border rounded-xl px-4 py-3 {{ $statusColors[$leave->status] ?? 'bg-gray-50 border-gray-200' }}">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-sm">{{ $leave->getLeaveTypeLabel() }}</span>
                                        <span class="text-xs opacity-70">
                                            {{ $leave->start_date->format('Y/m/d') }} ← {{ $leave->end_date->format('Y/m/d') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="font-bold text-lg">{{ $leave->days_count }}</span>
                                        <span class="text-xs opacity-70">يوم</span>
                                        <span class="text-xs px-2 py-1 rounded-full bg-white bg-opacity-60 border">{{ $leave->getStatusLabel() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-sm text-center py-6">لا يوجد سجل إجازات بعد</p>
                    @endif
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
        if (res.success) {
            alert(res.message);
            location.reload();
        } else {
            alert('حدث خطأ');
        }
    });
}
</script>
@endsection
