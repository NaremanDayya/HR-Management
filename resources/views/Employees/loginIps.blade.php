@extends('layouts.master')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-800">📡 إدارة أجهزة تسجيل الدخول</h1>

            <form method="GET" class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 اسم الموظف"
                    class="border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200 w-full md:w-64" />

                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">تصفية</button>

                @if (request('search'))
                    <a href="{{ route('admin.employee-ips.index') }}"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition">إعادة تعيين</a>
                @endif
            </form>


        </div>

        @forelse ($employees as $employee)
            <div class="mb-10 bg-white shadow rounded-lg p-6 border">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">{{ $employee->user->name ?? 'اسم غير متوفر' }}</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 rounded">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="p-3 border">IP الجهاز</th>
                                <th class="p-3 border">مسموح</th>
                                <th class="p-3 border">مؤقت</th>
                                <th class="p-3 border">انتهاء الصلاحية</th>
                                <th class="p-3 border">محظور</th>
                                <th class="p-3 border">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employee->loginIps as $ip)
                                @php
                                    $filterType = request('ip_type');
                                    $isTemporary = $ip->is_temporary;
                                    $show =
                                        !$filterType ||
                                        ($filterType === 'temporary' && $isTemporary) ||
                                        ($filterType === 'main' && !$isTemporary);
                                @endphp

                                @if ($show)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-3 border">{{ $ip->ip_address }}</td>
                                        <td class="p-3 border">{{ $ip->is_allowed ? 'نعم' : 'لا' }}</td>
                                        <td class="p-3 border">{{ $ip->is_temporary ? 'نعم' : 'لا' }}</td>
                                        <td class="p-3 border">
                                            {{ $ip->allowed_until ? $ip->allowed_until->format('Y-m-d H:i') : '-' }}</td>
                                        <td class="p-3 border">
                                            {{ $ip->blocked_at ? $ip->blocked_at->format('Y-m-d H:i') : '-' }}</td>
                                        <td class="p-3 border">
                                            @if ($ip->is_allowed)
                                                <form method="POST" action="{{ route('admin.employee-ips.block', $ip) }}">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:underline">حظر</button>
                                                </form>
                                            @else
                                                <form method="POST"
                                                    action="{{ route('admin.employee-ips.unblock', $ip) }}">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:underline">إلغاء
                                                        الحظر</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center p-4 text-gray-500">لا توجد أجهزة مسجلة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <form method="POST" action="{{ route('admin.employee-ips.add-temp-ip', $employee) }}"
                    class="flex flex-col sm:flex-row gap-3 mt-4">
                    @csrf
                    <input type="text" name="ip_address" placeholder="أدخل IP جديد"
                        class="border p-2 rounded w-full sm:w-48" required>
                    <input type="text" id="allowed_until" name="allowed_until"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-right rtl" placeholder="صالح لغاية"
                        required>
                    <button type="submit" class="bg-blue-600 text-white rounded px-4 py-2 hover:bg-blue-700">إضافة IP
                        مؤقت</button>
                </form>
            </div>
        @empty
            <p class="text-center text-gray-600">لا يوجد موظفون مطابقون للبحث.</p>
        @endforelse
    </div>
@push('scripts')
<script>
    flatpickr("#allowed_until", {
    locale: "ar",
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    altInput: true,
    altFormat: "F j, Y - H:i",
    allowInput: true,
    defaultHour: 12,
});
</script>
@endpush
@endsection
