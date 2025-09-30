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
        /* Increase Specific Styles */
        .increase-gradient-bg {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        }

        .increase-header-gradient {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
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
                    <a href="{{ route('employees.increases.all') }}"
                       class="bg-green-100 hover:bg-green-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-green-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>جميع الزيادات</span>
                    </a>
                    <button onclick="exportToPDF()" id="pdfExportBtn"
                            class=" px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-white">PDF</span>
                    </button>

                    <button onclick="exportToExcel()" id="excelExportBtn"
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
                            <tr class="hover:bg-gray-50 transition-colors">
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
                                <td class="increases-cell px-6 py-4 text-sm text-gray-500 text-center">
                                    <div class="mx-auto" style="max-width: 200px;">{{ $increase->employee->supervisor->name ?? 'لا يوجد' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 text-center max-w-xs">
                                    <div class="mx-auto truncate" style="max-width: 200px;"
                                        title="{{ $increase->reason }}">
                                        {{ $increase->reason ?? 'لا يوجد' }}
                                    </div>
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
                                <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
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
        </script>

@endpush
