@extends('layouts.master')
@section('title', 'جدول السلف لجميع الموظفين')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Amiri&display=swap');
        .table th {
            font-weight: 600;
            color: #374151;
            font-size: 13px;
        }

        .table td {
            font-weight: 600;
            color: #374151;
            font-size: 13px;
        }

        * {
            font-size: 14px;
            font-weight: 700;
        }

        .advance-gradient-bg {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        }

        .advance-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .advance-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .advance-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .advance-cell {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .advance-row:hover .advance-cell {
            background-color: #f5fbff;
        }

        .amount-cell {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        .status-badge {
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        #pdfExportBtn {
            background-color: #e74c3c;
        }

        #pdfExportBtn:hover {
            background-color: #c0392b;
        }

        #excelExportBtn {
            background-color: #2ecc71;
        }

        #excelExportBtn:hover {
            background-color: #27ae60;
        }

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

        .search-input:focus {
            outline: none !important;
            box-shadow: none !important;
            border: none !important;
        }

        .focus\:outline-none:focus {
            outline: none !important;
        }

        .focus\:ring-0:focus {
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(0px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        }

        .search-input::placeholder {
            color: #9ca3af;
        }

        #liveSearch:focus {
            outline: none;
        }

        #clearSearch {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .hidden {
            display: none !important;
        }

        tbody tr {
            transition: all 0.3s ease;
        }

        .employee-link {
            transition: all 0.2s ease;
        }

        .employee-link:hover {
            color: #1e40af !important;
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-xl overflow-hidden mb-6 advance-shadow advance-gradient-bg">
            <div class="bg-blue-50 hover:bg-blue-100 border-l-4 border-blue-500 rounded-lg px-6 py-4 flex items-center justify-between text-black transition-all duration-300">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-blue-100 p-3 rounded-full group-hover:bg-blue-200 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">سلف الموظفين</h2>
                        <p class="text-gray-600">إدارة طلبات السلف المقدمة من الموظفين</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <span class="bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
                        {{ count($advances) }} طلب
                    </span>
                    <button id="pdfExportBtn"
                            class="px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-white">PDF</span>
                    </button>

                    <button id="excelExportBtn"
                            class="px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-white">Excel</span>
                    </button>
                    <button onclick="window.print()"
                            class="bg-gray-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                  clip-rule="evenodd" />
                        </svg>
                        <span>طباعة</span>
                    </button>
                    <a href="{{ route('employees.advances.deductions.all') }}"
                       class="bg-blue-100 hover:bg-blue-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v8m0 0H6m6 0h6M4 6h16M4 6v12a2 2 0 002 2h12a2 2 0 002-2V6M4 6l8 6 8-6"/>
                        </svg>
                        <span>خصومات السلف</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl overflow-hidden alert-shadow mb-4 table-header-bg">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Search Bar - Right Side -->
                    <div class="flex-1 flex justify-end">
                        <div class="w-full max-w-md">
                            <div class="search-container px-4 py-2 flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input
                                    type="text"
                                    id="liveSearch"
                                    placeholder="ابحث في الموظفين أو السبب..."
                                    class="search-input text-sm text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-0 focus:border-none"
                                >
                                <button id="clearSearch" class="text-gray-400 hover:text-blue-600 transition-colors hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advances Table -->
        <div class="bg-white rounded-xl overflow-hidden advance-shadow">
            <div class="overflow-x-auto">
                <table class="table min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">#
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الموظف
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            المبلغ
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            النسبة
                        </th>

                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            مدير المشروع
                        </th>

                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            المشرف
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            منطقة العمل
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            السبب
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            تاريخ الطلب
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            تاريخ المعالجة
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            مدة الاستجابة
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الحالة
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($advances as $index => $advance)
                        <tr class="advance-row hover:bg-blue-50 transition-colors">
                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ $index + 1 }}
                            </td>

                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center space-y-1">
                                    <a href="{{ route('employees.advances', $advance->employee->id) }}"
                                       class="employee-link text-sm font-medium text-gray-900 hover:text-blue-600">
                                        {{ $advance->employee->user->name ?? '-' }}
                                    </a>
                                </div>
                            </td>

                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center amount-cell text-blue-800">
                                {{ number_format($advance->amount) }} ر.س
                            </td>

                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $salary = $advance->employee->salary ?? 0;
                                    $percentage = $salary > 0 ? round(($advance->amount / $salary) * 100, 2) : 0;
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $percentage }}%
                                </span>
                            </td>

                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                @if ($advance->manager)
                                    <div class="flex flex-col items-center space-y-1">
                                        <span class="text-sm text-gray-700">{{ $advance->manager->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">{{$advance->employee?->supervisor?->name}}</td>
                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">{{$advance->employee?->work_area}}</td>
                            <td class="advance-cell px-6 py-4 text-sm text-gray-500 text-center">
                                <div class="mx-auto" style="max-width: 200px;">{{ $advance->reason ?? '-' }}</div>
                            </td>

                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ $advance->created_at->format('Y-m-d') }}</span>
                                    <span
                                        class="text-xs text-gray-500">{{ $advance->created_at->locale('ar')->diffForHumans() }}</span>
                                </div>
                            </td>

                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                @if ($advance->approved_at)
                                    <div class="flex flex-col items-center">
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ $advance->approved_at->format('Y-m-d') }}</span>
                                        <span
                                            class="text-xs text-gray-500">{{ $advance->approved_at->locale('ar')->diffForHumans() }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    @if ($advance->approved_at)
                                        @php
                                            $diff = $advance->created_at->diff($advance->approved_at);
                                            $parts = [];
                                            if ($diff->d > 0) $parts[] = $diff->d . ' يوم';
                                            if ($diff->h > 0) $parts[] = $diff->h . ' ساعة';
                                            if ($diff->i > 0) $parts[] = $diff->i . ' دقيقة';
                                            $formattedDiff = implode(', ', $parts);
                                        @endphp

                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $formattedDiff }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $advance->created_at->diffForHumans($advance->approved_at) }}
                                        </span>
                                    @else
                                        <span class="text-sm font-medium text-gray-900">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="advance-cell px-6 py-4 whitespace-nowrap text-center">
                                @if ($advance->status == 'approved')
                                    <span
                                        class="status-badge inline-flex items-center rounded-full bg-green-100 text-green-800">
                                        <svg class="ml-0.5 mr-1.5 h-2 w-2 text-green-500" fill="currentColor"
                                             viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        معتمدة
                                    </span>
                                @elseif($advance->status == 'rejected')
                                    <span
                                        class="status-badge inline-flex items-center rounded-full bg-red-100 text-red-800">
                                        <svg class="ml-0.5 mr-1.5 h-2 w-2 text-red-500" fill="currentColor"
                                             viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        مرفوضة
                                    </span>
                                @else
                                    <span
                                        class="status-badge inline-flex items-center rounded-full bg-yellow-100 text-yellow-800">
                                        <svg class="ml-0.5 mr-1.5 h-2 w-2 text-yellow-500" fill="currentColor"
                                             viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        قيد الانتظار
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-lg">لا توجد سلف</p>
                                    <p class="text-sm mt-1">لم يتم تقديم أي طلبات سلف حتى الآن</p>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Live Search Variables
            const liveSearch = document.getElementById('liveSearch');
            const clearSearch = document.getElementById('clearSearch');
            const tableRows = document.querySelectorAll('tbody tr');

            // Live search functionality
            liveSearch.addEventListener('input', function() {
                const searchTerm = this.value.trim().toLowerCase();

                // Show/hide clear button
                if (searchTerm.length > 0) {
                    clearSearch.classList.remove('hidden');
                } else {
                    clearSearch.classList.add('hidden');
                }

                // Filter table rows
                tableRows.forEach(row => {
                    // Skip the empty state row
                    if (row.cells.length === 1) return;

                    if (searchTerm === '') {
                        row.style.display = '';
                        return;
                    }

                    // Get employee name (2nd column) and reason (8th column)
                    const employeeCell = row.cells[1];
                    const reasonCell = row.cells[7];

                    const employeeName = employeeCell?.textContent?.trim().toLowerCase() || '';
                    const reasonText = reasonCell?.textContent?.trim().toLowerCase() || '';

                    // Check if search term matches employee name OR reason
                    const matchesEmployee = employeeName.includes(searchTerm);
                    const matchesReason = reasonText.includes(searchTerm);

                    // Show row if either condition is true
                    if (matchesEmployee || matchesReason) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update empty state message
                updateEmptyState();
            });

            // Clear search functionality
            clearSearch.addEventListener('click', function() {
                liveSearch.value = '';
                liveSearch.focus();
                this.classList.add('hidden');

                // Show all rows
                tableRows.forEach(row => {
                    if (row.cells.length === 1) return; // Skip empty state row
                    row.style.display = '';
                });

                updateEmptyState();
            });

            // Function to update empty state message
            function updateEmptyState() {
                const visibleRows = Array.from(tableRows).filter(row => {
                    // Skip the empty state row in the count
                    if (row.cells.length === 1) return false;
                    return row.style.display !== 'none';
                });

                const emptyStateRow = document.querySelector('tbody tr:last-child');

                // Check if the last row is the empty state row (has only 1 cell)
                const isEmptyState = emptyStateRow && emptyStateRow.cells.length === 1;

                if (isEmptyState) {
                    if (visibleRows.length > 0) {
                        emptyStateRow.style.display = 'none';
                    } else {
                        emptyStateRow.style.display = '';
                    }
                }
            }

            // Export to Excel function with proper cell formatting
            function exportToExcel() {
                if (typeof XLSX === 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'مكتبة Excel غير محملة',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                const visibleRows = Array.from(tableRows).filter(row => {
                    if (row.cells.length === 1) return false; // Skip empty state row
                    return row.style.display !== 'none';
                });

                if (visibleRows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'لا توجد بيانات',
                        text: 'لا توجد صفوف مرئية للتصدير',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                try {
                    const table = document.querySelector('table.table');
                    if (!table) throw new Error('Table not found');

                    const tableClone = table.cloneNode(true);

                    // Remove empty state row from export
                    const emptyStateRow = tableClone.querySelector('tbody tr:last-child');
                    if (emptyStateRow && emptyStateRow.cells.length === 1) {
                        emptyStateRow.remove();
                    }

                    // Prepare data with proper cell formatting
                    const headers = Array.from(tableClone.querySelectorAll('thead th'))
                        .map(th => th.textContent.trim());

                    const data = [
                        headers,
                        ...Array.from(tableClone.querySelectorAll('tbody tr')).map(row => {
                            return Array.from(row.querySelectorAll('td')).map(td => {
                                // Get clean text content without HTML
                                let text = td.textContent.trim();

                                // Handle currency formatting
                                if (text.includes('ر.س')) {
                                    text = text.replace('ر.س', '').trim();
                                }

                                // Remove any extra spaces and normalize text
                                text = text.replace(/\s+/g, ' ').trim();

                                return text;
                            });
                        })
                    ];

                    // Create workbook with proper column widths
                    const wb = XLSX.utils.book_new();
                    const ws = XLSX.utils.aoa_to_sheet(data);

                    // Set column widths for better readability
                    const colWidths = headers.map((_, index) => {
                        // Set different widths based on column content
                        if (index === 0) return { wch: 5 };  // #
                        if (index === 1) return { wch: 20 }; // الموظف
                        if (index === 2) return { wch: 15 }; // المبلغ
                        if (index === 3) return { wch: 10 }; // النسبة
                        if (index === 4) return { wch: 15 }; // مدير المشروع
                        if (index === 5) return { wch: 15 }; // المشرف
                        if (index === 6) return { wch: 15 }; // منطقة العمل
                        if (index === 7) return { wch: 25 }; // السبب
                        if (index === 8) return { wch: 12 }; // تاريخ الطلب
                        if (index === 9) return { wch: 12 }; // تاريخ المعالجة
                        if (index === 10) return { wch: 15 }; // مدة الاستجابة
                        if (index === 11) return { wch: 12 }; // الحالة
                        return { wch: 15 }; // Default width
                    });

                    ws['!cols'] = colWidths;

                    // Add auto filter
                    ws['!autofilter'] = { ref: XLSX.utils.encode_range({
                            s: { r: 0, c: 0 },
                            e: { r: data.length, c: headers.length - 1 }
                        }) };

                    XLSX.utils.book_append_sheet(wb, ws, "سلف الموظفين");
                    XLSX.writeFile(wb, `سلف_الموظفين_${new Date().toISOString().slice(0, 10)}.xlsx`);

                } catch (error) {
                    console.error('Excel export error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: `حدث خطأ أثناء تصدير ملف Excel: ${error.message}`,
                        confirmButtonText: 'حسناً'
                    });
                }
            }

            // Export to PDF function with proper Arabic text handling
            async function exportToPDF() {
                const visibleRows = Array.from(tableRows).filter(row => {
                    if (row.cells.length === 1) return false; // Skip empty state row
                    return row.style.display !== 'none';
                });

                if (visibleRows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'لا توجد بيانات',
                        text: 'لا توجد صفوف مرئية للتصدير',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                if (typeof html2pdf === 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'مكتبة PDF غير محملة',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                try {
                    const table = document.querySelector('table.table');
                    if (!table) throw new Error('Table not found');

                    const tableClone = table.cloneNode(true);

                    // Remove empty state row from PDF
                    const emptyStateRow = tableClone.querySelector('tbody tr:last-child');
                    if (emptyStateRow && emptyStateRow.cells.length === 1) {
                        emptyStateRow.remove();
                    }

                    // FIX 1: Apply Arabic font to status badges in the cloned table
                    tableClone.querySelectorAll('.status-badge').forEach(badge => {
                        badge.style.fontFamily = "'Segoe UI', 'Tahoma', 'Arial', sans-serif";
                        badge.style.direction = 'rtl';
                        badge.style.unicodeBidi = 'bidi-override';
                    });

                    const pdfContainer = document.createElement('div');
                    pdfContainer.style.padding = '20px';
                    pdfContainer.style.direction = 'rtl';
                    pdfContainer.style.fontFamily = "'Segoe UI', Tahoma, Arial, sans-serif"; // FIX 2: Use better font stack
                    pdfContainer.style.textAlign = 'center';
                    pdfContainer.style.lineHeight = '1.6';

                    // Create header with proper Arabic text
                    const header = document.createElement('div');
                    header.style.marginBottom = '20px';
                    header.style.borderBottom = '2px solid #3b82f6';
                    header.style.paddingBottom = '15px';
                    header.style.textAlign = 'center';

                    const companyName = document.createElement('h1');
                    companyName.textContent = 'شركة افاق الخليج';
                    companyName.style.color = '#3b82f6';
                    companyName.style.margin = '0 0 10px 0';
                    companyName.style.fontSize = '24px';
                    companyName.style.fontWeight = 'bold';
                    companyName.style.letterSpacing = 'normal';
                    companyName.style.wordSpacing = 'normal';
                    companyName.style.fontFamily = "'Segoe UI', Tahoma, sans-serif"; // FIX 3: Consistent Arabic font

                    const title = document.createElement('h2');
                    title.textContent = 'تقرير سلف جميع الموظفين';
                    title.style.color = '#333';
                    title.style.margin = '0 0 10px 0';
                    title.style.fontSize = '18px';
                    title.style.fontWeight = '600';
                    title.style.letterSpacing = 'normal';
                    title.style.wordSpacing = 'normal';
                    title.style.fontFamily = "'Segoe UI', Tahoma, sans-serif"; // FIX 4: Consistent Arabic font

                    const reportDate = document.createElement('p');
                    reportDate.textContent = 'تاريخ التقرير: ' + new Date().toLocaleDateString('ar-EG');
                    reportDate.style.color = '#666';
                    reportDate.style.margin = '0';
                    reportDate.style.fontSize = '14px';
                    reportDate.style.letterSpacing = 'normal';
                    reportDate.style.wordSpacing = 'normal';
                    reportDate.style.fontFamily = "'Segoe UI', Tahoma, sans-serif"; // FIX 5: Consistent Arabic font

                    header.appendChild(companyName);
                    header.appendChild(title);
                    header.appendChild(reportDate);
                    pdfContainer.appendChild(header);

                    // Style table for PDF with proper text handling
                    tableClone.style.width = '100%';
                    tableClone.style.borderCollapse = 'collapse';
                    tableClone.style.marginTop = '20px';
                    tableClone.style.direction = 'rtl'; // FIX 6: Ensure RTL direction
                    tableClone.style.fontSize = '8px';
                    tableClone.style.fontFamily = "'Segoe UI', Tahoma, Arial, sans-serif"; // FIX 7: Better font support

                    // Style table headers
                    tableClone.querySelectorAll('th').forEach(th => {
                        th.style.backgroundColor = '#3b82f6';
                        th.style.color = 'white';
                        th.style.padding = '8px 4px';
                        th.style.border = '1px solid #ddd';
                        th.style.textAlign = 'center';
                        th.style.fontWeight = 'bold';
                        th.style.fontSize = '9px';
                        th.style.letterSpacing = 'normal';
                        th.style.wordSpacing = 'normal';
                        th.style.whiteSpace = 'nowrap';
                        th.style.fontFamily = "'Segoe UI', Tahoma, sans-serif"; // FIX 8: Header Arabic font
                    });

                    // Style table cells
                    tableClone.querySelectorAll('td').forEach(td => {
                        td.style.padding = '6px 4px';
                        td.style.border = '1px solid #ddd';
                        td.style.textAlign = 'center';
                        td.style.fontSize = '8px';
                        td.style.letterSpacing = 'normal';
                        td.style.wordSpacing = 'normal';
                        td.style.whiteSpace = 'normal';
                        td.style.wordBreak = 'break-word';
                        td.style.fontFamily = "'Segoe UI', Tahoma, sans-serif"; // FIX 9: Cell Arabic font

                        // FIX 10: Specifically target status cells for better Arabic handling
                        if (td.classList.contains('advance-cell') && td.querySelector('.status-badge')) {
                            td.style.fontFamily = "'Segoe UI', Tahoma, sans-serif";
                            td.style.direction = 'rtl';
                        }
                    });

                    pdfContainer.appendChild(tableClone);

                    // Add footer
                    const footer = document.createElement('div');
                    footer.style.marginTop = '20px';
                    footer.style.paddingTop = '10px';
                    footer.style.borderTop = '1px solid #eee';
                    footer.style.textAlign = 'center';
                    footer.style.color = '#666';
                    footer.style.fontSize = '10px';

                    const copyright = document.createElement('p');
                    copyright.textContent = `© ${new Date().getFullYear()} جميع الحقوق محفوظة لشركة افاق الخليج`;
                    copyright.style.margin = '5px 0';
                    copyright.style.letterSpacing = 'normal';
                    copyright.style.wordSpacing = 'normal';
                    copyright.style.fontFamily = "'Segoe UI', Tahoma, sans-serif"; // FIX 11: Footer Arabic font
                    footer.appendChild(copyright);
                    pdfContainer.appendChild(footer);

                    // PDF options with better Arabic text handling
                    const options = {
                        margin: [10, 10, 15, 10],
                        filename: `سلف_الموظفين_${new Date().toISOString().slice(0, 10)}.pdf`,
                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 2,
                            useCORS: true,
                            letterRendering: true,
                            onclone: function(clonedDoc) {
                                clonedDoc.documentElement.dir = 'rtl';
                                clonedDoc.body.style.direction = 'rtl';
                                clonedDoc.body.style.textAlign = 'right';
                                clonedDoc.body.style.fontFamily = "'Segoe UI', Tahoma, Arial, sans-serif"; // FIX 12: Global Arabic font

                                // FIX 13: Additional Arabic text enhancements
                                clonedDoc.querySelectorAll('*').forEach(element => {
                                    element.style.fontFamily = "'Segoe UI', Tahoma, Arial, sans-serif";
                                    element.style.direction = element.style.direction || 'rtl';
                                });
                            }
                        },
                        jsPDF: {
                            unit: 'mm',
                            format: 'a3',
                            orientation: 'landscape'
                        }
                    };

                    // Show loading
                    const btn = document.getElementById('pdfExportBtn');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
                    btn.disabled = true;

                    await html2pdf().set(options).from(pdfContainer).save();

                    // Restore button
                    btn.innerHTML = originalText;
                    btn.disabled = false;

                } catch (error) {
                    console.error('PDF export error:', error);
                    // Restore button in case of error
                    const btn = document.getElementById('pdfExportBtn');
                    btn.innerHTML = '<i class="fas fa-file-pdf"></i> PDF';
                    btn.disabled = false;

                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: `حدث خطأ أثناء تصدير ملف PDF: ${error.message}`,
                        confirmButtonText: 'حسناً'
                    });
                }
            }

            // Add event listeners with loading states
            document.getElementById('excelExportBtn')?.addEventListener('click', function() {
                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
                btn.disabled = true;

                setTimeout(() => {
                    exportToExcel();
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 100);
            });

            document.getElementById('pdfExportBtn')?.addEventListener('click', exportToPDF);
        });
    </script>
@endpush
