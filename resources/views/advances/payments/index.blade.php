@extends('layouts.master')
@section('title', 'جميع دفعات السلف')

@push('styles')
    <style>
        .table th { font-weight: 600; color: #374151; font-size: 13px; }
        .table td { font-weight: 600; color: #374151; font-size: 13px; }
        * { font-size: 14px; font-weight: 700; }
        
        .payment-gradient-bg { background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); }
        .payment-shadow { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
        .payment-row:hover .payment-cell { background-color: #f5fbff; }
        
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-postponed { background: #fce7f3; color: #9f1239; }
        
        .search-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .search-container:focus-within {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .search-input {
            background: transparent;
            border: none;
            outline: none;
            width: 100%;
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="rounded-xl overflow-hidden mb-6 payment-shadow payment-gradient-bg">
            <div class="bg-blue-50 hover:bg-blue-100 border-l-4 border-blue-500 rounded-lg px-6 py-4 flex items-center justify-between text-black transition-all duration-300">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">دفعات السلف</h2>
                        <p class="text-gray-600">{{ isset($employee) ? $employee->user->name : 'جميع الموظفين' }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <span class="bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
                        {{ $payments->count() }} دفعة
                    </span>
                    @if(isset($employee))
                        <a href="{{ route('employees.advances', $employee->id) }}" 
                           class="bg-blue-100 hover:bg-blue-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>رجوع للسلف</span>
                        </a>
                    @else
                        <a href="{{ route('employees.advances.all') }}" 
                           class="bg-blue-100 hover:bg-blue-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>رجوع للسلف</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-xl overflow-hidden payment-shadow mb-4">
            <div class="px-6 py-4">
                <div class="search-container px-4 py-2 flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="liveSearch" placeholder="ابحث في الموظفين أو المبالغ..." 
                           class="search-input text-sm text-gray-700 placeholder-gray-500">
                    <button id="clearSearch" class="text-gray-400 hover:text-blue-600 transition-colors hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-white rounded-xl overflow-hidden payment-shadow">
            <div class="overflow-x-auto">
                <table class="table min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">#</th>
                            @if(!isset($employee))
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">الموظف</th>
                            @endif
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">رقم الدفعة</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">تاريخ الاستحقاق</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($payments as $index => $payment)
                            <tr class="payment-row hover:bg-blue-50 transition-colors">
                                <td class="payment-cell px-6 py-4 whitespace-nowrap text-sm text-center">{{ $index + 1 }}</td>
                                @if(!isset($employee))
                                    <td class="payment-cell px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('advances.payments.show', $payment->advance_id) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $payment->employee->user->name ?? '-' }}
                                        </a>
                                    </td>
                                @endif
                                <td class="payment-cell px-6 py-4 whitespace-nowrap text-center">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                        #{{ $payment->payment_number }}
                                    </span>
                                </td>
                                <td class="payment-cell px-6 py-4 whitespace-nowrap text-center font-bold text-blue-800">
                                    {{ number_format($payment->amount) }} ر.س
                                </td>
                                <td class="payment-cell px-6 py-4 whitespace-nowrap text-center">
                                    <div>{{ $payment->scheduled_date->format('Y-m-d') }}</div>
                                    @if($payment->original_scheduled_date && $payment->original_scheduled_date != $payment->scheduled_date)
                                        <div class="text-xs text-orange-600">الأصلي: {{ $payment->original_scheduled_date->format('Y-m-d') }}</div>
                                    @endif
                                </td>
                                <td class="payment-cell px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold status-{{ $payment->status }}">
                                        @if($payment->status == 'paid') مدفوعة
                                        @elseif($payment->status == 'postponed') مؤجلة
                                        @else معلقة
                                        @endif
                                    </span>
                                </td>
                                <td class="payment-cell px-6 py-4 whitespace-nowrap text-center">
                                    @if($payment->status == 'pending')
                                        <div class="flex justify-center space-x-2 rtl:space-x-reverse">
                                            <button onclick="markAsPaid({{ $payment->id }})" 
                                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs transition-all">
                                                دفع
                                            </button>
                                            <button onclick="showPostponeModal({{ $payment->id }})" 
                                                    class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-xs transition-all">
                                                تأجيل
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ isset($employee) ? 6 : 7 }}" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-lg">لا توجد دفعات</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                    <button type="button" onclick="closePostponeModal()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">إلغاء</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">تأجيل</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Search functionality
        const liveSearch = document.getElementById('liveSearch');
        const clearSearch = document.getElementById('clearSearch');
        const tableRows = document.querySelectorAll('tbody tr');

        liveSearch.addEventListener('input', function() {
            const searchTerm = this.value.trim().toLowerCase();
            clearSearch.classList.toggle('hidden', searchTerm.length === 0);

            tableRows.forEach(row => {
                if (row.cells.length === 1) return;
                if (searchTerm === '') {
                    row.style.display = '';
                    return;
                }

                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        clearSearch.addEventListener('click', function() {
            liveSearch.value = '';
            liveSearch.dispatchEvent(new Event('input'));
            liveSearch.focus();
        });

        // Postpone modal
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
                    }).then(() => location.reload());
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
                        }).then(() => location.reload());
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
