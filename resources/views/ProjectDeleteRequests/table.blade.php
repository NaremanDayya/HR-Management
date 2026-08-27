@extends('layouts.master')
@section('title', 'طلبات حذف المشاريع')
@section('content')
    <div class="w-full px-0 mx-0" x-data="{ rejectOpen: false, selected: null }">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-8 py-6 bg-white border-b border-gray-200 shadow-sm">
                <div class="flex flex-col gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
                            <i class="fas fa-trash text-danger"></i>
                            طلبات حذف المشاريع
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">مراجعة وقبول أو رفض طلبات حذف المشاريع</p>
                    </div>

                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="text-sm text-gray-700 self-center">الحالة:</span>

                        <a href="{{ route('project-delete-requests.index', request()->except(['status', 'page'])) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full border transition-all
                {{ request('status') === null ? 'bg-blue-50 border-blue-200 text-blue-600' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                            الكل {{ $allCount }}
                        </a>

                        <a href="{{ route('project-delete-requests.index', ['status' => 'pending'] + request()->except(['status', 'page'])) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full border bg-amber-100 border-amber-300 text-amber-700">
                            قيد الانتظار {{ $pendingCount }}
                        </a>

                        <a href="{{ route('project-delete-requests.index', ['status' => 'approved'] + request()->except(['status', 'page'])) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full border bg-green-100 border-green-300 text-green-700">
                            تم القبول {{ $approvedCount }}
                        </a>

                        <a href="{{ route('project-delete-requests.index', ['status' => 'rejected'] + request()->except(['status', 'page'])) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full border bg-red-100 border-red-300 text-red-700">
                            تم الرفض {{ $rejectedCount }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المشروع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">مقدّم الطلب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">السبب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الطلب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($requests as $req)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $req->project->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $req->requester->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $req->reason ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $req->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($req->status === 'pending')
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-amber-100 text-amber-800">قيد الانتظار</span>
                                    @elseif ($req->status === 'approved')
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">تمت الموافقة</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">مرفوض</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if ($req->status === 'pending')
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('project-delete-requests.approve', $req->id) }}" method="POST"
                                                onsubmit="return confirm('تأكيد الموافقة؟ سيتم إيقاف المشروع وأرشفته.');">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i> موافقة
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                @click="selected = { rejectUrl: @js(route('project-delete-requests.reject', $req->id)) }; rejectOpen = true">
                                                <i class="fas fa-times"></i> رفض
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">
                                            {{ $req->reviewer->name ?? '-' }}
                                            @if ($req->status === 'rejected' && $req->rejection_reason)
                                                — {{ $req->rejection_reason }}
                                            @endif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400">لا توجد طلبات مطابقة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {{ $requests->links() }}
            </div>
        </div>

        <!-- Reject Reason Modal -->
        <div x-show="rejectOpen" x-cloak x-init="$nextTick(() => document.body.appendChild($el))"
            style="position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 1060; display: flex; align-items: center; justify-content: center;"
            @click.self="rejectOpen = false">
            <div style="background:#fff; border-radius:16px; max-width:420px; width:92%; padding:24px;">
                <template x-if="selected">
                    <form :action="selected.rejectUrl" method="POST">
                        @csrf
                        <h5 class="mb-3">سبب الرفض (اختياري)</h5>
                        <textarea name="rejection_reason" rows="3" class="form-control mb-3" placeholder="اكتب سبب الرفض..."></textarea>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary btn-sm" @click="rejectOpen = false">إلغاء</button>
                            <button type="submit" class="btn btn-danger btn-sm">تأكيد الرفض</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
@endsection
