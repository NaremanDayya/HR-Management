@extends('layouts.master')
@section('title', 'جدول الاستبدالات')

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
        .replacement-gradient-bg {
            background: linear-gradient(135deg, #f5f3ff 0%, #ddd6fe 100%);
        }

        .replacement-header-gradient {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        }

        .replacement-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .replacement-hover-effect:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .replacement-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .replacement-cell {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .replacement-row:hover .replacement-cell {
            background-color: #f5f3ff;
        }

        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-left: 12px;
        }

        .status-badge {
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
        }

        .status-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .date-badge {
            background: #ede9fe;
            color: #7c3aed;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .reason-content {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.4;
        }

        .empty-state-replacement {
            padding: 3rem;
            text-align: center;
            background: #f8fafc;
            border-radius: 0 0 12px 12px;
        }

        .empty-icon-replacement {
            font-size: 3.5rem;
            color: #ddd6fe;
            margin-bottom: 1.5rem;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .print-btn {
            background: #7c3aed;
            color: white;
        }

        .print-btn:hover {
            background: #6d28d9;
            transform: translateY(-2px);
        }

        .add-btn {
            background: #10b981;
            color: white;
        }

        .add-btn:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .employee-link {
            display: flex;
            align-items: center;
            color: #334155;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .employee-link:hover {
            color: #7c3aed;
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-xl overflow-hidden mb-6 replacement-shadow replacement-gradient-bg">
            <div class="bg-gray-50 hover:bg-gray-100 border-l-4 border-purple-500 rounded-lg px-6 py-4 flex items-center justify-between text-black transition-all duration-300">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-purple-100 p-3 rounded-full group-hover:bg-purple-200 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">بدائل الموظف</h2>
                        <p class="text-gray-600">{{ $oldEmployee->name }}</p>
                    </div>
                    <form method="GET" action="{{ route('employees.replacements', $oldEmployee->id) }}">
                        <select name="year" onchange="this.form.submit()"
                                class="bg-purple-100 hover:bg-purple-200 text-gray-800 px-3 py-1 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-300 transition-all text-sm">
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
        <span class="bg-purple-100 hover:bg-purple-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ $oldEmployee->replacements()->count() }} استبدالات
        </span>
                    <button id="pdfExportBtn"
                            class="px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-white">PDF</span>
                    </button>

                    <!-- Export Excel Button -->
                    <button id="excelExportBtn"
                            class="px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-white">Excel</span>
                    </button>
                    <button onclick="window.print()"
                            class="bg-gray-100 hover:bg-purple-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                  clip-rule="evenodd" />
                        </svg>
                        <span>طباعة</span>
                    </button>
                    <a href="{{ route('employees.replacements.all') }}"
                       class="bg-purple-100 hover:bg-purple-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>جميع الإستبدالات</span>
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input
                                    type="text"
                                    id="liveSearch"
                                    placeholder="ابحث في الموظف البديل أو سبب الاستبدال..."
                                    class="search-input text-sm text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-0 focus:border-none"
                                >
                                <button id="clearSearch" class="text-gray-400 hover:text-purple-600 transition-colors hidden">
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

        <!-- Replacements Table -->
        <div class="bg-white rounded-xl overflow-hidden replacement-shadow">
            @if ($oldEmployee->replacements->isEmpty())
                <div class="empty-state-replacement">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 empty-icon-replacement" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <h5 class="text-lg font-medium text-gray-700">لا يوجد بدائل</h5>
                    <p class="text-sm mt-1 text-gray-500">لم يتم تسجيل أي بدائل لهذا الموظف</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                #</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الموظف البديل</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                تاريخ التعيين</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                تاريخ آخر يوم عمل</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                سبب الاستبدال</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100" id="replacementsTableBody">
                        @foreach ($oldEmployee->replacements as $index => $replacement)
                            <tr class="replacement-row hover:bg-purple-50 transition-colors">
                                <td
                                    class="replacement-cell px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    {{ $index + 1 }}
                                </td>

                                <td class="replacement-cell px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('employees.show', $replacement->newEmployee->id) }}"
                                       class="employee-link">
                                        <div class="employee-avatar">
                                            {{ substr($replacement->newEmployee->name, 0, 1) }}
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $replacement->newEmployee->name }}</div>
                                            <div class="text-xs text-gray-500">#{{ $replacement->newEmployee->id }}
                                            </div>
                                        </div>
                                    </a>
                                </td>

                                <td class="replacement-cell px-6 py-4 whitespace-nowrap text-center">
                                        <span class="date-badge">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $replacement->replacement_date->format('Y-m-d') }}
                                        </span>
                                </td>

                                <td class="replacement-cell px-6 py-4 whitespace-nowrap text-center">
                                        <span class="date-badge">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $replacement->last_working_date->format('Y-m-d') }}
                                        </span>
                                </td>

                                <td class="replacement-cell px-6 py-4 text-sm text-gray-500 text-center">
                                    <div class="reason-content mx-auto" style="max-width: 250px;">
                                        {{ $replacement->reason }}
                                    </div>
                                </td>

                                <td class="replacement-cell px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $isActive = $replacement->newEmployee->account_status === 'active';
                                    @endphp
                                    <span class="status-badge {{ $isActive ? 'status-active' : 'status-inactive' }}">
                                            {{ $isActive ? 'نشط' : 'غير نشط' }}
                                        </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Reason Modal -->
    <div class="modal fade" id="reasonModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-purple-600 text-white">
                    <h5 class="modal-title">سبب الاستبدال الكامل</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="fullReasonText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
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
            tableRows = document.querySelectorAll('#replacementsTableBody tr');

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
                        if (searchTerm === '') {
                            row.style.display = '';
                            return;
                        }

                        // Get new employee name (2nd column) and reason (5th column)
                        const newEmployeeCell = row.cells[1];
                        const reasonCell = row.cells[4];

                        const newEmployeeName = newEmployeeCell?.textContent?.trim().toLowerCase() || '';
                        const reasonText = reasonCell?.textContent?.trim().toLowerCase() || '';

                        // Check if search term matches new employee OR reason
                        const matchesNewEmployee = newEmployeeName.includes(searchTerm);
                        const matchesReason = reasonText.includes(searchTerm);

                        // Show row if any condition is true
                        if (matchesNewEmployee || matchesReason) {
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
                        row.style.display = '';
                    });

                    updateEmptyState();
                });
            }

            // Reason modal handler
            const reasonModal = document.getElementById('reasonModal');
            if (reasonModal) {
                reasonModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const reason = button.getAttribute('data-reason');
                    document.getElementById('fullReasonText').textContent = reason;
                });
            }

            // Add event listeners for export buttons
            document.getElementById('excelExportBtn')?.addEventListener('click', exportToExcel);
            document.getElementById('pdfExportBtn')?.addEventListener('click', exportToPDF);
        });

        // Function to update empty state message
        function updateEmptyState() {
            const visibleRows = Array.from(tableRows).filter(row => {
                return row.style.display !== 'none';
            });

            // If no visible rows, you could show a message (though your view already has empty state)
            if (visibleRows.length === 0) {
                // Optional: Show a "no results" message
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

                // Prepare data with proper cell formatting
                const headers = Array.from(tableClone.querySelectorAll('thead th'))
                    .map(th => th.textContent.trim());

                const data = [
                    headers,
                    ...Array.from(tableClone.querySelectorAll('tbody tr')).map(row => {
                        return Array.from(row.querySelectorAll('td')).map(td => {
                            // Get clean text content without HTML
                            let text = td.textContent.trim();

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
                    if (index === 1) return { wch: 25 };  // الموظف البديل
                    if (index === 2) return { wch: 15 };  // تاريخ التعيين
                    if (index === 3) return { wch: 15 };  // تاريخ آخر يوم عمل
                    if (index === 4) return { wch: 40 };  // سبب الاستبدال
                    if (index === 5) return { wch: 12 };  // الحالة
                    return { wch: 15 }; // Default width
                });

                ws['!cols'] = colWidths;

                // Add auto filter
                ws['!autofilter'] = { ref: XLSX.utils.encode_range({
                        s: { r: 0, c: 0 },
                        e: { r: data.length, c: headers.length - 1 }
                    }) };

                XLSX.utils.book_append_sheet(wb, ws, "بدائل الموظف");
                XLSX.writeFile(wb, `بدائل_الموظف_{{ $oldEmployee->name }}_${new Date().toISOString().slice(0, 10)}.xlsx`);

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

        // Export to PDF function with proper styling
        async function exportToPDF() {
            const visibleRows = Array.from(tableRows).filter(row => {
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

                const pdfContainer = document.createElement('div');
                pdfContainer.style.padding = '20px';
                pdfContainer.style.direction = 'rtl';
                pdfContainer.style.fontFamily = 'Arial, sans-serif';
                pdfContainer.style.textAlign = 'center';
                pdfContainer.style.lineHeight = '1.6';

                // Create header with proper styling
                const header = document.createElement('div');
                header.style.marginBottom = '20px';
                header.style.borderBottom = '2px solid #6b46c1';
                header.style.paddingBottom = '15px';
                header.style.textAlign = 'center';

                const companyName = document.createElement('h1');
                companyName.textContent = 'شركة افاق الخليج';
                companyName.style.color = '#6b46c1';
                companyName.style.margin = '0 0 10px 0';
                companyName.style.fontSize = '24px';
                companyName.style.fontWeight = 'bold';
                companyName.style.letterSpacing = 'normal';
                companyName.style.wordSpacing = 'normal';

                const title = document.createElement('h2');
                title.textContent = 'تقرير بدائل الموظف - {{ $oldEmployee->name }}';
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
                    th.style.backgroundColor = '#6b46c1';
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
                    filename: `بدائل_الموظف_{{ $oldEmployee->name }}_${new Date().toISOString().slice(0, 10)}.pdf`,
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
