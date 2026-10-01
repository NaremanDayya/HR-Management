@extends('layouts.master')

@section('title', 'إدارة الإجازات')

@section('content')
<div class="container-fluid py-6 px-4" dir="rtl">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">سجل الإجازات</h1>
            <p class="text-sm text-gray-500 mt-1">متابعة جميع طلبات الإجازات وأرصدة الموظفين</p>
        </div>
        <a href="{{ route('sponsorship.index') }}"
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 text-sm transition">
            <i class="fas fa-arrow-right"></i>
            العودة للكفالة
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <select name="employee_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">كل الموظفين</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" @selected(request('employee_id') == $emp->id)>{{ $emp->name }}</option>
                @endforeach
            </select>
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">كل الحالات</option>
                <option value="approved" @selected(request('status')=='approved')>موافق</option>
                <option value="pending"  @selected(request('status')=='pending')>معلقة</option>
                <option value="rejected" @selected(request('status')=='rejected')>مرفوضة</option>
            </select>
            <select name="leave_type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">كل الأنواع</option>
                <option value="annual"    @selected(request('leave_type')=='annual')>سنوية</option>
                <option value="sick"      @selected(request('leave_type')=='sick')>مرضية</option>
                <option value="emergency" @selected(request('leave_type')=='emergency')>طارئة</option>
                <option value="maternity" @selected(request('leave_type')=='maternity')>أمومة</option>
                <option value="unpaid"    @selected(request('leave_type')=='unpaid')>بدون راتب</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                    <i class="fas fa-filter ml-1"></i> فلترة
                </button>
                <a href="{{ route('leaves.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm transition">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
    </form>

    {{-- Add Leave form --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6" x-data="{ open: false }">
        <button @click="open = !open"
                class="w-full flex items-center justify-between px-5 py-4 text-sm font-medium text-gray-700 hover:bg-gray-50 transition rounded-xl">
            <span class="flex items-center gap-2"><i class="fas fa-plus-circle text-blue-500"></i> تسجيل إجازة جديدة</span>
            <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="text-gray-400 text-xs"></i>
        </button>
        <form x-show="open" x-transition @submit.prevent="submitNewLeave()" class="px-5 pb-5 grid grid-cols-2 md:grid-cols-3 gap-3 border-t border-gray-100 pt-4">
            @csrf
            <div>
                <label class="block text-xs text-gray-600 mb-1">الموظف <span class="text-red-500">*</span></label>
                <select name="employee_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">اختر...</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">نوع الإجازة</label>
                <select name="leave_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
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
                <label class="block text-xs text-gray-600 mb-1">تاريخ البداية <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">تاريخ النهاية <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">ملاحظات</label>
                <input type="text" name="notes" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="اختياري">
            </div>
            <div class="col-span-2 md:col-span-3 flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-save ml-1"></i> حفظ
                </button>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 font-medium text-gray-600">الموظف</th>
                        <th class="px-4 py-3 font-medium text-gray-600">نوع الإجازة</th>
                        <th class="px-4 py-3 font-medium text-gray-600">من</th>
                        <th class="px-4 py-3 font-medium text-gray-600">إلى</th>
                        <th class="px-4 py-3 font-medium text-gray-600">الأيام</th>
                        <th class="px-4 py-3 font-medium text-gray-600">الحالة</th>
                        <th class="px-4 py-3 font-medium text-gray-600">الملاحظات</th>
                        <th class="px-4 py-3 font-medium text-gray-600">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($leaveRequests as $leave)
                        @php
                            $statusClasses = [
                                'approved' => 'bg-green-100 text-green-700',
                                'pending'  => 'bg-yellow-100 text-yellow-700',
                                'rejected' => 'bg-red-100 text-red-600',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50 transition" id="leave-row-{{ $leave->id }}">
                            <td class="px-4 py-3">
                                <a href="{{ route('sponsorship.profile', $leave->employee_id) }}"
                                   class="font-medium text-gray-800 hover:text-blue-600 transition">
                                    {{ $leave->employee->name ?? '—' }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $leave->getLeaveTypeLabel() }}</td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $leave->start_date->format('Y/m/d') }}</td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $leave->end_date->format('Y/m/d') }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ $leave->days_count }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClasses[$leave->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $leave->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 max-w-[200px] truncate">{{ $leave->notes ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    @if($leave->status === 'pending')
                                        <button onclick="reviewLeave({{ $leave->id }}, 'approved')"
                                                class="text-green-600 hover:text-green-800 text-xs font-medium transition">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="reviewLeave({{ $leave->id }}, 'rejected')"
                                                class="text-red-500 hover:text-red-700 text-xs font-medium transition">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    <button onclick="deleteLeave({{ $leave->id }})"
                                            class="text-gray-400 hover:text-red-500 text-xs transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                                <i class="fas fa-calendar-times text-3xl mb-3 block"></i>
                                لا يوجد سجل إجازات
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leaveRequests->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $leaveRequests->links() }}
            </div>
        @endif
    </div>

</div>

<script>
function submitNewLeave() {
    const form = event.target;
    const data = new FormData(form);
    fetch('{{ route('leaves.store') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: data,
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { location.reload(); }
        else { alert('حدث خطأ'); }
    });
}

function reviewLeave(id, status) {
    fetch(`/leaves/${id}`, {
        method: 'PUT',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ status }),
    })
    .then(r => r.json())
    .then(res => { if (res.success) location.reload(); });
}

function deleteLeave(id) {
    if (!confirm('هل تريد حذف هذه الإجازة؟')) return;
    fetch(`/leaves/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(res => { if (res.success) document.getElementById(`leave-row-${id}`).remove(); });
}
</script>
@endsection
