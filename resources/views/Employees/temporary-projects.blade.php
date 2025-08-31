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
        .assignment-gradient-bg {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
        }

        .assignment-header-gradient {
            background: linear-gradient(135deg, #00838f 0%, #006064 100%);
        }

        .assignment-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .assignment-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .assignment-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .assignment-cell {
            transition: all 0.3s ease;
            whitespace: nowrap;
        }

        .assignment-row:hover .assignment-cell {
            background-color: #e0f7fa;
        }

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

        .duration-bubble {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            background-color: #00838f;
            color: white;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .project-icon {
            width: 24px;
            height: 24px;
            margin-left: 0.5rem;
            color: #00838f;
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-xl overflow-hidden mb-6 assignment-shadow assignment-gradient-bg">
            <div class="assignment-header-gradient bg-teal-950 px-6 py-4 flex items-center justify-between text-white">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="p-3 rounded-full bg-teal-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">التكليفات المؤقتة للموظف</h2>
                        <p class="opacity-90">{{ $employee->user->name }}</p>
                    </div>
                    <form method="GET" action="{{ route('employees.assignments', $employee->id) }}">
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
                    <span class="assignment-badge px-3 py-1 rounded-full text-sm font-medium">
                        {{ $employee->temporaryAssignments->where('status', 'approved')->count() }} معتمدة
                    </span>
                    <span class="assignment-badge px-3 py-1 rounded-full text-sm font-medium bg-teal-700">
                        {{ $employee->temporaryAssignments->count() }} طلبات
                    </span>
                     <a href="{{ route('employees.assignments.all') }}"
               class="bg-teal-800 hover:bg-teal-600 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <span>جميع التكليفات المؤقتة</span>
            </a>
                </div>
            </div>
        </div>

        <!-- Assignments Table -->
        <div class="bg-white rounded-xl overflow-hidden assignment-shadow">
            <div class="overflow-x-auto">
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
                        @forelse ($assignments as $assignment)
                            <tr class="assignment-row hover:bg-blue-50 transition-colors">
                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="project-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>{{ $assignment->employee->user->name }}</span>
                                    </div>
                                </td>

                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="project-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span>{{ $assignment->fromProject->name ?? '—' }}</span>
                                    </div>
                                </td>

                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center font-medium text-teal-600">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="project-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <span>{{ $assignment->toProject->name }}</span>
                                    </div>
                                </td>

                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ $assignment->start_date->format('Y-m-d') }}</span>
                                        <span class="text-xs text-gray-500">{{ $assignment->start_date->locale('ar')->format('l') }}</span>
                                    </div>
                                </td>

                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ $assignment->end_date->format('Y-m-d') }}</span>
                                        <span class="text-xs text-gray-500">{{ $assignment->end_date->locale('ar')->format('l') }}</span>
                                    </div>
                                </td>

                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $durationInDays = $assignment->start_date->diffInDays($assignment->end_date) + 1;
                                    @endphp
                                    <span class="duration-bubble">{{ $durationInDays }} يوم</span>
                                </td>

                                <td class="assignment-cell px-6 py-4 text-sm text-gray-500 max-w-xs text-center">
                                    <div class="mx-auto" style="max-width: 200px;">{{ $assignment->reason }}</div>
                                </td>

                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center">
                                    @if ($assignment->status === 'pending')
                                        <span class="status-badge pending-badge">
                                            <i class="fas fa-clock ml-1"></i> قيد الانتظار
                                        </span>
                                    @elseif ($assignment->status === 'approved')
                                        <span class="status-badge approved-badge">
                                            <i class="fas fa-check-circle ml-1"></i> مقبول
                                        </span>
                                    @elseif ($assignment->status === 'rejected')
                                        <span class="status-badge rejected-badge">
                                            <i class="fas fa-times-circle ml-1"></i> مرفوض
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2a4 4 0 018 0v2m-4 4v-4" />
                                        </svg>
                                        <p class="text-lg">لا توجد مهام مؤقتة حالياً</p>
                                        <p class="text-sm mt-1">لم يتم تسجيل أي تكليفات مؤقتة لهذا الموظف</p>
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
            // Initialize tooltips if needed
            $('[data-toggle="tooltip"]').tooltip();

            // Initialize date formatting if needed
            flatpickr(".date-input", {
                locale: "ar",
                dateFormat: "Y-m-d",
                allowInput: true
            });
        });
    </script>
@endpush
