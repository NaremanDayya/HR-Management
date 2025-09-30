@extends('layouts.master')
@section('title', 'جدول الخصومات')

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
        .deduction-gradient-bg {
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
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
/*
        .deduction-header-gradient {
            background: linear-gradient(135deg, #f5365c 0%, #c53030 100%);
        } */

        .deduction-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .deduction-hover-effect:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .deduction-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .deduction-cell {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .deduction-row:hover .deduction-cell {
            background-color: #fff5f5;
        }

        .deduction-amount {
            font-weight: 700;
            color: #c53030;
        }

        .percentage-badge-danger {
            background: rgba(245, 54, 92, 0.1);
            color: #c53030;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
        }

        .salary-before {
            color: #4a5568;
            text-decoration: line-through;
        }

        .salary-after {
            color: #2d3748;
            font-weight: 600;
        }

        .empty-state-deduction {
            padding: 3rem;
            text-align: center;
            background: #f8fafc;
            border-radius: 0 0 12px 12px;
        }

        .empty-icon-deduction {
            font-size: 3.5rem;
            color: #fed7d7;
            margin-bottom: 1.5rem;
        }

        .project-badge {
            background: rgba(66, 153, 225, 0.1);
            color: #2b6cb0;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
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

            .empty-state-deduction {
                padding: 2rem 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-xl overflow-hidden mb-6 deduction-shadow deduction-gradient-bg">
            <div class="bg-gray-50 hover:bg-red-100 border-l-4 border-red-500 rounded-lg px-6 py-4 flex items-center justify-between text-black transition-all duration-300">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-red-100 p-3 rounded-full group-hover:bg-red-200 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">خصومات الموظف</h2>
                        <p class="text-gray-600">{{ $employee->user->name }}</p>
                    </div>
                    <form method="GET" action="{{ route('employees.deductions', $employee->id) }}">
                        <select name="year" onchange="this.form.submit()"
                                class="bg-red-100 hover:bg-red-200 text-gray-800 px-3 py-1 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-300 transition-all text-sm">
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
        <span class="bg-red-100 hover:bg-red-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ number_format($employee->deductions->sum('value')) }} ر.س إجمالي الخصومات
        </span>
                    <span class="bg-red-100 hover:bg-red-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ count($employee->deductions) }} خصم
        </span>
                    <a href="{{ route('employees.deductions.all') }}"
                       class="bg-red-100 hover:bg-red-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-red-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>جميع الخصومات</span>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
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
        <div class="bg-white rounded-xl overflow-hidden deduction-shadow">
            <div class="overflow-x-auto">
                <table class="table min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">المشروع</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">مدير المشروع</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قيمة الخصم</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الراتب قبل الخصم</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الراتب بعد الخصم</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النسبة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السبب</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الخصم</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($employee->deductions as $index => $deduction)
                            <tr class="deduction-row hover:bg-red-50 transition-colors">
                                <td class="deduction-cell px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    {{ $index + 1 }}
                                </td>

                                <td class="deduction-cell px-6 py-4 whitespace-nowrap text-center">
                                    @if ($employee->project)
                                        <span class="project-badge">
                                            {{ $employee->project->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="deduction-cell px-6 py-4 whitespace-nowrap text-center">
                                    @if ($employee->project && $employee->project->manager)
                                        <div class="flex flex-col items-center space-y-1">
                                            {{-- <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div> --}}
                                            <span class="text-sm text-gray-700">{{ $employee->project->manager->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="deduction-cell px-6 py-4 whitespace-nowrap text-right deduction-amount">
                                    {{ number_format($deduction->value) }} ر.س
                                </td>

                                <td class="deduction-cell px-6 py-4 whitespace-nowrap text-right salary-before">
                                    {{ number_format($deduction->payload['current_salary'] ?? 0) }} ر.س
                                </td>

                                <td class="deduction-cell px-6 py-4 whitespace-nowrap text-right salary-after">
                                    {{ number_format($deduction->payload['new_salary'] ?? 0) }} ر.س
                                </td>

                                <td class="deduction-cell px-6 py-4 whitespace-nowrap text-right">
                                    <span class="percentage-badge-danger">
                                        {{ $deduction->payload['deduction_percentage'] ?? 0 }}%
                                    </span>
                                </td>

                                <td class="deduction-cell px-6 py-4 text-sm text-gray-500 text-right">
                                    <div class="mx-auto" style="max-width: 200px;">{{ $deduction->reason }}</div>
                                </td>

                                <td class="deduction-cell px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ $deduction->created_at->format('Y-m-d') }}</span>
                                        <span class="text-xs text-gray-500">{{ $deduction->created_at->locale('ar')->diffForHumans() }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="empty-state-deduction">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 empty-icon-deduction" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <h5 class="text-lg font-medium text-gray-700">لا يوجد خصومات</h5>
                                        <p class="text-sm mt-1 text-gray-500">لم يتم تسجيل أي خصومات لهذا الموظف</p>
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
