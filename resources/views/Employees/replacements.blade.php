@extends('layouts.master')
@section('title', 'جدول الاستبدالات')

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
        .replacement-gradient-bg {
            background: linear-gradient(135deg, #f5f3ff 0%, #ddd6fe 100%);
        }

        .replacement-header-gradient {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        }

        .replacement-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .replacement-hover-effect:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .replacement-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .replacement-cell {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .replacement-row:hover .replacement-cell {
            background-color: #f5f3ff;
        }

        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-left: 12px;
        }

        .status-badge {
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
        }

        .status-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .date-badge {
            background: #ede9fe;
            color: #7c3aed;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .reason-content {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.4;
        }

        .empty-state-replacement {
            padding: 3rem;
            text-align: center;
            background: #f8fafc;
            border-radius: 0 0 12px 12px;
        }

        .empty-icon-replacement {
            font-size: 3.5rem;
            color: #ddd6fe;
            margin-bottom: 1.5rem;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .print-btn {
            background: #7c3aed;
            color: white;
        }

        .print-btn:hover {
            background: #6d28d9;
            transform: translateY(-2px);
        }

        .add-btn {
            background: #10b981;
            color: white;
        }

        .add-btn:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .employee-link {
            display: flex;
            align-items: center;
            color: #334155;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .employee-link:hover {
            color: #7c3aed;
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-xl overflow-hidden mb-6 replacement-shadow replacement-gradient-bg">
            <div class="replacement-header-gradient bg-purple-900 px-6 py-4 flex items-center justify-between text-white">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="p-3 rounded-full bg-purple-700 bg-opacity-80">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">بدائل الموظف</h2>
                        <p class="opacity-90">{{ $oldEmployee->name }}</p>
                    </div>
                    <form method="GET" action="{{ route('employees.replacements', $oldEmployee->id) }}">
                        <select name="year" onchange="this.form.submit()"
                            class="bg-purple-800 text-white px-3 py-1 rounded-lg focus:outline-none focus:ring focus:ring-purple-500 text-sm">
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
                    <span class="replacement-badge px-3 py-1 rounded-full text-sm font-medium">
                        {{ $oldEmployee->replacements()->count() }} استبدالات
                    </span>

                    <button onclick="window.print()" class="action-btn print-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                clip-rule="evenodd" />
                        </svg>

                        <span>طباعة</span>
                    </button>
                    <a href="{{ route('employees.replacements.all') }}"
                        class="bg-purple-800 hover:bg-purple-600 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>جميع الإستبدالات</span>
                    </a>

                </div>
            </div>
        </div>

        <!-- Replacements Table -->
        <div class="bg-white rounded-xl overflow-hidden replacement-shadow">
            @if ($oldEmployee->replacements->isEmpty())
                <div class="empty-state-replacement">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 empty-icon-replacement" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <h5 class="text-lg font-medium text-gray-700">لا يوجد بدائل</h5>
                    <p class="text-sm mt-1 text-gray-500">لم يتم تسجيل أي بدائل لهذا الموظف</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الموظف البديل</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    تاريخ التعيين</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    تاريخ آخر يوم عمل</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    سبب الاستبدال</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($oldEmployee->replacements as $index => $replacement)
                                <tr class="replacement-row hover:bg-purple-50 transition-colors">
                                    <td
                                        class="replacement-cell px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="replacement-cell px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('employees.show', $replacement->newEmployee->id) }}"
                                            class="employee-link">
                                            <div class="employee-avatar">
                                                {{ substr($replacement->newEmployee->name, 0, 1) }}
                                            </div>
                                            <div class="mr-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $replacement->newEmployee->name }}</div>
                                                <div class="text-xs text-gray-500">#{{ $replacement->newEmployee->id }}
                                                </div>
                                            </div>
                                        </a>
                                    </td>

                                    <td class="replacement-cell px-6 py-4 whitespace-nowrap text-center">
                                        <span class="date-badge">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $replacement->replacement_date->format('Y-m-d') }}
                                        </span>
                                    </td>

                                    <td class="replacement-cell px-6 py-4 whitespace-nowrap text-center">
                                        <span class="date-badge">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $replacement->last_working_date->format('Y-m-d') }}
                                        </span>
                                    </td>

                                    <td class="replacement-cell px-6 py-4 text-sm text-gray-500 text-center">
                                        <div class="reason-content mx-auto" style="max-width: 250px;">
                                            {{ $replacement->reason }}
                                        </div>
                                    </td>

                                    <td class="replacement-cell px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            $isActive = $replacement->newEmployee->account_status === 'active';
                                        @endphp
                                        <span class="status-badge {{ $isActive ? 'status-active' : 'status-inactive' }}">
                                            {{ $isActive ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Reason Modal -->
    <div class="modal fade" id="reasonModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-purple-600 text-white">
                    <h5 class="modal-title">سبب الاستبدال الكامل</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="fullReasonText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reason modal handler
            const reasonModal = document.getElementById('reasonModal');
            if (reasonModal) {
                reasonModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const reason = button.getAttribute('data-reason');
                    document.getElementById('fullReasonText').textContent = reason;
                });
            }
        });
    </script>
@endpush
