@extends('layouts.master')
@section('title', 'خصومات السلف')
@push('styles')
    <style>
          .table th {
            font-weight: 600;
            color: #374151;
            font-size: 13px;
        }

        .table td {
            font-weight: 600;
            color: #374151;
            font-size: 13px;
        }
    </style>
@endpush
@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-xl overflow-hidden mb-6 advance-shadow advance-gradient-bg">
            <div class="advance-header-gradient bg-teal-700 px-6 py-4 flex items-center justify-between text-white">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="p-3 rounded-full bg-teal-900 bg-opacity-80">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 14l2-2m0 0l2-2m-2 2l2 2m-2-2l-2 2m6 2a9 9 0 11-12 0 9 9 0 0112 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">خصومات السلف</h2>
                        <p class="opacity-90">عرض جميع الخصومات المسجلة</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <a href="{{ route('employees.advances.all') }}"
                        class="bg-teal-900 bg-opacity-80 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>العودة للسلف</span>
                    </a>

                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl overflow-hidden advance-shadow">
            <div class="overflow-x-auto">
                <table class="table min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">#
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الموظف</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المبلغ</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                تاريخ الخصم</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                السلفة</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($advancesDeductions as $index => $deduction)
                            <tr class="advance-row hover:bg-blue-50 transition-colors">
                                <td class="advance-cell px-6 py-4 text-center text-sm text-gray-500">{{ $index + 1 }}
                                </td>
                                <td class="advance-cell px-6 py-4 text-center text-sm text-gray-700">
                                    {{ $deduction->employee->user->name ?? '-' }}</td>
                                <td class="advance-cell px-6 py-4 text-center font-bold text-teal-700">
                                    {{ number_format($deduction->amount) }} ر.س</td>
                                <td class="advance-cell px-6 py-4 text-center">
                                    {{ $deduction->deducted_at->format('Y-m-d') }}</td>
                                <td class="advance-cell px-6 py-4 text-center">
                                    <span
                                        class="text-xs text-gray-700">{{ number_format($deduction->advance->amount ?? 0) }}
                                        ر.س</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="empty-state-advance">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 empty-icon-advance"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <h5 class="text-lg font-medium text-gray-700">لا توجد خصومات</h5>
                                        <p class="text-sm mt-1 text-gray-500">لم يتم تسجيل أي خصومات للسلف</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
