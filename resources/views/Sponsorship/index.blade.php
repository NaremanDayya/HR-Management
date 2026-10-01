@extends('layouts.master')

@section('title', 'الكفالة والموظفون')

@section('content')
<div class="container-fluid py-6 px-4" dir="rtl">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">ملف الكفالة والموظفون</h1>
            <p class="text-sm text-gray-500 mt-1">إدارة بيانات الكفالة، صلاحيات الإقامة، وتذاكر السفر</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('leaves.index') }}"
               class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                <i class="fas fa-calendar-alt"></i>
                سجل الإجازات
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('sponsorship.index') }}"
          class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="ابحث بالاسم أو الهوية..."
                   class="col-span-2 md:col-span-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

            <select name="project_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">كل المشاريع</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}" @selected(request('project_id') == $p->id)>{{ $p->name }}</option>
                @endforeach
            </select>

            <select name="id_expiry" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">انتهاء الإقامة / الهوية</option>
                <option value="expired" @selected(request('id_expiry')=='expired')>منتهية</option>
                <option value="1month"  @selected(request('id_expiry')=='1month')>خلال شهر</option>
                <option value="3months" @selected(request('id_expiry')=='3months')>خلال 3 أشهر</option>
                <option value="6months" @selected(request('id_expiry')=='6months')>خلال 6 أشهر</option>
            </select>

            <select name="service_years" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">سنوات الخدمة</option>
                <option value="under5" @selected(request('service_years')=='under5')>أقل من 5 سنوات (نصف تذكرة)</option>
                <option value="near5"  @selected(request('service_years')=='near5')>قريب من 5 سنوات</option>
                <option value="over5"  @selected(request('service_years')=='over5')>أكثر من 5 سنوات (تذكرة كاملة)</option>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-filter ml-1"></i> فلترة
                </button>
                <a href="{{ route('sponsorship.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm transition">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
    </form>

    {{-- Stats row --}}
    @php
        $expired       = $employees->filter(fn($e) => $e->id_expiry_date && $e->id_expiry_date->isPast());
        $expiringSoon  = $employees->filter(fn($e) => $e->id_expiry_date && !$e->id_expiry_date->isPast() && $e->id_expiry_date->diffInDays(now()) <= 90);
        $over5years    = $employees->filter(fn($e) => $e->joining_date && $e->joining_date->diffInYears(now()) >= 5);
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-gray-800">{{ $employees->count() }}</div>
            <div class="text-xs text-gray-500 mt-1">إجمالي الموظفين</div>
        </div>
        <div class="bg-red-50 rounded-xl border border-red-200 p-4 text-center">
            <div class="text-2xl font-bold text-red-600">{{ $expired->count() }}</div>
            <div class="text-xs text-red-500 mt-1">إقامة منتهية</div>
        </div>
        <div class="bg-amber-50 rounded-xl border border-amber-200 p-4 text-center">
            <div class="text-2xl font-bold text-amber-600">{{ $expiringSoon->count() }}</div>
            <div class="text-xs text-amber-500 mt-1">تنتهي خلال 3 أشهر</div>
        </div>
        <div class="bg-green-50 rounded-xl border border-green-200 p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $over5years->count() }}</div>
            <div class="text-xs text-green-500 mt-1">تجاوزوا 5 سنوات خدمة</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 font-medium text-gray-600 whitespace-nowrap">الموظف</th>
                        <th class="px-4 py-3 font-medium text-gray-600 whitespace-nowrap">رقم الهوية</th>
                        <th class="px-4 py-3 font-medium text-gray-600 whitespace-nowrap">الجوال</th>
                        <th class="px-4 py-3 font-medium text-gray-600 whitespace-nowrap">تاريخ الالتحاق</th>
                        <th class="px-4 py-3 font-medium text-gray-600 whitespace-nowrap">مدة الخدمة</th>
                        <th class="px-4 py-3 font-medium text-gray-600 whitespace-nowrap">تذكرة السفر</th>
                        <th class="px-4 py-3 font-medium text-gray-600 whitespace-nowrap">انتهاء الإقامة/الهوية</th>
                        <th class="px-4 py-3 font-medium text-gray-600 whitespace-nowrap">جواز السفر</th>
                        <th class="px-4 py-3 font-medium text-gray-600 whitespace-nowrap">رصيد الإجازة</th>
                        <th class="px-4 py-3 font-medium text-gray-600 whitespace-nowrap">المشروع</th>
                        <th class="px-4 py-3 font-medium text-gray-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($employees as $employee)
                        @php
                            $user          = $employee->user;
                            $expiryDays    = $employee->getIdExpiryDays();
                            $serviceYears  = $employee->joining_date ? (int)$employee->joining_date->diffInYears(now()) : null;
                            $ticket        = $employee->getFlightTicketEntitlement();
                            $leaveRemaining= $employee->getLeaveDaysRemaining();

                            $expiryClass = match(true) {
                                $expiryDays === null         => 'text-gray-400',
                                $expiryDays < 0              => 'text-red-600 font-bold',
                                $expiryDays <= 30            => 'text-red-500 font-semibold',
                                $expiryDays <= 90            => 'text-amber-500 font-semibold',
                                $expiryDays <= 180           => 'text-yellow-600',
                                default                      => 'text-green-600',
                            };

                            $expiryBadge = match(true) {
                                $expiryDays === null => null,
                                $expiryDays < 0     => 'منتهية',
                                $expiryDays <= 30   => "باقي {$expiryDays} يوم",
                                $expiryDays <= 90   => "باقي {$expiryDays} يوم",
                                default             => null,
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <a href="{{ route('sponsorship.profile', $employee) }}"
                                   class="flex items-center gap-3 group">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 flex-shrink-0 overflow-hidden">
                                        @if($user?->personal_image)
                                            <img src="{{ $user->personal_image }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-blue-600 font-semibold text-sm">
                                                {{ mb_substr($employee->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="font-medium text-gray-800 group-hover:text-blue-600 transition">{{ $employee->name }}</span>
                                </a>
                            </td>
                            <td class="px-4 py-3 text-gray-600 font-mono">{{ $user?->id_card ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600 font-mono text-sm">{{ $user?->contact_info['phone_number'] ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                {{ $employee->joining_date?->format('Y/m/d') ?? '—' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($serviceYears !== null)
                                    <span class="inline-flex items-center gap-1 text-gray-700">
                                        <span class="font-semibold">{{ $serviceYears }}</span>
                                        <span class="text-gray-400 text-xs">سنة</span>
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($ticket['type'] !== 'none')
                                    <div class="flex flex-col">
                                        <span class="{{ $ticket['type'] === 'full' ? 'text-green-600' : 'text-amber-600' }} font-medium text-xs">
                                            {{ $ticket['label'] }}
                                        </span>
                                        <span class="text-gray-500 text-xs">{{ number_format($ticket['amount'], 0) }} ر.س</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($employee->id_expiry_date)
                                    <div class="flex flex-col">
                                        <span class="{{ $expiryClass }} text-xs">{{ $employee->id_expiry_date->format('Y/m/d') }}</span>
                                        @if($expiryBadge)
                                            <span class="text-xs {{ $expiryDays < 0 ? 'text-red-500' : 'text-amber-500' }}">{{ $expiryBadge }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600 font-mono text-xs">
                                {{ $employee->passport_number ?? '—' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($employee->joining_date)
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-800">{{ (int)$leaveRemaining }} يوم</span>
                                        <span class="text-xs text-gray-400">من {{ $employee->getAnnualLeaveEntitlement() }} يوم/سنة</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $employee->project?->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('sponsorship.profile', $employee) }}"
                                   class="text-blue-500 hover:text-blue-700 transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-12 text-center text-gray-400">
                                <i class="fas fa-users text-3xl mb-3 block"></i>
                                لا يوجد موظفون مطابقون للبحث
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
