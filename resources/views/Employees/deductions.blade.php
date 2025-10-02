@extends('layouts.master')
@section('title', 'جدول الخصومات')

@push('styles')
    <style>
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
        .deduction-gradient-bg {
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
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
            border-color:mediumpurple;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .search-input {
            background: transparent;
            border: none;
            outline: none;
            width: 100%;
        }

        .search-input::placeholder {
            color: #9ca3af;
        }
        .search-input:focus {
            outline: none !important;
            box-shadow: none !important;
            border: none !important;
        }
        .hidden {
            display: none !important;
        }
        tbody tr {
            transition: all 0.3s ease;
        }
        /*
                .deduction-header-gradient {
                    background: linear-gradient(135deg, #f5365c 0%, #c53030 100%);
                } */

        .deduction-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .deduction-hover-effect:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .deduction-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .deduction-cell {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .deduction-row:hover .deduction-cell {
            background-color: #fff5f5;
        }

        .deduction-amount {
            font-weight: 700;
            color: #c53030;
        }

        .percentage-badge-danger {
            background: rgba(245, 54, 92, 0.1);
            color: #c53030;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
        }

        .salary-before {
            color: #4a5568;
            text-decoration: line-through;
        }

        .salary-after {
            color: #2d3748;
            font-weight: 600;
        }

        .empty-state-deduction {
            padding: 3rem;
            text-align: center;
            background: #f8fafc;
            border-radius: 0 0 12px 12px;
        }

        .empty-icon-deduction {
            font-size: 3.5rem;
            color: #fed7d7;
            margin-bottom: 1.5rem;
        }

        .project-badge {
            background: rgba(66, 153, 225, 0.1);
            color: #2b6cb0;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .table-responsive {
                border: 0;
            }

            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 1.5rem;
                border-radius: 0.75rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }

            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem;
                border-bottom: 1px solid #edf2f7;
            }

            .table tbody td:before {
                content: attr(data-label);
                font-weight: 600;
                color: #4a5568;
                margin-left: 0.5rem;
            }

            .table tbody td:last-child {
                border-bottom: 0;
            }

            .empty-state-deduction {
                padding: 2rem 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-xl overflow-hidden mb-6 deduction-shadow deduction-gradient-bg">
            <div class="bg-gray-50 hover:bg-red-100 border-l-4 border-red-500 rounded-lg px-6 py-4 flex items-center justify-between text-black transition-all duration-300">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-red-100 p-3 rounded-full group-hover:bg-red-200 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">خصومات الموظف</h2>
                        <p class="text-gray-600">{{ $employee->user->name }}</p>
                    </div>
                    <form method="GET" action="{{ route('employees.deductions', $employee->id) }}">
                        <select name="year" onchange="this.form.submit()"
                                class="bg-red-100 hover:bg-red-200 text-gray-800 px-3 py-1 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-300 transition-all text-sm">
                            @php
                                $currentYear = request('year', now()->year);
                                $startYear = now()->year - 10;
                                $endYear = now()->year + 1;
                            @endphp
                            @for ($year = $endYear; $year >= $startYear; $year--)
                                <option value="{{ $year }}" @selected($year == $currentYear)>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </form>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
        <span class="bg-red-100 hover:bg-red-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ number_format($employee->deductions->sum('value')) }} ر.س إجمالي الخصومات
        </span>
                    <span class="bg-red-100 hover:bg-red-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ count($employee->deductions) }} خصم
        </span>
                    <a href="{{ route('employees.deductions.all') }}"
                       class="bg-red-100 hover:bg-red-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-red-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>جميع الخصومات</span>
                    </a>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                  clip-rule="evenodd" />
                        </svg>
                        <span>طباعة</span>
                    </button>
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input
                                    type="text"
                                    id="liveSearch"
                                    placeholder="ابحث في المشاريع أو مدير المشروع أو السبب..."
                                    class="search-input text-sm text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-0 focus:border-none"
                                >
                                <button id="clearSearch" class="text-gray-400 hover:text-red-600 transition-colors hidden">
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
        <!-- Deductions Table -->
        <div class="bg-white rounded-xl overflow-hidden deduction-shadow">
            <div class="overflow-x-auto">
                <table class="table min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">المشروع</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">مدير المشروع</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قيمة الخصم</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الراتب قبل الخصم</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الراتب بعد الخصم</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النسبة</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السبب</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الخصم</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100" id="deductionsTableBody">
                    @forelse($employee->deductions as $index => $deduction)
                        <tr class="deduction-row hover:bg-red-50 transition-colors">
                            <td class="deduction-cell px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ $index + 1 }}
                            </td>

                            <td class="deduction-cell px-6 py-4 whitespace-nowrap text-center">
                                @if ($employee->project)
                                    <span class="project-badge">
                                            {{ $employee->project->name }}
                                        </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="deduction-cell px-6 py-4 whitespace-nowrap text-center">
                                @if ($employee->project && $employee->project->manager)
                                    <div class="flex flex-col items-center space-y-1">
                                        <span class="text-sm text-gray-700">{{ $employee->project->manager->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="deduction-cell px-6 py-4 whitespace-nowrap text-right deduction-amount">
                                {{ number_format($deduction->value) }} ر.س
                            </td>

                            <td class="deduction-cell px-6 py-4 whitespace-nowrap text-right salary-before">
                                {{ number_format($deduction->payload['current_salary'] ?? 0) }} ر.س
                            </td>

                            <td class="deduction-cell px-6 py-4 whitespace-nowrap text-right salary-after">
                                {{ number_format($deduction->payload['new_salary'] ?? 0) }} ر.س
                            </td>

                            <td class="deduction-cell px-6 py-4 whitespace-nowrap text-right">
                                    <span class="percentage-badge-danger">
                                        {{ $deduction->payload['deduction_percentage'] ?? 0 }}%
                                    </span>
                            </td>

                            <td class="deduction-cell px-6 py-4 text-sm text-gray-500 text-right">
                                <div class="mx-auto" style="max-width: 200px;">{{ $deduction->reason }}</div>
                            </td>

                            <td class="deduction-cell px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $deduction->created_at->format('Y-m-d') }}</span>
                                    <span class="text-xs text-gray-500">{{ $deduction->created_at->locale('ar')->diffForHumans() }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="empty-state-deduction">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 empty-icon-deduction" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h5 class="text-lg font-medium text-gray-700">لا يوجد خصومات</h5>
                                    <p class="text-sm mt-1 text-gray-500">لم يتم تسجيل أي خصومات لهذا الموظف</p>
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
        // Define global variables
        let tableRows = [];

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize table rows
            tableRows = document.querySelectorAll('#deductionsTableBody tr');

            // Live Search Variables
            const liveSearch = document.getElementById('liveSearch');
            const clearSearch = document.getElementById('clearSearch');

            // Live search functionality
            if (liveSearch) {
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

                        // Get project name (2nd column), manager name (3rd column), and reason (7th column)
                        const projectCell = row.cells[1];
                        const managerCell = row.cells[2];
                        const reasonCell = row.cells[7];

                        const projectName = projectCell?.textContent?.trim().toLowerCase() || '';
                        const managerName = managerCell?.textContent?.trim().toLowerCase() || '';
                        const reasonText = reasonCell?.textContent?.trim().toLowerCase() || '';

                        // Check if search term matches project name OR manager name OR reason
                        const matchesProject = projectName.includes(searchTerm);
                        const matchesManager = managerName.includes(searchTerm);
                        const matchesReason = reasonText.includes(searchTerm);

                        // Show row if any condition is true
                        if (matchesProject || matchesManager || matchesReason) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Update empty state message
                    updateEmptyState();
                });
            }

            // Clear search functionality
            if (clearSearch) {
                clearSearch.addEventListener('click', function() {
                    if (liveSearch) {
                        liveSearch.value = '';
                        liveSearch.focus();
                    }
                    this.classList.add('hidden');

                    // Show all rows
                    tableRows.forEach(row => {
                        if (row.cells.length === 1) return; // Skip empty state row
                        row.style.display = '';
                    });

                    updateEmptyState();
                });
            }

            // Add event listeners for export buttons
            document.getElementById('excelExportBtn')?.addEventListener('click', exportToExcel);
            document.getElementById('pdfExportBtn')?.addEventListener('click', exportToPDF);
        });

        // Function to update empty state message
        function updateEmptyState() {
            const visibleRows = Array.from(tableRows).filter(row => {
                // Skip the empty state row in the count
                if (row.cells.length === 1) return false;
                return row.style.display !== 'none';
            });

            const emptyStateRow = document.querySelector('#deductionsTableBody tr:last-child');

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

        // Export to Excel function
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
                const emptyStateRow = tableClone.querySelector('#deductionsTableBody tr:last-child');
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

                            // Handle special cases for better formatting
                            if (text.includes('ر.س') || text.includes('SAR')) {
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
                    if (index === 0) return { wch: 5 };   // #
                    if (index === 1) return { wch: 15 };  // المشروع
                    if (index === 2) return { wch: 15 };  // مدير المشروع
                    if (index === 3) return { wch: 12 };  // قيمة الخصم
                    if (index === 4) return { wch: 12 };  // الراتب قبل الخصم
                    if (index === 5) return { wch: 12 };  // الراتب بعد الخصم
                    if (index === 6) return { wch: 8 };   // النسبة
                    if (index === 7) return { wch: 30 };  // السبب
                    if (index === 8) return { wch: 12 };  // تاريخ الخصم
                    return { wch: 15 }; // Default width
                });

                ws['!cols'] = colWidths;

                // Add auto filter
                ws['!autofilter'] = { ref: XLSX.utils.encode_range({
                        s: { r: 0, c: 0 },
                        e: { r: data.length, c: headers.length - 1 }
                    }) };

                XLSX.utils.book_append_sheet(wb, ws, "خصومات الموظف");
                XLSX.writeFile(wb, `خصومات_الموظف_{{ $employee->user->name }}_${new Date().toISOString().slice(0, 10)}.xlsx`);

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
                const emptyStateRow = tableClone.querySelector('#deductionsTableBody tr:last-child');
                if (emptyStateRow && emptyStateRow.cells.length === 1) {
                    emptyStateRow.remove();
                }

                const pdfContainer = document.createElement('div');
                pdfContainer.style.padding = '20px';
                pdfContainer.style.direction = 'rtl';
                pdfContainer.style.fontFamily = 'Arial, sans-serif';
                pdfContainer.style.textAlign = 'center';
                pdfContainer.style.lineHeight = '1.6';

                // Create header with proper Arabic text
                const header = document.createElement('div');
                header.style.marginBottom = '20px';
                header.style.borderBottom = '2px solid #dc2626';
                header.style.paddingBottom = '15px';
                header.style.textAlign = 'center';

                const companyName = document.createElement('h1');
                companyName.textContent = 'شركة افاق الخليج';
                companyName.style.color = '#dc2626';
                companyName.style.margin = '0 0 10px 0';
                companyName.style.fontSize = '24px';
                companyName.style.fontWeight = 'bold';
                companyName.style.letterSpacing = 'normal';
                companyName.style.wordSpacing = 'normal';

                const title = document.createElement('h2');
                title.textContent = 'تقرير خصومات الموظف - {{ $employee->user->name }}';
                title.style.color = '#333';
                title.style.margin = '0 0 10px 0';
                title.style.fontSize = '18px';
                title.style.fontWeight = '600';
                title.style.letterSpacing = 'normal';
                title.style.wordSpacing = 'normal';

                const reportDate = document.createElement('p');
                reportDate.textContent = 'تاريخ التقرير: ' + new Date().toLocaleDateString('ar-EG');
                reportDate.style.color = '#666';
                reportDate.style.margin = '0';
                reportDate.style.fontSize = '14px';
                reportDate.style.letterSpacing = 'normal';
                reportDate.style.wordSpacing = 'normal';

                header.appendChild(companyName);
                header.appendChild(title);
                header.appendChild(reportDate);
                pdfContainer.appendChild(header);

                // Style table for PDF with proper text handling
                tableClone.style.width = '100%';
                tableClone.style.borderCollapse = 'collapse';
                tableClone.style.marginTop = '20px';
                tableClone.style.direction = 'rtl';
                tableClone.style.fontSize = '9px';
                tableClone.style.fontFamily = 'Arial, sans-serif';

                // Style table headers
                tableClone.querySelectorAll('th').forEach(th => {
                    th.style.backgroundColor = '#dc2626';
                    th.style.color = 'white';
                    th.style.padding = '10px 6px';
                    th.style.border = '1px solid #ddd';
                    th.style.textAlign = 'center';
                    th.style.fontWeight = 'bold';
                    th.style.fontSize = '10px';
                    th.style.letterSpacing = 'normal';
                    th.style.wordSpacing = 'normal';
                    th.style.whiteSpace = 'nowrap';
                });

                // Style table cells
                tableClone.querySelectorAll('td').forEach(td => {
                    td.style.padding = '8px 6px';
                    td.style.border = '1px solid #ddd';
                    td.style.textAlign = 'center';
                    td.style.fontSize = '9px';
                    td.style.letterSpacing = 'normal';
                    td.style.wordSpacing = 'normal';
                    td.style.whiteSpace = 'normal';
                    td.style.wordBreak = 'break-word';
                });

                pdfContainer.appendChild(tableClone);

                // Add footer
                const footer = document.createElement('div');
                footer.style.marginTop = '20px';
                footer.style.paddingTop = '10px';
                footer.style.borderTop = '1px solid #eee';
                footer.style.textAlign = 'center';
                footer.style.color = '#666';
                footer.style.fontSize = '11px';

                const copyright = document.createElement('p');
                copyright.textContent = `© ${new Date().getFullYear()} جميع الحقوق محفوظة لشركة افاق الخليج`;
                copyright.style.margin = '5px 0';
                copyright.style.letterSpacing = 'normal';
                copyright.style.wordSpacing = 'normal';
                footer.appendChild(copyright);
                pdfContainer.appendChild(footer);

                // PDF options with better Arabic text handling
                const options = {
                    margin: [15, 15, 20, 15],
                    filename: `خصومات_الموظف_{{ $employee->user->name }}_${new Date().toISOString().slice(0, 10)}.pdf`,
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
                            clonedDoc.body.style.fontFamily = 'Arial, sans-serif';
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
    </script>
@endpush
