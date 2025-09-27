@extends('layouts.master')
@section('title', 'لوحة التحكم')
@push('styles')
    <style>
        .flag-icon {
            width: 20px;
            height: 15px;
            display: inline-block;
            transition: transform 0.3s ease, filter 0.3s ease;
        }

        .group:hover .flag-icon {
            transform: scale(1.1);
            filter: drop-shadow(0 0 5px rgba(209, 18, 18, 0.5)) drop-shadow(0 0 10px rgba(255, 0, 0, 0.3));
        }
         .form-checkbox {
            border-radius: 0.25rem;
        }

        .modal-content {
            border-radius: 0.5rem;
        }

        .permission-checkbox:checked {
            background-color: #3b82f6;
        }
    </style>
@endpush
@section('content')
    <div class="container mx-auto py-8 px-4" dir="rtl">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">لوحة التحكم الإدارية</h1>
                <p class="text-gray-600 font-light">
                    مرحباً بك، <span class="font-bold text-gray-800">{{ Auth::user()->name }}</span>
                     @if(Auth::user()->role === 'admin')
        <button
            onclick="openPasswordModal()"
            class="ml-2 text-blue-500 hover:text-blue-700 transition"
            title="تغيير كلمة المرور"
        >
            <i class="fas fa-key"></i>
        </button>
    @endif
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="bg-blue-50 p-3 rounded-lg shadow-sm">
                    <p class="text-blue-800 font-medium">
                        <span class="text-gray-600">فريق الموارد البشرية:</span>
                        {{ $statistics['hr_manager_name'] }} (مدير) - {{ $statistics['hr_assistant_name'] }} (مساعد)
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-12">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-gray-500 font-medium">إجمالي المشاريع</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $statistics['managedProjectIds'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-project-diagram ml-2 group-hover:text-white transition-colors"></i>
                    </div>
                </div>
                <a href="{{ route('projects-statistics') }}"
                   class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                    عرض الكل
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>
{{--            test--}}
            <!-- Employee Stats -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-gray-500 font-medium">إجمالي الموظفين</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $statistics['employees_count'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <a href="{{ route('employees.index') }}"
                    class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                    عرض الكل
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>

            <!-- Active/Inactive Employees -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-gray-500 font-medium">الحسابات النشطة</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $statistics['active_count'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <a href="{{ route('employees.index', ['account_status' => 'active']) }}"
                    class="mt-4 inline-flex items-center text-green-600 hover:text-green-800 text-sm font-medium">
                    عرض النشطين
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-gray-500 font-medium">الحسابات غير النشطة</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $statistics['inactive_count'] }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <a href="{{ route('employees.index', ['account_status' => 'inactive']) }}"
                    class="mt-4 inline-flex items-center text-red-600 hover:text-red-800 text-sm font-medium">
                    عرض غير النشطين
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>

            <!-- Health Cards -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-gray-500 font-medium">بطاقات صحية</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $statistics['with_health_card'] }}/{{ $statistics['employees_count'] }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex space-x-3 space-x-reverse">
                    <a href="{{ route('employees.index', ['health_card' => '1']) }}"
                        class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                        لديهم بطاقة
                    </a>
                    <a href="{{ route('employees.index', ['health_card' => '0']) }}"
                        class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                        بدون بطاقة
                    </a>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">إدارة الموظفين</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Alerts Button -->
                <a href="{{ route('employees.alerts.all') }}" class="group">
                    <div
                        class="bg-red-50 hover:bg-red-100 border-l-4 border-red-500 rounded-lg p-4 transition-all duration-300 h-full flex items-center">
                        <div class="bg-red-100 p-3 rounded-full mr-4 group-hover:bg-red-200 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">الإنذارات</h3>
                            <p class="text-sm text-gray-600">عرض جميع الإنذارات المسجلة</p>
                        </div>
                    </div>
                </a>

                <!-- Deductions Button -->
                <a href="{{ route('employees.deductions.all') }}" class="group">
                    <div
                        class="bg-purple-50 hover:bg-purple-100 border-l-4 border-purple-500 rounded-lg p-4 transition-all duration-300 h-full flex items-center">
                        <div class="bg-purple-100 p-3 rounded-full mr-4 group-hover:bg-purple-200 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">الخصومات</h3>
                            <p class="text-sm text-gray-600">عرض جميع الخصومات المسجلة</p>
                        </div>
                    </div>
                </a>

                <!-- Advances Button -->
                <a href="{{ route('employees.advances.all') }}" class="group">
                    <div
                        class="bg-blue-50 hover:bg-blue-100 border-l-4 border-blue-500 rounded-lg p-4 transition-all duration-300 h-full flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4 group-hover:bg-blue-200 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">السلف</h3>
                            <p class="text-sm text-gray-600">عرض جميع السلف المسجلة</p>
                        </div>
                    </div>
                </a>

                <!-- Salary Increases Button -->
                <a href="{{ route('employees.increases.all') }}" class="group">
                    <div
                        class="bg-green-50 hover:bg-green-100 border-l-4 border-green-500 rounded-lg p-4 transition-all duration-300 h-full flex items-center">
                        <div class="bg-green-100 p-3 rounded-full mr-4 group-hover:bg-green-200 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">زيادات الرواتب</h3>
                            <p class="text-sm text-gray-600">عرض جميع الزيادات المسجلة</p>
                        </div>
                    </div>
                </a>

                <!-- Temporary Assignments Button -->
                <a href="{{ route('employees.assignments.all') }}" class="group">
                    <div
                        class="bg-yellow-50 hover:bg-yellow-100 border-l-4 border-yellow-500 rounded-lg p-4 transition-all duration-300 h-full flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-full mr-4 group-hover:bg-yellow-200 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">التكليفات المؤقتة</h3>
                            <p class="text-sm text-gray-600">عرض جميع التكليفات المؤقتة</p>
                        </div>
                    </div>
                </a>

                <!-- Replacements Button -->
                <a href="{{ route('employees.replacements.all') }}" class="group">
                    <div
                        class="bg-indigo-50 hover:bg-indigo-100 border-l-4 border-indigo-500 rounded-lg p-4 transition-all duration-300 h-full flex items-center">
                        <div class="bg-indigo-100 p-3 rounded-full mr-4 group-hover:bg-indigo-200 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">الاستبدالات</h3>
                            <p class="text-sm text-gray-600">عرض جميع طلبات الاستبدال</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>


        <!-- Roles Statistics -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">إحصاءات حسب الأدوار</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <h3 class="text-gray-600 font-medium">مدراء المشاريع</h3>
                        <span
                            class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">{{ $statistics['role_counts']['project_manager'] }}</span>
                    </div>
                    <a href="{{ route('employees.index', ['role' => 'project_manager']) }}"
                        class="mt-2 inline-flex items-center text-blue-600 hover:underline text-sm">
                        عرض القائمة
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <h3 class="text-gray-600 font-medium">مدراء المناطق</h3>
                        <span
                            class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">{{ $statistics['role_counts']['area_manager'] }}</span>
                    </div>
                    <a href="{{ route('employees.index', ['role' => 'area_manager']) }}"
                        class="mt-2 inline-flex items-center text-green-600 hover:underline text-sm">
                        عرض القائمة
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>


                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <h3 class="text-gray-600 font-medium">المشرفين</h3>
                        <span
                            class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">{{ $statistics['role_counts']['supervisor'] }}</span>
                    </div>
                    <a href="{{ route('employees.index', ['role' => 'supervisor']) }}"
                        class="mt-2 inline-flex items-center text-red-600 hover:underline text-sm">
                        عرض القائمة
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <h3 class="text-gray-600 font-medium">مرتبي الرفوف</h3>
                        <span
                            class="bg-yellow-100 text-yellow-800 text-sm font-medium px-2.5 py-0.5 rounded">{{ $statistics['role_counts']['shelf_stacker'] }}</span>
                    </div>
                    <a href="{{ route('employees.index', ['role' => 'shelf_stacker']) }}"
                       class="mt-2 inline-flex items-center text-yellow-600 hover:underline text-sm">
                        عرض القائمة
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Nationalities Statistics -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">توزيع الجنسيات</h2>
                <span class="bg-indigo-100 text-indigo-800 text-sm font-medium px-2.5 py-0.5 rounded">
                    {{ $statistics['nationalities_count'] }} جنسية مختلفة
                </span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach ($statistics['nationalities']->take(5) as $nationality => $count)
                    @php
                        $baseNationality = preg_replace('/(ة|ه)$/u', '', $nationality);
                        $flagCode = null;

                        if (isset($nationalityFlags[$nationality])) {
                            $flagCode = $nationalityFlags[$nationality];
                        } elseif (isset($nationalityFlags[$baseNationality])) {
                            $flagCode = $nationalityFlags[$baseNationality];
                        }
                    @endphp

                    <div class="border rounded-lg p-3 hover:shadow-md transition-shadow group">
                        <a href="{{route('employees.index',['nationality' => $baseNationality])}}">
                        <h3
                            class="text-gray-600 font-medium text-center flex items-center justify-center space-x-1 rtl:space-x-reverse">
                            @if ($flagCode)
                                <img src="https://flagcdn.com/24x18/{{ $flagCode }}.png" alt="{{ $nationality }}"
                                    class="flag-icon me-1">
                            @endif
                            <span>{{ $nationality }}</span>
                        </h3>
                        <p class="text-2xl font-bold text-center mt-1">{{ $count }}</p>
                        </a>
                    </div>
                @endforeach

                @if ($statistics['nationalities_count'] > 5)
                    <div class="border rounded-lg p-3 hover:shadow-md transition-shadow">
                        <h3 class="text-gray-600 font-medium text-center">أخرى</h3>
                        <p class="text-2xl font-bold text-center mt-1">
                            {{ $statistics['nationalities']->skip(5)->sum() }}
                        </p>
                    </div>
                @endif
            </div>


        </div>

        @if (Auth::user()->role === 'admin')
            @include('Employees.permissions')
        @endif
    </div>

<div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md relative">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">تغيير كلمة المرور</h2>

        <form method="POST" action="{{ route('admin.change-password') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">كلمة المرور الجديدة</label>
                <input type="password" name="new_password" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">تأكيد كلمة المرور</label>
                <input type="password" name="new_password_confirmation" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="flex justify-end mt-4 gap-2">
                <button type="button" onclick="closePasswordModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">إلغاء</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">تأكيد</button>
            </div>
        </form>

        <button onclick="closePasswordModal()" class="absolute top-2 left-2 text-gray-500 hover:text-red-500">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

@endsection

@push('scripts')
    <script>
           function openPasswordModal() {
        document.getElementById('passwordModal').classList.remove('hidden');
    }

    function closePasswordModal() {
        document.getElementById('passwordModal').classList.add('hidden');
    }
    </script>
@endpush
