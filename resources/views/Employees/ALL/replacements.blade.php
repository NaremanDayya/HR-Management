@extends('layouts.master')
@section('title', 'جدول جميع الاستبدالات')

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
        .replacements-gradient-bg {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        }

        .replacements-header-gradient {
            background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%);
        }

        .replacements-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .replacements-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .replacements-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .replacements-cell {
            transition: all 0.3s ease;
            white-space: nowrap;
            font-size: 14px;
            font-weight: 500;
        }

        .replacements-row:hover .replacements-cell {
            background-color: #f5fbff;
            text-align: center;

        }

        table th,
        table td {
            text-align: center !important;
            vertical-align: middle !important;
        }

        .employee-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #e3f2fd;
            color: #1976d2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-left: 8px;
        }

        .status-badge {
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            border-radius: 50px;
        }

        .status-active {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-inactive {
            background-color: #ffebee;
            color: #c62828;
        }

        .date-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            background-color: #f5f5f5;
            font-size: 0.85rem;
        }

        .date-badge i {
            margin-left: 4px;
            font-size: 0.75rem;
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-xl overflow-hidden mb-6 replacements-shadow replacements-gradient-bg">
            <div class="bg-gray-50 hover:bg-purple-100 border-l-4 border-purple-500 rounded-lg px-6 py-4 flex items-center justify-between text-black transition-all duration-300">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-purple-100 p-3 rounded-full group-hover:bg-purple-200 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">استبدالات الموظفين</h2>
                        <p class="text-gray-600">سجل استبدالات الموظفين في النظام</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
        <span class="bg-purple-100 hover:bg-purple-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ $replacements->count() }} استبدالات
        </span>
                    <button onclick="exportToPDF()" id="pdfExportBtn"
                            class="bg-purple-100 hover:bg-purple-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>PDF</span>
                    </button>

                    <!-- Export Excel Button -->
                    <button onclick="exportToExcel()" id="excelExportBtn"
                            class="bg-purple-100 hover:bg-purple-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Excel</span>
                    </button>

                    <button onclick="window.print()"
                            class="bg-purple-100 hover:bg-purple-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" viewBox="0 0 20 20" fill="currentColor">
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
                        <form method="GET" action="{{ route('employees.replacements.all') }}" class="w-full max-w-md">
                            <div class="search-container px-4 py-2 flex items-center gap-3">
                                <button type="submit" class="text-purple-600 hover:text-purple-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="ابحث في الاستبدالات..."
                                    class="search-input text-sm text-gray-700 placeholder-gray-500"
                                >
                                @if(request('year'))
                                    <input type="hidden" name="year" value="{{ request('year') }}">
                                @endif
                                @if(request('search'))
                                    <a href="{{ route('employees.replacements.all') }}" class="text-gray-400 hover:text-purple-600 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl overflow-hidden replacements-shadow">
            @if ($replacements->isEmpty())
                <div class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <p class="text-lg">لا يوجد استبدالات</p>
                        <p class="text-sm mt-1">لم يتم تسجيل أي استبدالات حتى الآن</p>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الموظف القديم</th>
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
                                    حالة البديل</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($replacements as $index => $replacement)
                                <tr class="replacements-row hover:bg-blue-50 transition-colors">
                                    <td class="replacements-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="replacements-cell px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('employees.replacements', $replacement->oldEmployee->id ?? '') }}"
                                            class="flex items-center justify-center">

                                            <div class="mr-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $replacement->oldEmployee->name ?? '—' }}</div>

                                            </div>
                                        </a>
                                    </td>

                                    <td class="replacements-cell px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('employees.replacements', $replacement->newEmployee->id ?? '') }}"
                                            class="flex items-center justify-center">

                                            <div class="mr-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $replacement->newEmployee->name ?? '—' }}</div>

                                            </div>
                                        </a>
                                    </td>

                                    <td class="replacements-cell px-6 py-4 whitespace-nowrap">
                                        <span class="date-badge">
                                            <i class="fas fa-calendar-check"></i>
                                            {{ $replacement->replacement_date->format('Y-m-d') }}
                                        </span>
                                    </td>

                                    <td class="replacements-cell px-6 py-4 whitespace-nowrap">
                                        <span class="date-badge">
                                            <i class="fas fa-calendar-times"></i>
                                            {{ $replacement->last_working_date->format('Y-m-d') }}
                                        </span>
                                    </td>

                                    <td class="replacements-cell px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate" data-bs-toggle="tooltip"
                                            title="{{ $replacement->reason }}">
                                            {{ \Illuminate\Support\Str::limit($replacement->reason, 50) }}
                                        </div>
                                        @if (strlen($replacement->reason) > 50)
                                            <button class="text-sm text-blue-600 hover:text-blue-800 mt-1"
                                                data-bs-toggle="modal" data-bs-target="#reasonModal"
                                                data-reason="{{ $replacement->reason }}">
                                                عرض المزيد
                                            </button>
                                        @endif
                                    </td>

                                    <td class="replacements-cell px-6 py-4 whitespace-nowrap">
                                        @php
                                            $isActive = ($replacement->newEmployee->account_status ?? '') === 'active';
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
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">سبب الاستبدال الكامل</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="إغلاق"></button>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                .map(el => new bootstrap.Tooltip(el, {
                    boundary: document.body
                }));

            // Show full reason text in modal
            const reasonModal = document.getElementById('reasonModal');
            if (reasonModal) {
                reasonModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const reason = button.getAttribute('data-reason');
                    document.getElementById('fullReasonText').textContent = reason;
                });
            }
        });

        // Export to PDF function
        // Export to PDF function - FIXED VERSION
        async function exportToPDF() {
            const pdfBtn = event.target.closest('button');
            const originalHTML = pdfBtn.innerHTML;

            // Show loading
            pdfBtn.innerHTML = `
        <div class="spinner" style="display: inline-block; width: 16px; height: 16px; border: 2px solid #f3f3f3; border-top: 2px solid #dc2626; border-radius: 50%;"></div>
        <span>جاري التصدير...</span>
    `;
            pdfBtn.disabled = true;

            try {
                // Create a temporary div for PDF content
                const pdfContent = document.createElement('div');
                pdfContent.style.direction = 'rtl';
                pdfContent.style.padding = '20px';
                pdfContent.style.fontFamily = 'Arial, sans-serif';

                // Get current date
                const currentDate = new Date().toLocaleDateString('ar-SA');

                // Start building the HTML content
                let htmlContent = `
            <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #6b46c1; padding-bottom: 10px;">
                <h1 style="color: #6b46c1; margin: 0;">تقرير استبدالات الموظفين</h1>
                <p style="color: #666; margin: 5px 0 0 0;">تاريخ التصدير: ${currentDate}</p>
                <p style="color: #666; margin: 0;">إجمالي الاستبدالات: ${document.querySelectorAll('tbody tr').length}</p>
            </div>
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">#</th>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">الموظف القديم</th>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">الموظف البديل</th>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">تاريخ التعيين</th>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">تاريخ آخر يوم عمل</th>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">سبب الاستبدال</th>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">حالة البديل</th>
                    </tr>
                </thead>
                <tbody>
        `;

                // Get all table rows and build the content dynamically
                const rows = document.querySelectorAll('tbody tr');
                rows.forEach((row, index) => {
                    const cells = row.querySelectorAll('td');

                    // Extract data from each cell
                    const oldEmployee = cells[1].textContent.trim();
                    const newEmployee = cells[2].textContent.trim();

                    // Extract dates
                    const replacementDate = cells[3].querySelector('.date-badge')?.textContent.replace(/[^0-9\-]/g, '').trim() || '';
                    const lastWorkingDate = cells[4].querySelector('.date-badge')?.textContent.replace(/[^0-9\-]/g, '').trim() || '';

                    // Extract reason (try to get full reason from button data if available)
                    let reason = cells[5].textContent.trim();
                    const reasonButton = cells[5].querySelector('button');
                    if (reasonButton && reasonButton.getAttribute('data-reason')) {
                        reason = reasonButton.getAttribute('data-reason');
                    }

                    // Extract status
                    const statusBadge = cells[6].querySelector('.status-badge');
                    const isActive = statusBadge?.classList.contains('status-active');
                    const statusText = isActive ? 'نشط' : 'غير نشط';
                    const statusColor = isActive ? '#d1fae5' : '#fee2e2';
                    const statusTextColor = isActive ? '#065f46' : '#991b1b';

                    htmlContent += `
                <tr>
                    <td style="border: 1px solid #ddd; padding: 10px; text-align: center; background-color: #f8fafc;">${index + 1}</td>
                    <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">${oldEmployee}</td>
                    <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">${newEmployee}</td>
                    <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">${replacementDate}</td>
                    <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">${lastWorkingDate}</td>
                    <td style="border: 1px solid #ddd; padding: 10px; text-align: center; max-width: 300px; word-wrap: break-word; white-space: normal;">${reason}</td>
                    <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                        <span style="padding: 4px 8px; border-radius: 12px; font-size: 10px; background-color: ${statusColor}; color: ${statusTextColor};">
                            ${statusText}
                        </span>
                    </td>
                </tr>
            `;
                });

                htmlContent += `
                </tbody>
            </table>
            <div style="margin-top: 20px; text-align: center; color: #666; font-size: 10px;">
                تم إنشاء هذا التقرير بواسطة النظام - ${document.querySelector('title')?.textContent || 'النظام'}
            </div>
        `;

                pdfContent.innerHTML = htmlContent;

                // PDF options
                const options = {
                    margin: [10, 10, 10, 10],
                    filename: `استبدالات-الموظفين-${currentDate}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: {
                        scale: 2,
                        useCORS: true,
                        logging: true,
                        scrollY: -window.scrollY
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'landscape'
                    }
                };

                // Generate PDF
                await html2pdf().set(options).from(pdfContent).save();

            } catch (error) {
                console.error('PDF export error:', error);
                alert('حدث خطأ أثناء تصدير PDF: ' + error.message);
            } finally {
                // Restore button
                pdfBtn.innerHTML = originalHTML;
                pdfBtn.disabled = false;
            }
        }

        async function exportToExcel() {
            const excelBtn = event.target.closest('button');
            const originalHTML = excelBtn.innerHTML;

            // Show loading
            excelBtn.innerHTML = `
        <div class="spinner" style="display: inline-block; width: 16px; height: 16px; border: 2px solid #f3f3f3; border-top: 2px solid #16a34a; border-radius: 50%;"></div>
        <span>جاري التصدير...</span>
    `;
            excelBtn.disabled = true;

            try {
                // Get table data
                const table = document.querySelector('table');
                const rows = table.querySelectorAll('tbody tr');

                // Prepare data for Excel
                const data = [];

                // Add headers with styling info
                const headers = [];
                table.querySelectorAll('thead th').forEach(th => {
                    headers.push(th.textContent.trim());
                });
                data.push(headers);

                // Add rows data
                rows.forEach(row => {
                    const rowData = [];
                    const cells = row.querySelectorAll('td');

                    cells.forEach((cell, index) => {
                        let cellText = cell.textContent.trim();

                        // Handle special cases
                        if (index === 3 || index === 4) { // Date columns
                            // Extract date from badge
                            const dateSpan = cell.querySelector('.date-badge');
                            if (dateSpan) {
                                cellText = dateSpan.textContent.replace(/[^0-9\-]/g, '').trim();
                            }
                        } else if (index === 6) { // Status column
                            // Extract status text
                            const statusSpan = cell.querySelector('.status-badge');
                            if (statusSpan) {
                                cellText = statusSpan.textContent.trim();
                            }
                        } else if (index === 5) { // Reason column
                            // Get full reason if available
                            const fullReasonBtn = cell.querySelector('button');
                            if (fullReasonBtn && fullReasonBtn.getAttribute('data-reason')) {
                                cellText = fullReasonBtn.getAttribute('data-reason');
                            }
                        }

                        rowData.push(cellText);
                    });

                    data.push(rowData);
                });

                // Create worksheet
                const ws = XLSX.utils.aoa_to_sheet(data);

                // Set column widths for better readability
                const colWidths = [
                    { wch: 5 },   // #
                    { wch: 20 },  // الموظف القديم
                    { wch: 20 },  // الموظف البديل
                    { wch: 15 },  // تاريخ التعيين
                    { wch: 15 },  // تاريخ آخر يوم عمل
                    { wch: 50 },  // سبب الاستبدال (wider for long text)
                    { wch: 12 }   // حالة البديل
                ];
                ws['!cols'] = colWidths;

                // Add header styling
                if (!ws['!merges']) ws['!merges'] = [];

                // Style header row
                const headerRange = XLSX.utils.decode_range(ws['!ref']);
                for (let C = headerRange.s.c; C <= headerRange.e.c; ++C) {
                    const cellAddress = XLSX.utils.encode_cell({ r: 0, c: C });
                    if (!ws[cellAddress]) continue;

                    // Add header styling
                    ws[cellAddress].s = {
                        fill: {
                            fgColor: { rgb: "6b46c1" } // Purple background
                        },
                        font: {
                            color: { rgb: "FFFFFF" }, // White text
                            bold: true,
                            sz: 12
                        },
                        alignment: {
                            horizontal: "center",
                            vertical: "center"
                        },
                        border: {
                            top: { style: "thin", color: { rgb: "000000" } },
                            left: { style: "thin", color: { rgb: "000000" } },
                            bottom: { style: "thin", color: { rgb: "000000" } },
                            right: { style: "thin", color: { rgb: "000000" } }
                        }
                    };
                }

                // Style data rows
                for (let R = 1; R <= headerRange.e.r; ++R) {
                    for (let C = headerRange.s.c; C <= headerRange.e.c; ++C) {
                        const cellAddress = XLSX.utils.encode_cell({ r: R, c: C });
                        if (!ws[cellAddress]) continue;

                        // Add data row styling
                        ws[cellAddress].s = {
                            font: {
                                sz: 11
                            },
                            alignment: {
                                horizontal: "center",
                                vertical: "center",
                                wrapText: true // Enable text wrapping
                            },
                            border: {
                                top: { style: "thin", color: { rgb: "dddddd" } },
                                left: { style: "thin", color: { rgb: "dddddd" } },
                                bottom: { style: "thin", color: { rgb: "dddddd" } },
                                right: { style: "thin", color: { rgb: "dddddd" } }
                            }
                        };

                        // Add background color for first column
                        if (C === 0) {
                            ws[cellAddress].s.fill = {
                                fgColor: { rgb: "f8fafc" } // Light gray background
                            };
                        }

                        // Enable text wrapping for reason column
                        if (C === 5) {
                            ws[cellAddress].s.alignment.wrapText = true;
                        }
                    }
                }

                // Create workbook
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'استبدالات الموظفين');

                // Generate Excel file and download
                const currentDate = new Date().toLocaleDateString('ar-SA');
                XLSX.writeFile(wb, `استبدالات-الموظفين-${currentDate}.xlsx`);

            } catch (error) {
                console.error('Excel export error:', error);
                alert('حدث خطأ أثناء تصدير Excel');
            } finally {
                // Restore button
                excelBtn.innerHTML = originalHTML;
                excelBtn.disabled = false;
            }
        }

    </script>
@endpush
