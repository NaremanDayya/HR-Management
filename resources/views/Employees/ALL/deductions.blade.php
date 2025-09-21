@extends('layouts.master')
@section('title', 'جدول الخصومات لجميع الموظفين')

@push('styles')
    <style>
        .table th {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .table td {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .deductions-gradient-bg {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
        }

        /* .deductions-header-gradient {
                background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
            } */

        .deductions-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .deductions-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .deductions-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .deductions-cell {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .deductions-row:hover .deductions-cell {
            background-color: #fff5f7;
        }

        .salary-cell {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        .deduction-value {
            font-weight: 700;
            color: #d32f2f;
        }

        .percentage-badge {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            min-width: 60px;
        }

        .employee-link {
            transition: all 0.2s ease;
        }

        .employee-link:hover {
            color: #d81b60 !important;
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-xl overflow-hidden mb-6 deductions-shadow deductions-gradient-bg">
            <div class="deductions-header-gradient bg-red-800 px-6 py-4 flex items-center justify-between text-white">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="p-3 rounded-full bg-red-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">خصومات الموظفين</h2>
                        <p class="opacity-90">إجمالي الخصومات: {{ number_format($deductions->sum('value')) }} ر.س</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <span class="deductions-badge px-3 py-1 rounded-full text-sm font-medium">{{ $deductions->count() }}
                        خصم</span>
                    <button onclick="window.print()"
                            class="bg-red-900 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <span>طباعة</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Deductions Table -->
        <div class="bg-white rounded-xl overflow-hidden deductions-shadow">
            <div class="overflow-x-auto">
                <table class="table min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">#
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الموظف
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            المشروع
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            مدير المشروع
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قيمة
                            الخصم
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الراتب قبل
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الراتب بعد
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            النسبة
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            التاريخ
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($deductions as $index => $deduction)
                        <tr class="deductions-row hover:bg-red-50 transition-colors">
                            <td class="deductions-cell px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ $index + 1 }}
                            </td>

                            <td class="deductions-cell px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center space-y-1">

                                    <a href="{{ route('employees.advances', $deduction->employee->id) }}"
                                       class=" hover:underline">
                                        {{ $deduction->employee->user->name ?? '-' }}
                                    </a>
                                </div>
                            </td>

                            <td class="deductions-cell px-6 py-4 whitespace-nowrap text-center">
                                @if ($deduction->employee->project)
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            {{ $deduction->employee->project->name }}
                                        </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="deductions-cell px-6 py-4 whitespace-nowrap text-center">
                                @if ($deduction->employee->project && $deduction->employee->project->manager)
                                    <div class="flex flex-col items-center space-y-1">

                                            <span
                                                class="text-sm text-gray-700">{{ $deduction->employee->project->manager->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td
                                class="deductions-cell px-6 py-4 whitespace-nowrap text-right deduction-value salary-cell">
                                {{ number_format($deduction->value) }} ر.س
                            </td>

                            <td
                                class="deductions-cell px-6 py-4 whitespace-nowrap text-right text-gray-500 salary-cell">
                                {{ number_format($deduction->payload['current_salary'] ?? 0) }} ر.س
                            </td>

                            <td
                                class="deductions-cell px-6 py-4 whitespace-nowrap text-right text-gray-500 salary-cell">
                                {{ number_format($deduction->payload['new_salary'] ?? 0) }} ر.س
                            </td>

                            <td class="deductions-cell px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="percentage-badge inline-flex justify-center items-center rounded-full bg-red-100 text-red-800">
                                        {{ $deduction->payload['deduction_percentage'] ?? 0 }}%
                                    </span>
                            </td>

                            <td class="deductions-cell px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ $deduction->created_at->format('Y-m-d') }}</span>
                                    <span
                                        class="text-xs text-gray-500">{{ $deduction->created_at->locale('ar')->diffForHumans() }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-lg">لا يوجد خصومات</p>
                                    <p class="text-sm mt-1">لم يتم تسجيل أي خصومات حتى الآن</p>
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
