@extends('layouts.master')
@section('title', 'طلباتي')
@section('content')
    <div class="w-full px-0 mx-0">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-8 py-6 bg-white border-b border-gray-200 shadow-sm">
                <div class="flex flex-col gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
                            <i class="fas fa-inbox text-primary"></i>
                            طلباتي
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">الطلبات التي أرسلتها ومتابعة حالتها</p>
                    </div>

                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="text-sm text-gray-700 self-center">الحالة:</span>

                        <a href="{{ route('my-requests.index', request()->except(['status', 'page'])) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full border transition-all
                {{ request('status') === null ? 'bg-blue-50 border-blue-200 text-blue-600' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                            الكل {{ $allCount }}
                        </a>

                        <a href="{{ route('my-requests.index', ['status' => 'pending'] + request()->except(['status', 'page'])) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full border bg-amber-100 border-amber-300 text-amber-700">
                            قيد الانتظار {{ $pendingCount }}
                        </a>

                        <a href="{{ route('my-requests.index', ['status' => 'approved'] + request()->except(['status', 'page'])) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full border bg-green-100 border-green-300 text-green-700">
                            تم القبول {{ $approvedCount }}
                        </a>

                        <a href="{{ route('my-requests.index', ['status' => 'rejected'] + request()->except(['status', 'page'])) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full border bg-red-100 border-red-300 text-red-700">
                            تم الرفض {{ $rejectedCount }}
                        </a>

                        <a href="{{ route('my-requests.index', ['status' => 'cancelled'] + request()->except(['status', 'page'])) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full border bg-gray-100 border-gray-300 text-gray-600">
                            ملغى {{ $cancelledCount }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if ($requests->isEmpty())
                    <div class="text-center py-16 text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <p class="text-lg">لا توجد طلبات مطابقة</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($requests as $req)
                            <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div>
                                        <div class="font-semibold text-gray-900">
                                            {{ $req->requestType->label ?? 'نوع غير معروف' }}
                                        </div>
                                        @if ($req->employee)
                                            <div class="text-xs text-gray-400 mt-0.5">
                                                بخصوص: {{ $req->employee->name }}
                                            </div>
                                        @endif
                                    </div>

                                    @if ($req->status === 'pending')
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-amber-100 text-amber-800 whitespace-nowrap">قيد الانتظار</span>
                                    @elseif ($req->status === 'approved')
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800 whitespace-nowrap">تمت الموافقة</span>
                                    @elseif ($req->status === 'cancelled')
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-200 text-gray-600 whitespace-nowrap">ملغى</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800 whitespace-nowrap">مرفوض</span>
                                    @endif
                                </div>

                                @if ($req->description)
                                    <p class="text-sm text-gray-600 mb-3">{{ $req->description }}</p>
                                @endif

                                <div class="flex items-center justify-between mt-3">
                                    <span class="text-xs text-gray-400">
                                        {{ $req->created_at->format('Y-m-d H:i') }}
                                    </span>

                                    @if ($req->status === 'pending')
                                        <form action="{{ route('my-requests.cancel', $req->id) }}" method="POST"
                                            onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟');">
                                            @csrf
                                            <button type="submit" class="text-xs px-3 py-1.5 rounded-lg border border-red-300 text-red-600 hover:bg-red-50 transition-colors">
                                                <i class="fas fa-times"></i> إلغاء الطلب
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
