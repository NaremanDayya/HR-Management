@extends('layouts.master')
@section('title', 'جدول زيادات الرواتب')

@push('styles')
    <style>
        /* Base Table Styles */
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

        * {
            font-size: 14px;
            font-weight: 700;
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
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .search-input {
            background: transparent;
            border: none;
            outline: none;
            width: 100%;
        }

        .search-input:focus {
            outline: none !important;
            box-shadow: none !important;
            border: none !important;
        }

        .focus\:outline-none:focus {
            outline: none !important;
        }

        .focus\:ring-0:focus {
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(0px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        }

        .search-input::placeholder {
            color: #9ca3af;
        }

        #liveSearch:focus {
            outline: none;
        }

        #clearSearch {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .hidden {
            display: none !important;
        }

        tbody tr {
            transition: all 0.3s ease;
        }

        /* Increase Specific Styles */
        .increase-gradient-bg {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        }

        .increase-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Summary Cards */
        .summary-card {
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .summary-card.static {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 4px solid #1976d2;
        }

        .summary-card.reward {
            background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
            border-left: 4px solid #ffa000;
        }

        .summary-card.total {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-left: 4px solid #388e3c;
        }

        .summary-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 50rem;
        }

        .approved-badge {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .pending-badge {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .rejected-badge {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        /* Filter Controls */
        .filter-controls {
            background: #f8f9fa;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .filter-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .filter-select {
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            padding: 0.5rem 1rem;
            width: 100%;
        }

        .employee-link {
            transition: all 0.2s ease;
        }

        .employee-link:hover {
            color: #059669 !important;
            text-decoration: underline;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .summary-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header Section -->
        <div class="rounded-xl overflow-hidden mb-6 increase-shadow increase-gradient-bg">
            <div class="bg-gray-50 hover:bg-green-100 border-l-4 border-green-500 rounded-lg px-6 py-4 flex items-center justify-between text-black transition-all duration-300">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-green-100 p-3 rounded-full group-hover:bg-green-200 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">زيادات راتب الموظف</h2>
                        <p class="text-gray-600">{{ $employee->user->name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <span class="bg-green-100 hover:bg-green-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
                        {{ number_format($employee->salary) }} ر.س الراتب الحالي
                    </span>
                    <span class="bg-green-100 hover:bg-green-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
                        {{ $increases->count() }} زيادات
                    </span>
                    <a href="{{ route('employees.increases.all') }}"
                       class="bg-green-100 hover:bg-green-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-green-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>جميع الزيادات</span>
                    </a>
                    <button id="pdfExportBtn"
                            class="px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-white">PDF</span>
                    </button>

                    <button id="excelExportBtn"
                            class="px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-white">Excel</span>
                    </button>
                    <button onclick="window.print()"
                            class="bg-gray-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                  clip-rule="evenodd" />
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
                        <div class="w-full max-w-md">
                            <div class="search-container px-4 py-2 flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input
                                    type="text"
                                    id="liveSearch"
                                    placeholder="ابحث في المبالغ أو السبب..."
                                    class="search-input text-sm text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-0 focus:border-none"
                                >
                                <button id="clearSearch" class="text-gray-400 hover:text-green-600 transition-colors hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="filter-controls mb-6">
            <form method="GET" action="{{ route('employees.increases', $employee->id) }}"
                  class="grid grid-cols-1 md:grid-cols-5 gap-4" id="increases-filter-form">
                <!-- Year Filter -->
                <div>
                    <label for="year" class="filter-label">السنة</label>
                    <select name="year" id="year" class="filter-select" onchange="this.form.submit()">
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
                </div>

                <!-- Type Filter -->
                <div>
                    <label for="type" class="filter-label">نوع الزيادة</label>
                    <select name="type" id="type" class="filter-select" onchange="this.form.submit()">
                        <option value="">الكل</option>
                        <option value="0" @selected(request('type') === '0')>زيادة ثابتة</option>
                        <option value="1" @selected(request('type') === '1')>مكافأة</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="filter-label">الحالة</label>
                    <select name="status" id="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">الكل</option>
                        <option value="approved" @selected(request('status') == 'approved')>معتمدة</option>
                        <option value="pending" @selected(request('status') == 'pending')>قيد الانتظار</option>
                        <option value="rejected" @selected(request('status') == 'rejected')>مرفوضة</option>
                    </select>
                </div>

                <!-- Search Filter -->
                <div>
                    <label for="search" class="filter-label">بحث</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="ابحث بالسبب أو المبلغ..." class="filter-select" onkeyup="debounceSubmit()">
                </div>

                <!-- Reset Button -->
                <div class="flex items-end">
                    <a href="{{ route('employees.increases', $employee->id) }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg w-full flex items-center justify-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        إعادة تعيين
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 summary-grid">
            <!-- Static Increases Card -->
            <div class="summary-card static">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-600">الزيادات الثابتة</h3>
                        <div class="summary-value text-blue-800">
                            {{ number_format($staticIncreasesTotal) }} ر.س
                        </div>
                        <p class="text-sm text-gray-600">{{ $staticIncreasesCount }} زيادة</p>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Reward Increases Card -->
            <div class="summary-card reward">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-600">المكافآت</h3>
                        <div class="summary-value text-amber-800">
                            {{ number_format($rewardIncreasesTotal) }} ر.س
                        </div>
                        <p class="text-sm text-gray-600">{{ $rewardIncreasesCount }} مكافأة</p>
                    </div>
                    <div class="p-3 rounded-full bg-amber-100 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Increases Card -->
            <div class="summary-card total">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-600">إجمالي الزيادات</h3>
                        <div class="summary-value text-green-800">
                            {{ number_format($totalIncreases) }} ر.س
                        </div>
                        <p class="text-sm text-gray-600">{{ $totalIncreasesCount }} زيادة + مكافأة</p>
                    </div>
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Increases Table -->
        <div class="bg-white rounded-xl overflow-hidden increase-shadow">
            <div class="overflow-x-auto">
                <table class="table min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">#
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            مبلغ الزيادة</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            نسبة الزيادة</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            نوع الزيادة</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            التغيير</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            مدير المشروع</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            المشروع</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            المشرف</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            السبب</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            تاريخ الطلب</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            تاريخ المعالجة</th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            مدة الاستجابة
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الحالة</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($increases as $index => $increase)
                        <tr class="hover:bg-green-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ $index + 1 }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center font-bold text-green-600">
                                +{{ number_format($increase->increase_amount) }} ر.س
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-15 h-8 rounded-full bg-green-100 text-green-800">
                                        {{ $increase->increase_percentage }}%
                                    </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if ($increase->is_reward)
                                    <span class="px-2 py-1 rounded-full text-xs bg-amber-100 text-amber-800">
                                            مكافأة
                                        </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                            زيادة ثابتة
                                        </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <div class="flex items-center justify-center space-x-2">
                                    <span>{{ number_format($increase->previous_salary) }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                         style="transform: scaleX(-1);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>

                                    <span class="font-bold text-green-600">
                                            {{ number_format($increase->new_salary) }}
                                        </span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center">
                                    @if ($increase->manager)
                                        <span>{{ $increase->manager->name }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center">
                                    @if ($increase->employee->project)
                                        <span>{{ $increase->employee->project->name }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="mx-auto" style="max-width: 200px;">{{ $increase->employee->supervisor->name ?? 'لا يوجد' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 text-center">
                                <div class="mx-auto" style="max-width: 200px;">{{ $increase->reason ?? 'لا يوجد' }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ $increase->created_at->format('Y-m-d') }}</span>
                                    <span
                                        class="text-xs text-gray-500">{{ $increase->created_at->locale('ar')->diffForHumans() }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    @if ($increase->approved_at)
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ $increase->approved_at->format('Y-m-d') }}</span>
                                        <span
                                            class="text-xs text-gray-500">{{ $increase->approved_at->locale('ar')->diffForHumans() }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    @if ($increase->approved_at)
                                        @php
                                            $diff = $increase->created_at->diff($increase->approved_at);
                                            $parts = [];
                                            if ($diff->d > 0) $parts[] = $diff->d . ' يوم';
                                            if ($diff->h > 0) $parts[] = $diff->h . ' ساعة';
                                            if ($diff->i > 0) $parts[] = $diff->i . ' دقيقة';
                                            $formattedDiff = implode(', ', $parts);
                                        @endphp

                                        <span class="text-sm font-medium text-gray-900">
                                                {{ $formattedDiff }}
                                            </span>
                                        <span class="text-xs text-gray-500">
                                                {{ $increase->created_at->diffForHumans($increase->approved_at) }}
                                            </span>
                                    @else
                                        <span class="text-sm font-medium text-gray-900">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if ($increase->status == 'approved')
                                    <span class="status-badge approved-badge">
                                            <i class="fas fa-check-circle ml-1"></i> معتمدة
                                        </span>
                                @elseif($increase->status == 'rejected')
                                    <span class="status-badge rejected-badge">
                                            <i class="fas fa-times-circle ml-1"></i> مرفوضة
                                        </span>
                                @else
                                    <span class="status-badge pending-badge">
                                            <i class="fas fa-clock ml-1"></i> قيد الانتظار
                                        </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                                    </svg>
                                    <p class="text-lg">لا يوجد زيادات</p>
                                    <p class="text-sm mt-1">لم يتم العثور على أي زيادة تطابق معايير البحث</p>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Live Search Variables
            const liveSearch = document.getElementById('liveSearch');
            const clearSearch = document.getElementById('clearSearch');
            const tableRows = document.querySelectorAll('tbody tr');

            // Live search functionality
            liveSearch.addEventListener('input', function() {
                const searchTerm = this.value.trim().toLowerCase();

                // Show/hide clear button
                if (searchTerm.length > 0) {
                    clearSearch.classList.remove('hidden');
                } else {
                    clearSearch.classList.add('hidden');
                }

                // Filter table rows
                tableRows.forEach(row => {
                    // Skip the empty state row
                    if (row.cells.length === 1) return;

                    if (searchTerm === '') {
                        row.style.display = '';
                        return;
                    }

                    // Get amount (2nd column) and reason (9th column)
                    const amountCell = row.cells[1];
                    const reasonCell = row.cells[8];

                    const amountText = amountCell?.textContent?.trim().toLowerCase() || '';
                    const reasonText = reasonCell?.textContent?.trim().toLowerCase() || '';

                    // Check if search term matches amount OR reason
                    const matchesAmount = amountText.includes(searchTerm);
                    const matchesReason = reasonText.includes(searchTerm);

                    // Show row if either condition is true
                    if (matchesAmount || matchesReason) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update empty state message
                updateEmptyState();
            });

            // Clear search functionality
            clearSearch.addEventListener('click', function() {
                liveSearch.value = '';
                liveSearch.focus();
                this.classList.add('hidden');

                // Show all rows
                tableRows.forEach(row => {
                    if (row.cells.length === 1) return; // Skip empty state row
                    row.style.display = '';
                });

                updateEmptyState();
            });

            // Function to update empty state message
            function updateEmptyState() {
                const visibleRows = Array.from(tableRows).filter(row => {
                    // Skip the empty state row in the count
                    if (row.cells.length === 1) return false;
                    return row.style.display !== 'none';
                });

                const emptyStateRow = document.querySelector('tbody tr:last-child');

                // Check if the last row is the empty state row (has only 1 cell)
                const isEmptyState = emptyStateRow && emptyStateRow.cells.length === 1;

                if (isEmptyState) {
                    if (visibleRows.length > 0) {
                        emptyStateRow.style.display = 'none';
                    } else {
                        emptyStateRow.style.display = '';
                    }
                }
            }

            // Export to Excel function with proper cell formatting
            function exportToExcel() {
                if (typeof XLSX === 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'مكتبة Excel غير محملة',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                const visibleRows = Array.from(tableRows).filter(row => {
                    if (row.cells.length === 1) return false; // Skip empty state row
                    return row.style.display !== 'none';
                });

                if (visibleRows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'لا توجد بيانات',
                        text: 'لا توجد صفوف مرئية للتصدير',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                try {
                    const table = document.querySelector('table.table');
                    if (!table) throw new Error('Table not found');

                    const tableClone = table.cloneNode(true);

                    // Remove empty state row from export
                    const emptyStateRow = tableClone.querySelector('tbody tr:last-child');
                    if (emptyStateRow && emptyStateRow.cells.length === 1) {
                        emptyStateRow.remove();
                    }

                    // Prepare data with proper cell formatting
                    const headers = Array.from(tableClone.querySelectorAll('thead th'))
                        .map(th => th.textContent.trim());

                    const data = [
                        headers,
                        ...Array.from(tableClone.querySelectorAll('tbody tr')).map(row => {
                            return Array.from(row.querySelectorAll('td')).map(td => {
                                // Get clean text content without HTML
                                let text = td.textContent.trim();

                                // Handle currency formatting
                                if (text.includes('ر.س')) {
                                    text = text.replace('ر.س', '').trim();
                                }

                                // Remove any extra spaces and normalize text
                                text = text.replace(/\s+/g, ' ').trim();

                                return text;
                            });
                        })
                    ];

                    // Create workbook with proper column widths
                    const wb = XLSX.utils.book_new();
                    const ws = XLSX.utils.aoa_to_sheet(data);

                    // Set column widths for better readability
                    const colWidths = headers.map((_, index) => {
                        // Set different widths based on column content
                        if (index === 0) return { wch: 5 };  // #
                        if (index === 1) return { wch: 15 }; // مبلغ الزيادة
                        if (index === 2) return { wch: 12 }; // نسبة الزيادة
                        if (index === 3) return { wch: 12 }; // نوع الزيادة
                        if (index === 4) return { wch: 20 }; // التغيير
                        if (index === 5) return { wch: 15 }; // مدير المشروع
                        if (index === 6) return { wch: 15 }; // المشروع
                        if (index === 7) return { wch: 15 }; // المشرف
                        if (index === 8) return { wch: 25 }; // السبب
                        if (index === 9) return { wch: 12 }; // تاريخ الطلب
                        if (index === 10) return { wch: 12 }; // تاريخ المعالجة
                        if (index === 11) return { wch: 15 }; // مدة الاستجابة
                        if (index === 12) return { wch: 12 }; // الحالة
                        return { wch: 15 }; // Default width
                    });

                    ws['!cols'] = colWidths;

                    // Add auto filter
                    ws['!autofilter'] = { ref: XLSX.utils.encode_range({
                            s: { r: 0, c: 0 },
                            e: { r: data.length, c: headers.length - 1 }
                        }) };

                    XLSX.utils.book_append_sheet(wb, ws, "زيادات الراتب");
                    XLSX.writeFile(wb, `زيادات_الراتب_${"{{ $employee->user->name }}"}_${new Date().toISOString().slice(0, 10)}.xlsx`);

                } catch (error) {
                    console.error('Excel export error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: `حدث خطأ أثناء تصدير ملف Excel: ${error.message}`,
                        confirmButtonText: 'حسناً'
                    });
                }
            }

            // Export to PDF function with proper Arabic text handling
            async function exportToPDF() {
                const visibleRows = Array.from(tableRows).filter(row => {
                    if (row.cells.length === 1) return false; // Skip empty state row
                    return row.style.display !== 'none';
                });

                if (visibleRows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'لا توجد بيانات',
                        text: 'لا توجد صفوف مرئية للتصدير',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                if (typeof html2pdf === 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'مكتبة PDF غير محملة',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                try {
                    const table = document.querySelector('table.table');
                    if (!table) throw new Error('Table not found');

                    const tableClone = table.cloneNode(true);

                    // Remove empty state row from PDF
                    const emptyStateRow = tableClone.querySelector('tbody tr:last-child');
                    if (emptyStateRow && emptyStateRow.cells.length === 1) {
                        emptyStateRow.remove();
                    }

                    const pdfContainer = document.createElement('div');
                    pdfContainer.style.padding = '20px';
                    pdfContainer.style.direction = 'rtl';
                    pdfContainer.style.fontFamily = "'Segoe UI', Tahoma, Arial, sans-serif";
                    pdfContainer.style.textAlign = 'center';
                    pdfContainer.style.lineHeight = '1.6';

                    // Create header with proper Arabic text
                    const header = document.createElement('div');
                    header.style.marginBottom = '20px';
                    header.style.borderBottom = '2px solid #10b981';
                    header.style.paddingBottom = '15px';
                    header.style.textAlign = 'center';

                    const companyName = document.createElement('h1');
                    companyName.textContent = 'شركة افاق الخليج';
                    companyName.style.color = '#10b981';
                    companyName.style.margin = '0 0 10px 0';
                    companyName.style.fontSize = '24px';
                    companyName.style.fontWeight = 'bold';
                    companyName.style.letterSpacing = 'normal';
                    companyName.style.wordSpacing = 'normal';
                    companyName.style.fontFamily = "'Segoe UI', Tahoma, sans-serif";

                    const title = document.createElement('h2');
                    title.textContent = 'تقرير زيادات الراتب - ' + "{{ $employee->user->name }}";
                    title.style.color = '#333';
                    title.style.margin = '0 0 10px 0';
                    title.style.fontSize = '18px';
                    title.style.fontWeight = '600';
                    title.style.letterSpacing = 'normal';
                    title.style.wordSpacing = 'normal';
                    title.style.fontFamily = "'Segoe UI', Tahoma, sans-serif";

                    const reportDate = document.createElement('p');
                    reportDate.textContent = 'تاريخ التقرير: ' + new Date().toLocaleDateString('ar-EG');
                    reportDate.style.color = '#666';
                    reportDate.style.margin = '0';
                    reportDate.style.fontSize = '14px';
                    reportDate.style.letterSpacing = 'normal';
                    reportDate.style.wordSpacing = 'normal';
                    reportDate.style.fontFamily = "'Segoe UI', Tahoma, sans-serif";

                    header.appendChild(companyName);
                    header.appendChild(title);
                    header.appendChild(reportDate);
                    pdfContainer.appendChild(header);

                    // Style table for PDF with proper text handling
                    tableClone.style.width = '100%';
                    tableClone.style.borderCollapse = 'collapse';
                    tableClone.style.marginTop = '20px';
                    tableClone.style.direction = 'rtl';
                    tableClone.style.fontSize = '7px';
                    tableClone.style.fontFamily = "'Segoe UI', Tahoma, Arial, sans-serif";

                    // Style table headers
                    tableClone.querySelectorAll('th').forEach(th => {
                        th.style.backgroundColor = '#10b981';
                        th.style.color = 'white';
                        th.style.padding = '6px 3px';
                        th.style.border = '1px solid #ddd';
                        th.style.textAlign = 'center';
                        th.style.fontWeight = 'bold';
                        th.style.fontSize = '8px';
                        th.style.letterSpacing = 'normal';
                        th.style.wordSpacing = 'normal';
                        th.style.whiteSpace = 'nowrap';
                        th.style.fontFamily = "'Segoe UI', Tahoma, sans-serif";
                    });

                    // Style table cells
                    tableClone.querySelectorAll('td').forEach(td => {
                        td.style.padding = '5px 3px';
                        td.style.border = '1px solid #ddd';
                        td.style.textAlign = 'center';
                        td.style.fontSize = '7px';
                        td.style.letterSpacing = 'normal';
                        td.style.wordSpacing = 'normal';
                        td.style.whiteSpace = 'normal';
                        td.style.wordBreak = 'break-word';
                        td.style.fontFamily = "'Segoe UI', Tahoma, sans-serif";
                    });

                    pdfContainer.appendChild(tableClone);

                    // Add footer
                    const footer = document.createElement('div');
                    footer.style.marginTop = '20px';
                    footer.style.paddingTop = '10px';
                    footer.style.borderTop = '1px solid #eee';
                    footer.style.textAlign = 'center';
                    footer.style.color = '#666';
                    footer.style.fontSize = '10px';

                    const copyright = document.createElement('p');
                    copyright.textContent = `© ${new Date().getFullYear()} جميع الحقوق محفوظة لشركة افاق الخليج`;
                    copyright.style.margin = '5px 0';
                    copyright.style.letterSpacing = 'normal';
                    copyright.style.wordSpacing = 'normal';
                    copyright.style.fontFamily = "'Segoe UI', Tahoma, sans-serif";
                    footer.appendChild(copyright);
                    pdfContainer.appendChild(footer);

                    // PDF options with better Arabic text handling
                    const options = {
                        margin: [10, 10, 15, 10],
                        filename: `زيادات_الراتب_${"{{ $employee->user->name }}"}_${new Date().toISOString().slice(0, 10)}.pdf`,
                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 2,
                            useCORS: true,
                            letterRendering: true,
                            onclone: function(clonedDoc) {
                                clonedDoc.documentElement.dir = 'rtl';
                                clonedDoc.body.style.direction = 'rtl';
                                clonedDoc.body.style.textAlign = 'right';
                                clonedDoc.body.style.fontFamily = "'Segoe UI', Tahoma, Arial, sans-serif";

                                // Additional Arabic text enhancements
                                clonedDoc.querySelectorAll('*').forEach(element => {
                                    element.style.fontFamily = "'Segoe UI', Tahoma, Arial, sans-serif";
                                    element.style.direction = element.style.direction || 'rtl';
                                });
                            }
                        },
                        jsPDF: {
                            unit: 'mm',
                            format: 'a3',
                            orientation: 'landscape'
                        }
                    };

                    // Show loading
                    const btn = document.getElementById('pdfExportBtn');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
                    btn.disabled = true;

                    await html2pdf().set(options).from(pdfContainer).save();

                    // Restore button
                    btn.innerHTML = originalText;
                    btn.disabled = false;

                } catch (error) {
                    console.error('PDF export error:', error);
                    // Restore button in case of error
                    const btn = document.getElementById('pdfExportBtn');
                    btn.innerHTML = '<i class="fas fa-file-pdf"></i> PDF';
                    btn.disabled = false;

                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: `حدث خطأ أثناء تصدير ملف PDF: ${error.message}`,
                        confirmButtonText: 'حسناً'
                    });
                }
            }

            // Add event listeners with loading states
            document.getElementById('excelExportBtn')?.addEventListener('click', function() {
                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
                btn.disabled = true;

                setTimeout(() => {
                    exportToExcel();
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 100);
            });

            document.getElementById('pdfExportBtn')?.addEventListener('click', exportToPDF);

            // Keep the existing filter functions
            function debounceSubmit() {
                clearTimeout(window.debounceTimeout);
                window.debounceTimeout = setTimeout(() => {
                    document.getElementById('increases-filter-form').submit();
                }, 500);
            }

            document.querySelectorAll('.filter-select').forEach(select => {
                select.style.appearance = 'none';
                select.style.backgroundImage = 'none';
                select.style.paddingRight = '1rem';
            });
        });
    </script>
@endpush
