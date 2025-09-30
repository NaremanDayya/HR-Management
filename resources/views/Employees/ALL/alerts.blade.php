@extends('layouts.master')
@section('title', 'إنذارات الموظفين')

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

        * {
            font-siz: 14px;
            font-weight: 700;
        }

        .alerts-gradient-bg {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
        }

        /*
                .alerts-header-gradient {
                    background: linear-gradient(135deg, #f44336 0%, #c62828 100%);
                } */

        .alerts-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .alerts-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .alerts-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
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

        .alerts-cell {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .alerts-row:hover .alerts-cell {
            background-color: #fff5f7;
        }

        .alert-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .employee-link {
            transition: all 0.2s ease;
        }

        .employee-link:hover {
            color: #d81b60 !important;
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-xl overflow-hidden mb-6 alerts-shadow alerts-gradient-bg">
            <div class="bg-gray-50 hover:bg-red-100 border-l-4 border-red-500 rounded-lg px-6 py-4 flex items-center justify-between text-black transition-all duration-300">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-red-100 p-3 rounded-full group-hover:bg-red-200 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">إنذارات الموظفين</h2>
                        <p class="text-gray-600">سجل الإنذارات الصادرة لجميع الموظفين</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
        <span class="bg-red-100 hover:bg-red-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ count($alerts) }} إنذار
        </span>
                    <button  id="pdfExportBtn"
                            class=" px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-white">PDF</span>
                    </button>

                    <button  id="excelExportBtn"
                            class="px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-white">Excel</span>
                    </button>
                    <button onclick="window.print()"
                            class="bg-gray-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 red-light-btn" viewBox="0 0 20 20" fill="currentColor">
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
                        <form method="GET" action="{{ route('employees.alerts.all') }}" class="w-full max-w-md">
                            <div class="search-container px-4 py-2 flex items-center gap-3">
                                <button type="submit" class="text-red-600 hover:text-red-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="ابحث في الإنذارات..."
                                    class="search-input text-sm text-gray-700 placeholder-gray-500"
                                >
                                @if(request('year'))
                                    <input type="hidden" name="year" value="{{ request('year') }}">
                                @endif
                                @if(request('search'))
                                    <a href="{{ route('employees.alerts.all') }}" class="text-gray-400 hover:text-red-600 transition-colors">
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
        <!-- Alerts Table -->
        <div class="bg-white rounded-xl overflow-hidden alerts-shadow">
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
                            المشروع
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
                            عنوان الإنذار
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            السبب
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            تاريخ الإرسال
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($alerts as $index => $alert)
                        <tr class="alerts-row hover:bg-red-50 transition-colors">
                            <td class="alerts-cell px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ $index + 1 }}
                            </td>

                            <td class="alerts-cell px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center space-y-1">

                                    <a href="{{ route('employees.alerts', $alert->employee->id) }}"
                                       class="employee-link text-sm font-medium text-gray-900 hover:text-red-600">
                                        {{ $alert->employee->user->name ?? '-' }}
                                    </a>
                                </div>
                            </td>

                            <td class="alerts-cell px-6 py-4 whitespace-nowrap text-center">
                                @if($alert->employee->project)
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            {{ $alert->employee->project->name }}
                                        </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="alerts-cell px-6 py-4 whitespace-nowrap text-center">
                                @if($alert->employee->project && $alert->employee->project->manager)
                                    <div class="flex flex-col items-center space-y-1">

                                        <span
                                            class="text-sm text-gray-700">{{ $alert->employee->project->manager->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="alert-cell px-6 py-4 whitespace-nowrap text-center">{{$alert->employee?->supervisor?->name}}</td>
                            <td class="alert-cell px-6 py-4 whitespace-nowrap text-center">{{$alert->employee?->work_area}}</td>

                            <td class="alerts-cell px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse">
                                    <div class="h-5 w-5 text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-bold text-red-600">{{ $alert->title }}</span>
                                </div>
                            </td>

                            <td class="alerts-cell px-6 py-4 text-sm text-gray-500 text-center">
                                <div class="alert-title mx-auto" style="max-width: 250px;">{{ $alert->reason }}</div>
                            </td>

                            <td class="alerts-cell px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ $alert->created_at->format('Y-m-d') }}</span>
                                    <span
                                        class="text-xs text-gray-500">{{ $alert->created_at->locale('ar')->diffForHumans() }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-lg">لا يوجد إنذارات</p>
                                    <p class="text-sm mt-1">لم يتم تسجيل أي إنذارات حالياً</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
                            if (index === 0) return { wch: 5 };  // #
                            if (index === 1) return { wch: 20 }; // الموظف
                            if (index === 2) return { wch: 15 }; // المشروع
                            if (index === 3) return { wch: 15 }; // مدير المشروع
                            if (index === 4) return { wch: 15 }; // المشرف
                            if (index === 5) return { wch: 15 }; // منطقة العمل
                            if (index === 6) return { wch: 25 }; // عنوان الإنذار
                            if (index === 7) return { wch: 30 }; // السبب
                            if (index === 8) return { wch: 12 }; // تاريخ الإرسال
                            return { wch: 15 }; // Default width
                        });

                        ws['!cols'] = colWidths;

                        // Add auto filter
                        ws['!autofilter'] = { ref: XLSX.utils.encode_range({
                                s: { r: 0, c: 0 },
                                e: { r: data.length, c: headers.length - 1 }
                            }) };

                        XLSX.utils.book_append_sheet(wb, ws, "إنذارات الموظفين");
                        XLSX.writeFile(wb, `إنذارات_الموظفين_${new Date().toISOString().slice(0, 10)}.xlsx`);

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
                        title.textContent = 'تقرير إنذارات جميع الموظفين';
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
                            filename: `إنذارات_الموظفين_${new Date().toISOString().slice(0, 10)}.pdf`,
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
            });
        </script>
    @endpush
@endsection
