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
            <div class="bg-gray-50 hover:bg-blue-100 border-l-4 border-blue-500 rounded-lg px-6 py-4 flex items-center justify-between text-black transition-all duration-300">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-blue-100 p-3 rounded-full group-hover:bg-blue-200 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">سلف الموظف</h2>
                        <p class="text-gray-600">{{ $employee->user->name }}</p>
                    </div>
                    <form method="GET" action="{{ route('employees.advances', $employee->id) }}">
                        <select name="year" onchange="this.form.submit()"
                                class="bg-blue-100 hover:bg-blue-200 text-gray-800 px-3 py-1 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 transition-all text-sm">
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
                       class="bg-blue-100 hover:bg-blue-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>جميع السلف</span>
                    </a>
                    <a href="{{ route('employees.advances_deductions', $employee->id) }}"
                       class="bg-blue-100 hover:bg-blue-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v8m0 0H6m6 0h6M4 6h16M4 6v12a2 2 0 002 2h12a2 2 0 002-2V6M4 6l8 6 8-6" />
                        </svg>
                        <span>خصومات السلف</span>
                    </a>
                    <span class="bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ number_format($employee->advances->where('status', 'approved')->sum('amount')) }} ر.س إجمالي السلف
        </span>
                    <span class="bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ $employee->advances->count() }} طلبات
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
                            class="bg-gray-100 hover:bg-purple-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
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
                        <form method="GET" action="{{ route('employees.advances', $employee->id) }}" class="w-full max-w-md">
                            <div class="search-container px-4 py-2 flex items-center gap-3">
                                <button type="submit" class="text-blue-600 hover:blue-red-700 transition-colors">
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
                                    <a href="{{ route('employees.advances', $employee->id) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
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
                            مدير المشروع</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المشرف</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                منطقة العمل</th>
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
                                <td class="alert-cell px-6 py-4 whitespace-nowrap text-center">{{$advance->employee?->supervisor?->name}}</td>
                                <td class="alert-cell px-6 py-4 whitespace-nowrap text-center">{{$advance->employee?->work_area}}</td>
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
                                <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center">
                                        @if ($advance->approved_at)
                                            @php
                                                $diff = $advance->created_at->diff($advance->approved_at);
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
                {{ $advance->created_at->diffForHumans($advance->approved_at) }}
            </span>
                                        @else
                                            <span class="text-sm font-medium text-gray-900">-</span>
                                        @endif
                                    </div>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-12 text-center">
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
