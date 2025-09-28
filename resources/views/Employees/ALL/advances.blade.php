@extends('layouts.master')
@section('title', 'جدول السلف لجميع الموظفين')

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

        .advance-gradient-bg {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        }

        .advance-header-gradient {
            background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%);
        }

        .advance-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .advance-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
            background-color: #f5fbff;
        }

        .amount-cell {
            font-family: 'Courier New', monospace;
            font-weight: bold;
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
        <div class="rounded-xl overflow-hidden mb-6 advance-shadow advance-gradient-bg">
            <div class="bg-teal-50 hover:bg-teal-100 border-l-4 border-teal-500 rounded-lg px-6 py-4 flex items-center justify-between text-black transition-all duration-300">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-teal-100 p-3 rounded-full group-hover:bg-teal-200 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">سلف الموظفين</h2>
                        <p class="text-gray-600">إدارة طلبات السلف المقدمة من الموظفين</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
        <span class="bg-teal-100 hover:bg-teal-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ count($advances) }} طلب
        </span>
                    <button onclick="window.print()"
                            class="bg-teal-100 hover:bg-teal-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-teal-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <span>طباعة</span>
                    </button>
                    <a href="{{ route('employees.advances.deductions.all') }}"
                       class="bg-teal-100 hover:bg-teal-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-teal-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v8m0 0H6m6 0h6M4 6h16M4 6v12a2 2 0 002 2h12a2 2 0 002-2V6M4 6l8 6 8-6"/>
                        </svg>
                        <span>خصومات السلف</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Advances Table -->
        <div class="bg-white rounded-xl overflow-hidden advance-shadow">
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
                            المبلغ
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            النسبة
                        </th>

                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            مدير المشروع
                        </th>

                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            المشرف
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            منطقة العمل
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            السبب
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            تاريخ الطلب
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            تاريخ المعالجة
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            مدة الاستجابة
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الحالة
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($advances as $index => $advance)
                        <tr class="advance-row hover:bg-blue-50 transition-colors">
                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ $index + 1 }}
                            </td>

                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center space-y-1">

                                    <a href="{{ route('employees.advances', $advance->employee->id) }}"
                                       class="text-sm font-medium text-gray-900 hover:underline">
                                        {{ $advance->employee->user->name ?? '-' }}
                                    </a>
                                </div>
                            </td>

                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center amount-cell text-blue-800">
                                {{ number_format($advance->amount) }} ر.س
                            </td>

                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $salary = $advance->employee->salary ?? 0;
                                    $percentage = $salary > 0 ? round(($advance->amount / $salary) * 100, 2) : 0;
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $percentage }}%
                                    </span>
                            </td>


                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                @if ($advance->manager)
                                    <div class="flex flex-col items-center space-y-1">

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
                                @if ($advance->approved_at)
                                    <div class="flex flex-col items-center">
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ $advance->approved_at->format('Y-m-d') }}</span>
                                        <span
                                            class="text-xs text-gray-500">{{ $advance->approved_at->locale('ar')->diffForHumans() }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
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
                                    <span
                                        class="status-badge inline-flex items-center rounded-full bg-green-100 text-green-800">
                                            <svg class="ml-0.5 mr-1.5 h-2 w-2 text-green-500" fill="currentColor"
                                                 viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            معتمدة
                                        </span>
                                @elseif($advance->status == 'rejected')
                                    <span
                                        class="status-badge inline-flex items-center rounded-full bg-red-100 text-red-800">
                                            <svg class="ml-0.5 mr-1.5 h-2 w-2 text-red-500" fill="currentColor"
                                                 viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            مرفوضة
                                        </span>
                                @else
                                    <span
                                        class="status-badge inline-flex items-center rounded-full bg-yellow-100 text-yellow-800">
                                            <svg class="ml-0.5 mr-1.5 h-2 w-2 text-yellow-500" fill="currentColor"
                                                 viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            قيد الانتظار
                                        </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-lg">لا توجد سلف</p>
                                    <p class="text-sm mt-1">لم يتم تقديم أي طلبات سلف حتى الآن</p>
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
