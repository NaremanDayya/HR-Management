@extends('layouts.master')
@section('title', 'سجل عمل الموظفين')
@push('styles')
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #740e0e 0%, #ed5565 100%);
        }
        .hover-lift {
            transition: all 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .export-loading {
            display: none;
            background: rgba(116, 14, 14, 0.9);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
        }
        .export-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <!-- Export Loading Overlay -->
    <div id="exportLoading" class="export-loading">
        <div class="export-spinner">
            <i class="fas fa-spinner fa-spin fa-3x mb-4"></i>
            <p class="text-xl">جاري تحضير التقرير...</p>
        </div>
    </div>

    <div class="min-h-screen">
        <!-- Header -->
        <header class="gradient-bg text-white shadow-lg">
            <div class="container mx-auto px-4 py-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">
                        <i class="fas fa-history ml-2"></i>
                        سجل عمل الموظفين
                    </h1>
                    <div class="flex space-x-3 space-x-reverse">
                        <button onclick="exportToExcel()" class="bg-white text-[#740e0e] px-4 py-2 rounded-lg hover:bg-gray-100 transition flex items-center">
                            <i class="fas fa-file-excel ml-2"></i>
                            تصدير Excel
                        </button>
                        <button onclick="exportToPDF()" class="bg-white text-[#740e0e] px-4 py-2 rounded-lg hover:bg-gray-100 transition flex items-center">
                            <i class="fas fa-file-pdf ml-2"></i>
                            تصدير PDF
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Rest of your content remains the same -->
        <!-- Filters -->
        <div class="container mx-auto px-4 py-6">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">البحث بالاسم</label>
                        <input type="text" id="searchInput" placeholder="ابحث باسم الموظف..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#740e0e] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                        <input type="date" id="startDate"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#740e0e] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                        <input type="date" id="endDate"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#740e0e] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                        <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#740e0e] focus:border-transparent">
                            <option value="">جميع الحالات</option>
                            <option value="active">نشط فقط</option>
                            <option value="inactive">منتهي فقط</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button onclick="applyFilters()" class="bg-[#740e0e] text-white px-6 py-2 rounded-lg hover:bg-[#5d0a0a] transition">
                        <i class="fas fa-filter ml-2"></i>
                        تطبيق الفلترة
                    </button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover-lift">
                    <div class="text-3xl font-bold text-[#740e0e]">{{ $summary['total_periods'] ?? 0 }}</div>
                    <div class="text-gray-600">إجمالي الفترات</div>
                    <i class="fas fa-calendar-alt text-[#740e0e] text-2xl mt-2"></i>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover-lift">
                    <div class="text-3xl font-bold text-green-600">{{ $summary['active_periods'] ?? 0 }}</div>
                    <div class="text-gray-600">فترات نشطة</div>
                    <i class="fas fa-play-circle text-green-600 text-2xl mt-2"></i>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover-lift">
                    <div class="text-3xl font-bold text-blue-600">{{ $summary['total_work_days'] ?? 0 }}</div>
                    <div class="text-gray-600">إجمالي أيام العمل</div>
                    <i class="fas fa-clock text-blue-600 text-2xl mt-2"></i>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover-lift">
                    <div class="text-3xl font-bold text-purple-600">{{ round($summary['average_period_days'] ?? 0) }}</div>
                    <div class="text-gray-600">متوسط المدة (أيام)</div>
                    <i class="fas fa-chart-line text-purple-600 text-2xl mt-2"></i>
                </div>
            </div>

            <!-- Work History Table -->
            <div id="workHistoryTable" class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700">الموظف</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700">الفترة الزمنية</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700">المدة</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700">الحالة</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700">سبب الإيقاف</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700">التفاصيل</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($histories as $history)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="bg-[#740e0e] text-white rounded-full w-10 h-10 flex items-center justify-center">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="mr-3">
                                            <div class="font-medium text-gray-900">{{ $history->employee->user->name ?? 'غير محدد' }}</div>
                                            <div class="text-sm text-gray-500">{{ $history->employee->user->email ?? 'غير محدد' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $history->period_text }}</div>
                                    <div class="text-xs text-gray-500">{{ $history->work_days }} يوم</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                        {{ $history->duration_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="status-badge {{ $history->is_active ? 'status-active' : 'status-inactive' }}">
                                        <i class="fas {{ $history->is_active ? 'fa-play' : 'fa-stop' }} ml-1"></i>
                                        {{ $history->is_active ? 'نشط' : 'منتهي' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">{{ $history->stop_reason ?? '---' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <button onclick="showDetails({{ $history->id }})"
                                            class="text-[#740e0e] hover:text-[#5d0a0a] transition">
                                        <i class="fas fa-eye"></i>
                                        عرض
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $histories->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Include html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        // Show loading overlay
        function showLoading() {
            document.getElementById('exportLoading').style.display = 'block';
        }

        // Hide loading overlay
        function hideLoading() {
            document.getElementById('exportLoading').style.display = 'none';
        }

        // Apply filters function with AJAX
        function applyFilters() {
            showLoading();

            const search = document.getElementById('searchInput').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const status = document.getElementById('statusFilter').value;

            // Build query parameters
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            if (status) params.append('status', status);
            params.append('ajax', true);

            fetch(`{{ request()->url() }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateTable(data.data);
                        updateSummary(data.summary);
                        updatePagination(data.pagination);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'حدث خطأ أثناء التصفية');
                })
                .finally(() => {
                    hideLoading();
                });
        }

        // Update table with new data
        function updateTable(histories) {
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = '';

            histories.forEach(history => {
                const row = `
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="bg-[#740e0e] text-white rounded-full w-10 h-10 flex items-center justify-center">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="mr-3">
                                <div class="font-medium text-gray-900">${history.employee?.user?.name || 'غير محدد'}</div>
                                <div class="text-sm text-gray-500">${history.employee?.user?.email || 'غير محدد'}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">${history.period_text}</div>
                        <div class="text-xs text-gray-500">${history.work_days} يوم</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            ${history.duration_text}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="status-badge ${history.is_active ? 'status-active' : 'status-inactive'}">
                            <i class="fas ${history.is_active ? 'fa-play' : 'fa-stop'} ml-1"></i>
                            ${history.is_active ? 'نشط' : 'منتهي'}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600">${history.stop_reason || '---'}</span>
                    </td>
                    <td class="px-6 py-4">
                        <button onclick="showDetails(${history.id})"
                                class="text-[#740e0e] hover:text-[#5d0a0a] transition">
                            <i class="fas fa-eye"></i>
                            عرض
                        </button>
                    </td>
                </tr>
            `;
                tbody.innerHTML += row;
            });
        }

        // Update summary cards
        function updateSummary(summary) {
            document.querySelector('.grid > div:nth-child(1) .text-3xl').textContent = summary.total_periods || 0;
            document.querySelector('.grid > div:nth-child(2) .text-3xl').textContent = summary.active_periods || 0;
            document.querySelector('.grid > div:nth-child(3) .text-3xl').textContent = summary.total_work_days || 0;
            document.querySelector('.grid > div:nth-child(4) .text-3xl').textContent = Math.round(summary.average_period_days) || 0;
        }

        // Update pagination (simplified version)
        function updatePagination(pagination) {
            // You can implement dynamic pagination update here
            console.log('Pagination updated:', pagination);
        }

        // Excel Export Function with filters
        function exportToExcel() {
            showLoading();

            const search = document.getElementById('searchInput').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const status = document.getElementById('statusFilter').value;

            // Build export URL with current filters
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            if (status) params.append('status', status);
            params.append('export', 'excel');

            window.location.href = `{{ request()->url() }}?${params.toString()}`;

            // Hide loading after a delay (since we're redirecting)
            setTimeout(() => {
                hideLoading();
                showNotification('success', 'جاري تحميل ملف Excel');
            }, 1000);
        }

        // PDF Export Function
        function exportToPDF() {
            showLoading();

            try {
                // Your existing PDF export code remains the same
                const element = document.getElementById('workHistoryTable').cloneNode(true);
                const buttons = element.querySelectorAll('button');
                buttons.forEach(btn => btn.remove());

                const pdfContent = document.createElement('div');
                pdfContent.style.padding = '20px';
                pdfContent.style.fontFamily = 'Arial, Tahoma, sans-serif';
                pdfContent.style.direction = 'rtl';
                pdfContent.style.textAlign = 'right';

                // ... rest of your PDF code remains unchanged

            } catch (error) {
                console.error('PDF export error:', error);
                showNotification('error', 'حدث خطأ أثناء تصدير PDF');
                hideLoading();
            }
        }

        // Notification function
        function showNotification(type, message) {
            const notification = document.createElement('div');
            notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            z-index: 10000;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;

            if (type === 'success') {
                notification.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            } else {
                notification.style.background = 'linear-gradient(135deg, #dc3545, #e83e8c)';
            }

            notification.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'}" style="margin-left: 8px;"></i>
            ${message}
        `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Add event listeners for Enter key in search
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });

        // Show details function
        function showDetails(historyId) {
            // Implement your modal or details view here
            alert('عرض التفاصيل للسجل: ' + historyId);
        }

        // Add CSS animations for notifications
        const style = document.createElement('style');
        style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
        document.head.appendChild(style);
    </script>
@endpush
