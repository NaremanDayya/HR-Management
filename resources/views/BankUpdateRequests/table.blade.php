@extends('layouts.master')
@section('title', 'طلبات تعديل البيانات البنكية')
@section('content')
    <div class="w-full px-0 mx-0" x-data="{ detailsOpen: false, rejectOpen: false, selected: null }">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="px-8 py-6 bg-white border-b border-gray-200 shadow-sm">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
                                <i class="fas fa-university text-warning"></i>
                                طلبات تعديل البيانات البنكية
                                @if ($filterEmployee)
                                    <span class="text-base font-normal text-gray-500">— {{ $filterEmployee->name }}</span>
                                @endif
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">مراجعة وقبول أو رفض طلبات تعديل البيانات البنكية للموظفين</p>
                        </div>

                        <div class="relative w-full md:w-64">
                            <form method="GET" action="{{ route('bank-update-requests.index') }}">
                                <input type="text" name="search"
                                    class="w-full pl-4 pr-4 py-2 bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300 placeholder-gray-400 text-gray-700"
                                    placeholder="ابحث باسم الموظف..." value="{{ request('search') }}">
                                @if (request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                @if (request('employee_id'))
                                    <input type="hidden" name="employee_id" value="{{ request('employee_id') }}">
                                @endif
                            </form>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="text-sm text-gray-700 self-center">الحالة:</span>

                        <a href="{{ route('bank-update-requests.index', request()->except(['status', 'page'])) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full border transition-all
                {{ request('status') === null ? 'bg-blue-50 border-blue-200 text-blue-600' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                            الكل {{ $allCount }}
                        </a>

                        <a href="{{ route('bank-update-requests.index', ['status' => 'pending'] + request()->except(['status', 'page'])) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full border bg-amber-100 border-amber-300 text-amber-700">
                            قيد الانتظار {{ $pendingCount }}
                        </a>

                        <a href="{{ route('bank-update-requests.index', ['status' => 'approved'] + request()->except(['status', 'page'])) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full border bg-green-100 border-green-300 text-green-700">
                            تم القبول {{ $approvedCount }}
                        </a>

                        <a href="{{ route('bank-update-requests.index', ['status' => 'rejected'] + request()->except(['status', 'page'])) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full border bg-red-100 border-red-300 text-red-700">
                            تم الرفض {{ $rejectedCount }}
                        </a>

                        @if ($filterEmployee)
                            <a href="{{ route('bank-update-requests.index', request()->except(['employee_id', 'page'])) }}"
                                class="text-sm px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 transition-colors">
                                إزالة فلتر الموظف
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموظف</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الطلب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">البنك الجديد</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">الآيبان الجديد</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($requests as $req)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $req->employee->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $req->employee->project?->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $req->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $req->new_bank_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center" dir="ltr">{{ $req->new_iban }}</td>
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
                                    <button type="button"
                                        @click="selected = {
                                            id: {{ $req->id }},
                                            employee: @js($req->employee->name),
                                            project: @js($req->employee->project?->name),
                                            createdAt: @js($req->created_at->format('Y-m-d H:i')),
                                            status: @js($req->status),
                                            fullName: @js($req->full_name),
                                            accountStatus: @js($req->account_status === 'active' ? 'نشط' : 'غير نشط'),
                                            idCardNumber: @js($req->id_card_number),
                                            mobileNumber: @js($req->mobile_number),
                                            city: @js($req->city),
                                            currentIban: @js($req->current_iban),
                                            currentBank: @js($req->current_bank_name),
                                            currentOwner: @js($req->current_owner_account_name),
                                            newIban: @js($req->new_iban),
                                            newBank: @js($req->new_bank_name),
                                            newOwner: @js($req->new_owner_account_name),
                                            notes: @js($req->notes),
                                            images: @js($req->id_card_image_urls),
                                            rejectionReason: @js($req->rejection_reason),
                                            reviewer: @js($req->reviewer?->name),
                                            approveUrl: @js(route('bank-update-requests.approve', $req->id)),
                                            rejectUrl: @js(route('bank-update-requests.reject', $req->id)),
                                        }; detailsOpen = true"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                        <i class="fas fa-eye me-1"></i> التفاصيل
                                    </button>
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

        <!-- Details Modal -->
        <div x-show="detailsOpen" x-cloak x-init="$nextTick(() => document.body.appendChild($el))"
            style="position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 1050; display: flex; align-items: center; justify-content: center;"
            @click.self="detailsOpen = false">
            <div style="background: #fff; border-radius: 16px; max-width: 560px; width: 92%; max-height: 88vh; overflow-y: auto; padding: 28px;">
                <template x-if="selected">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="mb-0" x-text="selected.employee"></h4>
                                <small class="text-muted" x-text="selected.project"></small>
                            </div>
                            <button type="button" class="btn-close" @click="detailsOpen = false"></button>
                        </div>

                        <p class="text-muted small mb-3">
                            تاريخ الطلب: <span x-text="selected.createdAt"></span>
                        </p>

                        <div class="p-3 rounded mb-3" style="background:#f8f9fa;">
                            <div class="fw-bold mb-2">البيانات الشخصية</div>
                            <div class="row small">
                                <div class="col-6 mb-1">الاسم الثلاثي: <span x-text="selected.fullName"></span></div>
                                <div class="col-6 mb-1">حالة الموظف: <span x-text="selected.accountStatus"></span></div>
                                <div class="col-6 mb-1">رقم الهوية: <span x-text="selected.idCardNumber"></span></div>
                                <div class="col-6 mb-1">رقم الجوال: <span x-text="selected.mobileNumber" dir="ltr"></span></div>
                                <div class="col-6 mb-1">المدينة: <span x-text="selected.city"></span></div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="p-3 rounded" style="background:#fff5f5;">
                                    <div class="fw-bold mb-2 text-danger">البيانات الحالية</div>
                                    <div class="small">صاحب الحساب: <span x-text="selected.currentOwner || '-'"></span></div>
                                    <div class="small">البنك: <span x-text="selected.currentBank || '-'"></span></div>
                                    <div class="small" dir="ltr">IBAN: <span x-text="selected.currentIban || '-'"></span></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded" style="background:#f0fdf4;">
                                    <div class="fw-bold mb-2 text-success">البيانات المطلوبة</div>
                                    <div class="small">صاحب الحساب: <span x-text="selected.newOwner"></span></div>
                                    <div class="small">البنك: <span x-text="selected.newBank"></span></div>
                                    <div class="small" dir="ltr">IBAN: <span x-text="selected.newIban"></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" x-show="selected.notes">
                            <div class="fw-bold mb-1">ملاحظات</div>
                            <p class="small text-muted" x-text="selected.notes"></p>
                        </div>

                        <div class="mb-3">
                            <div class="fw-bold mb-2">صور الهوية</div>
                            <div class="d-flex flex-wrap gap-2">
                                <template x-for="(img, idx) in (selected.images || [])" :key="idx">
                                    <a :href="img" target="_blank">
                                        <img :src="img" style="width:140px; height:140px; object-fit:cover; border-radius:8px; border:1px solid #eee;">
                                    </a>
                                </template>
                            </div>
                        </div>

                        <div class="mb-3" x-show="selected.status !== 'pending'">
                            <div class="small text-muted">
                                <span x-show="selected.status === 'approved'">تمت الموافقة بواسطة <span x-text="selected.reviewer"></span></span>
                                <span x-show="selected.status === 'rejected'">تم الرفض بواسطة <span x-text="selected.reviewer"></span></span>
                                <span x-show="selected.rejectionReason"> — سبب الرفض: <span x-text="selected.rejectionReason"></span></span>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end" x-show="selected.status === 'pending'">
                            <button type="button" class="btn btn-outline-danger btn-sm" @click="rejectOpen = true; detailsOpen = false">
                                <i class="fas fa-times"></i> رفض
                            </button>
                            <form :action="selected.approveUrl" method="POST" @submit="if(!confirm('تأكيد الموافقة وتحديث البيانات البنكية؟')) $event.preventDefault()">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> موافقة
                                </button>
                            </form>
                        </div>
                    </div>
                </template>
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
