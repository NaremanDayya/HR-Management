@extends('layouts.master')
@section('title', 'دفعات السلفة')

@push('styles')
    <style>
        .payment-card {
            transition: all 0.3s ease;
            border-left: 4px solid #3b82f6;
        }
        .payment-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-postponed { background: #fce7f3; color: #9f1239; }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6 mb-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-blue-500 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">دفعات السلفة</h2>
                        <p class="text-gray-600">{{ $advance->employee->user->name }}</p>
                        <p class="text-sm text-gray-500">إجمالي السلفة: {{ number_format($advance->amount) }} ر.س</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <a href="{{ route('employees.advances', $advance->employee_id) }}" 
                       class="bg-white hover:bg-gray-50 px-4 py-2 rounded-lg shadow transition-all flex items-center space-x-2 rtl:space-x-reverse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>رجوع للسلف</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Advance Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg p-4 shadow">
                <div class="text-sm text-gray-500">إجمالي المبلغ</div>
                <div class="text-2xl font-bold text-blue-600">{{ number_format($advance->amount) }} ر.س</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow">
                <div class="text-sm text-gray-500">عدد الدفعات</div>
                <div class="text-2xl font-bold text-green-600">{{ $payments->count() }}</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow">
                <div class="text-sm text-gray-500">المدفوع</div>
                <div class="text-2xl font-bold text-green-600">{{ number_format($payments->where('status', 'paid')->sum('amount')) }} ر.س</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow">
                <div class="text-sm text-gray-500">المتبقي</div>
                <div class="text-2xl font-bold text-orange-600">{{ number_format($payments->where('status', 'pending')->sum('amount')) }} ر.س</div>
            </div>
        </div>

        <!-- Payments List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h3 class="text-lg font-semibold text-gray-800">جدول الدفعات</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($payments as $payment)
                        <div class="payment-card bg-white rounded-lg p-5 shadow-md">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                    <div class="bg-blue-100 rounded-full p-3">
                                        <span class="text-xl font-bold text-blue-600">#{{ $payment->payment_number }}</span>
                                    </div>
                                    <div>
                                        <div class="text-lg font-semibold text-gray-800">{{ number_format($payment->amount) }} ر.س</div>
                                        <div class="text-sm text-gray-500">تاريخ الاستحقاق: {{ $payment->scheduled_date->format('Y-m-d') }}</div>
                                        @if($payment->original_scheduled_date && $payment->original_scheduled_date != $payment->scheduled_date)
                                            <div class="text-xs text-orange-600">التاريخ الأصلي: {{ $payment->original_scheduled_date->format('Y-m-d') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                    <span class="px-4 py-2 rounded-full text-sm font-semibold status-{{ $payment->status }}">
                                        @if($payment->status == 'paid')
                                            مدفوعة
                                        @elseif($payment->status == 'postponed')
                                            مؤجلة
                                        @else
                                            معلقة
                                        @endif
                                    </span>
                                    @if($payment->status == 'pending')
                                        <div class="flex space-x-2 rtl:space-x-reverse">
                                            <button onclick="markAsPaid({{ $payment->id }})" 
                                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition-all">
                                                تحديد كمدفوعة
                                            </button>
                                            <button onclick="showPostponeModal({{ $payment->id }})" 
                                                    class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-sm transition-all">
                                                تأجيل
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if($payment->postpone_reason)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <div class="text-sm text-gray-600">
                                        <span class="font-semibold">سبب التأجيل:</span> {{ $payment->postpone_reason }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-lg">لا توجد دفعات</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Postpone Modal -->
    <div id="postponeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold mb-4">تأجيل الدفعة</h3>
            <form id="postponeForm">
                <input type="hidden" id="paymentId" name="payment_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع التأجيل</label>
                    <select id="postponeType" name="postpone_type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="extra_month">تأجيل لشهر إضافي</option>
                        <option value="new_payment">إضافة دفعة جديدة بعد آخر شهر</option>
                        <option value="new_advance">إنشاء سلفة جديدة</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">التاريخ الجديد</label>
                    <input type="date" id="newDate" name="new_date" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">السبب (اختياري)</label>
                    <textarea id="reason" name="reason" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                </div>

                <div class="flex justify-end space-x-3 rtl:space-x-reverse">
                    <button type="button" onclick="closePostponeModal()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        إلغاء
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        تأجيل
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showPostponeModal(paymentId) {
            document.getElementById('paymentId').value = paymentId;
            document.getElementById('postponeModal').classList.remove('hidden');
        }

        function closePostponeModal() {
            document.getElementById('postponeModal').classList.add('hidden');
            document.getElementById('postponeForm').reset();
        }

        document.getElementById('postponeForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const paymentId = document.getElementById('paymentId').value;
            const formData = {
                postpone_type: document.getElementById('postponeType').value,
                new_date: document.getElementById('newDate').value,
                reason: document.getElementById('reason').value,
                _token: '{{ csrf_token() }}'
            };

            try {
                const response = await fetch(`/advances/payments/${paymentId}/postpone`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم التأجيل بنجاح',
                        text: result.message,
                        confirmButtonText: 'حسناً'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: result.message,
                        confirmButtonText: 'حسناً'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء التأجيل',
                    confirmButtonText: 'حسناً'
                });
            }

            closePostponeModal();
        });

        async function markAsPaid(paymentId) {
            const result = await Swal.fire({
                title: 'تأكيد الدفع',
                text: 'هل أنت متأكد من تحديد هذه الدفعة كمدفوعة؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'نعم، تأكيد',
                cancelButtonText: 'إلغاء'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/advances/payments/${paymentId}/mark-paid`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم بنجاح',
                            text: data.message,
                            confirmButtonText: 'حسناً'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: data.message,
                            confirmButtonText: 'حسناً'
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'حدث خطأ أثناء تحديث حالة الدفعة',
                        confirmButtonText: 'حسناً'
                    });
                }
            }
        }
    </script>
@endpush
