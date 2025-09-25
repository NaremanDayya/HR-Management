@extends('layouts.master')
@section('title', 'إنذارات الموظفين')

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

        * {
            font-siz: 14px;
            font-weight: 700;
        }

        .alerts-gradient-bg {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
        }

        /*
                .alerts-header-gradient {
                    background: linear-gradient(135deg, #f44336 0%, #c62828 100%);
                } */

        .alerts-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .alerts-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .alerts-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .alerts-cell {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .alerts-row:hover .alerts-cell {
            background-color: #fff5f7;
        }

        .alert-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
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
        <div class="rounded-xl overflow-hidden mb-6 alerts-shadow alerts-gradient-bg">
            <div class="alerts-header-gradient bg-red-800 px-6 py-4 flex items-center justify-between text-white">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="p-3 rounded-full bg-red-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">إنذارات الموظفين</h2>
                        <p class="opacity-90">سجل الإنذارات الصادرة لجميع الموظفين</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <span
                        class="alerts-badge px-3 py-1 rounded-full text-sm font-medium">{{ count($alerts) }} إنذار</span>
                    <button onclick="window.print()"
                            class=" bg-red-900  px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <span>طباعة</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Alerts Table -->
        <div class="bg-white rounded-xl overflow-hidden alerts-shadow">
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
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            المشرف
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            منطقة العمل
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            عنوان الإنذار
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            السبب
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            تاريخ الإرسال
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($alerts as $index => $alert)
                        <tr class="alerts-row hover:bg-red-50 transition-colors">
                            <td class="alerts-cell px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ $index + 1 }}
                            </td>

                            <td class="alerts-cell px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center space-y-1">

                                    <a href="{{ route('employees.alerts', $alert->employee->id) }}"
                                       class="employee-link text-sm font-medium text-gray-900 hover:text-red-600">
                                        {{ $alert->employee->user->name ?? '-' }}
                                    </a>
                                </div>
                            </td>

                            <td class="alerts-cell px-6 py-4 whitespace-nowrap text-center">
                                @if($alert->employee->project)
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            {{ $alert->employee->project->name }}
                                        </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="alerts-cell px-6 py-4 whitespace-nowrap text-center">
                                @if($alert->employee->project && $alert->employee->project->manager)
                                    <div class="flex flex-col items-center space-y-1">

                                        <span
                                            class="text-sm text-gray-700">{{ $alert->employee->project->manager->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="alert-cell px-6 py-4 whitespace-nowrap text-center">{{$alert->employee?->supervisor?->name}}</td>
                            <td class="alert-cell px-6 py-4 whitespace-nowrap text-center">{{$alert->employee?->work_area}}</td>

                            <td class="alerts-cell px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse">
                                    <div class="h-5 w-5 text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-bold text-red-600">{{ $alert->title }}</span>
                                </div>
                            </td>

                            <td class="alerts-cell px-6 py-4 text-sm text-gray-500 text-center">
                                <div class="alert-title mx-auto" style="max-width: 250px;">{{ $alert->reason }}</div>
                            </td>

                            <td class="alerts-cell px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ $alert->created_at->format('Y-m-d') }}</span>
                                    <span
                                        class="text-xs text-gray-500">{{ $alert->created_at->locale('ar')->diffForHumans() }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-lg">لا يوجد إنذارات</p>
                                    <p class="text-sm mt-1">لم يتم تسجيل أي إنذارات حالياً</p>
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
