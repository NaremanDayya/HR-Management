@extends('layouts.master')
@section('title', 'جدول السلف')

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

        .advance-gradient-bg {
            background: linear-gradient(135deg, #e6f7ff 0%, #b3e0ff 100%);
        }

        .advance-header-gradient {
            background: linear-gradient(135deg, #17a2b8 0%, #0d6e7e 100%);
        }

        .advance-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .advance-hover-effect:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .advance-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .advance-cell {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .advance-row:hover .advance-cell {
            background-color: #f0f9ff;
        }

        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .amount-cell {
            font-weight: 700;
            color: #0d6e7e;
        }

        .percentage-badge {
            background: rgba(23, 162, 184, 0.1);
            color: #0d6e7e;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
        }

        .empty-state-advance {
            padding: 3rem;
            text-align: center;
            background: #f8fafc;
            border-radius: 0 0 12px 12px;
        }

        .empty-icon-advance {
            font-size: 3.5rem;
            color: #cfe8ef;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .table-responsive {
                border: 0;
            }

            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 1.5rem;
                border-radius: 0.75rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }

            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem;
                border-bottom: 1px solid #edf2f7;
            }

            .table tbody td:before {
                content: attr(data-label);
                font-weight: 600;
                color: #4a5568;
                margin-left: 0.5rem;
            }

            .table tbody td:last-child {
                border-bottom: 0;
            }

            .empty-state-advance {
                padding: 2rem 1rem;
            }
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
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">سلف الموظف</h2>
                        <p class="opacity-90">{{ $employee->user->name }}</p>
                    </div>
                    <form method="GET" action="{{ route('employees.advances', $employee->id) }}">
                        <select name="year" onchange="this.form.submit()"
                            class="bg-teal-800 text-white px-3 py-1 rounded-lg focus:outline-none focus:ring focus:ring-teal-500 text-sm">
                            @php
                                $currentYear = request('year', now()->year);
                                $startYear = now()->year - 10;
                                $endYear = now()->year + 1;
                            @endphp
                            @for ($year = $endYear; $year >= $startYear; $year--)
                                <option value="{{ $year }}" @selected($year == $currentYear)>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </form>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <a href="{{ route('employees.advances.all') }}"
                        class="bg-teal-900 bg-opacity-80 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>جميع السلف</span>
                    </a>
                    <a href="{{ route('employees.advances_deductions', $employee->id) }}"
                        class="bg-teal-900 bg-opacity-80 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v8m0 0H6m6 0h6M4 6h16M4 6v12a2 2 0 002 2h12a2 2 0 002-2V6M4 6l8 6 8-6" />
                        </svg>
                        <span>خصومات السلف</span>
                    </a>
                    <span class="advance-badge px-3 py-1 rounded-full text-sm font-medium">
                        {{ number_format($employee->advances->where('status', 'approved')->sum('amount')) }} ر.س إجمالي
                        السلف
                    </span>

                    <span class="advance-badge px-3 py-1 rounded-full text-sm font-medium">
                        {{ $employee->advances->count() }} طلبات
                    </span>

                </div>
            </div>
        </div>

        <!-- Advances Table -->
        <div class="bg-white rounded-xl overflow-hidden advance-shadow">
            <div class="overflow-x-auto">
                <table class="table min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">#
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المبلغ</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                النسبة من الراتب</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المسؤول</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                السبب</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                تاريخ الطلب</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                تاريخ المعالجة</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($employee->advances as $index => $advance)
                            <tr class="advance-row hover:bg-blue-50 transition-colors">
                                <td class="advance-cell px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    {{ $index + 1 }}
                                </td>

                                <td class="advance-cell px-6 py-4 whitespace-nowrap text-center amount-cell">
                                    {{ number_format($advance->amount) }} ر.س
                                </td>

                                <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                    <span class="percentage-badge">
                                        {{ round(($advance->amount / $employee->salary) * 100, 2) }}%
                                    </span>
                                </td>

                                <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                    @if ($advance->status == 'approved')
                                        <span class="status-badge bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle ml-1"></i> معتمدة
                                        </span>
                                    @elseif($advance->status == 'rejected')
                                        <span class="status-badge bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle ml-1"></i> مرفوضة
                                        </span>
                                    @else
                                        <span class="status-badge bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock ml-1"></i> قيد الانتظار
                                        </span>
                                    @endif
                                </td>

                                <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                    @if ($advance->manager)
                                        <div class="flex flex-col items-center space-y-1">
                                            {{-- <div
                                                class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div> --}}
                                            <span class="text-sm text-gray-700">{{ $advance->manager->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="advance-cell px-6 py-4 text-sm text-gray-500 max-w-xs text-center">
                                    <div class="mx-auto" style="max-width: 200px;">{{ $advance->reason ?? '-' }}</div>
                                </td>

                                <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center">
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ $advance->created_at->format('Y-m-d') }}</span>
                                        <span
                                            class="text-xs text-gray-500">{{ $advance->created_at->locale('ar')->diffForHumans() }}</span>
                                    </div>
                                </td>

                                <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $advance->approved_at ? $advance->approved_at->format('Y-m-d') : '-' }}
                                        </span>
                                        @if ($advance->approved_at)
                                            <span
                                                class="text-xs text-gray-500">{{ $advance->approved_at->locale('ar')->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="empty-state-advance">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 empty-icon-advance"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <h5 class="text-lg font-medium text-gray-700">لا يوجد سلف</h5>
                                        <p class="text-sm mt-1 text-gray-500">لم يتم تقديم أي طلبات سلف لهذا الموظف</p>
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
