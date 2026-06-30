@extends('layouts.master')
@section('title', 'إحصائيات جميع المشاريع')
@push('styles')
    <style>
        .fa-user-tie:before {
            content: "\f508";
        }

        .fa-user-cog:before {
            content: "\f4fe";
        }

        .fa-user-edit:before {
            content: "\f4ff";
        }

        .fa-calculator:before {
            content: "\f1ec";
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
    </style>
@endpush
@section('content')

    <div class="w-full rtl p-6 bg-gray-50 min-h-screen">

        <div  class="bg-gray-100 hover:bg-purple-200 border-purple-600 border-2 border-black rounded-2xl p-6 mb-8 transition-all duration-300 shadow-md">
            <div class="flex flex-col lg:flex-row justify-between items-center mb-6 gap-4">
                <div class="text-center lg:text-right">
                    <h1 class="text-2xl font-bold mb-2 tracking-tight">إحصائيات المشاريع</h1>
                    <p class="text-xl font-light opacity-90">نظرة شاملة على أداء المشاريع والموظفين</p>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <button
                        class="btn d-flex align-items-center border-4 border-purple-600 gap-2 px-5 py-2.5 rounded-full font-bold text-sm transition-all transform hover:scale-105 bg-purple-400 text-black shadow-lg hover:bg-purple-500"
                        data-bs-toggle="modal" data-bs-target="#createProjectModal">
                        <i class="fas fa-plus"></i>
                        <span>إضافة مشروع</span>
                    </button>

                    <div class="export-btn-group no-print flex gap-2">
                        <button  id="pdfExportBtn"
                                 class=" px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-white">PDF</span>
                        </button>

                        <button  id="excelExportBtn"
                                 class="px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-white">Excel</span>
                        </button>
                        <button onclick="window.print()"
                                class="bg-gray-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                      clip-rule="evenodd" />
                            </svg>
                            <span>طباعة</span>
                        </button>
                    </div>
                </div>
            </div>

            <div
                class="flex flex-col lg:flex-row items-center border-black border-2 justify-between gap-6 bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                <div class="flex flex-wrap items-center justify-center gap-2  text-xs">
                    <div
                        class="flex items-center gap-1 px-3 py-1.5 rounded-full bg-white/10 text-black backdrop-blur-md border border-white/20">
                        <span class="font-semibold">إجمالي الموظفين</span>
                        <span
                            class="inline-flex items-center justify-center w-7 h-7 text-xs font-bold rounded-full bg-blue-400 text-black">
                            {{ $totalActiveEmployees + $totalInActiveEmployees }}
                        </span>
                    </div>

                    <div
                        class="flex items-center gap-1 px-3 py-1.5 rounded-full bg-white/10 text-black backdrop-blur-md border border-white/20">
                        <span class="font-semibold">الموظفين النشطين</span>
                        <span
                            class="inline-flex items-center justify-center w-7 h-7 text-xs font-bold rounded-full bg-green-400 text-black">
                            {{ $totalActiveEmployees }}
                        </span>
                    </div>

                    <div
                        class="flex items-center gap-1 px-3 py-1.5 rounded-full bg-white/10 text-black backdrop-blur-md border border-white/20">
                        <span class="font-semibold">الموظفين غير النشطين</span>
                        <span
                            class="inline-flex items-center justify-center w-7 h-7 text-xs font-bold rounded-full bg-red-400 text-black">
                            {{ $totalInActiveEmployees }}
                        </span>
                    </div>
                </div>
                <div class="w-full lg:w-auto">
                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="{{ route('projects-statistics') }}"
                            class="px-5 py-2.5 text-sm font-bold rounded-full transition-all transform hover:scale-105
                    {{ !request()->route('project') ? 'bg-white text-blue-600 shadow-lg' : 'bg-white/10 text-black hover:bg-white/20' }}">
                            جميع المشاريع
                            <span
                                class="inline-flex items-center justify-center w-6 h-6 ml-1 text-xs font-bold rounded-full
                    {{ !request()->route('project') ? 'bg-blue-100 text-blue-600' : 'bg-white/20 text-black' }}">
                                {{ $totalProjects }}
                            </span>
                        </a>

                        <!-- Projects Dropdown -->
                        <div class="relative group">
                            <button
                                class="px-5 py-2.5 text-sm font-bold border-2 border-black rounded-full transition-all bg-white/10 text-black hover:bg-white/20 flex items-center">
                                المشاريع
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div
                                class="absolute right-0 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden group-hover:block z-10">
                                <div class="py-1 max-h-96 overflow-y-auto">
                                    @foreach ($projects as $project)
                                        <a href="{{ route('project.statistics', $project) }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900
                                    {{ request()->route('project') && request()->route('project')->id == $project->id ? 'bg-blue-50 text-blue-600' : '' }}">
                                            <div class="flex justify-between items-center">
                                                <span>{{ $project->name }}</span>
                                                <span
                                                    class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold rounded-full bg-gray-100 text-gray-600">
                                                    {{ $project->employees->count() }}
                                                </span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Account Status Filter -->
                <form method="GET" class="flex flex-wrap gap-4 items-center justify-center lg:justify-end">
                    <!-- Account Status Dropdown (same as before) -->
                    <div x-data="{
                        open: false,
                        selected: '{{ request('account_status') === 'active' ? 'نشط' : (request('account_status') === 'inactive' ? 'غير نشط' : 'حالة الحساب') }}',
                        options: {
                            '': 'الكل',
                            'active': 'نشط',
                            'inactive': 'غير نشط'
                        },
                        applyFilter(value, key) {
                            const url = new URL(window.location.href);
                            if (value) {
                                url.searchParams.set(key, value);
                            } else {
                                url.searchParams.delete(key);
                            }
                            window.location.href = url.toString();
                        }
                    }" x-cloak>
                        <div class="relative">
                            <button @click="open = !open" type="button"
                                class="px-6 py-3 bg-white border border-white rounded-full hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/30 flex items-center justify-center gap-2 transition-all duration-200 min-w-[180px]">
                                <span x-text="selected" class="font-medium"></span>
                                <svg class="w-5 h-5 transform transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <ul x-show="open" @click.away="open = false" x-transition
                                class="absolute z-20 mt-2 w-48 bg-white rounded-xl shadow-xl overflow-hidden py-1 top-full">
                                <template x-for="(label, value) in options" :key="value">
                                    <li @click="selected = label; open = false; applyFilter(value, 'account_status')"
                                        class="px-4 py-3 cursor-pointer hover:bg-blue-50 text-sm font-medium text-gray-700 flex items-center justify-between transition-colors"
                                        :class="{ 'bg-blue-50 text-blue-600': selected === label }">
                                        <span x-text="label"></span>
                                        <svg x-show="selected === label" class="w-4 h-4 text-blue-500" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- Year Filter -->
                    {{-- <div class="flex items-center gap-3">
                        <label for="year" class="text-sm text-black font-medium whitespace-nowrap">السنة:</label>
                        <div class="relative">
                            <select name="year" id="year" onchange="this.form.submit()"
                                class="appearance-none w-full text-sm rounded-xl px-5 py-2 bg-purple-600 border border-purple/30 text-black focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition duration-200">
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

                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                <svg class="w-4 h-4 text-black opacity-70" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div> --}}

                </form>


            </div>
        </div>

        <!-- Create Project Modal -->
        <div class="modal fade" id="createProjectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">إضافة مشروع جديد</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="createProjectForm" action="{{ route('projects.store') }}" method="POST"
                        class="bg-white rounded-xl shadow-md p-6 space-y-6 w-full max-w-lg mx-auto">
                        @csrf

                        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">إضافة مشروع جديد</h2>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">اسم المشروع</label>
                            <input type="text" id="name" name="name" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            <div id="name-error" class="text-red-500 text-xs mt-1"></div>
                        </div>

                        <div>
                            <label for="manager_name" class="block text-sm font-medium text-gray-700 mb-1">مدير
                                المشروع</label>
                            <input type="text" id="manager_name" name="manager_name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            <p class="mt-1 text-xs text-gray-500">اتركه فارغًا إذا لم يكن المدير مسجلاً في النظام بعد —
                                يمكنك مشاركة رابط تسجيل خاص بمدير هذا المشروع لاحقًا من صفحة الموظفين.</p>
                            <div id="manager_name-error" class="text-red-500 text-xs mt-1"></div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                            <div id="description-error" class="text-red-500 text-xs mt-1"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الأدوار المسموحة في هذا
                                المشروع</label>
                            <div class="flex flex-col gap-2 bg-gray-50 border border-gray-200 rounded-lg p-3">
                                @foreach (\App\Models\Project::SELF_REGISTRATION_ROLES as $roleKey => $roleLabel)
                                    <label class="flex items-center gap-2 text-sm text-gray-700">
                                        <input type="checkbox" name="allowed_roles[]" value="{{ $roleKey }}" checked
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        {{ $roleLabel }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2 pt-4 border-t">
                            <button type="button" data-bs-dismiss="modal"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                إلغاء
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-black rounded-lg hover:bg-blue-700 transition">
                                حفظ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            <section class="bg-white rounded-xl shadow-lg p-6 hover:shadow-indigo-300 transition-shadow duration-300">
                <h2
                    class="text-2xl font-semibold mb-6 border-b-4 border-black pb-2 text-center text-black flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16l-4 6 4 6H4V6z" />
                    </svg>

                    إحصائيات جنسيات الموظفين
                </h2>

                <div class="overflow-x-auto">
                    <table id="nationalitiesTable" class="min-w-full border-collapse border border-gray-300 text-center">
                        <thead class="bg-indigo-50 hover:bg-indigo-100 border-l-4 border-indigo-500 text-black text-lg rounded-t-xl">
                            <tr>
                                <th class="py-3 px-5 border border-indigo-700">الجنسية</th>
                                <th class="py-3 px-5 border border-indigo-700">عدد الموظفين</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($nationalities as $nat => $count)
                                @php
                                    $baseNationality = preg_replace('/(ة|ه)$/u', '', $nat);
                                    $flagCode = null;

                                    if (isset($nationalityFlags[$nat])) {
                                        $flagCode = $nationalityFlags[$nat];
                                    } elseif (isset($nationalityFlags[$baseNationality])) {
                                        $flagCode = $nationalityFlags[$baseNationality];
                                    }
                                @endphp
                                <tr class="hover:bg-indigo-50 transition-colors duration-200">
                                    <td
                                        class="py-3 px-5 font-semibold text-indigo-600 border border-indigo-300 whitespace-nowrap text-center align-middle">
                                        <div class="grid grid-cols-2 gap-x-4 items-center justify-center">
                                            <div class="flex flex-col items-center gap-y-2">

                                                @if ($flagCode)
                                                    <img src="https://flagcdn.com/w40/{{ $flagCode }}.png"
                                                        alt="{{ $nat }}"
                                                        class="w-8 h-6 rounded shadow-md border border-gray-300">
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </div>
                                            <div class="flex flex-col items-start gap-y-2">
                                                <span class="text-indigo-700 text-sm">{{ $nat }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-3 px-5 font-bold text-indigo-700 border border-indigo-300">
                                        {{ $count }}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-6 text-gray-400 font-medium">لا بيانات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="bg-white rounded-xl shadow-lg p-6 hover:shadow-green-300 transition-shadow duration-300">
                <h2
                    class="text-2xl font-semibold mb-6 border-b-4 border-black pb-2 text-center text-black flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    إحصائيات أعمار الموظفين
                </h2>

                <div class="overflow-x-auto">
                    <table id="ageGroupsTable" class="min-w-full border-collapse border border-gray-300 text-center">
                        <thead class="bg-green-50 hover:bg-green-100 border-l-4 border-green-500 text-black text-lg rounded-t-xl">

                        <tr>
                                <th class="py-3 px-5 border border-green-700">العمر</th>
                                <th class="py-3 px-5 border border-green-700">عدد الموظفين</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ageGroups as $ageGroup => $count)
                                <tr class="hover:bg-green-50 transition-colors duration-200">
                                    <td
                                        class="py-3 px-5 font-semibold text-green-600 border border-green-300 whitespace-nowrap text-center align-middle">
                                        <div class="grid grid-cols-2 gap-x-16 items-center justify-center">
                                            <div
                                                class="flex flex-col items-center gap-y-2 text-green-600 text-xl select-none">
                                                📅
                                            </div>
                                            <div class="flex flex-col items-start gap-y-2">
                                                <span> {{ is_numeric($ageGroup) ? $ageGroup . ' سنة' : $ageGroup }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-5 font-bold text-green-700 border border-green-300">
                                        {{ $count }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-6 text-gray-400 font-medium">لا بيانات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>


            {{-- إحصائيات الرواتب --}}
            <section class="bg-white rounded-xl shadow-lg p-6 hover:shadow-indigo-300 transition-shadow duration-300">
                <h2
                    class="text-2xl font-semibold mb-6 border-b-4 border-black pb-2 text-center text-black flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 1v22m0-11a4 4 0 110 8 4 4 0 010-8z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5h8m-8 14h8" />
                    </svg>
                    إحصائيات الرواتب
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 mr-4">
                    <div
                        class="bg-indigo-50 rounded-lg p-4 shadow-sm hover:shadow-md transition-all border border-indigo-100">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-indigo-700 font-medium text-sm">إجمالي الرواتب</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-indigo-900 font-bold text-xl">
                            {{ number_format($salariesStats['salaryStats']['totalSalary'] ?? 0) }}
                            <span class="text-indigo-600 text-sm">ر.س</span>
                        </p>
                    </div>

                    <div
                        class="bg-green-50 rounded-lg p-4 shadow-sm hover:shadow-md transition-all border border-green-100">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-green-700 font-medium text-sm">متوسط الراتب</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <p class="text-green-900 font-bold text-xl">
                            {{ number_format($salariesStats['salaryStats']['averageSalary'] ?? 0) }}
                            <span class="text-green-600 text-sm">ر.س</span>
                        </p>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4 shadow-sm hover:shadow-md transition-all border border-blue-100">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-blue-700 font-medium text-sm">الحد الأدنى</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <p class="text-blue-900 font-bold text-xl">
                            {{ number_format($salariesStats['salaryStats']['minSalary'] ?? 0) }}
                            <span class="text-blue-600 text-sm">ر.س</span>
                        </p>
                    </div>

                    <div
                        class="bg-purple-50 rounded-lg p-4 shadow-sm hover:shadow-md transition-all border border-purple-100">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-purple-700 font-medium text-sm">الحد الأقصى</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <p class="text-purple-900 font-bold text-xl">
                            {{ number_format($salariesStats['salaryStats']['maxSalary'] ?? 0) }}
                            <span class="text-purple-600 text-sm">ر.س</span>
                        </p>
                    </div>
                </div>


                <!-- جدول توزيع الرواتب -->
                <div class="overflow-x-auto">
                    <table id="salariesTable" class="min-w-full border-collapse border border-gray-300 text-center">
                        <thead class="bg-indigo-50 hover:bg-indigo-100 border-l-4 border-indigo-500 text-black text-lg rounded-t-xl">
                        <tr>
                                <th class="py-3 px-5 border border-indigo-700">الراتب (ر.س)</th>
                                <th class="py-3 px-5 border border-indigo-700">عدد الموظفين</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($salaryCounts as $salary => $count)
                                <tr class="hover:bg-indigo-50 transition-colors duration-200">
                                    <td class="py-3 px-5 font-semibold border border-indigo-300">
                                        {{ number_format($salary) }}
                                    </td>
                                    <td class="py-3 px-5 font-bold text-indigo-700 border border-indigo-300">
                                        {{ $count }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-6 text-gray-400 font-medium">لا بيانات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
            <!-- إحصائيات أدوار الموظفين -->
            <section class="bg-white rounded-xl shadow-lg p-6 hover:shadow-purple-300 transition-shadow duration-300">
                <h2
                    class="text-2xl font-semibold mb-6 border-b-4 border-black pb-2 text-center text-black flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    إحصائيات أدوار الموظفين
                </h2>

                <div class="overflow-x-auto">
                    <table id="rolesTable" class="min-w-full border-collapse border border-gray-300 text-center">
                        <thead class="bg-purple-50 hover:bg-purple-100 border-l-4 border-purple-500  text-black text-lg rounded-t-xl">
                        <tr>
                                <th class="py-3 px-5 border border-purple-700">الدور الوظيفي</th>
                                <th class="py-3 px-5 border border-purple-700">عدد الموظفين</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rolesStats as $role => $count)
                                <tr class="hover:bg-purple-50 transition-colors duration-200">
                                    <td
                                        class="py-3 px-5 font-semibold text-purple-600 border border-purple-300 whitespace-nowrap text-center">
                                        <div class="grid grid-cols-2 gap-x-16 items-center justify-center">
                                            <div
                                                class="flex flex-col items-center gap-y-2 text-purple-600 text-xl select-none">
                                                @switch($role)
                                                    @case('hr_manager')
                                                        <i class="fas fa-users-cog" title="HR Manager"></i>
                                                    @break

                                                    @case('hr_assistant')
                                                        <i class="fas fa-user-friends" title="HR Assistant"></i>
                                                    @break

                                                    @case('shelf_stacker')
                                                        <i class="fas fa-boxes" title="Shelf Stacker"></i>
                                                    @break

                                                    @case('area_manager')
                                                        <i class="fas fa-map-marked-alt" title="Area Manager"></i>
                                                    @break

                                                    @case('supervisor')
                                                        <i class="fas fa-user-check" title="Supervisor"></i>
                                                    @break

                                                    @case('project_manager')
                                                        <i class="fas fa-project-diagram" title="Project Manager"></i>
                                                    @break

                                                    @default
                                                        <i class="fas fa-user" title="User"></i>
                                                @endswitch
                                            </div>
                                            <div class="flex flex-col items-start gap-y-2">
                                                <span>{{ __($role) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-5 font-bold text-purple-700 border border-purple-300">
                                        {{ $count }}
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="py-6 text-gray-400 font-medium">لا بيانات</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof $ === 'undefined' || typeof Swal === 'undefined') {
                    console.error('Required libraries not loaded');
                    return;
                }

                $(document).ready(function() {
                    $('#createProjectForm').on('submit', function(e) {
                        e.preventDefault();

                        const form = $(this);
                        const submitBtn = form.find('button[type="submit"]');

                        // Clear previous errors
                        $('.text-red-500').text('');
                        $('.is-invalid').removeClass('is-invalid');

                        // Show loading state
                        submitBtn.prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...'
                        );

                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(response) {
                                $('#createProjectModal').modal('hide');

                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم الحفظ!',
                                    text: response.message ||
                                        'تم إنشاء المشروع بنجاح',
                                    confirmButtonText: 'حسناً',
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    },
                                    timer: 2000,
                                    timerProgressBar: true,
                                    willClose: () => {
                                        location.reload();
                                    }
                                });
                            },
                            error: function(xhr) {
                                submitBtn.prop('disabled', false).html('حفظ');

                                if (xhr.status === 422) {
                                    // Validation errors
                                    const errors = xhr.responseJSON.errors;
                                    let errorList = '';

                                    for (const field in errors) {
                                        $(`#${field}`).addClass('is-invalid');
                                        $(`#${field}-error`).text(errors[field][0]);
                                        errorList += `<div>${errors[field][0]}</div>`;
                                    }

                                    Swal.fire({
                                        icon: 'error',
                                        title: 'يوجد أخطاء!',
                                        html: errorList,
                                        confirmButtonText: 'حسناً',
                                        customClass: {
                                            confirmButton: 'btn btn-danger'
                                        }
                                    });
                                } else {
                                    // Server error
                                    const errorMsg = xhr.responseJSON?.message ||
                                        'حدث خطأ غير متوقع';

                                    Swal.fire({
                                        icon: 'error',
                                        title: 'خطأ!',
                                        text: errorMsg,
                                        confirmButtonText: 'حسناً',
                                        customClass: {
                                            confirmButton: 'btn btn-danger'
                                        }
                                    });
                                }
                            }
                        });
                    });
                });

                // Function to export all tables to Excel in one file with multiple sheets
                function exportAllToExcel(fileName) {
                    if (typeof XLSX === 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: 'مكتبة Excel غير محملة',
                            confirmButtonText: 'حسناً'
                        });
                        return false;
                    }

                    try {
                        const tables = [
                            { id: 'nationalitiesTable', name: 'جنسيات الموظفين' },
                            { id: 'ageGroupsTable', name: 'الفئات العمرية' },
                            { id: 'salariesTable', name: 'توزيع الرواتب' },
                            { id: 'rolesTable', name: 'أدوار الموظفين' }
                        ];

                        const wb = XLSX.utils.book_new();

                        tables.forEach(tableInfo => {
                            const table = document.getElementById(tableInfo.id);
                            if (!table) {
                                console.warn(`Table with id ${tableInfo.id} not found`);
                                return; // Continue to next table, don't break the loop
                            }

                            const tableClone = table.cloneNode(true);

                            // Prepare data
                            const headers = Array.from(tableClone.querySelectorAll('thead th'))
                                .map(th => th.textContent.trim());

                            const data = [
                                headers,
                                ...Array.from(tableClone.querySelectorAll('tbody tr')).map(row => {
                                    return Array.from(row.querySelectorAll('td')).map((td, index) => {
                                        // Special handling for age groups table
                                        if (tableInfo.id === 'ageGroupsTable' && index === 0) {
                                            const ageSpan = td.querySelector(
                                                '.flex.flex-col.items-start.gap-y-2 span');
                                            return ageSpan ? ageSpan.textContent.trim() : td.textContent.trim();
                                        }
                                        // Special handling for nationalities table (remove flag images)
                                        if (tableInfo.id === 'nationalitiesTable' && index === 0) {
                                            // Get only the text content, ignore images
                                            const textSpan = td.querySelector('.flex.flex-col.items-start.gap-y-2 span');
                                            return textSpan ? textSpan.textContent.trim() : td.textContent.trim();
                                        }
                                        // Special handling for roles table (remove icons)
                                        if (tableInfo.id === 'rolesTable' && index === 0) {
                                            const textSpan = td.querySelector('.flex.flex-col.items-start.gap-y-2 span');
                                            return textSpan ? textSpan.textContent.trim() : td.textContent.trim();
                                        }
                                        // Default handling for all other cases
                                        return td.textContent.trim();
                                    });
                                })
                            ];

                            const ws = XLSX.utils.aoa_to_sheet(data);
                            XLSX.utils.book_append_sheet(wb, ws, tableInfo.name);
                        });

                        // Check if any sheets were added
                        if (wb.SheetNames.length === 0) {
                            throw new Error('لم يتم العثور على أي جداول للتصدير');
                        }

                        XLSX.writeFile(wb, `${fileName}_${new Date().toISOString().slice(0, 10)}.xlsx`);
                        return true;
                    } catch (error) {
                        console.error('Excel export error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: `حدث خطأ أثناء تصدير ملف Excel: ${error.message}`,
                            confirmButtonText: 'حسناً'
                        });
                        return false;
                    }
                }

                // Function to export all tables to PDF in one file
                async function exportAllToPDF(fileName, reportTitle) {
                    if (typeof html2pdf === 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: 'مكتبة PDF غير محملة',
                            confirmButtonText: 'حسناً'
                        });
                        return false;
                    }

                    try {
                        const tables = [
                            { id: 'nationalitiesTable', title: 'جنسيات الموظفين' },
                            { id: 'ageGroupsTable', title: 'الفئات العمرية' },
                            { id: 'salariesTable', title: 'توزيع الرواتب' },
                            { id: 'rolesTable', title: 'أدوار الموظفين' }
                        ];

                        const pdfContainer = document.createElement('div');
                        pdfContainer.style.padding = '15px';
                        pdfContainer.style.direction = 'rtl';
                        pdfContainer.style.fontFamily = 'Arial, sans-serif';
                        pdfContainer.style.textAlign = 'center';
                        pdfContainer.style.lineHeight = '1.4';

                        // Create main header
                        const header = document.createElement('div');
                        header.style.marginBottom = '20px';
                        header.style.borderBottom = '2px solid #6e48aa';
                        header.style.paddingBottom = '12px';
                        header.style.textAlign = 'center';

                        const companyName = document.createElement('h1');
                        companyName.textContent = 'شركة افاق الخليج';
                        companyName.style.color = '#6e48aa';
                        companyName.style.margin = '0 0 8px 0';
                        companyName.style.fontSize = '22px';
                        companyName.style.fontWeight = 'bold';

                        const title = document.createElement('h2');
                        title.textContent = reportTitle;
                        title.style.color = '#333';
                        title.style.margin = '0 0 8px 0';
                        title.style.fontSize = '18px';
                        title.style.fontWeight = '600';

                        const reportDate = document.createElement('p');
                        reportDate.textContent = 'تاريخ التقرير: ' + new Date().toLocaleDateString('ar-EG');
                        reportDate.style.color = '#666';
                        reportDate.style.margin = '0';
                        reportDate.style.fontSize = '14px';

                        header.appendChild(companyName);
                        header.appendChild(title);
                        header.appendChild(reportDate);
                        pdfContainer.appendChild(header);

                        // Add each table to the PDF with compact spacing
                        tables.forEach((tableInfo, index) => {
                            const table = document.getElementById(tableInfo.id);
                            if (!table) return;

                            // Add section title with compact spacing
                            const sectionTitle = document.createElement('h3');
                            sectionTitle.textContent = tableInfo.title;
                            sectionTitle.style.color = '#6e48aa';
                            sectionTitle.style.margin = '20px 0 10px 0';
                            sectionTitle.style.fontSize = '16px';
                            sectionTitle.style.fontWeight = 'bold';
                            sectionTitle.style.textAlign = 'right';
                            sectionTitle.style.padding = '5px 0';
                            pdfContainer.appendChild(sectionTitle);

                            // Clone and style table with compact design
                            const tableClone = table.cloneNode(true);
                            tableClone.style.width = '100%';
                            tableClone.style.borderCollapse = 'collapse';
                            tableClone.style.marginBottom = '15px';
                            tableClone.style.direction = 'rtl';
                            tableClone.style.fontSize = '12px';

                            // Style table headers more compact
                            tableClone.querySelectorAll('th').forEach(th => {
                                th.style.backgroundColor = '#6e48aa';
                                th.style.color = 'white';
                                th.style.padding = '8px 6px';
                                th.style.border = '1px solid #ddd';
                                th.style.textAlign = 'center';
                                th.style.fontWeight = 'bold';
                                th.style.fontSize = '12px';
                            });

                            // Style table cells more compact
                            tableClone.querySelectorAll('td').forEach(td => {
                                td.style.padding = '6px 4px';
                                td.style.border = '1px solid #ddd';
                                td.style.textAlign = 'center';
                                td.style.fontSize = '11px';
                            });

                            // Remove any existing margins/padding from inner elements
                            tableClone.querySelectorAll('div, span').forEach(el => {
                                el.style.margin = '0';
                                el.style.padding = '0';
                            });

                            pdfContainer.appendChild(tableClone);

                            // Add a subtle separator instead of page break (except after last table)
                            if (index < tables.length - 1) {
                                const separator = document.createElement('div');
                                separator.style.height = '1px';
                                separator.style.backgroundColor = '#f0f0f0';
                                separator.style.margin = '10px 0';
                                pdfContainer.appendChild(separator);
                            }
                        });

                        // Add compact footer
                        const footer = document.createElement('div');
                        footer.style.marginTop = '20px';
                        footer.style.paddingTop = '10px';
                        footer.style.borderTop = '1px solid #eee';
                        footer.style.textAlign = 'center';
                        footer.style.color = '#666';
                        footer.style.fontSize = '11px';

                        const copyright = document.createElement('p');
                        copyright.textContent = `© ${new Date().getFullYear()} جميع الحقوق محفوظة لشركة افاق الخليج`;
                        copyright.style.margin = '5px 0';
                        footer.appendChild(copyright);
                        pdfContainer.appendChild(footer);

                        // PDF options - using smaller format for better flow
                        const options = {
                            margin: [10, 10, 15, 10], // Smaller margins
                            filename: `${fileName}_${new Date().toISOString().slice(0, 10)}.pdf`,
                            image: {
                                type: 'jpeg',
                                quality: 0.98
                            },
                            html2canvas: {
                                scale: 2,
                                useCORS: true,
                                scrollX: 0,
                                scrollY: 0
                            },
                            jsPDF: {
                                unit: 'mm',
                                format: 'a4', // Use A4 for better flow
                                orientation: 'portrait'
                            }
                        };

                        // Generate PDF
                        await html2pdf().set(options).from(pdfContainer).save();
                        return true;
                    } catch (error) {
                        console.error('PDF export error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: `حدث خطأ أثناء تصدير ملف PDF: ${error.message}`,
                            confirmButtonText: 'حسناً'
                        });
                        return false;
                    }
                }

                // Individual table export functions (for the individual buttons)
                function exportToExcel(tableId, fileName) {
                    if (typeof XLSX === 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: 'مكتبة Excel غير محملة',
                            confirmButtonText: 'حسناً'
                        });
                        return false;
                    }

                    try {
                        const table = document.getElementById(tableId);
                        if (!table) throw new Error('Table not found');

                        const tableClone = table.cloneNode(true);

                        // Prepare data
                        const headers = Array.from(tableClone.querySelectorAll('thead th'))
                            .map(th => th.textContent.trim());

                        const data = [
                            headers,
                            ...Array.from(tableClone.querySelectorAll('tbody tr')).map(row => {
                                return Array.from(row.querySelectorAll('td')).map((td, index) => {
                                    // Special handling for age groups table
                                    if (tableId === 'ageGroupsTable' && index === 0) {
                                        const ageSpan = td.querySelector(
                                            '.flex.flex-col.items-start.gap-y-2 span');
                                        return ageSpan ? ageSpan.textContent.trim() : '';
                                    }
                                    // Default handling for all other cases
                                    return td.textContent.trim();
                                });
                            })
                        ];

                        // Create workbook
                        const wb = XLSX.utils.book_new();
                        const ws = XLSX.utils.aoa_to_sheet(data);
                        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
                        XLSX.writeFile(wb, `${fileName}_${new Date().toISOString().slice(0, 10)}.xlsx`);

                        return true;
                    } catch (error) {
                        console.error('Excel export error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: `حدث خطأ أثناء تصدير ملف Excel: ${error.message}`,
                            confirmButtonText: 'حسناً'
                        });
                        return false;
                    }
                }

                async function exportToPDF(tableId, fileName, reportTitle) {
                    if (typeof html2pdf === 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: 'مكتبة PDF غير محملة',
                            confirmButtonText: 'حسناً'
                        });
                        return false;
                    }

                    try {
                        const table = document.getElementById(tableId);
                        if (!table) throw new Error('Table not found');

                        const tableClone = table.cloneNode(true);

                        const pdfContainer = document.createElement('div');
                        pdfContainer.style.padding = '20px';
                        pdfContainer.style.direction = 'rtl';
                        pdfContainer.style.fontFamily = 'Arial, sans-serif';
                        pdfContainer.style.textAlign = 'center';

                        const header = document.createElement('div');
                        header.style.marginBottom = '30px';
                        header.style.borderBottom = '2px solid #6e48aa';
                        header.style.paddingBottom = '15px';
                        header.style.textAlign = 'center';

                        const companyName = document.createElement('h1');
                        companyName.textContent = 'شركة افاق الخليج';
                        companyName.style.color = '#6e48aa';
                        companyName.style.margin = '0 0 5px 0';
                        companyName.style.fontSize = '24px';
                        companyName.style.fontWeight = 'bold';

                        const title = document.createElement('h2');
                        title.textContent = reportTitle;
                        title.style.color = '#333';
                        title.style.margin = '0 0 5px 0';
                        title.style.fontSize = '20px';
                        title.style.fontWeight = '600';

                        const reportDate = document.createElement('p');
                        reportDate.textContent = 'تاريخ التقرير: ' + new Date().toLocaleDateString('ar-EG');
                        reportDate.style.color = '#666';
                        reportDate.style.margin = '0';
                        reportDate.style.fontSize = '16px';

                        header.appendChild(companyName);
                        header.appendChild(title);
                        header.appendChild(reportDate);

                        tableClone.style.width = '100%';
                        tableClone.style.borderCollapse = 'collapse';
                        tableClone.style.marginTop = '20px';
                        tableClone.style.direction = 'rtl';

                        tableClone.querySelectorAll('th').forEach(th => {
                            th.style.backgroundColor = '#6e48aa';
                            th.style.color = 'white';
                            th.style.padding = '12px';
                            th.style.border = '1px solid #ddd';
                            th.style.textAlign = 'center';
                            th.style.fontWeight = 'bold';
                        });

                        tableClone.querySelectorAll('td').forEach(td => {
                            td.style.padding = '8px';
                            td.style.border = '1px solid #ddd';
                            td.style.textAlign = 'center';
                        });

                        const footer = document.createElement('div');
                        footer.style.marginTop = '20px';
                        footer.style.paddingTop = '10px';
                        footer.style.borderTop = '1px solid #eee';
                        footer.style.textAlign = 'center';
                        footer.style.color = '#666';
                        footer.style.fontSize = '12px';

                        const copyright = document.createElement('p');
                        copyright.textContent = `© ${new Date().getFullYear()} جميع الحقوق محفوظة لشركة افاق الخليج`;
                        footer.appendChild(copyright);

                        pdfContainer.appendChild(header);
                        pdfContainer.appendChild(tableClone);
                        pdfContainer.appendChild(footer);

                        const options = {
                            margin: [15, 15, 30, 15],
                            filename: `${fileName}_${new Date().toISOString().slice(0, 10)}.pdf`,
                            image: {
                                type: 'jpeg',
                                quality: 0.98
                            },
                            html2canvas: {
                                scale: 2,
                                useCORS: true
                            },
                            jsPDF: {
                                unit: 'mm',
                                format: 'a2',
                                orientation: 'portrait'
                            }
                        };

                        await html2pdf().set(options).from(pdfContainer).save();
                        return true;
                    } catch (error) {
                        console.error('PDF export error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: `حدث خطأ أثناء تصدير ملف PDF: ${error.message}`,
                            confirmButtonText: 'حسناً'
                        });
                        return false;
                    }
                }

                // Header export buttons - Export ALL tables
                document.getElementById('excelExportBtn')?.addEventListener('click', async function() {
                    const btn = this;
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
                    btn.disabled = true;

                    try {
                        const success = await exportAllToExcel('إحصائيات_المشاريع_الشاملة');
                        if (success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'نجاح!',
                                text: 'تم تصدير جميع الجداول في ملف Excel واحد',
                                confirmButtonText: 'حسناً',
                                timer: 2000
                            });
                        }
                    } finally {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                });

                document.getElementById('pdfExportBtn')?.addEventListener('click', async function() {
                    const btn = this;
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
                    btn.disabled = true;

                    try {
                        const success = await exportAllToPDF('إحصائيات_المشاريع_الشاملة', 'تقرير إحصائيات المشاريع الشامل');
                        if (success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'نجاح!',
                                text: 'تم تصدير جميع الجداول في ملف PDF واحد',
                                confirmButtonText: 'حسناً',
                                timer: 2000
                            });
                        }
                    } finally {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                });

                // Add individual export buttons for each table
                function addTableExportButtons() {
                    const tables = [
                        { id: 'nationalitiesTable', title: 'جنسيات الموظفين' },
                        { id: 'ageGroupsTable', title: 'الفئات العمرية' },
                        { id: 'salariesTable', title: 'توزيع الرواتب' },
                        { id: 'rolesTable', title: 'أدوار الموظفين' }
                    ];

                    tables.forEach(table => {
                        const tableElement = document.getElementById(table.id);
                        if (!tableElement) return;

                        const exportDiv = document.createElement('div');
                        exportDiv.className = 'flex gap-2 justify-end mb-4';

                        const excelBtn = document.createElement('button');
                        excelBtn.className = 'px-3 py-1 bg-green-500 text-black rounded hover:bg-green-600 text-sm';
                        excelBtn.innerHTML = '<i class="fas fa-file-excel mr-1"></i> Excel';
                        excelBtn.onclick = () => exportToExcel(table.id, table.title);
                        exportDiv.appendChild(excelBtn);

                        const pdfBtn = document.createElement('button');
                        pdfBtn.className = 'px-3 py-1 bg-red-500 text-black rounded hover:bg-red-600 text-sm';
                        pdfBtn.innerHTML = '<i class="fas fa-file-pdf mr-1"></i> PDF';
                        pdfBtn.onclick = () => exportToPDF(table.id, table.title, table.title);
                        exportDiv.appendChild(pdfBtn);

                        tableElement.parentNode.insertBefore(exportDiv, tableElement);
                    });
                }

                // Initialize table export buttons
                addTableExportButtons();
            });
        </script>
    @endpush
@endsection
