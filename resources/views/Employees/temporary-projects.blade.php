@extends('layouts.master')
@section('title', 'جدول المشاريع المؤقتة')

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
        .assignment-gradient-bg {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
        }

        .assignment-header-gradient {
            background: linear-gradient(135deg, #00838f 0%, #006064 100%);
        }

        .assignment-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
        .assignment-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .assignment-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .assignment-cell {
            transition: all 0.3s ease;
            whitespace: nowrap;
        }

        .assignment-row:hover .assignment-cell {
            background-color: #e0f7fa;
        }

        .status-badge {
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 50rem;
        }

        .approved-badge {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .pending-badge {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .rejected-badge {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .duration-bubble {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            background-color: #00838f;
            color: white;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .project-icon {
            width: 24px;
            height: 24px;
            margin-left: 0.5rem;
            color: #00838f;
        }
    </style>
@endpush

@section('content')
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-xl overflow-hidden mb-6 assignment-shadow assignment-gradient-bg">
            <div class="bg-gray-50 hover:bg-yellow-100 border-l-4 border-yellow-500 rounded-lg px-6 py-4 flex items-center justify-between text-black transition-all duration-300">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="bg-yellow-100 p-3 rounded-full group-hover:bg-yellow-200 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">التكليفات المؤقتة للموظف</h2>
                        <p class="text-gray-600">{{ $employee->user->name }}</p>
                    </div>
                    <form method="GET" action="{{ route('employees.assignments', $employee->id) }}">
                        <select name="year" onchange="this.form.submit()"
                                class="bg-yellow-100 hover:bg-yellow-200 text-gray-800 px-3 py-1 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300 transition-all text-sm">
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
                         <span class="bg-yellow-100 hover:bg-yellow-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ $employee->temporaryAssignments->count() }} طلبات
        </span>
        <span class="bg-yellow-100 hover:bg-yellow-200 px-3 py-1 rounded-full text-sm font-medium text-gray-800 transition-all">
            {{ $employee->temporaryAssignments->where('status', 'approved')->count() }} معتمدة
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                  clip-rule="evenodd" />
                        </svg>
                        <span>طباعة</span>
                    </button>

                    <a href="{{ route('employees.assignments.all') }}"
                       class="bg-yellow-100 hover:bg-yellow-200 px-4 py-2 rounded-lg flex items-center space-x-2 rtl:space-x-reverse transition-all text-gray-800 border border-yellow-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>جميع التكليفات المؤقتة</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl overflow-hidden alert-shadow mb-4 table-header-bg">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Search Bar - Right Side -->
                    <div class="flex-1 flex justify-end">
                        <form method="GET" action="{{ route('employees.assignments',$employee->id) }}" class="w-full max-w-md">
                            <div class="search-container px-4 py-2 flex items-center gap-3">
                                <button type="submit" class="text-yellow-600 hover:text-yellow-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="ابحث في التكليفات المؤقتة..."
                                    class="search-input text-sm text-gray-700 placeholder-gray-500"
                                >
                                @if(request('year'))
                                    <input type="hidden" name="year" value="{{ request('year') }}">
                                @endif
                                @if(request('search'))
                                    <a href="{{ route('employees.assignments',$employee->id) }}" class="text-gray-400 hover:text-yellow-600 transition-colors">
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

        <!-- Assignments Table -->
        <div class="bg-white rounded-xl overflow-hidden assignment-shadow">
            <div class="overflow-x-auto">
                <table class="table min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الموظف</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">المشروع الأساسي</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">المشروع المؤقت</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">من تاريخ</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">إلى تاريخ</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الفترة</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">السبب</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($assignments as $assignment)
                            <tr class="assignment-row hover:bg-blue-50 transition-colors">
                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="project-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>{{ $assignment->employee->user->name }}</span>
                                    </div>
                                </td>

                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="project-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span>{{ $assignment->fromProject->name ?? '—' }}</span>
                                    </div>
                                </td>

                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center font-medium text-yellow-600">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="project-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <span>{{ $assignment->toProject->name }}</span>
                                    </div>
                                </td>

                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ $assignment->start_date->format('Y-m-d') }}</span>
                                        <span class="text-xs text-gray-500">{{ $assignment->start_date->locale('ar')->format('l') }}</span>
                                    </div>
                                </td>

                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ $assignment->end_date->format('Y-m-d') }}</span>
                                        <span class="text-xs text-gray-500">{{ $assignment->end_date->locale('ar')->format('l') }}</span>
                                    </div>
                                </td>

                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $durationInDays = $assignment->start_date->diffInDays($assignment->end_date) + 1;
                                    @endphp
                                    <span class="duration-bubble">{{ $durationInDays }} يوم</span>
                                </td>

                                <td class="assignment-cell px-6 py-4 text-sm text-gray-500 max-w-xs text-center">
                                    <div class="mx-auto" style="nowrap:none;">{{ $assignment->reason }}</div>
                                </td>

                                <td class="assignment-cell px-6 py-4 whitespace-nowrap text-center">
                                    @if ($assignment->status === 'pending')
                                        <span class="status-badge pending-badge">
                                            <i class="fas fa-clock ml-1"></i> قيد الانتظار
                                        </span>
                                    @elseif ($assignment->status === 'approved')
                                        <span class="status-badge approved-badge">
                                            <i class="fas fa-check-circle ml-1"></i> مقبول
                                        </span>
                                    @elseif ($assignment->status === 'rejected')
                                        <span class="status-badge rejected-badge">
                                            <i class="fas fa-times-circle ml-1"></i> مرفوض
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2a4 4 0 018 0v2m-4 4v-4" />
                                        </svg>
                                        <p class="text-lg">لا توجد مهام مؤقتة حالياً</p>
                                        <p class="text-sm mt-1">لم يتم تسجيل أي تكليفات مؤقتة لهذا الموظف</p>
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
        // Export Configuration
        const ExportConfig = {
            pdf: {
                margin: [15, 15, 15, 15],
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a3',
                    orientation: 'landscape',
                    compress: true
                }
            },
            styles: {
                header: {
                    background: '#00838f',
                    color: '#ffffff',
                    fontSize: '24px',
                    fontWeight: 'bold',
                    padding: '20px',
                    textAlign: 'center',
                    borderBottom: '3px solid #006064'
                },
                subHeader: {
                    background: '#e0f7fa',
                    color: '#006064',
                    fontSize: '16px',
                    fontWeight: '600',
                    padding: '15px',
                    textAlign: 'center',
                    borderBottom: '2px solid #b2ebf2'
                },
                table: {
                    header: {
                        background: '#00838f',
                        color: '#ffffff',
                        fontWeight: 'bold',
                        textAlign: 'center',
                        padding: '12px',
                        border: '1px solid #006064'
                    },
                    cell: {
                        padding: '10px',
                        border: '1px solid #e0e0e0',
                        textAlign: 'center',
                        fontSize: '12px'
                    },
                    alternateRow: {
                        background: '#f8f9fa'
                    }
                }
            }
        };

        // Utility Functions
        const ExportUtils = {
            showLoading(button, text = 'جاري التصدير...') {
                const originalHTML = button.innerHTML;
                button.innerHTML = `
            <div class="spinner" style="display: inline-block; width: 16px; height: 16px; border: 2px solid #f3f3f3; border-top: 2px solid #00838f; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            <span style="margin-right: 8px;">${text}</span>
        `;
                button.disabled = true;
                return originalHTML;
            },

            restoreButton(button, originalHTML) {
                button.innerHTML = originalHTML;
                button.disabled = false;
            },

            formatDate(date) {
                return new Date(date).toLocaleDateString('ar-SA');
            },

            getStatusBadge(status) {
                const statusConfig = {
                    'pending': { text: 'قيد الانتظار', color: '#ffc107', bgColor: '#fff3cd' },
                    'approved': { text: 'مقبول', color: '#28a745', bgColor: '#d4edda' },
                    'rejected': { text: 'مرفوض', color: '#dc3545', bgColor: '#f8d7da' }
                };
                return statusConfig[status] || statusConfig.pending;
            }
        };

        // PDF Export Functions
        const PDFExporter = {
            async exportToPDF() {
                const button = event.target.closest('button');
                const originalHTML = ExportUtils.showLoading(button, 'جاري إنشاء PDF...');

                try {
                    const pdfContent = this.createPDFContent();
                    const options = {
                        ...ExportConfig.pdf,
                        filename: `التكليفات_المؤقتة_${this.getEmployeeName()}_${ExportUtils.formatDate(new Date())}.pdf`
                    };

                    await html2pdf().set(options).from(pdfContent).save();
                } catch (error) {
                    console.error('PDF export error:', error);
                    this.showError('حدث خطأ أثناء تصدير PDF: ' + error.message);
                } finally {
                    ExportUtils.restoreButton(button, originalHTML);
                }
            },

            createPDFContent() {
                const container = document.createElement('div');
                container.style.cssText = `
            direction: rtl;
            font-family: 'Arial', 'Segoe UI', sans-serif;
            padding: 20px;
            background: white;
            color: #333;
        `;

                container.innerHTML = this.generatePDFHTML();
                return container;
            },

            generatePDFHTML() {
                const assignments = this.getAssignmentsData();

                return `
            ${this.generatePDFHeader()}
            ${this.generatePDFSubHeader()}
            ${this.generatePDFTable(assignments)}
            ${this.generatePDFFooter()}
        `;
            },

            generatePDFHeader() {
                return `
            <div style="
                background: ${ExportConfig.styles.header.background};
                color: ${ExportConfig.styles.header.color};
                font-size: ${ExportConfig.styles.header.fontSize};
                font-weight: ${ExportConfig.styles.header.fontWeight};
                padding: ${ExportConfig.styles.header.padding};
                text-align: ${ExportConfig.styles.header.textAlign};
                border-bottom: ${ExportConfig.styles.header.borderBottom};
                margin-bottom: 20px;
                border-radius: 8px 8px 0 0;
            ">
                <h1 style="margin: 0; font-size: 28px;">تقرير التكليفات المؤقتة</h1>
                <p style="margin: 10px 0 0 0; font-size: 16px; opacity: 0.9;">شركة آفاق الخليج</p>
            </div>
        `;
            },

            generatePDFSubHeader() {
                return `
            <div style="
                background: ${ExportConfig.styles.subHeader.background};
                color: ${ExportConfig.styles.subHeader.color};
                font-size: ${ExportConfig.styles.subHeader.fontSize};
                font-weight: ${ExportConfig.styles.subHeader.fontWeight};
                padding: ${ExportConfig.styles.subHeader.padding};
                text-align: ${ExportConfig.styles.subHeader.textAlign};
                border-bottom: ${ExportConfig.styles.subHeader.borderBottom};
                margin-bottom: 25px;
                border-radius: 6px;
            ">
                <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 20px;">
                    <div>
                        <strong>الموظف:</strong> ${this.getEmployeeName()}
                    </div>
                    <div>
                        <strong>رقم الموظف:</strong> ${this.getEmployeeId()}
                    </div>
                    <div>
                        <strong>تاريخ التقرير:</strong> ${ExportUtils.formatDate(new Date())}
                    </div>
                    <div>
                        <strong>إجمالي التكليفات:</strong> ${this.getAssignmentsCount()}
                    </div>
                </div>
            </div>
        `;
            },

            generatePDFTable(assignments) {
                return `
            <table style="
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
                font-size: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                border-radius: 8px;
                overflow: hidden;
            ">
                ${this.generateTableHeader()}
                ${this.generateTableBody(assignments)}
            </table>
        `;
            },

            generateTableHeader() {
                const headers = [
                    'الموظف', 'المشروع الأساسي', 'المشروع المؤقت',
                    'من تاريخ', 'إلى تاريخ', 'الفترة', 'السبب', 'الحالة'
                ];

                return `
            <thead>
                <tr>
                    ${headers.map(header => `
                        <th style="
                            background: ${ExportConfig.styles.table.header.background};
                            color: ${ExportConfig.styles.table.header.color};
                            font-weight: ${ExportConfig.styles.table.header.fontWeight};
                            text-align: ${ExportConfig.styles.table.header.textAlign};
                            padding: ${ExportConfig.styles.table.header.padding};
                            border: ${ExportConfig.styles.table.header.border};
                            font-size: 13px;
                            white-space: nowrap;
                        ">${header}</th>
                    `).join('')}
                </tr>
            </thead>
        `;
            },

            generateTableBody(assignments) {
                if (assignments.length === 0) {
                    return `
            <tbody>
                <tr>
                    <td colspan="8" style="
                        padding: 40px;
                        text-align: center;
                        color: #666;
                        font-style: italic;
                        background: #f8f9fa;
                    ">
                        لا توجد تكليفات مؤقتة لعرضها
                    </td>
                </tr>
            </tbody>
        `;
                }

                return `
        <tbody>
            ${assignments.map((assignment, index) => {
                    const status = ExportUtils.getStatusBadge(assignment.status);
                    return `
                    <tr style="background: ${index % 2 === 0 ? ExportConfig.styles.table.alternateRow.background : 'white'};">
                        <td style="${this.getCellStyle()}">${assignment.employeeName}</td>
                        <td style="${this.getCellStyle()}">${assignment.fromProject}</td>
                        <td style="${this.getCellStyle()}">${assignment.toProject}</td>
                        <td style="${this.getCellStyle()}">${assignment.startDate}</td>
                        <td style="${this.getCellStyle()}">${assignment.endDate}</td>
                        <td style="${this.getCellStyle()}">${assignment.duration}</td>
                        <td style="${this.getCellStyle()} max-width: 250px; word-wrap: break-word; white-space: normal; line-height: 1.4;">${assignment.reason}</td>
                        <td style="${this.getCellStyle()}">
                            <span style="
                                padding: 4px 8px;
                                border-radius: 12px;
                                font-size: 10px;
                                background-color: ${status.bgColor};
                                color: ${status.color};
                                font-weight: bold;
                            ">${status.text}</span>
                        </td>
                    </tr>
                `;
                }).join('')}
        </tbody>
    `;
            },

            generatePDFFooter() {
                return `
            <div style="
                margin-top: 30px;
                padding: 15px;
                text-align: center;
                color: #666;
                font-size: 11px;
                border-top: 2px solid #e0f7fa;
                background: #f8f9fa;
                border-radius: 0 0 8px 8px;
            ">
                <p style="margin: 5px 0;">
                    <strong>تم إنشاء هذا التقرير تلقائياً بواسطة نظام إدارة الموارد البشرية</strong>
                </p>
                <p style="margin: 5px 0;">
                    © ${new Date().getFullYear()} جميع الحقوق محفوظة لشركة آفاق الخليج
                </p>
                <p style="margin: 5px 0; font-size: 10px;">
                    تاريخ الإنشاء: ${new Date().toLocaleString('ar-SA')}
                </p>
            </div>
        `;
            },

            getCellStyle() {
                return `
            padding: ${ExportConfig.styles.table.cell.padding};
            border: ${ExportConfig.styles.table.cell.border};
            text-align: ${ExportConfig.styles.table.cell.textAlign};
            font-size: ${ExportConfig.styles.table.cell.fontSize};
        `;
            },

            getAssignmentsData() {
                const rows = document.querySelectorAll('tbody tr');
                const assignments = [];

                rows.forEach(row => {
                    // Skip empty rows or rows with no valid data
                    if (row.querySelector('.empty-state-replacement') || row.textContent.trim() === '') {
                        return;
                    }

                    const cells = row.querySelectorAll('td');

                    // Check if this is a valid data row (should have at least 3 cells with content)
                    if (cells.length < 3) return;

                    const employeeName = cells[0]?.textContent?.trim() || '';
                    const fromProject = cells[1]?.textContent?.trim() || '—';
                    const toProject = cells[2]?.textContent?.trim() || '';

                    // Skip rows that don't have proper employee name or project data
                    if (!employeeName || employeeName === 'لا توجد مهام مؤقتة حالياً') {
                        return;
                    }

                    // Get dates properly
                    const startDateElement = cells[3]?.querySelector('.text-sm');
                    const endDateElement = cells[4]?.querySelector('.text-sm');
                    const startDate = startDateElement?.textContent?.trim() || '';
                    const endDate = endDateElement?.textContent?.trim() || '';

                    // Get duration
                    const duration = cells[5]?.textContent?.trim() || '';

                    // Get reason - FIX: Get the full text content, not truncated
                    const reasonCell = cells[6];
                    let reason = '';
                    if (reasonCell) {
                        // Try to get the full reason text from the div content
                        const reasonDiv = reasonCell.querySelector('div');
                        if (reasonDiv) {
                            reason = reasonDiv.textContent?.trim() || '';
                        } else {
                            reason = reasonCell.textContent?.trim() || '';
                        }
                    }

                    // Get status
                    const status = this.getStatusFromCell(cells[7]);

                    assignments.push({
                        employeeName,
                        fromProject,
                        toProject,
                        startDate,
                        endDate,
                        duration,
                        reason,
                        status
                    });
                });

                return assignments;
            },

            getStatusFromCell(cell) {
                const statusText = cell?.textContent?.trim() || '';
                if (statusText.includes('قيد الانتظار')) return 'pending';
                if (statusText.includes('مقبول')) return 'approved';
                if (statusText.includes('مرفوض')) return 'rejected';
                return 'pending';
            },

            getEmployeeName() {
                return "{{ $employee->user->name }}";
            },

            getEmployeeId() {
                return "{{ $employee->id }}";
            },

            getAssignmentsCount() {
                return document.querySelectorAll('tbody tr').length;
            },

            showError(message) {
                alert(message);
            }
        };

        // Excel Export Functions
        const ExcelExporter = {
            async exportToExcel() {
                const button = event.target.closest('button');
                const originalHTML = ExportUtils.showLoading(button, 'جاري إنشاء Excel...');

                try {
                    const data = this.prepareExcelData();
                    const worksheet = XLSX.utils.aoa_to_sheet(data);
                    this.applyExcelStyling(worksheet, data);

                    const workbook = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(workbook, worksheet, 'التكليفات المؤقتة');

                    const fileName = `التكليفات_المؤقتة_${this.getEmployeeName()}_${ExportUtils.formatDate(new Date())}.xlsx`;
                    XLSX.writeFile(workbook, fileName);
                } catch (error) {
                    console.error('Excel export error:', error);
                    this.showError('حدث خطأ أثناء تصدير Excel');
                } finally {
                    ExportUtils.restoreButton(button, originalHTML);
                }
            },

            prepareExcelData() {
                const headers = [
                    'الموظف', 'المشروع الأساسي', 'المشروع المؤقت',
                    'من تاريخ', 'إلى تاريخ', 'الفترة', 'السبب', 'الحالة'
                ];

                const data = [headers];
                const assignments = this.getAssignmentsData();

                assignments.forEach(assignment => {
                    data.push([
                        assignment.employeeName,
                        assignment.fromProject,
                        assignment.toProject,
                        assignment.startDate,
                        assignment.endDate,
                        assignment.duration,
                        assignment.reason,
                        assignment.statusText
                    ]);
                });

                return data;
            },

            getAssignmentsData() {
                const rows = document.querySelectorAll('tbody tr');
                return Array.from(rows).map(row => {
                    const cells = row.querySelectorAll('td');
                    const status = this.getStatusFromCell(cells[7]);

                    return {
                        employeeName: cells[0]?.textContent?.trim() || '',
                        fromProject: cells[1]?.textContent?.trim() || '—',
                        toProject: cells[2]?.textContent?.trim() || '',
                        startDate: cells[3]?.querySelector('.text-sm')?.textContent?.trim() || '',
                        endDate: cells[4]?.querySelector('.text-sm')?.textContent?.trim() || '',
                        duration: cells[5]?.textContent?.trim() || '',
                        reason: cells[6]?.textContent?.trim() || '',
                        statusText: status.text
                    };
                });
            },

            getStatusFromCell(cell) {
                const statusText = cell?.textContent?.trim() || '';
                if (statusText.includes('قيد الانتظار')) return { text: 'قيد الانتظار' };
                if (statusText.includes('مقبول')) return { text: 'مقبول' };
                if (statusText.includes('مرفوض')) return { text: 'مرفوض' };
                return { text: 'قيد الانتظار' };
            },

            applyExcelStyling(worksheet, data) {
                // Set column widths
                const colWidths = [
                    { wch: 20 }, { wch: 25 }, { wch: 25 },
                    { wch: 15 }, { wch: 15 }, { wch: 12 },
                    { wch: 40 }, { wch: 15 }
                ];
                worksheet['!cols'] = colWidths;

                // Style header row
                const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
                for (let C = headerRange.s.c; C <= headerRange.e.c; ++C) {
                    const cellAddress = XLSX.utils.encode_cell({ r: 0, c: C });
                    if (worksheet[cellAddress]) {
                        worksheet[cellAddress].s = {
                            fill: { fgColor: { rgb: "00838f" } },
                            font: { color: { rgb: "FFFFFF" }, bold: true, sz: 12 },
                            alignment: { horizontal: "center", vertical: "center" },
                            border: {
                                top: { style: "thin", color: { rgb: "006064" } },
                                left: { style: "thin", color: { rgb: "006064" } },
                                bottom: { style: "thin", color: { rgb: "006064" } },
                                right: { style: "thin", color: { rgb: "006064" } }
                            }
                        };
                    }
                }

                // Style data rows
                for (let R = 1; R <= headerRange.e.r; ++R) {
                    for (let C = headerRange.s.c; C <= headerRange.e.c; ++C) {
                        const cellAddress = XLSX.utils.encode_cell({ r: R, c: C });
                        if (worksheet[cellAddress]) {
                            worksheet[cellAddress].s = {
                                font: { sz: 11 },
                                alignment: {
                                    horizontal: "center",
                                    vertical: "center",
                                    wrapText: C === 6 // Enable text wrapping for reason column
                                },
                                border: {
                                    top: { style: "thin", color: { rgb: "e0e0e0" } },
                                    left: { style: "thin", color: { rgb: "e0e0e0" } },
                                    bottom: { style: "thin", color: { rgb: "e0e0e0" } },
                                    right: { style: "thin", color: { rgb: "e0e0e0" } }
                                }
                            };

                            // Alternate row colors
                            if (R % 2 === 0) {
                                worksheet[cellAddress].s.fill = { fgColor: { rgb: "f8f9fa" } };
                            }
                        }
                    }
                }
            },

            getEmployeeName() {
                return "{{ $employee->user->name }}";
            },

            showError(message) {
                alert(message);
            }
        };

        // Global Export Functions
        function exportToPDF() {
            PDFExporter.exportToPDF();
        }

        function exportToExcel() {
            ExcelExporter.exportToExcel();
        }

        // Add CSS for spinner animation
        const style = document.createElement('style');
        style.textContent = `
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .spinner {
        animation: spin 1s linear infinite;
    }
`;
        document.head.appendChild(style);

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips if needed
            $('[data-toggle="tooltip"]').tooltip();

            // Initialize date formatting if needed
            flatpickr(".date-input", {
                locale: "ar",
                dateFormat: "Y-m-d",
                allowInput: true
            });
        });
    </script>
@endpush
