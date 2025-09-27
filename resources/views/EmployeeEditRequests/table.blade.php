@extends('layouts.master')
@section('title', 'طلبات الموظفين')
@section('content')
    <div class="w-full px-0 mx-0">
        <!-- Premium Card Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-8 py-6 bg-white border-b border-gray-200 shadow-sm">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">طلبات تعديل بيانات الموظفين</h2>
                            <p class="text-sm text-gray-500 mt-1">إدارة طلبات تعديل بيانات الموظفين</p>
                        </div>

                        <!-- Search -->
                        <!-- Search -->
                        <div class="relative w-full md:w-64">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <form method="GET" action="{{ route('employee-request.index') }}">
                                <input type="text" name="search"
                                    class="w-full pl-10 pr-4 py-2 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300 placeholder-gray-400 text-gray-700"
                                    placeholder="ابحث عن طلب..." value="{{ request('search') }}">

                                <!-- Keep existing filters when searching -->
                                @if (request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                @if (request('project_id'))
                                    <input type="hidden" name="project_id" value="{{ request('project_id') }}">
                                @endif
                                @if (request('request_type_id'))
                                    <input type="hidden" name="request_type_id" value="{{ request('request_type_id') }}">
                                @endif
                                @if (request('year'))
                                    <input type="hidden" name="year" value="{{ request('year') }}">
                                @endif

                            </form>

                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex flex-wrap gap-2">
                            <span class="text-sm text-gray-700 self-center">الحالة:</span>

                            <a href="{{ route('employee-request.index') }}"
                                class="px-4 py-2 text-sm font-medium rounded-full border transition-all
                {{ request('status') === null ? 'bg-blue-50 border-blue-200 text-blue-600' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                                الكل {{$allRequests}}
                            </a>

                            <a href="{{ route('employee-request.index', ['status' => 'pending'] + request()->except('page')) }}"
                                class="px-4 py-2 text-sm font-medium rounded-full border bg-amber-100 border-amber-300 text-amber-700">
                                 قيد الانتظار {{$pendedRequests}}
                            </a>

                            <a href="{{ route('employee-request.index', ['status' => 'approved'] + request()->except('page')) }}"
                                class="px-4 py-2 text-sm font-medium rounded-full border bg-green-100 border-green-300 text-green-700">
                                تم القبول {{$approvedRequests}}
                            </a>

                            <a href="{{ route('employee-request.index', ['status' => 'rejected'] + request()->except('page')) }}"
                                class="px-4 py-2 text-sm font-medium rounded-full border bg-red-100 border-red-300 text-red-700">
                                تم الرفض {{$rejectedRequests}}
                            </a>

                        </div>

                        <!-- Group 2: Dropdown Filters -->
                        <form method="GET" class="flex items-center gap-3 flex-wrap">
                            <!-- Project Dropdown -->
                            <div class="flex items-center gap-2">
                                <label for="project_id" class="text-sm text-gray-600 whitespace-nowrap">المشروع:</label>
                                <select name="project_id" id="project_id" onchange="this.form.submit()"
                                    class="text-sm rounded-lg border-gray-300 focus:border-blue-300 focus:ring-blue-200 shadow-sm">
                                    <option value="">كل المشاريع</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Request Type Dropdown -->
                            <div class="flex items-center gap-2">
                                <label for="request_type_id" class="text-sm text-gray-600 whitespace-nowrap">نوع
                                    الطلب:</label>
                                <select name="request_type_id" id="request_type_id" onchange="this.form.submit()"
                                    class="text-sm rounded-lg border-gray-300 focus:border-blue-300 focus:ring-blue-200 shadow-sm">
                                    <option value="">كل الأنواع</option>
                                    @foreach ($requestTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ request('request_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <label for="year" class="text-sm text-gray-600 whitespace-nowrap">السنة:</label>
                                <select name="year" id="year" onchange="this.form.submit()"
                                    class="text-sm rounded-lg border-gray-300 focus:border-blue-300 focus:ring-blue-200 shadow-sm">
                                    @php
                                        $currentYear = request('year', now()->year);
                                        $startYear = now()->year - 10;
                                        $endYear = now()->year + 1;
                                    @endphp
                                    @for ($year = $endYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}" @selected($year == $currentYear)>
                                            {{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Reset Button -->
                            @if (request()->hasAny(['status', 'project_id', 'request_type_id']))
                                <a href="{{ route('employee-request.index') }}"
                                    class="text-sm px-3 py-1.5 rounded-lg border border-gray-300 bg-white
                    text-gray-600 hover:bg-gray-50 transition-colors">
                                    إعادة الضبط
                                </a>
                            @endif

                            <!-- Keep status in form when selected -->
                            @if (request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                        </form>
                    </div>

                </div>
            </div>

            <div class="overflow-hidden">
                <div class="min-w-full">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200/60">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-8 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200/60">
                                        #</th>
                                    <th scope="col"
                                        class="px-8 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200/60">
                                        الموظف</th>
                                    <th scope="col"
                                        class="px-8 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200/60">
                                        المشروع</th>
                                    <th scope="col"
                                        class="px-8 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200/60">
                                        نوع الطلب</th>
                                    <th scope="col"
                                        class="px-8 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200/60">
                                        الوصف</th>

                                    <th scope="col"
                                        class="px-8 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200/60">
                                        مدير المشروع</th>
                                    <th scope="col"
                                        class="px-8 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200/60">
                                        تاريخ إرسال الطلب</th>
                                    <th scope="col"
                                        class="px-8 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200/60">
                                        تاريخ استجابة الطلب</th>
                                    <th scope="col"
                                        class="px-8 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200/60">
                                        مدة استجابة الطلب</th>
                                    @if (($role && $role->hasPermissionTo('review_employee_requests')) || Auth::user()->role('admin'))
                                        <th scope="col"
                                            class="px-8 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200/60">
                                            الإجراءات</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200/40">
                                @forelse($resources as $request)
                                    <tr class="hover:bg-gray-50/80 transition-all duration-150">
                                        <!-- Serial Number -->
                                        <td class="px-8 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-blue-700 font-medium">{{ $loop->iteration }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Employee -->
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover"
                                                        src="{{ $request->employee->user->getPersonalImageAttribute() ?? asset('images/default-avatar.png') }}"
                                                        alt="">
                                                </div>
                                                <div class="mr-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $request->employee->user->name ?? '-' }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $request->employee->job ?? '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-700">
                                            <div class="flex items-center">

                                                <div class="">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $request->employee->project->name ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Field -->
                                        <td
                                            class="px-8 py-5 whitespace-nowrap text-sm text-gray-700 font-medium leading-6">
                                            <div>
                                                <div class="text-gray-900 font-semibold">
                                                    {{ $request->requestType->label ?? 'نوع غير معروف' }}
                                                </div>
                                                @if ($request->edited_field)
                                                    <div class="text-gray-500 text-xs mt-1">
                                                        {{ \App\Models\EmployeeEditRequest::editableFields()[$request->edited_field] ?? $request->edited_field }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>


                                        <!-- Description -->
                                        <td class="px-8 py-5 text-sm text-gray-600">
                                            <div x-data="{ tooltip: false }" @mouseenter="tooltip = true"
                                                @mouseleave="tooltip = false" class="relative inline-block w-full">
                                                <p class="truncate max-w-xs">{{ $request->description }}</p>

                                                <div x-show="tooltip" x-transition x-cloak
                                                    class="absolute z-50 p-3 mt-1 text-sm text-white bg-gray-800 rounded-lg shadow-lg whitespace-normal"
                                                    style="min-width: 16rem; max-width: 32rem; width: max-content;">
                                                    {{ $request->description }}
                                                    <div class="absolute w-3 h-3 -mt-1.5 rotate-45 bg-gray-800"
                                                        style="right: 1.5rem; top: 0;"></div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Manager -->
                                        <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-700">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <img class="h-8 w-8 rounded-full object-cover"
                                                        src="{{ $request->manager->personal_image ?? asset('images/default-avatar.png') }}"
                                                        alt="">
                                                </div>
                                                <div class="mr-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $request->manager->name ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Date -->
                                        <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex flex-col">
                                                <span>{{ $request->created_at->format('Y-m-d') }}</span>
                                                <span
                                                    class="text-xs text-gray-400">{{ $request->created_at->locale('ar')->diffForHumans() }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex flex-col">
                                                @php

                                                    $rawResponseDate = $request->getRawOriginal('response_date');
                                                    $parsedResponseDate = $rawResponseDate
                                                        ? Carbon\Carbon::parse($rawResponseDate)
                                                        : null;
                                                @endphp

                                                <span>{{ $parsedResponseDate?->format('Y-m-d') }}</span>
                                                <span class="text-xs text-gray-400">
                                                    {{ $parsedResponseDate?->locale('ar')->diffForHumans() }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex flex-col items-center">
                                                @if ($parsedResponseDate)
                                                    @php
                                                        $diff = $request->created_at->diff($parsedResponseDate);
                                                        $parts = [];
                                                        if ($diff->d > 0) $parts[] = $diff->d . ' يوم';
                                                        if ($diff->h > 0) $parts[] = $diff->h . ' ساعة';
                                                        if ($diff->i > 0) $parts[] = $diff->i . ' دقيقة';
                                                        $formattedDiff = implode(', ', $parts);
                                                    @endphp

                                                    <span class="text-sm font-medium text-gray-900">
                {{ $formattedDiff }}
            </span>

                                                @else
                                                    <span class="text-sm font-medium text-gray-900">-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <!-- Actions -->
                                        <td class="px-8 py-5 whitespace-nowrap text-sm font-medium">
                                            @if ($request->status === 'pending')
                                                @if (($role && $role->hasPermissionTo('review_employee_requests')) || Auth::user()->role === 'admin')
                                                    <div class="flex space-x-3 space-x-reverse">
                                                        <!-- Approve Button -->
                                                        <form
                                                            action="{{ route('employee-request.change-status', $request->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit"
                                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-150">
                                                                <svg class="w-4 h-4 mr-1" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                الموافقة
                                                            </button>
                                                        </form>

                                                        <!-- Reject Button -->
                                                        <form
                                                            action="{{ route('employee-request.change-status', $request->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit"
                                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-150">
                                                                <svg class="w-4 h-4 mr-1" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                                رفض
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span
                                                        class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">
                                                        قيد الانتظار
                                                    </span>
                                                @endif
                                            @else
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded
            @if ($request->status === 'approved') bg-green-100 text-green-800
            @else bg-red-100 text-red-800 @endif">
                                                    @if ($request->status === 'approved')
                                                        مقبول
                                                    @else
                                                        مرفوض
                                                    @endif
                                                </span>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan=" 8" class="px-8 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                                <h3 class="mt-4 text-lg font-medium text-gray-700">لا توجد طلبات</h3>
                                                <p class="mt-1 text-sm text-gray-500">لا يوجد أي طلبات تعديل لعرضها حالياً
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
