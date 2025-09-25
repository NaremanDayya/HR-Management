@extends('layouts.master')
@section('title', 'جدول الإنذارات')

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
        .alert-gradient-bg {
            background: linear-gradient(135deg, #191818 0%, #440f0f 100%);
        }

        /* .alert-header-gradient {
            background: linear-gradient(135deg, #151515 0%, #880e4f 100%);
        } */

        .alert-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .alert-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .alert-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .alert-cell {
            transition: all 0.3s ease;
            whitespace: nowrap;
        }

        .alert-row:hover .alert-cell {
            background-color: #fff5f7;
        }

        .alert-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
       <div class="rounded-xl overflow-hidden mb-6 alert-shadow alert-gradient-bg">
    <div class="alert-header-gradient bg-red-800 px-6 py-4 flex items-center justify-between text-white">
        <div class="flex items-center space-x-4 rtl:space-x-reverse">
            <div class="p-3 rounded-full bg-red-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold">إنذارات الموظف</h2>
                <p class="opacity-90">{{ $employee->user->name }}</p>
            </div>
              <form method="GET" action="{{ route('employees.alerts', $employee->id) }}">
                        <select name="year" onchange="this.form.submit()"
                            class="bg-red-800 text-white px-3 py-1 rounded-lg focus:outline-none focus:ring focus:ring-red-500 text-sm">
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
            <span class="alert-badge px-3 py-1 rounded-full text-sm font-medium">{{ count($employee->alerts) }}
                إنذار</span>
            <a href="{{ route('employees.alerts.all') }}"
               class="bg-red-800 hover:bg-red-600 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <span>جميع الإنذارات</span>
            </a>
            <button onclick="window.print()"
                class="bg-grey bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
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

        <!-- Alerts Table -->
        <div class="bg-white rounded-xl overflow-hidden alert-shadow">
            <div class="overflow-x-auto">
                <table class="table min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">#
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المشروع</th>
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
                                عنوان الإنذار</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                السبب</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                تاريخ الإرسال</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($employee->alerts as $index => $alert)
                            <tr class="alert-row hover:bg-pink-50 transition-colors">
                                <td class="alert-cell px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    {{ $index + 1 }}</td>

                                <td class="alert-cell px-6 py-4 whitespace-nowrap text-center">
                                    @if ($employee->project)
                                        <div class="flex justify-center">
                                            <span
                                                class="px-3 py-1 inline-flex text-xs font-bold rounded-full bg-indigo-100 text-indigo-800">
                                                {{ $employee->project->name }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="alert-cell px-6 py-4 whitespace-nowrap text-center">
                                    @if ($employee->project && $employee->project->manager)
                                        <div class="flex flex-col items-center space-y-2">
                                            <div
                                                class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <span class="text-gray-700">{{ $employee->project->manager->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="alert-cell px-6 py-4 whitespace-nowrap text-center">{{$employee?->supervisor?->name}}</td>
                                <td class="alert-cell px-6 py-4 whitespace-nowrap text-center">{{$employee?->work_area}}</td>
                                <td class="alert-cell px-6 py-4 text-center">
                                    <div class="flex flex-col items-center space-y-1">
                                        <div class="h-5 w-5 text-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-red-600 alert-title">{{ $alert->title }}</span>
                                    </div>
                                </td>

                                <td class="alert-cell px-6 py-4 text-sm text-gray-500 max-w-xs text-center whitespace-nowrap">
                                    <div class="alert-title mx-auto" style="max-width: 300px;">{{ $alert->reason }}</div>
                                </td>

                                <td class="alert-cell px-6 py-4 whitespace-nowrap text-center">
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
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-lg">لا يوجد إنذارات</p>
                                        <p class="text-sm mt-1">لم يتم تسجيل أي إنذارات لهذا الموظف</p>
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
