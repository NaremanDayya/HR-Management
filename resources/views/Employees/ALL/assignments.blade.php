@extends('layouts.master')
@section('title', 'جدول المشاريع المؤقتة')

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
              border-color:yellow;
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
        .assignments-gradient-bg {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
        }

        .assignments-header-gradient {
            background: linear-gradient(135deg, #00acc1 0%, #00838f 100%);
        }

        .assignments-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .assignments-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .assignments-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .assignments-cell {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .assignments-row:hover .assignments-cell {
            background-color: #f0fdfd;
        }

        .duration-badge {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            background-color: #e1f5fe;
            color: #0277bd;
        }

        .status-badge {
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-xl overflow-hidden mb-6 assignments-shadow assignments-gradient-bg">
            <div class="bg-gray-50 hover:bg-yellow-100 border-l-4 border-yellow-500 rounded-lg px-6 py-4 flex items-center justify-between text-black transition-all duration-300">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-yellow-100 p-3 rounded-full group-hover:bg-yellow-200 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">المشاريع المؤقتة</h2>
                        <p class="text-gray-600">سجل تكليفات الموظفين للمشاريع المؤقتة</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
        <span class="bg-yellow-100 hover:bg-yellow-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ count($assignments) }} تكليف
        </span>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
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
                        <form method="GET" action="{{ route('employees.assignments.all') }}" class="w-full max-w-md">
                            <div class="search-container px-4 py-2 flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="ابحث في التكليفات المؤقتة..."
                                    class="search-input text-sm text-gray-700 placeholder-gray-500"
                                >
                                @if(request('year'))
                                    <input type="hidden" name="year" value="{{ request('year') }}">
                                @endif
                                @if(request('search'))
                                    <a href="{{ route('employees.assignments.all') }}" class="text-gray-400 hover:text-yellow-600 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        <!-- Assignments Table -->
        <div class="bg-white rounded-xl overflow-hidden assignments-shadow">
            <div class="overflow-x-auto">
                @if ($assignments->count() > 0)
                    <table class="table min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الموظف</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">المشروع الأساسي</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">المشروع المؤقت</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">من تاريخ</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">إلى تاريخ</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الفترة</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">السبب</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($assignments as $assignment)
                                <tr class="assignments-row hover:bg-yellow-50 transition-colors">
                                    <td class="assignments-cell px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex flex-col items-center space-y-1">
                                            <a href="{{route('employees.assignments',$assignment->employee->id)}}" >
                                            <span class="text-sm font-medium text-gray-900">{{ $assignment->employee->user->name ?? '-' }}</span>
                                            </a>
                                        </div>
                                    </td>

                                    <td class="assignments-cell px-6 py-4 whitespace-nowrap text-center">
                                        @if($assignment->fromProject)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                {{ $assignment->fromProject->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>

                                    <td class="assignments-cell px-6 py-4 whitespace-nowrap text-center">
                                        @if($assignment->toProject)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ $assignment->toProject->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>

                                    <td class="assignments-cell px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                        {{ $assignment->start_date->format('Y-m-d') }}
                                    </td>

                                    <td class="assignments-cell px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                        {{ $assignment->end_date->format('Y-m-d') }}
                                    </td>

                                    <td class="assignments-cell px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            $durationInDays = $assignment->start_date->diffInDays($assignment->end_date) + 1;
                                        @endphp
                                        <span class="duration-badge px-2 py-1 rounded-full text-xs">
                                            {{ $durationInDays }} يوم
                                        </span>
                                    </td>

                                    <td class="assignments-cell px-6 py-4 text-sm text-gray-500 text-center">
                                        <div class="mx-auto" style="nowrap:none;">{{ $assignment->reason ?? '-' }}</div>
                                    </td>

                                    <td class="assignments-cell px-6 py-4 whitespace-nowrap text-center">
                                        @if ($assignment->status === 'pending')
                                            <span class="status-badge inline-flex items-center rounded-full bg-yellow-100 text-yellow-800">
                                                <svg class="ml-0.5 mr-1.5 h-2 w-2 text-yellow-500" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                قيد الانتظار
                                            </span>
                                        @elseif ($assignment->status === 'approved')
                                            <span class="status-badge inline-flex items-center rounded-full bg-green-100 text-green-800">
                                                <svg class="ml-0.5 mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                مقبول
                                            </span>
                                        @elseif ($assignment->status === 'rejected')
                                            <span class="status-badge inline-flex items-center rounded-full bg-red-100 text-red-800">
                                                <svg class="ml-0.5 mr-1.5 h-2 w-2 text-red-500" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                مرفوض
                                            </span>
                                        @else
                                            <span class="status-badge inline-flex items-center rounded-full bg-gray-100 text-gray-800">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-500" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                غير معروف
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2a4 4 0 018 0v2m-4 4v-4" />
                            </svg>
                            <p class="text-lg">لا يوجد تكليفات مؤقتة</p>
                            <p class="text-sm mt-1">لم يتم تسجيل أي تكليفات مؤقتة حالياً</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('[data-toggle="tooltip"]').tooltip();

            flatpickr(".date-input", {
                locale: "ar",
                dateFormat: "Y-m-d",
                allowInput: true
            });
        });
    </script>
@endpush
