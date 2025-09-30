@extends('layouts.master')
@section('title', 'بيانات الموظفين المالية')
@push('styles')
    <style>
        #salaryAdjustmentModal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 1000;
        }

        #salaryAdjustmentModal .modal-content {
            background: white;
            border-radius: 8px;
            padding: 20px;
            width: 90%;
            max-width: 500px;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        #salaryAdjustmentModal.hidden {
            opacity: 0;
            pointer-events: none;
        }

        #salaryAdjustmentModal.hidden .modal-content {
            transform: translateY(-20px);
        }

        #salaryAdjustmentModal.show {
            opacity: 1;
            pointer-events: auto;
        }

        #salaryAdjustmentModal.show .modal-content {
            transform: translateY(0);
        }

        .salary-updated {
            color: #e67e22;
            font-weight: 600;
            position: relative;
        }

        .salary-updated::after {
            content: "🔄";
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
        }

        .value-updated {
            animation: pulseUpdate 1s;
        }

        @keyframes pulseUpdate {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
                color: #e67e22;
            }

            100% {
                transform: scale(1);
            }
        }

        .export-btn-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .dropdown-toggle::after {
            display: none !important;
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
        }

        .export-btn:hover {
            background-color: #5a3a8a;
            transform: translateY(-1px);
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

        .financial-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #6e48aa;
        }

        .summary-card h3 {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .summary-card .amount {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }

        .summary-card .subtext {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }

        .financial-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .financial-table th {
            background-color: #f8fafc;
            padding: 15px;
            text-align: center;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
            border-bottom: 2px solid #e5e7eb;
        }

        .financial-table td {
            padding: 15px;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
            color: #4b5563;
        }

        .financial-table tr:last-child td {
            border-bottom: none;
        }

        .financial-table tr:hover td {
            background-color: #f9fafb;
        }

        .bank-details {
            display: flex;
            align-items: center;
            gap: 10px;
            align-items: center;
            justify-content: center;
        }



        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .view-btn {
            background-color: #e0e7ff;
            color: #4f46e5;
            border: none;
        }

        .view-btn:hover {
            background-color: #c7d2fe;
        }

        .empty-state {
            padding: 40px 20px;
            text-align: center;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .empty-icon {
            font-size: 48px;
            color: #e5e7eb;
            margin-bottom: 15px;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div
                        class="card-header p-2 position-relative z-index-2 d-flex align-items-center justify-content-between w-100">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 flex-grow-1">
                            <h6 class="text-black text-capitalize ps-3 mb-0"
                                style="font-size:25px; font-weight:800;color: #000;">
                                البيانات المالية للموظفين
                            </h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <!-- Export Buttons -->
                            <div class="export-btn-group no-print">
                                <!-- PDF Export Button -->
                                <button id="pdfExportBtn" class="export-btn">
                                    <span class="btn-icon"><i class="fas fa-file-pdf"></i></span>
                                    <span class="btn-text">تصدير PDF</span>
                                </button>

                                <!-- Excel Export Button -->
                                <button id="excelExportBtn" class="export-btn">
                                    <span class="btn-icon"><i class="fas fa-file-excel"></i></span>
                                    <span class="btn-text">تصدير Excel</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Summary Cards -->
                    <div class="financial-summary">
                        <div class="summary-card">
                            <h3>💰 إجمالي الرواتب الأساسية</h3>
                            <div class="amount" id="totalSalariesCard">{{ number_format($totalSalaries) }} ر.س</div>
                            <div class="subtext">لـ {{ $employeesCount }} موظف</div>
                        </div>

                        <div class="summary-card">
                            <h3>📈 إجمالي المكافآت</h3>
                            <div class="amount" id="totalIncreasesCard">{{ number_format($totalIncreases) }} ر.س</div>
                            <div class="subtext">لشهر {{ $currentMonth }}</div>
                        </div>

                        <div class="summary-card">
                            <h3>💸 إجمالي الخصومات</h3>
                            <div class="amount" id="totalDeductionsCard">{{ number_format($totalDeductions) }} ر.س</div>
                            <div class="subtext">لشهر {{ $currentMonth }}</div>
                        </div>

                        <div class="summary-card">
                            <h3>💵 إجمالي صافي الرواتب</h3>
                            <div class="amount" id="totalNetSalariesCard">{{ number_format($totalNetSalaries) }} ر.س</div>
                            <div class="subtext">بعد المكافآت والخصومات</div>
                        </div>
                    </div>

                    <!-- Employees Financial Table -->
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            @if (count($employees) > 0)
                                <table class="financial-table">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-id-card-alt me-1 text-primary"></i> ID</th>
                                            <th><i class="fas fa-user-tie me-1 text-info"></i> اسم الموظف</th>
                                            <th><i class="fas fa-coins me-1 text-warning"></i> الراتب الأساسي</th>
                                            <th><i class="fas fa-plus-circle me-1 text-success"></i> المكافآت</th>
                                            <th><i class="fas fa-minus-circle me-1 text-danger"></i> خصومات الشهر</th>
                                            <th><i class="fas fa-hand-holding-usd me-1 text-danger"></i> خصومات السلف</th>
                                            <th><i class="fas fa-money-bill-wave me-1 text-success"></i> صافي الراتب</th>
                                            <th><i class="fas fa-credit-card me-1 text-purple"></i> رقم الآيبان</th>
                                            <th><i class="fas fa-user-circle me-1 text-purple"></i> اسم صاحب الحساب</th>
                                            <th><i class="fas fa-university me-1 text-purple"></i> البنك</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $employee)
                                            <tr>
                                                <td>{{ $employee['id'] }}</td>
                                                <td class="font-weight-600">{{ $employee['name'] }}</td>
                                                <td>{{ number_format($employee['base_salary']) }} ر.س</td>
                                                <td class="text-success">
                                                    @if ($employee['current_month_increases'] > 0)
                                                        +{{ number_format($employee['current_month_increases']) }} ر.س
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-danger">
                                                    {{ number_format($employee['current_month_deductions']) }} ر.س
                                                </td>
                                                <td class="text-danger">
                                                    {{ number_format($employee['advance_deductions']) }} ر.س
                                                </td>
                                                <td class="text-success font-weight-bold">
                                                    {{ number_format($employee['net_salary']) }} ر.س
                                                </td>
                                                <td class="text-success font-weight-bold">
                                                    {{ ($employee['bank_details']['iban']) }}

                                                <td class="text-success font-weight-bold">
                                                    {{ ($employee['bank_details']['owner_account_name']) }}
                                                </td>
                                                <td>
                                                    <div class="bank-details">
                                                        @php
                                                            $bankFileBase = 'build/assets/img/' . strtolower(str_replace(' ', '-', $employee['bank_details']['bank_name']));
                                                            $extensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];

                                                            $bankLogo = null;
                                                            foreach ($extensions as $ext) {
                                                                if (file_exists(public_path($bankFileBase . '.' . $ext))) {
                                                                    $bankLogo = asset($bankFileBase . '.' . $ext);
                                                                    break;
                                                                }
                                                            }
                                                        @endphp

                                                        @if ($employee['bank_details']['bank_name'] && $bankLogo)
                                                            <img src="{{ $bankLogo }}"
                                                                 alt="{{ $employee['bank_details']['bank_name'] }}"
                                                                 class="h-12 w-12">
                                                            <span>{{ $employee['bank_details']['bank_name'] }}</span>
                                                        @else
                                                            غير محدد
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <button
                                                        onclick="openSalaryModal(
                            '{{ $employee['id'] }}',
                            '{{ $employee['name'] }}',
                            '{{ $employee['base_salary'] }}',
                            this.parentNode.parentNode
                        )"
                                                        class="action-btn view-btn">
                                                        <i class="fas fa-edit"></i> تعديل
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                    <h3 class="text-gray-500">لا توجد بيانات مالية متاحة</h3>
                                    <p class="text-gray-400 mt-2">لا يوجد موظفين لعرض بياناتهم المالية</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id="salaryAdjustmentModal">
        <div class="modal-content">
            <button onclick="closeSalaryModal()" class="absolute left-4 top-4 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h2 class="text-xl font-bold mb-4 text-center">تعديل الراتب حسب الغياب والخصومات</h2>

            <div class="mb-3">
                <label class="block text-sm text-gray-700 mb-1">اسم الموظف</label>
                <input id="employeeName" class="w-full border border-gray-300 rounded-md px-3 py-2" readonly />
            </div>

            <div class="mb-3">
                <label class="block text-sm text-gray-700 mb-1">الراتب الأساسي</label>
                <input id="currentSalary" class="w-full border border-gray-300 rounded-md px-3 py-2" readonly />
            </div>

            <div class="mb-3">
                <label class="block text-sm text-gray-700 mb-1">خصومات الشهر الحالي</label>
                <input id="currentDeductions" class="w-full border border-gray-300 rounded-md px-3 py-2"
                    oninput="validateNumberInput(this)" />
            </div>

            <div class="mb-3">
                <label class="block text-sm text-gray-700 mb-1">خصومات السلف</label>
                <input id="advanceDeductions" class="w-full border border-gray-300 rounded-md px-3 py-2"
                    oninput="validateNumberInput(this)" />
            </div>

            <div class="mb-3">
                <label class="block text-sm text-gray-700 mb-1">عدد أيام الغياب</label>
                <input id="absenceDays" type="number" min="0" max="26" step="1"
                    class="w-full border border-gray-300 rounded-md px-3 py-2" oninput="validateAbsenceDays(this)" />
            </div>

            <div class="mb-3">
                <label class="block text-sm text-gray-700 mb-1">صافي الراتب</label>
                <input id="adjustedSalary"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 font-bold" readonly />
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                <button onclick="closeSalaryModal()"
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    إلغاء
                </button>
                <button onclick="calculateAdjustedSalary()"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    احتساب الراتب
                </button>
                <button onclick="saveAdjustedSalary()" id="saveSalaryBtn"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 hidden">
                    حفظ التعديل
                </button>
            </div>
        </div>
    </div>
{{--    test--}}
@endsection

@push('scripts')
    <script>
        document.getElementById('excelExportBtn').addEventListener('click', function() {
            const table = document.querySelector('.financial-table');
            const rows = table.querySelectorAll('tr');
            const data = [];

            // Export all columns except actions (last column)
            const colsToExport = table.rows[0].cells.length - 1;

            const headers = [];
            for (let i = 0; i < colsToExport; i++) {
                headers.push(table.rows[0].cells[i].textContent.trim());
            }
            data.push(headers);

            for (let i = 1; i < rows.length; i++) {
                const row = [];
                const cells = rows[i].querySelectorAll('td');

                for (let j = 0; j < colsToExport; j++) {
                    // Handle bank logo cell - extract bank name
                    if (j === 9) { // Bank column
                        const bankDetails = cells[j].querySelector('.bank-details');
                        if (bankDetails) {
                            const bankNameSpan = bankDetails.querySelector('span');
                            row.push(bankNameSpan ? bankNameSpan.textContent.trim() : 'غير محدد');
                        } else {
                            row.push(cells[j].textContent.trim());
                        }
                    } else {
                        row.push(cells[j].textContent.trim());
                    }
                }
                data.push(row);
            }

            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(data);

            // Adjust column widths for new structure
            const wscols = [
                { width: 10 }, // ID
                { width: 25 }, // Name
                { width: 15 }, // Base Salary
                { width: 15 }, // Increases
                { width: 15 }, // Current Deductions
                { width: 15 }, // Advance Deductions
                { width: 15 }, // Net Salary
                { width: 25 }, // IBAN
                { width: 20 }, // Owner Name
                { width: 20 }  // Bank
            ];
            ws['!cols'] = wscols;

            XLSX.utils.book_append_sheet(wb, ws, "البيانات المالية");

            XLSX.writeFile(wb, 'البيانات_المالية_للموظفين_' + new Date().toISOString().slice(0, 10) + '.xlsx');
        });

        document.getElementById('pdfExportBtn').addEventListener('click', function() {
            const originalTable = document.querySelector('.financial-table');

            const tableClone = originalTable.cloneNode(true);

            const rows = tableClone.querySelectorAll('tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('th, td');
                if (cells.length > 0) {
                    row.removeChild(cells[cells.length - 1]);
                }
            });

            const pdfContainer = document.createElement('div');
            pdfContainer.style.padding = '20px';
            pdfContainer.style.direction = 'rtl';
            pdfContainer.style.fontFamily = 'Arial, sans-serif';
            pdfContainer.style.textAlign = 'right';

            const header = document.createElement('div');
            header.style.marginBottom = '30px';
            header.style.borderBottom = '2px solid #6e48aa';
            header.style.paddingBottom = '15px';
            header.style.display = 'flex';
            header.style.justifyContent = 'space-between';
            header.style.alignItems = 'center';

            const companyInfo = document.createElement('div');
            const companyName = document.createElement('h1');
            companyName.textContent = 'تقرير البيانات المالية للموظفين';
            companyName.style.color = '#6e48aa';
            companyName.style.margin = '0 0 5px 0';
            companyName.style.fontSize = '20px';

            const reportDate = document.createElement('p');
            reportDate.textContent = 'تاريخ التقرير: ' + new Date().toLocaleDateString('ar-EG');
            reportDate.style.color = '#666';
            reportDate.style.margin = '0';

            companyInfo.appendChild(companyName);
            companyInfo.appendChild(reportDate);

            const logoContainer = document.createElement('div');
            const logo = document.createElement('img');
            logo.src = '{{ asset('build/assets/img/logo.png') }}';
            logo.style.maxWidth = '100px';
            logo.style.maxHeight = '60px';
            logo.alt = 'شعار الشركة';

            logo.onerror = function() {
                this.style.display = 'none';
                const fallbackText = document.createElement('div');
                fallbackText.textContent = 'الشعار';
                fallbackText.style.width = '100px';
                fallbackText.style.height = '60px';
                fallbackText.style.display = 'flex';
                fallbackText.style.alignItems = 'center';
                fallbackText.style.justifyContent = 'center';
                fallbackText.style.backgroundColor = '#f5f5f5';
                fallbackText.style.borderRadius = '4px';
                fallbackText.style.fontWeight = 'bold';
                logoContainer.appendChild(fallbackText);
            };

            logoContainer.appendChild(logo);
            header.appendChild(companyInfo);
            header.appendChild(logoContainer);

            tableClone.style.width = '100%';
            tableClone.style.borderCollapse = 'collapse';
            tableClone.style.marginTop = '20px';

            const thElements = tableClone.querySelectorAll('th');
            thElements.forEach(th => {
                th.style.backgroundColor = '#6e48aa';
                th.style.color = 'white';
                th.style.padding = '12px';
                th.style.border = '1px solid #ddd';
                th.style.textAlign = 'right';
            });

            const tdElements = tableClone.querySelectorAll('td');
            tdElements.forEach(td => {
                td.style.padding = '10px';
                td.style.border = '1px solid #ddd';
                td.style.textAlign = 'right';
            });

            pdfContainer.appendChild(header);
            pdfContainer.appendChild(tableClone);

            const footer = document.createElement('div');
            footer.style.marginTop = '20px';
            footer.style.paddingTop = '10px';
            footer.style.borderTop = '1px solid #eee';
            footer.style.textAlign = 'center';
            footer.style.color = '#666';
            footer.style.fontSize = '12px';

            const copyright = document.createElement('p');
            copyright.textContent = `© ${new Date().getFullYear()} جميع الحقوق محفوظة`;

            footer.appendChild(copyright);
            pdfContainer.appendChild(footer);

            const options = {
                margin: [15, 15, 30, 15],
                filename: 'البيانات_المالية_للموظفين_' + new Date().toISOString().slice(0, 10) + '.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    logging: true,
                    useCORS: true,
                    allowTaint: true,
                    scrollX: 0,
                    scrollY: 0,
                    letterRendering: true,
                    onclone: function(clonedDoc) {
                        clonedDoc.documentElement.dir = 'rtl';
                        clonedDoc.body.style.direction = 'rtl';
                        clonedDoc.body.style.textAlign = 'right';
                    }
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a2',
                    orientation: 'portrait',
                    compress: true
                }
            };

            const pdfBtn = document.getElementById('pdfExportBtn');
            const originalText = pdfBtn.innerHTML;
            pdfBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
            pdfBtn.disabled = true;

            html2pdf()
                .set(options)
                .from(pdfContainer)
                .save()
                .catch(err => {
                    console.error('PDF export error:', err);
                    alert('حدث خطأ أثناء التصدير: ' + err.message);
                })
                .finally(() => {
                    pdfBtn.innerHTML = originalText;
                    pdfBtn.disabled = false;
                });
        });
        document.addEventListener('DOMContentLoaded', function() {
            originalTotalSalaries = parseFloat(document.getElementById('totalSalariesCard').textContent.replace(
                /[^\d.]/g, '')) || 0;
            originalTotalDeductions = parseFloat(document.getElementById('totalDeductionsCard').textContent.replace(
                /[^\d.]/g, '')) || 0;
            originalTotalNetSalaries = parseFloat(document.getElementById('totalNetSalariesCard').textContent
                .replace(/[^\d.]/g, '')) || 0;
        });
    </script>
    <script>
        let currentEmployeeRow = null;
        let originalSalary = 0;
        let currentEmployeeId = null;
        let originalTotalSalaries = parseFloat({{ $totalSalaries }});
        let originalTotalDeductions = parseFloat({{ $totalDeductions }});
        let originalTotalNetSalaries = parseFloat({{ $totalNetSalaries }});

        function openSalaryModal(employeeId, employeeName, baseSalary, rowElement) {
            currentEmployeeRow = rowElement;
            currentEmployeeId = employeeId;
            originalSalary = parseFloat(baseSalary);

            // Get current values from the table row with new column indices
            // Cell indices: 0=ID, 1=Name, 2=Base Salary, 3=Increases, 4=Current Deductions, 5=Advance Deductions, 6=Net Salary, 7=IBAN, 8=Owner Name, 9=Bank, 10=Actions

            const currentDeductions = parseFloat(rowElement.cells[4].textContent.replace(/[^\d.]/g, '')) || 0;
            const advanceDeductions = parseFloat(rowElement.cells[5].textContent.replace(/[^\d.]/g, '')) || 0;
            const netSalary = parseFloat(rowElement.cells[6].textContent.replace(/[^\d.]/g, '')) || 0;

            // Set values in the modal
            document.getElementById('employeeName').value = employeeName;
            document.getElementById('currentSalary').value = numberFormat(originalSalary) + ' ر.س';
            document.getElementById('currentDeductions').value = numberFormat(currentDeductions);
            document.getElementById('advanceDeductions').value = numberFormat(advanceDeductions);
            document.getElementById('absenceDays').value = 0; // Reset absence days
            document.getElementById('adjustedSalary').value = numberFormat(netSalary) + ' ر.س';

            // Hide save button initially
            document.getElementById('saveSalaryBtn').classList.add('hidden');

            // Show modal
            document.getElementById('salaryAdjustmentModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSalaryModal() {
            document.getElementById('salaryAdjustmentModal').classList.remove('show');
            document.body.style.overflow = 'auto'; // Re-enable scrolling
            currentEmployeeRow = null;
            currentEmployeeId = null;
            originalSalary = 0;
        }

        function calculateAdjustedSalary() {
            const absenceDays = parseInt(document.getElementById('absenceDays').value) || 0;
            const currentDeductions = parseFloat(
                document.getElementById('currentDeductions').value.replace(/[^0-9.]/g, '')
            ) || 0;
            const advanceDeductions = parseFloat(
                document.getElementById('advanceDeductions').value.replace(/[^0-9.]/g, '')
            ) || 0;

            // Get the current month increases from the table row (cell index 3)
            const currentIncreasesText = currentEmployeeRow.cells[3].textContent;
            const currentIncreases = parseFloat(currentIncreasesText.replace(/[^0-9.]/g, '')) || 0;

            if (isNaN(absenceDays)) {
                alert('الرجاء إدخال عدد أيام غياب صحيح');
                return;
            }

            if (absenceDays < 0 || absenceDays > 26) {
                alert('عدد أيام الغياب يجب أن يكون بين 0 و 26');
                return;
            }

            // Calculate daily rate based on base salary + increases
            const dailyRate = (originalSalary + currentIncreases) / 26;
            const absenceDeduction = absenceDays * dailyRate;

            // Calculate net salary: base + increases - all deductions
            const netSalary = (originalSalary + currentIncreases) - currentDeductions - advanceDeductions - absenceDeduction;

            // Ensure net salary doesn't go negative
            const finalNetSalary = Math.max(0, netSalary);

            document.getElementById('adjustedSalary').value = numberFormat(finalNetSalary) + ' ر.س';
            document.getElementById('saveSalaryBtn').classList.remove('hidden');
        }

        function updateSummaryCards(currentDeductionsDiff, advanceDeductionsDiff, absenceDeductionDiff) {
            const totalDeductionsDiff = currentDeductionsDiff + advanceDeductionsDiff + absenceDeductionDiff;

            const totalDeductionsElement = document.getElementById('totalDeductionsCard');
            const totalNetSalariesElement = document.getElementById('totalNetSalariesCard');

            let currentTotalDeductions = parseFloat(totalDeductionsElement.textContent.replace(/[^\d.]/g, '')) || 0;
            let currentTotalNetSalaries = parseFloat(totalNetSalariesElement.textContent.replace(/[^\d.]/g, '')) || 0;

            const newTotalDeductions = Math.max(0, currentTotalDeductions + totalDeductionsDiff);
            const newTotalNetSalaries = Math.max(0, currentTotalNetSalaries - totalDeductionsDiff);

            totalDeductionsElement.textContent = numberFormat(newTotalDeductions) + ' ر.س';
            totalNetSalariesElement.textContent = numberFormat(newTotalNetSalaries) + ' ر.س';

            totalDeductionsElement.classList.add('value-updated');
            totalNetSalariesElement.classList.add('value-updated');

            setTimeout(() => {
                totalDeductionsElement.classList.remove('value-updated');
                totalNetSalariesElement.classList.remove('value-updated');
            }, 1000);
        }

        function saveAdjustedSalary() {
            if (!currentEmployeeRow) return;

            const absenceDays = parseInt(document.getElementById('absenceDays').value) || 0;
            const currentDeductions = parseFloat(
                document.getElementById('currentDeductions').value.replace(/[^0-9.]/g, '')
            ) || 0;
            const advanceDeductions = parseFloat(
                document.getElementById('advanceDeductions').value.replace(/[^0-9.]/g, '')
            ) || 0;

            // Get previous values from the table row
            const prevCurrentDeductions = parseFloat(currentEmployeeRow.cells[3].textContent.replace(/[^\d.]/g, '')) || 0;
            const prevAdvanceDeductions = parseFloat(currentEmployeeRow.cells[4].textContent.replace(/[^\d.]/g, '')) || 0;
            const prevAbsenceDays = parseInt(currentEmployeeRow.cells[5].textContent.replace(/[^\d]/g, '')) || 0;

            // Get current increases value
            const currentIncreasesText = currentEmployeeRow.cells[3].textContent;
            const currentIncreases = parseFloat(currentIncreasesText.replace(/[^0-9.]/g, '')) || 0;

            // Calculate new values
            const dailyRate = (originalSalary + currentIncreases) / 26;
            const absenceDeduction = absenceDays * dailyRate;
            const netSalary = Math.max(0, (originalSalary + currentIncreases) - currentDeductions - advanceDeductions - absenceDeduction);

            // Update table cells - note the adjusted cell indices due to new columns
            // Cell indices: 0=ID, 1=Name, 2=Base Salary, 3=Increases, 4=Current Deductions, 5=Advance Deductions, 6=Net Salary, 7=IBAN, 8=Owner Name, 9=Bank, 10=Actions

            // Update deductions (cell 4)
            currentEmployeeRow.cells[4].textContent = numberFormat(currentDeductions) + ' ر.س';
            currentEmployeeRow.cells[4].className = 'text-danger';

            // Update advance deductions (cell 5)
            currentEmployeeRow.cells[5].textContent = numberFormat(advanceDeductions) + ' ر.س';
            currentEmployeeRow.cells[5].className = 'text-danger';

            // Update net salary (cell 6)
            currentEmployeeRow.cells[6].textContent = numberFormat(netSalary) + ' ر.س';
            currentEmployeeRow.cells[6].className = 'text-success font-weight-bold salary-updated';

            // Show success message
            showToast('تم تحديث الراتب بنجاح', 'success');

            // Update summary cards
            const currentDeductionsDiff = currentDeductions - prevCurrentDeductions;
            const advanceDeductionsDiff = advanceDeductions - prevAdvanceDeductions;
            const absenceDeductionDiff = absenceDeduction - (prevAbsenceDays * ((originalSalary + currentIncreases) / 26));

            updateSummaryCards(currentDeductionsDiff, advanceDeductionsDiff, absenceDeductionDiff);

            closeSalaryModal();
        }

        function numberFormat(number) {
            return new Intl.NumberFormat('en-US').format(Math.round(number));
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        function validateNumberInput(input) {
            input.value = input.value.replace(/[^0-9.]/g, '');
        }

        // Helper function to validate absence days
        function validateAbsenceDays(input) {
            let value = parseInt(input.value);
            if (isNaN(value)) {
                input.value = '';
            } else {
                if (value < 0) input.value = 0;
                if (value > 26) input.value = 26;
            }
        }
    </script>
@endpush
