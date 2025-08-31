@extends('layouts.master')
@section('title', 'جدول جميع الاستبدالات')

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

        .replacements-gradient-bg {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        }

        .replacements-header-gradient {
            background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%);
        }

        .replacements-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .replacements-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .replacements-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .replacements-cell {
            transition: all 0.3s ease;
            white-space: nowrap;
            font-size: 14px;
            font-weight: 500;
        }

        .replacements-row:hover .replacements-cell {
            background-color: #f5fbff;
            text-align: center;

        }

        table th,
        table td {
            text-align: center !important;
            vertical-align: middle !important;
        }

        .employee-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #e3f2fd;
            color: #1976d2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-left: 8px;
        }

        .status-badge {
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            border-radius: 50px;
        }

        .status-active {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-inactive {
            background-color: #ffebee;
            color: #c62828;
        }

        .date-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            background-color: #f5f5f5;
            font-size: 0.85rem;
        }

        .date-badge i {
            margin-left: 4px;
            font-size: 0.75rem;
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-xl overflow-hidden mb-6 replacements-shadow replacements-gradient-bg">
            <div class="replacements-header-gradient bg-blue-800 px-6 py-4 flex items-center justify-between text-white">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="p-3 rounded-full bg-blue-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">استبدالات الموظفين</h2>
                        <p class="opacity-90">سجل استبدالات الموظفين في النظام</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <span class="replacements-badge px-3 py-1 rounded-full text-sm font-medium">{{ $replacements->count() }}
                        استبدالات</span>
                    <button onclick="window.print()"
                        class="bg-blue-900 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>طباعة</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl overflow-hidden replacements-shadow">
            @if ($replacements->isEmpty())
                <div class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <p class="text-lg">لا يوجد استبدالات</p>
                        <p class="text-sm mt-1">لم يتم تسجيل أي استبدالات حتى الآن</p>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الموظف القديم</th>
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
                                    حالة البديل</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($replacements as $index => $replacement)
                                <tr class="replacements-row hover:bg-blue-50 transition-colors">
                                    <td class="replacements-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="replacements-cell px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('employees.show', $replacement->oldEmployee->id ?? '') }}"
                                            class="flex items-center justify-center">

                                            <div class="mr-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $replacement->oldEmployee->name ?? '—' }}</div>

                                            </div>
                                        </a>
                                    </td>

                                    <td class="replacements-cell px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('employees.show', $replacement->newEmployee->id ?? '') }}"
                                            class="flex items-center justify-center">

                                            <div class="mr-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $replacement->newEmployee->name ?? '—' }}</div>

                                            </div>
                                        </a>
                                    </td>

                                    <td class="replacements-cell px-6 py-4 whitespace-nowrap">
                                        <span class="date-badge">
                                            <i class="fas fa-calendar-check"></i>
                                            {{ $replacement->replacement_date->format('Y-m-d') }}
                                        </span>
                                    </td>

                                    <td class="replacements-cell px-6 py-4 whitespace-nowrap">
                                        <span class="date-badge">
                                            <i class="fas fa-calendar-times"></i>
                                            {{ $replacement->last_working_date->format('Y-m-d') }}
                                        </span>
                                    </td>

                                    <td class="replacements-cell px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate" data-bs-toggle="tooltip"
                                            title="{{ $replacement->reason }}">
                                            {{ \Illuminate\Support\Str::limit($replacement->reason, 50) }}
                                        </div>
                                        @if (strlen($replacement->reason) > 50)
                                            <button class="text-sm text-blue-600 hover:text-blue-800 mt-1"
                                                data-bs-toggle="modal" data-bs-target="#reasonModal"
                                                data-reason="{{ $replacement->reason }}">
                                                عرض المزيد
                                            </button>
                                        @endif
                                    </td>

                                    <td class="replacements-cell px-6 py-4 whitespace-nowrap">
                                        @php
                                            $isActive = ($replacement->newEmployee->account_status ?? '') === 'active';
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
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">سبب الاستبدال الكامل</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="إغلاق"></button>
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
            // Initialize tooltips
            const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                .map(el => new bootstrap.Tooltip(el, {
                    boundary: document.body
                }));

            // Show full reason text in modal
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
