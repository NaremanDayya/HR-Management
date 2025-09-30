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
        #pdfExportBtn {
            background-color: #e74c3c;
        }

        #pdfExportBtn:hover {
            background-color: #c0392b;
        }

        #excelExportBtn {
            background-color: #2ecc71;
        }

        #excelExportBtn:hover {
            background-color: #27ae60;
        }
        .search-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .search-container:focus-within {
            border-color:mediumpurple;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .search-input {
            background: transparent;
            border: none;
            outline: none;
            width: 100%;
        }

        .search-input::placeholder {
            color: #9ca3af;
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
            <div class="bg-gray-50 hover:bg-red-100 border-l-4 border-red-500 rounded-lg px-6 py-4 flex items-center justify-between text-black transition-all duration-300">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-red-100 p-3 rounded-full group-hover:bg-red-200 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">خصومات الموظفين</h2>
                        <p class="text-gray-600">إجمالي الخصومات: {{ number_format($deductions->sum('value')) }} ر.س</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
        <span class="bg-red-100 hover:bg-red-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ $deductions->count() }} خصم
        </span>
                    <button onclick="exportToPDF()" id="pdfExportBtn"
                            class="  px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-white">PDF</span>
                    </button>

                    <!-- Export Excel Button -->
                    <button onclick="exportToExcel()" id="excelExportBtn"
                            class=" px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-white">Excel</span>
                    </button>

                    <button onclick="window.print()"
                            class="bg-red-100 hover:bg-red-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-red-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <span>طباعة</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl overflow-hidden alert-shadow mb-4 table-header-bg">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Search Bar - Right Side -->
                    <div class="flex-1 flex justify-end">
                        <form method="GET" action="{{ route('employees.deductions.all') }}" class="w-full max-w-md">
                            <div class="search-container px-4 py-2 flex items-center gap-3">
                                <button type="submit" class="text-red-600 hover:text-red-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="ابحث في الإنذارات..."
                                    class="search-input text-sm text-gray-700 placeholder-gray-500"
                                >
                                @if(request('year'))
                                    <input type="hidden" name="year" value="{{ request('year') }}">
                                @endif
                                @if(request('search'))
                                    <a href="{{ route('employees.deductions.all') }}" class="text-gray-400 hover:text-red-600 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
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

                                    <a href="{{ route('employees.deductions', $deduction->employee->id) }}"
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
