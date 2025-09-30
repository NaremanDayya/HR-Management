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
                    <button onclick="exportToPDF()" id="pdfExportBtn"
                            class="  px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-white">PDF</span>
                    </button>

                    <!-- Export Excel Button -->
                    <button onclick="exportToExcel()" id="excelExportBtn"
                            class=" px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-purple-200">
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
                        <form method="GET" action="{{ route('employees.replacements', $oldEmployee->id) }}" class="w-full max-w-md">
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
                                    <a href="{{ route('employees.replacements', $oldEmployee->id) }}" class="text-gray-400 hover:text-purple-600 transition-colors">
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
                        <tbody class="bg-white divide-y divide-gray-100">
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
        document.addEventListener('DOMContentLoaded', function() {
            // Reason modal handler
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
                const employeeName = "{{ $oldEmployee->name }}";

                // Start building the HTML content
                let htmlContent = `
                    <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #6b46c1; padding-bottom: 10px;">
                        <h1 style="color: #6b46c1; margin: 0;">تقرير بدائل الموظف</h1>
                        <p style="color: #666; margin: 5px 0 0 0;">الموظف: ${employeeName}</p>
                        <p style="color: #666; margin: 5px 0 0 0;">تاريخ التصدير: ${currentDate}</p>
                        <p style="color: #666; margin: 0;">إجمالي البدائل: ${document.querySelectorAll('tbody tr').length}</p>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px;">
                        <thead>
                            <tr>
                                <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">#</th>
                                <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">الموظف البديل</th>
                                <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">رقم الموظف</th>
                                <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">تاريخ التعيين</th>
                                <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">تاريخ آخر يوم عمل</th>
                                <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">سبب الاستبدال</th>
                                <th style="border: 1px solid #ddd; padding: 12px; text-align: center; background-color: #6b46c1; color: white; font-weight: bold;">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                // Get all table rows and build the content dynamically
                const rows = document.querySelectorAll('tbody tr');
                rows.forEach((row, index) => {
                    const cells = row.querySelectorAll('td');

                    // Extract data from each cell
                    const employeeLink = cells[1].querySelector('.employee-link');
                    const employeeName = employeeLink ? employeeLink.querySelector('.text-sm').textContent.trim() : '';
                    const employeeId = employeeLink ? employeeLink.querySelector('.text-xs').textContent.replace('#', '').trim() : '';

                    // Extract dates
                    const replacementDate = cells[2].querySelector('.date-badge')?.textContent.replace(/[^0-9\-]/g, '').trim() || '';
                    const lastWorkingDate = cells[3].querySelector('.date-badge')?.textContent.replace(/[^0-9\-]/g, '').trim() || '';

                    // Extract reason
                    const reasonContent = cells[4].querySelector('.reason-content');
                    let reason = reasonContent ? reasonContent.textContent.trim() : '';

                    // Extract status
                    const statusBadge = cells[5].querySelector('.status-badge');
                    const isActive = statusBadge?.classList.contains('status-active');
                    const statusText = isActive ? 'نشط' : 'غير نشط';
                    const statusColor = isActive ? '#d1fae5' : '#fee2e2';
                    const statusTextColor = isActive ? '#065f46' : '#991b1b';

                    htmlContent += `
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 10px; text-align: center; background-color: #f8fafc;">${index + 1}</td>
                            <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">${employeeName}</td>
                            <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">${employeeId}</td>
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
                    filename: `بدائل-الموظف-${employeeName}-${currentDate}.pdf`,
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

        // Export to Excel function
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

                // Add headers
                const headers = [
                    '#',
                    'الموظف البديل',
                    'رقم الموظف',
                    'تاريخ التعيين',
                    'تاريخ آخر يوم عمل',
                    'سبب الاستبدال',
                    'الحالة'
                ];
                data.push(headers);

                // Add rows data
                rows.forEach(row => {
                    const rowData = [];
                    const cells = row.querySelectorAll('td');

                    cells.forEach((cell, index) => {
                        let cellText = cell.textContent.trim();

                        // Handle special cases
                        if (index === 1) { // Employee name and ID
                            const employeeLink = cell.querySelector('.employee-link');
                            if (employeeLink) {
                                const name = employeeLink.querySelector('.text-sm')?.textContent.trim() || '';
                                const id = employeeLink.querySelector('.text-xs')?.textContent.replace('#', '').trim() || '';
                                if (index === 1) {
                                    rowData.push(name); // Name
                                    rowData.push(id);   // ID (will be in next column)
                                }
                            }
                        } else if (index === 2 || index === 3) { // Date columns
                            // Extract date from badge
                            const dateSpan = cell.querySelector('.date-badge');
                            if (dateSpan) {
                                cellText = dateSpan.textContent.replace(/[^0-9\-]/g, '').trim();
                            }
                            rowData.push(cellText);
                        } else if (index === 4) { // Reason column
                            const reasonContent = cell.querySelector('.reason-content');
                            cellText = reasonContent ? reasonContent.textContent.trim() : '';
                            rowData.push(cellText);
                        } else if (index === 5) { // Status column
                            // Extract status text
                            const statusSpan = cell.querySelector('.status-badge');
                            if (statusSpan) {
                                cellText = statusSpan.textContent.trim();
                            }
                            rowData.push(cellText);
                        } else if (index === 0) { // Index column
                            rowData.push(cellText);
                        }
                    });

                    data.push(rowData);
                });

                // Create worksheet
                const ws = XLSX.utils.aoa_to_sheet(data);

                // Set column widths for better readability
                const colWidths = [
                    { wch: 5 },   // #
                    { wch: 20 },  // الموظف البديل
                    { wch: 12 },  // رقم الموظف
                    { wch: 15 },  // تاريخ التعيين
                    { wch: 15 },  // تاريخ آخر يوم عمل
                    { wch: 50 },  // سبب الاستبدال (wider for long text)
                    { wch: 12 }   // الحالة
                ];
                ws['!cols'] = colWidths;

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
                XLSX.utils.book_append_sheet(wb, ws, 'بدائل الموظف');

                // Generate Excel file and download
                const currentDate = new Date().toLocaleDateString('ar-SA');
                const employeeName = "{{ $oldEmployee->name }}";
                XLSX.writeFile(wb, `بدائل-الموظف-${employeeName}-${currentDate}.xlsx`);

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
