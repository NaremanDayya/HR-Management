<!-- resources/views/Employees/table.blade.php -->
@extends('layouts.master')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Header and Search -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-2xl font-bold text-gray-800">Employees</h1>

                <div class="w-full md:w-64">
                    <form method="GET" action="{{ route('employees.actions') }}">
                        <div class="relative">
                            <input
                                type="text"
                                name="search"
                                placeholder="Search by name..."
                                value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Filters (optional) -->
            <!-- You can add filter dropdowns here similar to the search -->

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class=" text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الموظف
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            حالة الموظف
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الدور الوظيفي
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الإنذارات
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الخصومات
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            السلف
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الاستبدالات
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            زيادات الرواتب
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            التكليفات المؤقتة
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            طلبات الموظفين
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            خصومات السلف
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الإجراءات
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($employees as $employee)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 pl-2">
                                        <img class="h-10 w-10 rounded-full "
                                             src="{{ $employee->user->personal_image ?? 'https://ui-avatars.com/api/?name='.urlencode($employee->name).'&color=7F9CF5&background=EBF4FF' }}"
                                             alt="">
                                    </div>
                                    <div class="mr-4">
                                        <a href="{{ route('employees.show', $employee->id) }}"
                                           class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                            {{ $employee->name }}
                                        </a>
                                        <div
                                            class="text-sm text-gray-500">{{ $employee->user->nationality ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($employee->user->account_status === 'active')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    نشط
                                </span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    غير نشط
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ __($employee->user->role) ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="{{route('employees.alerts' ,$employee->id)}}"
                                class="{{ $employee->alerts_count > 0 ? 'text-red-500 font-medium' : 'text-gray-500' }}">
                                {{ $employee->alerts_count }}
                            </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                             <a href="{{route('employees.deductions' ,$employee->id)}}"
                             class="{{ $employee->deductions_count > 0 ? 'text-red-700 font-medium' : 'text-gray-500' }}">
                            {{ $employee->deductions_count }}
                            </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                               <a href="{{route('employees.advances' ,$employee->id)}}"
                                   class="{{ $employee->advances_count > 0 ? 'text-blue-700 font-medium' : 'text-gray-500' }}">
                            {{ $employee->advances_count }}
                            </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                 <a href="{{route('employees.replacements' ,$employee->id)}}"
                                     class="{{ $employee->replacements_count > 0 ? 'text-blue-900 font-medium' : 'text-gray-500' }}">
                            {{ $employee->replacements_count }}
                            </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                              <a href="{{route('employees.increases' ,$employee->id)}}"
                                  class="{{ $employee->increases_count > 0 ? 'text-green-700 font-medium' : 'text-gray-500' }}">
                            {{ $employee->increases_count }}
                            </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                               <a href="{{route('employees.alerts' ,$employee->id)}}"
                                   class="{{ $employee->temporary_assignments_count > 0 ? 'text-teal-700 font-medium' : 'text-gray-500' }}">
                            {{ $employee->temporary_assignments_count }}
                            </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                               <a href="{{route('employee-request.index' ,['search' => $employee->name])}}"
                                   class="{{ $employee->employee_requests_count > 0 ? 'text-purple-700 font-medium' : 'text-gray-500' }}">
                            {{ $employee->employee_requests_count }}
                            </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{route('employees.advances_deductions' ,$employee->id)}}"
                                    class="{{ $employee->advance_deductions_count > 0 ? 'text-teal-700 font-medium' : 'text-gray-500' }}">
                            {{ $employee->advance_deductions_count }}
                            </a>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('employees.show', $employee->id) }}"
                                   class="text-blue-600 hover:text-blue-900 mr-3">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-right text-sm text-gray-500">
                                لا يوجد موظفين بعد
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
