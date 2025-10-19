@extends('layouts.master')
@section('title', 'سجل عمل الموظفين')
@push('styles')
    <style>

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
        .export-btn {
            position: relative;
            min-width: 120px;
            justify-content: center;
        }

        .export-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .export-btn .btn-spinner {
            display: none;
        }

        .export-btn.loading .btn-text,
        .export-btn.loading .btn-icon {
            opacity: 0;
        }

        .export-btn.loading .btn-spinner {
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .export-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
        }
        .export-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 6px;
            background-color: #6e48aa;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
            min-width: 120px;
            justify-content: center;
        }

        .export-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .export-btn .btn-spinner {
            display: none;
        }

        .export-btn.loading .btn-text,
        .export-btn.loading .btn-icon {
            opacity: 0;
        }

        .export-btn.loading .btn-spinner {
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .export-btn:hover {
            background-color: #5a3a8a;
            transform: translateY(-1px);
        }

        .export-btn .btn-icon {
            font-size: 14px;
        }

        .export-btn .btn-text {
            margin-right: 5px;
        }

        /* Specific colors for different export types */
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
        <header class="bg-gray-50 text-black shadow-lg">
            <div class="container full-width px-4 py-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">
                        <i class="fas fa-history ml-2"></i>
                        سجل عمل الموظفين
                    </h1>
                    <div class="flex space-x-3 space-x-reverse">
                        <button id="pdfExportBtn" onclick="exportToPDF()" class="export-btn">
        <span class="btn-spinner">
            <i class="fas fa-spinner fa-spin"></i>
        </span>
                            <span class="btn-icon"><i class="fas fa-file-pdf"></i></span>
                            <span class="btn-text">تصدير PDF</span>
                        </button>

                        <button id="excelExportBtn" onclick="exportToExcel()" class="export-btn">
        <span class="btn-spinner">
            <i class="fas fa-spinner fa-spin"></i>
        </span>
                            <span class="btn-icon"><i class="fas fa-file-excel"></i></span>
                            <span class="btn-text">تصدير Excel</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Rest of your content remains the same -->
        <!-- Filters -->
        <div class="container full-width px-4 py-6">
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">المشروع</label>
                        <select id="projectFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#740e0e] focus:border-transparent">
                            <option value="">جميع المشاريع</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button onclick="resetFilters()" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-redo ml-2"></i>
                        إعادة التعيين
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
                            <th class="px-6 py-4 text-right font-semibold text-gray-700">المشروع</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700">الفترة الزمنية</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700">المدة</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700">الحالة</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700">سبب الإيقاف</th>
{{--                            <th class="px-6 py-4 text-right font-semibold text-gray-700">التفاصيل</th>--}}
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
                                            <a href="{{route('employees.histories',$history->employee->id)}}" >
                                            <div class="font-medium text-gray-900">{{ $history->employee->user->name ?? 'غير محدد' }}</div>
                                            <div class="text-sm text-gray-500">{{ $history->employee->user->email ?? 'غير محدد' }}</div>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                                        {{ $history->employee?->project?->name ?? '-'}}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $history->period_text }}</div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    // Loading state functions - simplified without overlay
    function showLoading() {
        // Minimal loading state - you can add a small spinner if needed
        document.body.style.cursor = 'wait';
    }

    function hideLoading() {
        document.body.style.cursor = 'default';
    }

    // Debounce function to limit API calls
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Initialize filters from URL on page load
    function initializeFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);

        document.getElementById('searchInput').value = urlParams.get('search') || '';
        document.getElementById('startDate').value = urlParams.get('start_date') || '';
        document.getElementById('endDate').value = urlParams.get('end_date') || '';
        document.getElementById('statusFilter').value = urlParams.get('status') || '';
        document.getElementById('projectFilter').value = urlParams.get('project') || '';
    }

    // Update URL without page reload
    function updateURL(params) {
        const newUrl = window.location.origin + window.location.pathname + '?' + params.toString();
        window.history.pushState({}, '', newUrl);
    }

    // Apply filters function with AJAX and URL update
    function applyFilters() {
        showLoading();

        const search = document.getElementById('searchInput').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const status = document.getElementById('statusFilter').value;
        const project = document.getElementById('projectFilter').value;

        // Build query parameters
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (startDate) params.append('start_date', startDate);
        if (endDate) params.append('end_date', endDate);
        if (status) params.append('status', status);
        if (project) params.append('project', project);

        // Keep existing pagination if any
        const currentPage = new URLSearchParams(window.location.search).get('page');
        if (currentPage) {
            params.append('page', currentPage);
        }

        // Don't include ajax=true in URL - only in fetch request
        const fetchParams = new URLSearchParams(params);
        fetchParams.append('ajax', true);

        // Update URL without ajax parameter
        updateURL(params);

        fetch(`{{ request()->url() }}?${fetchParams.toString()}`, {
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

    // Debounced version of applyFilters for live filtering
    const debouncedApplyFilters = debounce(applyFilters, 500);

    // Handle pagination clicks to preserve filters
    function handlePaginationClick(event) {
        event.preventDefault();

        const url = new URL(event.target.href);
        const currentParams = new URLSearchParams(window.location.search);

        // Get all current filters
        const search = currentParams.get('search');
        const startDate = currentParams.get('start_date');
        const endDate = currentParams.get('end_date');
        const status = currentParams.get('status');
        const project = currentParams.get('project');

        // Build new URL with filters and the clicked page
        const newParams = new URLSearchParams();
        if (search) newParams.append('search', search);
        if (startDate) newParams.append('start_date', startDate);
        if (endDate) newParams.append('end_date', endDate);
        if (status) newParams.append('status', status);
        if (project) newParams.append('project', project);

        // Add the page from the clicked link
        const page = url.searchParams.get('page');
        if (page) {
            newParams.append('page', page);
        }

        // Don't include ajax=true in URL
        const fetchParams = new URLSearchParams(newParams);
        fetchParams.append('ajax', true);

        showLoading();

        fetch(`{{ request()->url() }}?${fetchParams.toString()}`, {
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
                    updateURL(newParams); // Update URL without ajax parameter
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'حدث خطأ أثناء تحميل الصفحة');
            })
            .finally(() => {
                hideLoading();
            });
    }

    // Update pagination links to use AJAX
    function updatePagination(pagination) {
        const paginationContainer = document.querySelector('.bg-gray-50.px-6.py-4');
        if (paginationContainer) {
            // Reattach event listeners to new pagination links
            setTimeout(() => {
                const paginationLinks = paginationContainer.querySelectorAll('a[href*="page"]');
                paginationLinks.forEach(link => {
                    link.addEventListener('click', handlePaginationClick);
                });
            }, 100);
        }
    }

    // Set export button loading state
    function setExportButtonLoading(buttonId, isLoading) {
        const button = document.getElementById(buttonId);
        if (isLoading) {
            button.classList.add('loading');
            button.disabled = true;
        } else {
            button.classList.remove('loading');
            button.disabled = false;
        }
    }

    // Excel Export Function with current filters
    function exportToExcel() {
        setExportButtonLoading('excelExportBtn', true);

        try {
            const table = document.querySelector('table.w-full');
            if (!table) {
                throw new Error('Table not found');
            }

            // Get all table data
            const data = [];
            const rows = table.querySelectorAll('tr');

            // Process headers - add work days as separate column
            const headerRow = [];
            const headerCells = rows[0].querySelectorAll('th');
            headerCells.forEach(cell => {
                headerRow.push(cell.textContent.trim());
            });
            // Add "أيام العمل" column header after period column
            headerRow.splice(3, 0, "أيام العمل");
            data.push(headerRow);

            // Process data rows
            for (let i = 1; i < rows.length; i++) {
                const rowData = [];
                const cells = rows[i].querySelectorAll('td');

                cells.forEach((cell, index) => {
                    let cellContent = cell.textContent.trim();

                    // For period column (index 2), extract only the period text without work days
                    if (index === 2) {
                        const periodText = cell.querySelector('.text-sm.text-gray-900');
                        cellContent = periodText ? periodText.textContent.trim() : cellContent;

                        // Extract work days for the new column
                        const workDaysElement = cell.querySelector('.text-xs.text-gray-500');
                        const workDaysText = workDaysElement ? workDaysElement.textContent : '';
                        const workDaysMatch = workDaysText.match(/(\d+)\s*يوم/);
                        const workDays = workDaysMatch ? workDaysMatch[1] : '0';

                        // Insert work days after period column
                        if (index === 2) {
                            rowData.push(cellContent); // Period text
                            rowData.push(workDays);    // Work days
                        }
                    } else {
                        rowData.push(cellContent);
                    }
                });

                data.push(rowData);
            }

            // Create workbook
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(data);

            // Set column widths
            const colWidths = [
                { width: 30 },  // Employee
                { width: 20 },  // Project
                { width: 25 },  // Period
                { width: 15 },  // Work Days (new column)
                { width: 15 },  // Duration
                { width: 15 },  // Status
                { width: 25 }   // Stop Reason
            ];
            ws['!cols'] = colWidths;

            // Add to workbook and download
            XLSX.utils.book_append_sheet(wb, ws, "سجل العمل");
            const fileName = `سجل_عمل_الموظفين_${new Date().toISOString().slice(0, 10)}.xlsx`;
            XLSX.writeFile(wb, fileName);

            setExportButtonLoading('excelExportBtn', false);
            showNotification('success', 'تم تحميل ملف Excel بنجاح');

        } catch (error) {
            console.error('Excel export error:', error);
            setExportButtonLoading('excelExportBtn', false);
            showNotification('error', 'حدث خطأ أثناء تصدير Excel');
        }
    }

    // PDF Export Function
    function exportToPDF() {
        setExportButtonLoading('pdfExportBtn', true);

        try {
            const element = document.getElementById('workHistoryTable');
            const buttons = element.querySelectorAll('button');
            buttons.forEach(btn => btn.remove());

            // Create PDF container with header and footer
            const pdfContainer = document.createElement('div');
            pdfContainer.style.padding = '20px';
            pdfContainer.style.direction = 'rtl';
            pdfContainer.style.fontFamily = 'Arial, Tahoma, sans-serif';
            pdfContainer.style.textAlign = 'right';

            // Create header
            const header = document.createElement('div');
            header.style.marginBottom = '30px';
            header.style.borderBottom = '2px solid #740e0e';
            header.style.paddingBottom = '15px';
            header.style.display = 'flex';
            header.style.justifyContent = 'space-between';
            header.style.alignItems = 'center';

            const reportInfo = document.createElement('div');
            const reportTitle = document.createElement('h1');
            reportTitle.textContent = 'تقرير سجل عمل الموظفين';
            reportTitle.style.color = '#740e0e';
            reportTitle.style.margin = '0 0 5px 0';
            reportTitle.style.fontSize = '20px';
            reportTitle.style.fontWeight = 'bold';

            const reportDate = document.createElement('p');
            reportDate.textContent = 'تاريخ التقرير: ' + new Date().toLocaleDateString('ar-EG');
            reportDate.style.color = '#666';
            reportDate.style.margin = '0';
            reportDate.style.fontSize = '14px';

            reportInfo.appendChild(reportTitle);
            reportInfo.appendChild(reportDate);

            // Add logo (optional)
            const logoContainer = document.createElement('div');
            const logo = document.createElement('img');
            logo.src = '{{ asset('build/assets/img/logo.png') }}';
            logo.style.maxWidth = '80px';
            logo.style.maxHeight = '50px';
            logo.alt = 'شعار الشركة';

            logo.onerror = function() {
                this.style.display = 'none';
                const fallbackText = document.createElement('div');
                fallbackText.textContent = 'الشعار';
                fallbackText.style.width = '80px';
                fallbackText.style.height = '50px';
                fallbackText.style.display = 'flex';
                fallbackText.style.alignItems = 'center';
                fallbackText.style.justifyContent = 'center';
                fallbackText.style.backgroundColor = '#f5f5f5';
                fallbackText.style.borderRadius = '4px';
                fallbackText.style.fontWeight = 'bold';
                fallbackText.style.fontSize = '12px';
                logoContainer.appendChild(fallbackText);
            };

            logoContainer.appendChild(logo);
            header.appendChild(reportInfo);
            header.appendChild(logoContainer);

            // Create a new table for PDF with the updated structure
            const pdfTable = document.createElement('table');
            pdfTable.style.width = '100%';
            pdfTable.style.borderCollapse = 'collapse';
            pdfTable.style.marginTop = '20px';
            pdfTable.style.fontSize = '12px';

            // Create table header
            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');

            const headers = ['الموظف', 'المشروع', 'الفترة الزمنية', 'أيام العمل', 'المدة', 'الحالة', 'سبب الإيقاف'];

            headers.forEach(headerText => {
                const th = document.createElement('th');
                th.textContent = headerText;
                th.style.backgroundColor = '#740e0e';
                th.style.color = 'white';
                th.style.padding = '10px';
                th.style.border = '1px solid #ddd';
                th.style.textAlign = 'right';
                th.style.fontSize = '12px';
                headerRow.appendChild(th);
            });

            thead.appendChild(headerRow);
            pdfTable.appendChild(thead);

            // Create table body
            const tbody = document.createElement('tbody');
            const rows = element.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const pdfRow = document.createElement('tr');

                cells.forEach((cell, index) => {
                    const pdfCell = document.createElement('td');
                    pdfCell.style.padding = '8px';
                    pdfCell.style.border = '1px solid #ddd';
                    pdfCell.style.textAlign = 'right';
                    pdfCell.style.fontSize = '11px';

                    if (index === 2) {
                        // Period column - extract only period text
                        const periodText = cell.querySelector('.text-sm.text-gray-900');
                        pdfCell.textContent = periodText ? periodText.textContent.trim() : cell.textContent.trim();

                        // Create separate cell for work days
                        const workDaysCell = document.createElement('td');
                        workDaysCell.style.padding = '8px';
                        workDaysCell.style.border = '1px solid #ddd';
                        workDaysCell.style.textAlign = 'right';
                        workDaysCell.style.fontSize = '11px';

                        const workDaysElement = cell.querySelector('.text-xs.text-gray-500');
                        const workDaysText = workDaysElement ? workDaysElement.textContent : '';
                        const workDaysMatch = workDaysText.match(/(\d+)\s*يوم/);
                        workDaysCell.textContent = workDaysMatch ? workDaysMatch[1] : '0';

                        // Add cells to row
                        pdfRow.appendChild(pdfCell); // Period
                        pdfRow.appendChild(workDaysCell); // Work Days
                    } else {
                        pdfCell.innerHTML = cell.innerHTML;
                        // Clean up any HTML tags for text content
                        pdfCell.textContent = cell.textContent.trim();
                        pdfRow.appendChild(pdfCell);
                    }
                });

                tbody.appendChild(pdfRow);
            });

            pdfTable.appendChild(tbody);

            // Create footer
            const footer = document.createElement('div');
            footer.style.marginTop = '30px';
            footer.style.paddingTop = '15px';
            footer.style.borderTop = '1px solid #eee';
            footer.style.textAlign = 'center';
            footer.style.color = '#666';
            footer.style.fontSize = '10px';

            const summaryInfo = document.createElement('p');
            summaryInfo.textContent = `إجمالي السجلات: ${document.querySelector('.grid > div:nth-child(1) .text-3xl').textContent} | السجلات النشطة: ${document.querySelector('.grid > div:nth-child(2) .text-3xl').textContent}`;
            summaryInfo.style.marginBottom = '5px';

            const copyright = document.createElement('p');
            copyright.textContent = `© ${new Date().getFullYear()} جميع الحقوق محفوظة - نظام إدارة الموارد البشرية`;
            copyright.style.margin = '0';

            footer.appendChild(summaryInfo);
            footer.appendChild(copyright);

            // Assemble PDF content
            pdfContainer.appendChild(header);
            pdfContainer.appendChild(pdfTable);
            pdfContainer.appendChild(footer);

            const opt = {
                margin: [15, 15, 25, 15],
                filename: `سجل_عمل_الموظفين_${new Date().toISOString().split('T')[0]}.pdf`,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    scrollX: 0,
                    scrollY: 0
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a2',
                    orientation: 'portrait',
                    compress: true
                }
            };

            // Generate PDF
            html2pdf()
                .set(opt)
                .from(pdfContainer)
                .save()
                .then(() => {
                    setExportButtonLoading('pdfExportBtn', false);
                    showNotification('success', 'تم تصدير ملف PDF بنجاح');
                })
                .catch(error => {
                    console.error('PDF export error:', error);
                    setExportButtonLoading('pdfExportBtn', false);
                    showNotification('error', 'حدث خطأ أثناء تصدير PDF');
                });

        } catch (error) {
            console.error('PDF export error:', error);
            setExportButtonLoading('pdfExportBtn', false);
            showNotification('error', 'حدث خطأ أثناء تصدير PDF');
        }
    }

    // Update table with new data
    function updateTable(histories) {
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = '';

        histories.forEach(history => {
            const projectName = history.employee?.project?.name ||
                history.project_name ||
                history.employee?.project_name ||
                '-';

            const row = `
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="bg-[#740e0e] text-white rounded-full w-10 h-10 flex items-center justify-center">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="mr-3">
                                <a href="employees/${history.employee?.id}/histories">
                                    <div class="font-medium text-gray-900">${history.employee?.user?.name || 'غير محدد'}</div>
                                    <div class="text-sm text-gray-500">${history.employee?.user?.email || 'غير محدد'}</div>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                            ${projectName}
                        </span>
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
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    // Update summary cards
    function updateSummary(summary) {
        const summaryCards = document.querySelectorAll('.grid > div .text-3xl');
        if (summaryCards.length >= 4) {
            summaryCards[0].textContent = summary.total_periods || 0;
            summaryCards[1].textContent = summary.active_periods || 0;
            summaryCards[2].textContent = summary.total_work_days || 0;
            summaryCards[3].textContent = Math.round(summary.average_period_days) || 0;
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize filters from URL
        initializeFiltersFromURL();

        // Add event listeners for live filtering
        document.getElementById('searchInput').addEventListener('input', debouncedApplyFilters);
        document.getElementById('startDate').addEventListener('change', debouncedApplyFilters);
        document.getElementById('endDate').addEventListener('change', debouncedApplyFilters);
        document.getElementById('statusFilter').addEventListener('change', debouncedApplyFilters);
        document.getElementById('projectFilter').addEventListener('change', debouncedApplyFilters);

        // Add event listener for browser back/forward buttons
        window.addEventListener('popstate', function() {
            // Check if we have filter parameters (excluding ajax)
            const urlParams = new URLSearchParams(window.location.search);
            const hasFilters = urlParams.toString().replace('ajax=true', '').trim().length > 0;

            if (hasFilters) {
                initializeFiltersFromURL();
                applyFilters();
            } else {
                window.location.reload();
            }
        });

        // Initialize pagination links
        updatePagination();

        // Check if we have URL parameters and apply filters on initial load
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.toString().includes('search') ||
            urlParams.toString().includes('start_date') ||
            urlParams.toString().includes('end_date') ||
            urlParams.toString().includes('status') ||
            urlParams.toString().includes('project')) {
            // Apply filters after a short delay to ensure DOM is ready
            setTimeout(() => {
                applyFilters();
            }, 500);
        }
    });

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
    function resetFilters() {
        // Clear all filter inputs
        document.getElementById('searchInput').value = '';
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('projectFilter').value = '';

        // Clear URL parameters and reload to base URL
        const newUrl = window.location.origin + window.location.pathname;
        window.history.pushState({}, '', newUrl);

        // Reload the page to get fresh non-AJAX content
        window.location.reload();
    }</script>
@endpush
