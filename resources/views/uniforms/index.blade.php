@extends('layouts.master')

@section('title', 'إدارة اليونيفورم')

@section('content')
<div class="container mx-auto px-4 py-6" dir="rtl">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-tshirt text-red-700"></i>
            إدارة اليونيفورم
        </h1>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: 'table' }" class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="flex border-b border-gray-200">
            <button @click="tab='table'"
                    :class="tab==='table' ? 'border-b-2 border-red-700 text-red-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-4 text-sm transition-colors flex items-center gap-2">
                <i class="fas fa-table"></i> جدول اليونيفورم
            </button>
            <button @click="tab='requests'"
                    :class="tab==='requests' ? 'border-b-2 border-red-700 text-red-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-4 text-sm transition-colors flex items-center gap-2">
                <i class="fas fa-clipboard-list"></i> الطلبات
                @php $pendingCount = $requests->where('status','pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="bg-red-600 text-white text-xs rounded-full px-2 py-0.5">{{ $pendingCount }}</span>
                @endif
            </button>
        </div>

        {{-- TABLE TAB --}}
        <div x-show="tab==='table'" class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase">
                            <th class="px-4 py-3 font-semibold">#</th>
                            <th class="px-4 py-3 font-semibold">الموظف</th>
                            <th class="px-4 py-3 font-semibold">المشروع</th>
                            <th class="px-4 py-3 font-semibold text-center">تيشيرت</th>
                            <th class="px-4 py-3 font-semibold text-center">قبعة</th>
                            <th class="px-4 py-3 font-semibold text-center">بطاقة</th>
                            <th class="px-4 py-3 font-semibold text-center">شنطة</th>
                            <th class="px-4 py-3 font-semibold">تاريخ الاستلام</th>
                            <th class="px-4 py-3 font-semibold text-center">الحالة</th>
                            <th class="px-4 py-3 font-semibold text-center">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($employees as $i => $employee)
                        @php
                            $uniform = $employee->uniform;
                            $eligible = $uniform && $uniform->isEligibleForFree();
                            $daysLeft = $uniform ? $uniform->daysUntilEligible() : null;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors"
                            x-data="uniformRow({{ $employee->id }}, {{ $uniform ? "'" . $uniform->received_at->format('Y-m-d') . "'" : 'null' }})">
                            <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $employee->user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $employee->joining_date }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $employee->project->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" min="0" max="10"
                                       x-model="tshirt"
                                       class="w-14 text-center border border-gray-200 rounded px-1 py-0.5 text-sm focus:ring-1 focus:ring-red-400"
                                       value="{{ $uniform->tshirt_count ?? 0 }}">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" min="0" max="10"
                                       x-model="hat"
                                       class="w-14 text-center border border-gray-200 rounded px-1 py-0.5 text-sm focus:ring-1 focus:ring-red-400"
                                       value="{{ $uniform->hat_count ?? 0 }}">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" min="0" max="10"
                                       x-model="id_card"
                                       class="w-14 text-center border border-gray-200 rounded px-1 py-0.5 text-sm focus:ring-1 focus:ring-red-400"
                                       value="{{ $uniform->id_card_count ?? 0 }}">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" min="0" max="10"
                                       x-model="tool_bag"
                                       class="w-14 text-center border border-gray-200 rounded px-1 py-0.5 text-sm focus:ring-1 focus:ring-red-400"
                                       value="{{ $uniform->tool_bag_count ?? 0 }}">
                            </td>
                            <td class="px-4 py-3">
                                <input type="date" x-model="received_at"
                                       class="border border-gray-200 rounded px-2 py-0.5 text-sm focus:ring-1 focus:ring-red-400">
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($uniform)
                                    @if($eligible)
                                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-medium px-2 py-1 rounded-full">
                                            <i class="fas fa-check-circle"></i> مؤهل مجاناً
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 text-xs font-medium px-2 py-1 rounded-full" title="متبقي {{ $daysLeft }} يوم">
                                            <i class="fas fa-clock"></i> {{ $daysLeft }} يوم
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs">لا يوجد</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button @click="save({{ $employee->id }})"
                                        class="bg-red-700 hover:bg-red-800 text-white text-xs px-3 py-1.5 rounded-lg transition-colors">
                                    حفظ
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-4 py-8 text-center text-gray-400">لا يوجد موظفون نشطون</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- REQUESTS TAB --}}
        <div x-show="tab==='requests'" class="p-6">

            {{-- New Request Form --}}
            <div x-data="{ open: false, type: 'request' }" class="mb-6">
                <button @click="open=!open"
                        class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition-colors">
                    <i class="fas fa-plus"></i> طلب / إرجاع يونيفورم
                </button>

                <div x-show="open" x-transition class="mt-4 bg-gray-50 border border-gray-200 rounded-xl p-5"
                     x-data="uniformRequestForm()">
                    <h3 class="font-semibold text-gray-700 mb-4">طلب جديد</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الموظف</label>
                            <select x-model="employee_id" @change="checkEligibility()"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400">
                                <option value="">اختر موظف</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->id }}"
                                        data-eligible="{{ $emp->uniform && $emp->uniform->isEligibleForFree() ? '1' : '0' }}"
                                        data-days="{{ $emp->uniform ? $emp->uniform->daysUntilEligible() : '' }}">
                                    {{ $emp->user->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">نوع الطلب</label>
                            <select x-model="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400">
                                <option value="request">طلب يونيفورم</option>
                                <option value="return">إرجاع يونيفورم</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">عدد التيشيرتات</label>
                            <input type="number" x-model="tshirt" min="0" max="10"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">عدد القبعات</label>
                            <input type="number" x-model="hat" min="0" max="10"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">بطاقات ID</label>
                            <input type="number" x-model="id_card" min="0" max="10"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">شنط أدوات</label>
                            <input type="number" x-model="tool_bag" min="0" max="10"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                            <textarea x-model="notes" rows="2"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400"
                                      placeholder="أي ملاحظات إضافية..."></textarea>
                        </div>
                    </div>

                    {{-- Cost Warning --}}
                    <div x-show="type==='request' && !eligible && employee_id && tshirt > 0"
                         class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3 flex items-start gap-2">
                        <i class="fas fa-exclamation-triangle text-red-600 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-red-700">تحذير: سيتم خصم 50 ريال لكل تيشيرت</p>
                            <p class="text-xs text-red-500 mt-0.5">لم يمر سنة كاملة على آخر استلام - إجمالي الخصم المتوقع: <span x-text="tshirt * 50"></span> ريال</p>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-3">
                        <button @click="submitRequest()"
                                class="bg-red-700 hover:bg-red-800 text-white px-5 py-2 rounded-lg text-sm transition-colors">
                            إرسال الطلب
                        </button>
                        <button @click="open=false"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-lg text-sm transition-colors">
                            إلغاء
                        </button>
                    </div>
                </div>
            </div>

            {{-- Requests Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase">
                            <th class="px-4 py-3 font-semibold">الموظف</th>
                            <th class="px-4 py-3 font-semibold">النوع</th>
                            <th class="px-4 py-3 font-semibold text-center">تيشيرت</th>
                            <th class="px-4 py-3 font-semibold text-center">قبعة</th>
                            <th class="px-4 py-3 font-semibold text-center">بطاقة</th>
                            <th class="px-4 py-3 font-semibold text-center">شنطة</th>
                            <th class="px-4 py-3 font-semibold">التكلفة</th>
                            <th class="px-4 py-3 font-semibold">الحالة</th>
                            <th class="px-4 py-3 font-semibold">التاريخ</th>
                            <th class="px-4 py-3 font-semibold text-center">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($requests as $req)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $req->employee->user->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($req->type === 'request')
                                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">طلب</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">إرجاع</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">{{ $req->tshirt_count ?: '—' }}</td>
                            <td class="px-4 py-3 text-center">{{ $req->hat_count ?: '—' }}</td>
                            <td class="px-4 py-3 text-center">{{ $req->id_card_count ?: '—' }}</td>
                            <td class="px-4 py-3 text-center">{{ $req->tool_bag_count ?: '—' }}</td>
                            <td class="px-4 py-3">
                                @if($req->cost_amount > 0)
                                    <span class="text-red-600 font-medium">{{ $req->cost_amount }} ريال</span>
                                @else
                                    <span class="text-green-600 text-xs">مجاناً</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($req->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">قيد الانتظار</span>
                                @elseif($req->status === 'approved')
                                    <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">مقبول</span>
                                @else
                                    <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">مرفوض</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $req->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($req->status === 'pending')
                                <div x-data="{ open: false }" class="relative inline-block">
                                    <button @click="open=!open"
                                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs px-3 py-1.5 rounded-lg transition-colors">
                                        مراجعة ▾
                                    </button>
                                    <div x-show="open" @click.outside="open=false" x-transition
                                         class="absolute left-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 p-3 w-64">
                                        @if($req->type === 'request' && $req->tshirt_count > 0 && $req->cost_amount > 0)
                                        <label class="flex items-center gap-2 mb-3 cursor-pointer">
                                            <input type="checkbox" id="deduct_{{ $req->id }}" class="w-4 h-4 text-red-600 rounded" checked>
                                            <span class="text-sm text-gray-700">خصم {{ $req->cost_amount }} ريال على الموظف</span>
                                        </label>
                                        @endif
                                        <div class="flex gap-2">
                                            <button onclick="reviewRequest({{ $req->id }}, 'approved', document.getElementById('deduct_{{ $req->id }}')?.checked ?? false)"
                                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs py-1.5 rounded transition-colors">
                                                قبول
                                            </button>
                                            <button onclick="reviewRequest({{ $req->id }}, 'rejected', false)"
                                                    class="flex-1 bg-red-600 hover:bg-red-700 text-white text-xs py-1.5 rounded transition-colors">
                                                رفض
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-4 py-8 text-center text-gray-400">لا توجد طلبات</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function uniformRow(employeeId, receivedAt) {
    return {
        tshirt: 0,
        hat: 0,
        id_card: 0,
        tool_bag: 0,
        received_at: receivedAt || '',
        save(id) {
            axios.post('/uniforms', {
                employee_id: id,
                tshirt_count: this.tshirt,
                hat_count: this.hat,
                id_card_count: this.id_card,
                tool_bag_count: this.tool_bag,
                received_at: this.received_at,
                _token: document.querySelector('meta[name="csrf-token"]').content
            }).then(r => {
                if (r.data.success) {
                    Swal.fire({ icon: 'success', title: r.data.message, timer: 1500, showConfirmButton: false });
                    setTimeout(() => location.reload(), 1600);
                }
            }).catch(() => Swal.fire({ icon: 'error', title: 'حدث خطأ', timer: 1500 }));
        }
    }
}

function uniformRequestForm() {
    return {
        employee_id: '',
        type: 'request',
        tshirt: 0,
        hat: 0,
        id_card: 0,
        tool_bag: 0,
        notes: '',
        eligible: false,
        checkEligibility() {
            const opt = document.querySelector(`option[value="${this.employee_id}"]`);
            this.eligible = opt?.dataset.eligible === '1';
        },
        submitRequest() {
            axios.post('/uniforms/request', {
                employee_id: this.employee_id,
                type: this.type,
                tshirt_count: this.tshirt,
                hat_count: this.hat,
                id_card_count: this.id_card,
                tool_bag_count: this.tool_bag,
                notes: this.notes,
                _token: document.querySelector('meta[name="csrf-token"]').content
            }).then(r => {
                if (r.data.success) {
                    Swal.fire({ icon: 'success', title: r.data.message, timer: 1500, showConfirmButton: false });
                    setTimeout(() => location.reload(), 1600);
                }
            }).catch(e => {
                const msg = e.response?.data?.message || 'حدث خطأ';
                Swal.fire({ icon: 'error', title: msg, timer: 2000 });
            });
        }
    }
}

function reviewRequest(id, status, deductCost) {
    axios.post(`/uniforms/requests/${id}/review`, {
        status: status,
        deduct_cost: deductCost ? 1 : 0,
        _token: document.querySelector('meta[name="csrf-token"]').content
    }).then(r => {
        if (r.data.success) {
            Swal.fire({ icon: 'success', title: r.data.message, timer: 1500, showConfirmButton: false });
            setTimeout(() => location.reload(), 1600);
        }
    }).catch(() => Swal.fire({ icon: 'error', title: 'حدث خطأ', timer: 1500 }));
}
</script>
@endpush
@endsection
