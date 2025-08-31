@extends('layouts.master')
@section('title', 'الموظفين المؤقتين')
@section('content')
    <div class="w-full p-3 font-semibold">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">الموظفون المنقولون مؤقتًا للمشاريع</h2>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class=" py-4 text-sm  text-gray-700 tracking-wider text-center">الموظف</th>
                            <th class=" py-4 text-sm  text-gray-700 tracking-wider text-center">من مشروع</th>
                            <th class=" py-4 text-sm  text-gray-700 tracking-wider text-center">إلى مشروع</th>
                            <th class=" py-4 text-sm  text-gray-700 tracking-wider text-center">من الفترة
                            </th>
                            <th class=" py-4 text-sm  text-gray-700 tracking-wider text-center">إلى</th>
                            <th class=" py-4 text-sm  text-gray-700 tracking-wider text-center">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($assignments as $assignment)
                            @php
                                $now = Carbon\Carbon::now();
                                $start = Carbon\Carbon::parse($assignment->start_date);
                                $end = Carbon\Carbon::parse($assignment->end_date);

                                if ($now->lt($start->startOfDay())) {
                                    $status = 'لم يبدأ بعد';
                                    $statusClass = 'bg-blue-50 text-blue-700';
                                } elseif ($now->between($start->startOfDay(), $end->endOfDay())) {
                                    $status = 'نشط';
                                    $statusClass = 'bg-green-50 text-green-700';
                                } else {
                                    $status = 'منتهي';
                                    $statusClass = 'bg-gray-50 text-gray-500';
                                }
                            @endphp

                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class=" py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm  text-gray-900 text-center">
                                                {{ $assignment->employee->user->name ?? 'غير معروف' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class=" py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 text-center">
                                        {{ $assignment->fromProject->name ?? '---' }}</div>
                                </td>
                                <td class=" py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 text-center">
                                        {{ $assignment->toProject->name ?? '---' }}</div>
                                </td>
                                <td class=" py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 text-center">{{ $start->format('Y-m-d') }}</div>
                                </td>
                                <td class=" py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 text-center">{{ $end->format('Y-m-d') }}</div>
                                </td>
                                <td class=" py-4 whitespace-nowrap">
                                    <div class="flex justify-center">
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ $status }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class=" py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-users-slash text-3xl text-gray-300 mb-2"></i>
                                        <p class="text-sm">لا يوجد موظفون مؤقتون حاليًا</p>
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
