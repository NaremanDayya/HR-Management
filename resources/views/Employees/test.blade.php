@extends('layouts.master')
@section('title', 'جدول الموظفين')
<style>
    .export-btn-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .dropdown-toggle::after {
        display: none !important;
    }

    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1050;
    }

    .modal-dialog {
        max-width: 500px;
        width: 100%;
    }

    .modal-content {
        background-color: #fff;
        border-radius: 5px;
    }

    .modal-header,
    .modal-footer {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
        border-bottom: none;
    }

    .modal-body {
        padding: 1rem;
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

    .filter-controls {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .filter-group {
        margin-bottom: 15px;
    }

    .filter-label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .filter-select {
        width: 100%;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        background-color: white;
    }

    .filter-select:focus {
        border-color: #6e48aa;
        box-shadow: 0 0 0 0.2rem rgba(110, 72, 170, 0.25);
    }

    .custom-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: none !important;
        padding-right: 12px;
        font-size: 17px;
        font-weight: 500;
    }

    .custom-select option {
        font-size: 15px;
        font-weight: 500;
    }

    .columns-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1050;
        justify-content: center;
        align-items: center;
    }

    .columns-modal-content {
        background-color: white;
        border-radius: 10px;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .columns-modal-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .columns-modal-title {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .columns-modal-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #64748b;
        transition: color 0.2s;
    }

    .columns-modal-close:hover {
        color: #ef4444;
    }

    .columns-modal-body {
        padding: 15px;
        overflow-y: auto;
        flex-grow: 1;
    }

    .columns-search {
        position: relative;
        margin-bottom: 15px;
    }

    .columns-search input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        font-size: 14px;
    }

    .columns-search i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .columns-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .column-item {
        padding: 5px 0;
    }

    .column-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        padding: 8px 10px;
        border-radius: 6px;
        transition: background-color 0.2s;
    }

    .column-checkbox:hover {
        background-color: #f8fafc;
    }

    .column-checkbox input {
        display: none;
    }

    .checkmark {
        width: 18px;
        height: 18px;
        border: 2px solid #d1d5db;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .column-checkbox input:checked~.checkmark {
        background-color: #6e48aa;
        border-color: #6e48aa;
    }

    .checkmark:after {
        content: "✓";
        color: white;
        font-size: 12px;
        display: none;
    }

    .column-checkbox input:checked~.checkmark:after {
        display: block;
    }

    .column-name {
        font-size: 14px;
        color: #334155;
    }

    .columns-modal-footer {
        padding: 15px 20px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .columns-actions {
        display: flex;
        gap: 10px;
    }

    .btn-select-all {
        background: none;
        border: none;
        color: #6e48aa;
        font-size: 14px;
        cursor: pointer;
        padding: 5px 10px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .btn-select-all:hover {
        background-color: #f1f5f9;
    }

    .btn-cancel {
        padding: 8px 16px;
        border-radius: 6px;
        background-color: white;
        color: #334155;
        border: 1px solid #d1d5db;
        cursor: pointer;
        font-size: 14px;
        margin-left: 10px;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background-color: #f8fafc;
    }

    .btn-apply {
        padding: 8px 16px;
        border-radius: 6px;
        background-color: #6e48aa;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }

    .btn-apply:hover {
        background-color: #9875ea;
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
        background-color: #6a7ef9;
        transform: translateY(-1px);
    }

    .columns-badge {
        background-color: white;
        color: #6e48aa;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        z-index: 10;
        min-width: 180px;
        margin-top: 4px;
    }

    .dropdown.active .dropdown-menu {
        display: block;
    }

    .status-dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        padding: 8px 12px;
        text-align: right;
        color: #374151;
        font-size: 14px;
        background: none;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;

    }

    .status-dropdown-item:hover {
        background-color: #f8fafc;
    }

    .bulk-actions {
        margin-right: 10px;
    }

    #bulkActionsBtn {
        padding: 8px 16px;
        border-radius: 6px;
        background-color: #f8fafc;
        color: #334155;
        border: 1px solid #d1d5db;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    #bulkActionsBtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #f8f9fa;
        color: #6c757d;
        border-color: #dee2e6;
    }

    .dropdown.active #bulkActionsBtn svg {
        transform: rotate(180deg);
    }

    .dropdown.active #bulkActionsBtn svg {
        transform: rotate(180deg);
    }

    #bulkActionsDropdown {
        min-width: 180px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        padding: 5px 0;
        background-color: white;
    }

    #bulkActionsBtn:hover {
        background-color: #f1f5f9;
    }



    #bulkActionsBtn svg {
        transition: transform 0.2s;
    }

    .emp-checkbox {
        margin: 0 auto;
        display: block;
        width: 16px;
        height: 16px;
    }

    #selectAllCheckbox {
        width: 16px;
        height: 16px;
    }

    .thead {
        font-size: 18px;
    }

    .tbody {
        font-size: 18px;
    }

    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    }

    .status-indicator.active {
        background: rgb(43, 222, 43);
    }

    .status-indicator.inactive {
        background: red;
    }

    .employee-table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }

    .employee-name {
        font-weight: 600;
        color: #0d0e10;
    }

    .employee-detail {
        font-weight: 500;
    }

    .status-badge {
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        border-radius: 0.25rem;
    }

    .status-active {
        background: linear-gradient(195deg, #66BB6A 0%, #43A047 100%);
        color: white;
    }

    .status-inactive {
        background: linear-gradient(195deg, #EF5350 0%, #E53935 100%);
        color: white;
    }

    .employee-image {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .phone-icon {
        width: 20px;
        height: 20px;
    }

    .action-btn {
        font-weight: 500;
    }

    .empty-state {
        padding: 40px 20px;
        text-align: center;
        color: #6b7280;
    }

    [x-cloak] {
        display: none !important;
    }

    .empty-icon {
        font-size: 48px;
        color: #e5e7eb;
        margin-bottom: 15px;
    }
</style>
@section('content')
    {{--
<pre>{{ print_r($resources->toArray(request()), true) }}</pre> --}}

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div
                        class="card-header p-2 position-relative z-index-2 d-flex align-items-center justify-content-between w-100">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 flex-grow-1">
                            <h6 class="text-black text-capitalize ps-3 mb-0"
                                style="font-size:25px; font-weight:800;color: #000;">الموظفين</h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-purple" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">
                                <i class="fas fa-plus"></i> إضافة موظف
                            </button>

                            <!-- Column Selection Button -->
                            <div class="export-btn-group no-print">
                                <button id="columnsBtn" class="export-btn columns-btn" onclick="openColumnsModal()">
                                    <span class="btn-icon"><i class="fas fa-columns"></i></span>
                                    <span class="btn-text">اختيار الأعمدة</span>
                                    <span id="columnsBadge" class="columns-badge">17</span>
                                </button>
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

                            <div x-data="{ bulkActionsOpen: false }" class="bulk-actions no-print ms-2" x-cloak>
                                <div class="dropdown">
                                    <button @click="bulkActionsOpen = !bulkActionsOpen"
                                        class="btn btn-outline-secondary dropdown-toggle" id="bulkActionsBtn" type="button"
                                        :aria-expanded="bulkActionsOpen" disabled>
                                        تنفيذ الإجراء
                                        <svg width="12" height="8" viewBox="0 0 12 8" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="bulkActionsBtn" id="bulkActionsDropdown">
                                        <li>
                                            <button class="status-dropdown-item" data-action="activate">تفعيل
                                                الحساب</button>
                                        </li>
                                        <li>
                                            <button class="status-dropdown-item" data-action="deactivate">تعطيل
                                                الحساب</button>
                                        </li>
                                        <li>
                                            <button class="status-dropdown-item" data-action="united_clothes">ملابس
                                                موحدة</button>
                                        </li>
                                        <li>
                                            <button class="status-dropdown-item" data-action="tool_bag">حقيبة
                                                أدوات</button>
                                        </li>
                                        <li>
                                            <button class="status-dropdown-item" data-action="salary_advance">سلفة
                                                راتب</button>
                                        </li>
                                        <li>
                                            <button class="status-dropdown-item" data-action="generate_health_card">إنشاء
                                                بطاقة صحية</button>
                                        </li>
                                        <li>
                                            <button class="status-dropdown-item" data-action="salary_increase">زيادة
                                                راتب</button>
                                        </li>
                                        <li>
                                            <button class="status-dropdown-item" data-action="add_alert">إرسال
                                                إنذار</button>
                                        </li>
                                        <li>
                                            <button class="status-dropdown-item" data-action="add_deduction">إجراء
                                                خصم</button>
                                        </li>
                                        <li>
                                            <button type="button" class="status-dropdown-item"
                                                @click="$store.salaryAdvance.openModal()">
                                                طلب سلفة
                                            </button>
                                        </li>

                                </div>
                                </ul>
                            </div>
                        </div>


                    </div>
                    <!-- Age Threshold Modal Button -->
                    <div x-data="{ open: false }">
                        <button @click="open = true"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow">
                            تعديل العمر الأقصى
                        </button>
                        <div x-show="open" x-cloak
                            class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                            <div @click.away="open = false" class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">

                                <h3 class="text-lg font-bold mb-4">تعديل العمر الأقصى</h3>

                                <form action="{{ route('settings.age_threshold.update') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="max_age" class="block text-sm font-medium text-gray-700">
                                            العمر</label>
                                        <input type="number" name="max_age" id="max_age" min="1" required
                                            value="{{ old('max_age', \App\Models\Setting::where('key', 'age_alert_threshold ')->value('value') ?? 3) }}"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500">
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" @click="open = false"
                                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">إلغاء</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">حفظ</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div x-data="{ showSalaryAdvanceModal: false }">
                        <!-- ... your dropdown here ... -->

                        <!-- Salary Advance Modal -->
                        <div x-show="showSalaryAdvanceModal"
                            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                            <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg">
                                <h2 class="text-lg font-bold mb-4">طلب سلفة جماعي</h2>
                                <form id="salaryAdvanceForm" @submit.prevent="submitSalaryAdvance">
                                    <div class="mb-3">
                                        <label class="block mb-1 text-sm">قيمة السلفة</label>
                                        <input type="number" name="advance_amount" min="100" required
                                            class="w-full border px-3 py-2 rounded" />
                                    </div>

                                    <div class="mb-3">
                                        <label class="block mb-1 text-sm">السبب</label>
                                        <input type="text" name="reason" required
                                            class="w-full border px-3 py-2 rounded" />
                                    </div>

                                    <div class="mb-3">
                                        <label class="block mb-1 text-sm">عدد أشهر السداد</label>
                                        <input type="number" name="repayment_months" min="1" max="12"
                                            required class="w-full border px-3 py-2 rounded" />
                                    </div>

                                    <input type="hidden" name="employee_ids"
                                        :value="JSON.stringify(selectedEmployeeIds)" />

                                    <div class="flex justify-end mt-4 gap-2">
                                        <button type="button" class="px-4 py-2 bg-gray-300 rounded"
                                            @click="showSalaryAdvanceModal = false">إلغاء</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded">إرسال</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Column Selection Modal -->
        <div id="columnsModal" class="columns-modal">
            <div class="columns-modal-content">
                <div class="columns-modal-header">
                    <h3 class="columns-modal-title">
                        <i class="fas fa-columns"></i>
                        اختيار الأعمدة للعرض
                    </h3>
                    <button class="columns-modal-close" onclick="closeColumnsModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="columns-modal-body">
                    <div class="columns-search">
                        <input type="text" id="columnsSearch" placeholder="بحث عن عمود..." onkeyup="filterColumns()">
                        <i class="fas fa-search"></i>
                    </div>

                    <div class="columns-list" id="columnsList">
                        <!-- Column items will be generated here -->
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="image" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">الصورة</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="name" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">اسم الموظف</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="id_card" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">رقم الهوية</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="birthday" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">تاريخ الميلاد</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="salary" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">الراتب</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="certificate_type" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">التعليم</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="project" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">المشروع</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="members_number" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">العائلة</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="deductions_number" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">العقوبات</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="Tshirt_size" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">مقاسات الملابس</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="stop_reason" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">سبب التوقف</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="members_number" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">العائلة</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="marital_status" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">الحالة الاجتماعية</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="phone_number" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">الهاتف</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="residence" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">مقر الإقامة</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="vehicle_info" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">بيانات المركبة</span>
                            </label>
                        </div>
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="joining_date" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">تاريخ الالتحاق</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="columns-modal-footer">
                    <div class="columns-actions">
                        <button class="btn-select-all" onclick="toggleSelectAll()">تحديد الكل</button>
                        <button class="btn-select-all" onclick="resetSelection()">إعادة تعيين</button>
                    </div>
                    <div>
                        <button class="btn-cancel" onclick="closeColumnsModal()">إلغاء</button>
                        <button class="btn-apply" onclick="applyColumnSelection()">
                            <i class="fas fa-check"></i>
                            تطبيق
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Employee Modal -->
        <div class="modal fade" id="createEmployeeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">إضافة موظف جديد</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="createEmployeeForm" action="{{ route('employees.store') }}" method="POST"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="modal-body p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Column 1 -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">الاسم
                                            الكامل</label>
                                        <input type="text" name="name"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                            required>
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">المهنة في
                                            الهوية</label>
                                        <input type="text" name="job"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('job') border-red-500 @enderror">
                                        @error('job')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">رقم
                                            الهوية</label>
                                        <input type="text" name="id_card"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('id_card') border-red-500 @enderror"
                                            required>
                                        @error('id_card')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">الجنسية</label>
                                        <input type="text" name="nationality"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nationality') border-red-500 @enderror"
                                            required>
                                        @error('nationality')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ
                                            الميلاد</label>
                                        <input type="text" id="birthday" name="birthday"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('birthday') border-red-500 @enderror"
                                            placeholder="ادخل تاريخ الميلاد" required>
                                        @error('birthday')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">العمر</label>
                                        <input type="number" name="age"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('age') border-red-500 @enderror"
                                            required>
                                        @error('age')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">الجنس</label>
                                        <select name="gender"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('gender') border-red-500 @enderror"
                                            required>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                ذكر</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                أنثى</option>
                                        </select>
                                        @error('gender')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">مقر
                                            الإقامة</label>
                                        <input type="text" name="residence"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('residence') border-red-500 @enderror"
                                            required>
                                        @error('residence')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">الحي
                                            السكني</label>
                                        <input type="text" name="residence_neighborhood"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('residence_neighborhood') border-red-500 @enderror"
                                            required>
                                        @error('residence_neighborhood')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">نوع
                                            المركبة</label>
                                        <input type="text" name="vehicle_type"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('vehicle_type') border-red-500 @enderror"
                                            required>
                                        @error('vehicle_type')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">موديل
                                            المركبة</label>
                                        <input type="text" name="vehicle_model"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('vehicle_model') border-red-500 @enderror"
                                            required>
                                        @error('vehicle_model')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">رقم لوحة
                                            المركبة</label>
                                        <input type="text" name="vehicle_ID"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('vehicle_ID') border-red-500 @enderror"
                                            required>
                                        @error('vehicle_ID')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>



                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">نوع
                                            الشهادة</label>
                                        <select name="certificate_type"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('certificate_type') border-red-500 @enderror">
                                            @foreach ($certificateTypes as $value => $label)
                                                <option value="{{ $value }}"
                                                    {{ old('certificate_type') == $value ? 'selected' : '' }}>
                                                    {{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('certificate_type')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">صورة
                                            الموظف</label>
                                        <div class="mt-1 flex items-center">
                                            <input type="file" name="personal_image"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('personal_image') border-red-500 @enderror"
                                                required>
                                        </div>
                                        @error('personal_image')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Column 2 -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">البريد
                                            الإلكتروني</label>
                                        <input type="email" name="email"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                            required>
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">رقم
                                            الجوال</label>
                                        <input type="tel" name="phone_number"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone_number') border-red-500 @enderror"
                                            required>
                                        @error('phone_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">نوع
                                            الجوال</label>
                                        <select name="phone_type"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone_type') border-red-500 @enderror"
                                            required>
                                            <option value="android"
                                                {{ old('phone_type') == 'android' ? 'selected' : '' }}>
                                                أندرويد</option>
                                            <option value="iphone" {{ old('phone_type') == 'iphone' ? 'selected' : '' }}>
                                                آيفون</option>
                                        </select>
                                        @error('phone_type')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">الدور
                                            الوظيفي</label>
                                        <input type="text" name="role"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror"
                                            required>
                                        @error('role')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">منطقة
                                            العمل</label>
                                        <input type="text" name="work_area"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('work_area') border-red-500 @enderror"
                                            required>
                                        @error('work_area')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ
                                            الإنضمام</label>
                                        <input type="text" id="joining_date" name="joining_date"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('joining_date') border-red-500 @enderror"
                                            placeholder="اختر تاريخ الانضمام" required>
                                        @error('joining_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">مقاس التي
                                            شيرت</label>
                                        <select name="Tshirt_size"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('Tshirt_size') border-red-500 @enderror">
                                            @foreach ($shirtSizes as $value => $label)
                                                <option value="{{ $value }}"
                                                    {{ old('Tshirt_size') == $value ? 'selected' : '' }}>
                                                    {{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('Tshirt_size')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">مقاس
                                            البنطال</label>
                                        <select name="pants_size"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('pants_size') border-red-500 @enderror">
                                            @foreach ($pantsSizes as $value => $label)
                                                <option value="{{ $value }}"
                                                    {{ old('pants_size') == $value ? 'selected' : '' }}>
                                                    {{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('pants_size')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">هل لدى
                                            الموظف شهادة صحية (كرت البلدية)؟</label>
                                        <select name="health_card"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('health_card') border-red-500 @enderror"
                                            required>
                                            <option value="" {{ old('health_card') == '' ? 'selected' : '' }}>اختر
                                                الحالة</option>
                                            <option value="1" {{ old('health_card') == '1' ? 'selected' : '' }}>
                                                نعم</option>
                                            <option value="0" {{ old('health_card') == '0' ? 'selected' : '' }}>لا
                                            </option>
                                        </select>
                                        @error('health_card')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">المشروع</label>
                                        <select name="project"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('project') border-red-500 @enderror">
                                            @foreach ($projects as $value => $label)
                                                <option value="{{ $value }}"
                                                    {{ old('project') == $value ? 'selected' : '' }}>
                                                    {{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('project')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">الراتب</label>
                                        <input type="number" step="0.01" name="salary"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('salary') border-red-500 @enderror"
                                            required>
                                        @error('salary')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">مستوى اللغة
                                            الإنجليزية</label>
                                        <select name="english_level"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('english_level') border-red-500 @enderror">
                                            @foreach ($englishLevels as $value => $label)
                                                <option value="{{ $value }}"
                                                    {{ old('english_level') == $value ? 'selected' : '' }}>
                                                    {{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('english_level')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div x-data="{ maritalStatus: '{{ old('marital_status', 'single') }}' }">
                                        <!-- Marital Status Dropdown -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">الحالة
                                                الاجتماعية</label>
                                            <select name="marital_status" x-model="maritalStatus"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('marital_status') border-red-500 @enderror">
                                                @foreach ($maritalStatuses as $value => $label)
                                                    <option value="{{ $value }}"
                                                        {{ old('marital_status') == $value ? 'selected' : '' }}>
                                                        {{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('marital_status')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Members Number (conditionally shown) -->
                                        <div x-show="maritalStatus !== 'single'" x-cloak style="padding-top:20px;">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">عدد أفراد
                                                الأسرة</label>
                                            <input type="number" name="members_number"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('members_number') border-red-500 @enderror"
                                                min="0">
                                            @error('members_number')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                            <button type="button"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                إلغاء
                            </button>
                            <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                إضافة الموظف
                            </button>
                        </div>
                    </form>




                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col space-y-4 md:space-y-0 md:flex-row md:flex-wrap md:items-center md:gap-4">
                <!-- Search Input -->
                <div class="flex-grow min-w-[250px]">
                    <div class="relative flex items-center">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" id="globalSearch"
                            class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right"
                            placeholder="ابحث في جميع الحقول..." value="{{ request('search') ?? '' }}">
                        <button class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                            type="button" id="clearSearch">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Project Filter -->
                <div x-data="{
                    open: false,
                    selected: '{{ $projects[request('project')] ?? '' }}',
                    options: {{ json_encode($projects) }},
                    applyFilter(value, key) {
                        const url = new URL(window.location.href);
                        if (value) {
                            url.searchParams.set(key, value);
                        } else {
                            url.searchParams.delete(key);
                        }
                        window.location.href = url.toString();
                    }
                }" x-cloak class="relative min-w-[200px] text-right" style="direction: rtl;">
                    <button @click="open = !open"
                        class="custom-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                        <span x-text="selected || 'جميع المشاريع'"></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" @click.away="open = false"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg max-h-60 overflow-auto shadow-lg">
                        <li @click="selected = ''; open = false; applyFilter('', 'project')"
                            class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium">
                            جميع المشاريع
                        </li>
                        <template x-for="(label, value) in options" :key="value">
                            <li @click="selected = label; open = false; applyFilter(value, 'project')"
                                class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium" x-text="label">
                            </li>
                        </template>
                    </ul>
                </div>

                <!-- Account Status Filter -->
                <div x-data="{
                    open: false,
                    selected: '{{ request('account_status') ?? '' }}',
                    options: { active: 'نشط', inactive: 'غير نشط' },
                    applyFilter(value, key) {
                        const url = new URL(window.location.href);
                        if (value) {
                            url.searchParams.set(key, value);
                        } else {
                            url.searchParams.delete(key);
                        }
                        window.location.href = url.toString();
                    }
                }" x-cloak class="relative min-w-[180px] text-right" style="direction: rtl;">
                    <button @click="open = !open"
                        class="custom-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                        <span x-text="selected ? (options[selected] ?? '') : 'حالة حساب الموظف'"></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" @click.away="open = false"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg max-h-60 overflow-auto shadow-lg">
                        <li @click="selected = ''; open = false; applyFilter('', 'account_status')"
                            class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium">جميع
                            الحالات</li>
                        <template x-for="(label, value) in options" :key="value">
                            <li @click="selected = value; open = false; applyFilter(value, 'account_status')"
                                class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium" x-text="label">
                            </li>
                        </template>
                    </ul>
                </div>

                <!-- Marital Status Filter -->
                <div x-data="{
                    open: false,
                    selected: '{{ $maritalStatuses[request('marital_status')] ?? '' }}',
                    options: {{ json_encode($maritalStatuses) }},
                    applyFilter(value, key) {
                        const url = new URL(window.location.href);
                        if (value) {
                            url.searchParams.set(key, value);
                        } else {
                            url.searchParams.delete(key);
                        }
                        window.location.href = url.toString();
                    }
                }" x-cloak class="relative min-w-[180px] text-right" style="direction: rtl;">
                    <button @click="open = !open"
                        class="custom-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                        <span x-text="selected || 'جميع الحالات الاجتماعية'"></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" @click.away="open = false"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg max-h-60 overflow-auto shadow-lg">
                        <li @click="selected = ''; open = false; applyFilter('', 'marital_status')"
                            class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium">
                            جميع الحالات الاجتماعية
                        </li>
                        <template x-for="(label, value) in options" :key="value">
                            <li @click="selected = label; open = false; applyFilter(value, 'marital_status')"
                                class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium" x-text="label">
                            </li>
                        </template>
                    </ul>
                </div>

                <!-- English Level Filter -->
                <div x-data="{
                    open: false,
                    selected: '{{ $englishLevels[request('english_level')] ?? '' }}',
                    options: {{ json_encode($englishLevels) }},
                    applyFilter(value, key) {
                        const url = new URL(window.location.href);
                        if (value) {
                            url.searchParams.set(key, value);
                        } else {
                            url.searchParams.delete(key);
                        }
                        window.location.href = url.toString();
                    }
                }" x-cloak class="relative min-w-[180px] text-right" style="direction: rtl;">
                    <button @click="open = !open"
                        class="custom-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                        <span x-text="selected || 'جميع مستويات الإنجليزية'"></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" @click.away="open = false"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg max-h-60 overflow-auto shadow-lg">
                        <li @click="selected = ''; open = false; applyFilter('', 'english_level')"
                            class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium">
                            جميع مستويات الإنجليزية
                        </li>
                        <template x-for="(label, value) in options" :key="value">
                            <li @click="selected = label; open = false; applyFilter(value, 'english_level')"
                                class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium" x-text="label">
                            </li>
                        </template>
                    </ul>
                </div>

                <!-- Residence Filter -->
                <div x-data="{
                    open: false,
                    selected: '{{ request('residence') ?? '' }}',
                    options: {{ json_encode($residences) }},
                    applyFilter(value, key) {
                        const url = new URL(window.location.href);
                        if (value) {
                            url.searchParams.set(key, value);
                        } else {
                            url.searchParams.delete(key);
                        }
                        window.location.href = url.toString();
                    }
                }" x-cloak class="relative min-w-[180px] text-right" style="direction: rtl;">
                    <button @click="open = !open"
                        class="custom-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                        <span x-text="selected || 'جميع مناطق الإقامة'"></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" @click.away="open = false"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg max-h-60 overflow-auto shadow-lg">
                        <li @click="selected = ''; open = false; applyFilter('', 'residence')"
                            class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium">
                            جميع مناطق الإقامة
                        </li>
                        <template x-for="label in options" :key="label">
                            <li @click="selected = label; open = false; applyFilter(label, 'residence')"
                                class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium" x-text="label">
                            </li>
                        </template>
                    </ul>
                </div>

                <!-- Reset Button -->
                <button id="resetFilters"
                    class="flex items-center justify-center px-4 py-2 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    إعادة تعيين
                </button>
            </div>
        </div>
        <div class="card-body px-0 pb-2">
            <div class="table-responsive p-0">
                @if (count($employees) > 0)
                    <table id="employeesTable" class="table align-items-center mb-0">
                        <thead class="thead" style="font-weight:800;color: #000;">
                            <tr>
                                <th class="no-print w-8 text-center align-middle">
                                    <input type="checkbox" id="selectAllCheckbox" onclick="toggleSelectAllEmps()"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                </th>
                                <th style="font-size:16px; font-weight:600; padding-right:25px;"
                                    class="text-right text-uppercase ">
                                    الصورة</th>
                                <th class="text-center text-uppercase">
                                    الاسم</th>
                                <th class="text-center text-uppercase ">
                                    رقم الهوية</th>
                                <th class="text-center text-uppercase ">
                                    تاريخ الميلاد</th>
                                <th class="text-center text-uppercase ">
                                    المشروع</th>
                                <th class="text-center text-uppercase">الراتب</th>
                                <th class="text-center text-uppercase">التعليم </th>
                                <th class="text-center text-uppercase">العائلة</th>
                                <th class="text-center text-uppercase">العقوبات</th>

                                <th class="text-center text-uppercase ">
                                    الهاتف</th>
                                <th class="text-center text-uppercase ">
                                    مقر الإقامة</th>
                                <th class="text-center text-uppercase ">
                                    مقاسات الملابس</th>
                                <th class="text-center text-uppercase ">
                                    بيانات المركبة</th>

                                <th class="text-center text-uppercase ">
                                    تاريخ الالتحاق</th>
                                <th class="text-center text-uppercase ">
                                    سبب التوقف</th>

                            </tr>
                        </thead>
                        <tbody class="tbody" style="font-weight:500;color: #000;">
                            @foreach ($employees as $employee)
                                {{-- @php
                    $firstEmployee = $employees[0] ?? null; // Array access
                    dump($firstEmployee['id_card'] ?? null);
                    dump(gettype($firstEmployee));
                    @endphp --}}

                                <tr style="font-weight: 500; color: #000;">
                                    <td class="no-print w-8 text-center align-middle">
                                        <input type="checkbox"
                                            class="emp-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                            value="{{ $employee['id'] }}"
                                            data-status="{{ $employee['account_status'] }}"
                                            onchange="updateBulkActionsButton()">
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1 no-print">
                                            <div>
                                                <img src="{{ $employee['personal_image'] }}"
                                                    style="width: 60px; height: 60px; object-fit: cover;"
                                                    class="h-16 w-16 mx-auto rounded-full border object-cover rounded-5"
                                                    alt="user image">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex align-items-center justify-content-center text-center gap-2">
                                            <div class="status-indicator {{ $employee['account_status'] }}"></div>
                                            <a href="{{ route('employees.show', $employee['id']) }}"
                                                class="text-decoration-none text-dark text-sm fw-bold mb-0">
                                                {{ $employee['name'] }}
                                            </a>
                                        </div>
                                        <span class="text-secondary " style="color: #000;">{{ $employee['job'] }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary "
                                            style="color: #000;">{{ $employee['id_card'] }}</span>
                                        <div style="font-size: 13px; font-weight: 600; margin-top: 5px;">
                                            🏥
                                            @if ($employee['health_card'])
                                                <span class="badge bg-success" style="font-size: 12px;">
                                                    لديه بطاقة صحية
                                                </span>
                                            @else
                                                <span class="badge bg-danger" style="font-size: 12px;">
                                                    لا يملك بطاقة صحية
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">

                                        <div>
                                            <span class="text-secondary ">{{ $employee['birthday'] }}</span>
                                        </div>
                                        <div>
                                            @php
                                                $ageThreshold = \App\Models\Setting::get('age_alert_threshold', 30);
                                            @endphp

                                            <span class=""
                                                style="font-size: 0.9rem; font-weight: 600; padding: 0.25em 0.5em; display: inline-block; min-width: 40px; color: {{ $employee['age'] >= $ageThreshold ? 'red' : '#000' }};">
                                                {{ $employee['age'] }} عامًا
                                            </span>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if (!empty($employee['project']))
                                            {{ $employee['project'] }}
                                        @else
                                            <span class="text-danger ">
                                                -
                                            </span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-start">
                                        <div style="font-size: 16px; font-weight: 500; color: #000;">
                                            💰 {{ number_format($employee['salary']) }} ر.س
                                        </div>
                                    </td>

                                    <td class="align-middle text-start">
                                        <div style="font-size: 15px; font-weight: 500; color: #000;">
                                            <i class="fas fa-language me-1"></i>{{ $employee['english_level'] }}
                                        </div>
                                        <div style="font-size: 13px; font-weight: 500; color: #000;">
                                            🎓 {{ $employee['certificate_type'] }}
                                        </div>

                                    </td>

                                    <td class="align-middle text-start">
                                        <div style="font-size: 15px; font-weight: 500; color: #000;">
                                            💍 {{ $employee['marital_status'] }}
                                        </div>
                                        <div style="font-size: 15px; font-weight: 500; color: #000;">
                                            👨‍👩‍👧 {{ $employee['members_number'] }}
                                        </div>
                                    </td>

                                    <td class="align-middle text-start">
                                        <div style="font-size: 15px; font-weight: 500; color: #000;">
                                            ⚠️ {{ $employee['alerts_number'] }}
                                        </div>
                                        <div style="font-size: 15px; font-weight: 500; color: #000;">
                                            💸 {{ $employee['deductions_number'] }}
                                        </div>
                                    </td>

                                    <td class="align-middle text-center">
                                        <div style="display: flex; flex-direction: column; gap: 4px; align-items: center;">
                                            <span class="text-secondary "
                                                style="display: flex; align-items: center; gap: 8px;">
                                                {{ $employee['phone_number'] }}
                                                @if (!empty($employee['whats_app_link']))
                                                    <a href="{{ $employee['whats_app_link'] }}" target="_blank"
                                                        style="color: #25D366;" title="Chat on WhatsApp">
                                                        <i class="fab fa-whatsapp" style="font-size: 18px;"></i>
                                                    </a>
                                                @endif
                                            </span>
                                            <span class="text-secondary "
                                                style="display: flex; align-items: center; gap: 6px;">
                                                @if (strtolower($employee['phone_type']) === 'android')
                                                    <img src="{{ asset('build/assets/img/android.png') }}" alt="Android"
                                                        style="width: 30px; height: 30px;">
                                                @elseif(strtolower($employee['phone_type']) === 'iphone')
                                                    <img src="{{ asset('build/assets/img/iphone.png') }}" alt="iPhone"
                                                        style="width: 20px; height: 20px;">
                                                @endif
                                            </span>
                                        </div>
                                    </td>

                                    <td class="align-middle text-center">
                                        <div>
                                            <span class="text-secondary ">{{ $employee['residence'] }}</span>
                                        </div>
                                        <div>
                                            <span class="text-secondary ">
                                                {{ $employee['residence_neighborhood'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div>
                                            <span class="badge bg-success mb-1"
                                                style="font-size: 0.75rem; font-weight: 600; padding: 0.25em 0.5em; display: inline-block; min-width: 40px;">
                                                تي شيرت: {{ $employee['Tshirt_size'] }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary"
                                                style="font-size: 0.75rem; font-weight: 600; padding: 0.25em 0.5em; display: inline-block; min-width: 40px;">
                                                بنطال: {{ $employee['pants_size'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div>
                                            <span class="text-secondary ">
                                                {{ $employee['vehicle_model'] }}
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            @if ($employee['vehicle_type'] === 'دراجة نارية')
                                                <i class="fas fa-motorcycle text-primary" title="دراجة نارية"></i>
                                            @else
                                                <i class="fas fa-car text-success" title="سيارة"></i>
                                            @endif
                                            <span class="text-secondary ">
                                                {{ $employee['vehicle_ID'] }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="align-middle text-center">
                                        <div>
                                            <span class="text-secondary ">
                                                {{ $employee['joining_date'] }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-secondary "
                                                style="font-size: 0.9rem; font-weight: 600; padding: 0.25em 0.5em; display: inline-block; min-width: 40px;">
                                                {{ $employee['work_duration'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if ($employee['stop_reason'])
                                            <span class="text-danger ">
                                                {{ $employee['stop_reason'] }}
                                            </span>
                                        @else
                                            <span class="text-success ">
                                                -
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                    </table>
                    </tbody>
                @else
                    <div class="text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 h-16 w-16 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 118 0v2m-4 4v-4" />
                        </svg>
                        <p class="text-gray-500 text-lg font-semibold">عذرًا، لا توجد بيانات مطابقة للفلاتر
                            المحددة.</p>
                        <p class="text-gray-400 mt-2">حاول تعديل الفلاتر أو مسحها لعرض جميع البيانات.</p>
                    </div>
                @endif


            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            if (document.querySelector('[data-toggle="tooltip"]')) {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Datatable initialization if needed
            if (document.querySelector('.table')) {
                $('.table').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Arabic.json"
                    },
                    "responsive": true,
                    "autoWidth": false,
                    "order": [
                        [2, "desc"]
                    ]
                });
            }
        });
        document.getElementById('createEmployeeForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const submitButton = form.querySelector('button[type="submit"]');
            const formData = new FormData(form);

            // Save original button text
            const originalButtonText = submitButton.innerHTML;

            // Update button state
            submitButton.disabled = true;
            submitButton.innerHTML = `
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        جاري الحفظ...
    `;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessages = [];
                        for (const field in data.errors) {
                            errorMessages.push(...data.errors[field]);
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ في التحقق',
                            html: errorMessages.join('<br>'),
                            confirmButtonText: 'حسناً',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                        return;
                    }
                    throw new Error(data.message || 'Request failed');
                }

                // Success case - show success message then redirect
                await Swal.fire({
                    icon: 'success',
                    title: 'نجاح!',
                    text: data.message || 'تمت العملية بنجاح',
                    confirmButtonText: 'حسناً',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    },
                    timer: 3000,
                    timerProgressBar: true,
                    willClose: () => {
                        window.location.href = "{{ route('employees.index') }}";
                    }
                });

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: 'حدث خطأ: ' + (error.message || 'غير معروف'),
                    confirmButtonText: 'حسناً',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
            } finally {
                // Restore button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        });
    </script>
    <script>
        document.getElementById('clearSearch').addEventListener('click', function() {
            document.getElementById('globalSearch').value = '';
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            window.location.href = url.toString();
        });

        document.getElementById('globalSearch').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const url = new URL(window.location.href);
                if (this.value.trim() !== '') {
                    url.searchParams.set('search', this.value.trim());
                } else {
                    url.searchParams.delete('search');
                }
                window.location.href = url.toString();
            }
        });

        document.getElementById('resetFilters').addEventListener('click', function() {
            const url = new URL(window.location.href);
            url.search = '';
            window.location.href = url.toString();
        });
    </script>
    <script>
        flatpickr("#joining_date", {
            locale: "ar",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            allowInput: true,
            defaultHour: 12,
        });
        flatpickr("#birthday", {
            locale: "ar",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            allowInput: true,
            defaultHour: 12,
        });
    </script>
    <script>
        function openColumnsModal() {
            document.getElementById('columnsModal').style.display = 'flex';
            updateColumnsBadge();
        }

        function closeColumnsModal() {
            document.getElementById('columnsModal').style.display = 'none';
        }

        function filterColumns() {
            const searchTerm = document.getElementById('columnsSearch').value.toLowerCase();
            const columnItems = document.querySelectorAll('.column-item');

            columnItems.forEach(item => {
                const columnName = item.querySelector('.column-name').textContent.toLowerCase();
                if (columnName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.column-checkbox input');
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
                const event = new Event('change');
                checkbox.dispatchEvent(event);
            });

            updateColumnsBadge();
        }

        function resetSelection() {
            const checkboxes = document.querySelectorAll('.column-checkbox input');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
                const event = new Event('change');
                checkbox.dispatchEvent(event);
            });

            updateColumnsBadge();
        }

        function applyColumnSelection() {
            const selectedColumns = [];
            const checkboxes = document.querySelectorAll('.column-checkbox input:checked');

            checkboxes.forEach(checkbox => {
                selectedColumns.push(checkbox.value);
            });

            updateTableColumns(selectedColumns);
            updateColumnsBadge();
            closeColumnsModal();
        }

        function updateTableColumns(selectedColumns) {
            const columnMapping = {
                'image': 'الصورة',
                'name': 'الاسم',
                'id_card': 'رقم الهوية',
                'birthday': 'تاريخ الميلاد',
                'salary': 'الراتب',
                'certificate_type': 'التعليم',
                'project': 'المشروع',
                'deductions_number': 'العقوبات',
                'Tshirt_size': 'مقاسات الملابس',
                'members_number': 'العائلة',
                'stop_reason': 'سبب التوقف',
                'marital_status': 'الحالة الاجتماعية',
                'phone_number': 'الهاتف',
                'residence': 'مقر الإقامة',
                'vehicle_info': 'بيانات المركبة',
                'joining_date': 'تاريخ الالتحاق'
            };

            const headers = document.querySelectorAll('.table thead th');
            headers.forEach((header, index) => {
                const headerText = header.textContent.trim();
                const isActionColumn = header.classList.contains('no-print');

                if (isActionColumn) {
                    return;
                }

                const columnKey = Object.keys(columnMapping).find(key => columnMapping[key] === headerText);
                const shouldShow = selectedColumns.includes(columnKey);

                header.style.display = shouldShow ? '' : 'none';
                document.querySelectorAll('.table tbody tr').forEach(row => {
                    if (row.cells[index]) row.cells[index].style.display = shouldShow ? '' : 'none';
                });
            });
        }

        function updateColumnsBadge() {
            const checkedCount = document.querySelectorAll('.column-checkbox input:checked').length;
            document.getElementById('columnsBadge').textContent = checkedCount;
        }

        // Bulk actions functions
        function toggleSelectAllEmps() {
            const selectAll = document.getElementById('selectAllCheckbox').checked;
            document.querySelectorAll('.emp-checkbox').forEach(checkbox => {
                checkbox.checked = selectAll;
            });
            updateBulkActionsButton();
        }

        function updateBulkActionsButton() {
            const checkedBoxes = document.querySelectorAll('.emp-checkbox:checked');
            const bulkActionsBtn = document.getElementById('bulkActionsBtn');
            const dropdown = document.querySelector('.dropdown');

            if (checkedBoxes.length > 0) {
                bulkActionsBtn.disabled = false;
                // Close dropdown if it was open when selection was cleared
                if (dropdown) dropdown.classList.remove('active');
            } else {
                bulkActionsBtn.disabled = true;
                // Ensure dropdown is closed when disabled
                if (dropdown) dropdown.classList.remove('active');
            }
        }

        function setupBulkActions() {
            const dropdown = document.querySelector('.dropdown');
            const bulkActionsBtn = document.getElementById('bulkActionsBtn');

            bulkActionsBtn.addEventListener('click', function(e) {
                if (this.disabled) return;
                e.stopPropagation();
                dropdown.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('active');
                }
            });

            document.querySelectorAll('#bulkActionsDropdown .status-dropdown-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    if (bulkActionsBtn.disabled) return;
                    e.preventDefault();
                    e.stopPropagation();

                    const action = this.getAttribute('data-action');
                    const selectedIds = Array.from(document.querySelectorAll('.emp-checkbox:checked'))
                        .map(checkbox => checkbox.value);

                    if (selectedIds.length === 0) {
                        alert('يرجى تحديد موظف واحد على الأقل');
                        return;
                    }

                    if (confirm(
                            `هل أنت متأكد من أنك تريد ${action === 'activate' ? 'تفعيل' : 'تعطيل'} ${selectedIds.length} موظف؟`
                        )) {
                        performBulkAction(selectedIds, action);
                    }
                });
            });
        }

        function performBulkAction(ids, action) {
            const url = `/employees/bulk-${action}`;
            const token = document.querySelector('meta[name="csrf-token"]').content;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ids: ids
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`تم ${action === 'activate' ? 'تفعيل' : 'تعطيل'} الحسابات المحددة بنجاح`);
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء تنفيذ العملية: ' + (data.message || ''));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء تنفيذ العملية');
                });
        }

        function getSelectedColumns() {
            const checkboxes = document.querySelectorAll('.column-checkbox input:checked');
            return Array.from(checkboxes).map(checkbox => checkbox.value);
        }

        // Helper function to prepare export data
        function prepareExportData(selectedColumns) {
            const columnMapping = {
                'image': {
                    header: 'الصورة',
                    accessor: e => ''
                },
                'name': {
                    header: 'اسم الموظف',
                    accessor: e => e.name
                },
                'id_card': {
                    header: 'رقم الهوية',
                    accessor: e => e.id_card
                },
                'birthday': {
                    header: 'تاريخ الميلاد',
                    accessor: e => e.birthday
                },
                'salary': {
                    header: 'الراتب',
                    accessor: e => e.salary
                },
                'certificate_type': {
                    header: 'التعليم',
                    accessor: e => e.certificate_type
                },
                'project': {
                    header: 'المشروع',
                    accessor: e => e.project || '-'
                },
                'deductions_number': {
                    header: 'العقوبات',
                    accessor: e => e.deductions_number
                },
                'Tshirt_size': {
                    header: 'مقاس التي شيرت',
                    accessor: e => e.Tshirt_size
                },
                'members_number': {
                    header: 'أفراد الأسرة',
                    accessor: e => e.members_number
                },
                'stop_reason': {
                    header: 'سبب التوقف',
                    accessor: e => e.stop_reason || '-'
                },
                'marital_status': {
                    header: 'الحالة الاجتماعية',
                    accessor: e => e.marital_status
                },
                'phone_number': {
                    header: 'الهاتف',
                    accessor: e => e.phone_number
                },
                'residence': {
                    header: 'مقر الإقامة',
                    accessor: e => e.residence
                },
                'vehicle_info': {
                    header: 'بيانات المركبة',
                    accessor: e => `${e.vehicle_type} - ${e.vehicle_ID}`
                },
                'joining_date': {
                    header: 'تاريخ الالتحاق',
                    accessor: e => e.joining_date
                }
            };

            // Filter columns based on selection
            const activeColumns = selectedColumns.map(col => columnMapping[col]);

            // Prepare headers
            const headers = activeColumns.map(col => col.header);

            // Prepare data
            const employees = @json($employees);
            const data = employees.map(employee => {
                return activeColumns.map(col => {
                    try {
                        return col.accessor(employee);
                    } catch (e) {
                        return '';
                    }
                });
            });

            return {
                headers,
                data
            };
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            setupBulkActions();
            // Initialize with all columns selected
            updateColumnsBadge();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="{{ asset('js/alamariFontBase64.js') }}"></script>

    <script type="module">
        document.getElementById('pdfExportBtn').addEventListener('click', async function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
            this.disabled = true;

            try {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF({
                    orientation: 'landscape',
                    unit: 'mm',
                    format: 'a4'
                });
                const pageWidth = doc.internal.pageSize.getWidth();

                // تحميل الخط العربي
                doc.addFileToVFS("Almarai-Regular.ttf", window.almaraiFontBase64);
                doc.addFont("Almarai-Regular.ttf", "Almarai", "normal");
                doc.setFont("Almarai"); // تعيين الخط الافتراضي

                doc.setFontSize(14);
                doc.text('تقرير الموظفين', pageWidth - 148, 15, {
                    align: 'right'
                });
                doc.setFontSize(10);
                doc.text('تاريخ التصدير: ' + new Date().toLocaleDateString('ar-EG'), 285, 10, {
                    align: 'right'
                });

                // جمع رؤوس الجدول
                const headers = [];
                document.querySelectorAll('#employeesTable thead th').forEach(th => {
                    if (th.style.display !== 'none' && !th.classList.contains('no-print')) {
                        headers.push(th.innerText.trim());
                    }
                });

                // جمع الصفوف
                const data = [];
                document.querySelectorAll('#employeesTable tbody tr').forEach(row => {
                    const rowData = [];
                    row.querySelectorAll('td').forEach(td => {
                        if (td.style.display !== 'none') {
                            rowData.push(td.innerText.trim());
                        }
                    });
                    if (rowData.length) {
                        data.push(rowData);
                    }
                });

                // بناء الجدول مع استخدام الخط العربي
                doc.autoTable({
                    head: [headers],
                    body: data,
                    startY: 25,
                    styles: {
                        font: 'Almarai',
                        fontSize: 8,
                        textColor: [0, 0, 0],
                        halign: 'right',
                        fontStyle: 'normal'
                    },
                    headStyles: {
                        fillColor: [70, 130, 180],
                        textColor: [255, 255, 255],
                        fontStyle: 'bold'
                    },
                    alternateRowStyles: {
                        fillColor: [245, 245, 245]
                    },
                    columnStyles: {
                        0: {
                            cellWidth: 'wrap'
                        }
                        // or use { 0: { cellWidth: 30 }, 1: { cellWidth: 50 }, ... }
                    },
                    margin: {
                        right: 10,
                        left: 10
                    }
                });
                doc.save('تقرير_الموظفين_' + new Date().toISOString().slice(0, 10) + '.pdf');
            } catch (error) {
                alert('حدث خطأ أثناء التصدير: ' + error.message);
                console.error(error);
            } finally {
                this.innerHTML = originalText;
                this.disabled = false;
            }
        });
    </script>




    <script>
        // Export to Excel function
        document.getElementById('excelExportBtn').addEventListener('click', function() {
            // Get selected columns
            const selectedColumns = getSelectedColumns();

            // Prepare data for export
            const {
                headers,
                data
            } = prepareExportData(selectedColumns);

            // Create workbook
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet([headers].concat(data));

            // Set column widths
            const wscols = headers.map(() => ({
                width: 20
            }));
            ws['!cols'] = wscols;

            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(wb, ws, "الموظفين");

            // Generate Excel file
            XLSX.writeFile(wb, 'الموظفين_' + new Date().toISOString().slice(0, 10) + '.xlsx');
        });
    </script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('salaryAdvance', {
                openModal() {
                    const selectedIds = Array.from(document.querySelectorAll('.emp-checkbox:checked'))
                        .map(checkbox => checkbox.value);

                    if (selectedIds.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'تنبيه',
                            text: 'يرجى تحديد موظف واحد على الأقل.'
                        });
                        return;
                    }

                    Alpine.store('salaryAdvance').showModal = true;
                },
                showModal: false
            });
        });

        function salaryAdvanceModal() {
            return {
                showModal: false,
                formData: {
                    advance_amount: '',
                    reason: '',
                    repayment_months: ''
                },
                openModal() {
                    const selectedIds = this.getSelectedEmployeeIds();
                    if (selectedIds.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'تنبيه',
                            text: 'يرجى تحديد موظف واحد على الأقل.'
                        });
                        return;
                    }
                    this.showModal = true;
                },
                getSelectedEmployeeIds() {
                    return Array.from(document.querySelectorAll('input[name="selected_employees[]"]:checked'))
                        .map(checkbox => checkbox.value);
                },
                async submitForm() {
                    const selectedIds = this.getSelectedEmployeeIds();

                    try {
                        const response = await fetch("#", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                employee_ids: selectedIds,
                                ...this.formData
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم بنجاح',
                                text: result.message
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'فشل',
                                text: result.message || 'حدث خطأ غير متوقع'
                            });
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: 'حدث خطأ أثناء إرسال الطلب'
                        });
                    } finally {
                        this.showModal = false;
                    }
                }
            };
        }
    </script>
@endpush
public function replace(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:employees,id',
        'name' => 'required|string|max:255',
        'id_card' => 'required|string|unique:employees,id_card',
        'nationality' => 'required|string',
        'phone_number' => 'required|string',
        // Add other validation rules as needed
    ]);

    DB::beginTransaction();
    try {
        // Get employees to be replaced
        $employeesToReplace = Employee::whereIn('id', $request->ids)->get();

        // Create new employee
        $newEmployee = Employee::create([
            'name' => $request->name,
            'id_card' => $request->id_card,
            'nationality' => $request->nationality,
            'phone_number' => $request->phone_number,
            // Add other fields as needed
            'account_status' => 'active',
            'replaced_employees' => $request->ids
        ]);

        // Deactivate old employees
        Employee::whereIn('id', $request->ids)->update([
            'account_status' => 'inactive',
            'stop_reason' => 'تم استبداله بموظف جديد',
            'replaced_by' => $newEmployee->id
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'تم استبدال الموظفين بنجاح',
            'redirect' => route('employees.index')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء استبدال الموظفين: ' . $e->getMessage()
        ], 500);
    }
}
   document.getElementById('pdfExportBtn').addEventListener('click', function() {
    // Clone the table to avoid modifying the original
    const originalTable = document.getElementById('employeesTable');
    const tableClone = originalTable.cloneNode(true);

    // Remove checkboxes and action columns from the clone
    const noPrintElements = tableClone.querySelectorAll('.no-print');
    noPrintElements.forEach(el => el.remove());

    // Create a container for the PDF content
    const pdfContainer = document.createElement('div');
    pdfContainer.style.padding = '20px';
    pdfContainer.style.direction = 'rtl';
    pdfContainer.style.fontFamily = 'Arial, sans-serif';

    // Add header
    const header = document.createElement('div');
    header.style.marginBottom = '20px';
    header.style.borderBottom = '2px solid #6e48aa';
    header.style.paddingBottom = '10px';

    const title = document.createElement('h1');
    title.textContent = 'تقرير الموظفين';
    title.style.textAlign = 'center';
    title.style.color = '#6e48aa';
    title.style.marginBottom = '5px';
    title.style.fontSize = '24px';

    const company = document.createElement('p');
    company.textContent = 'شركة افاق الخليج';
    company.style.textAlign = 'center';
    company.style.color = '#555';
    company.style.marginBottom = '5px';

    const date = document.createElement('p');
    date.textContent = 'تاريخ التقرير: ' + new Date().toLocaleDateString('ar-EG');
    date.style.textAlign = 'center';
    date.style.color = '#777';

    header.appendChild(title);
    header.appendChild(company);
    header.appendChild(date);

    // Add footer template (will be added by html2pdf)
    const footer = `
        <div style="text-align: center; padding: 10px; font-size: 10px; color: #666; border-top: 1px solid #eee; width: 100%;">
            <p>صفحة <span class="pageNumber"></span> من <span class="totalPages"></span></p>
            <p>© ${new Date().getFullYear()} جميع الحقوق محفوظة</p>
        </div>
    `;

    // Prepare the table for PDF
    tableClone.style.width = '100%';
    tableClone.style.borderCollapse = 'collapse';
    tableClone.style.marginTop = '20px';

    // Style table headers
    const thElements = tableClone.querySelectorAll('th');
    thElements.forEach(th => {
        th.style.backgroundColor = '#6e48aa';
        th.style.color = 'white';
        th.style.padding = '10px';
        th.style.border = '1px solid #ddd';
    });

    // Style table cells
    const tdElements = tableClone.querySelectorAll('td');
    tdElements.forEach(td => {
        td.style.padding = '8px';
        td.style.border = '1px solid #ddd';
    });

    // Ensure images are properly sized
    const imgElements = tableClone.querySelectorAll('img');
    imgElements.forEach(img => {
        img.style.maxWidth = '60px';
        img.style.maxHeight = '60px';
        img.style.display = 'block';
        img.style.margin = '0 auto';
    });

    // Add elements to container
    pdfContainer.appendChild(header);
    pdfContainer.appendChild(tableClone);

    // PDF options
    const options = {
        margin: [20, 20, 40, 20], // top, right, bottom, left
        filename: 'تقرير_الموظفين_' + new Date().toISOString().slice(0, 10) + '.pdf',
        image: {
            type: 'jpeg',
            quality: 0.98
        },
        html2canvas: {
            scale: 2,
            logging: true,
            useCORS: true,
            letterRendering: true,
            allowTaint: true, // Important for images
            scrollX: 0,
            scrollY: 0
        },
        jsPDF: {
            unit: 'mm',
            format: 'a2',
            orientation: 'landscape',
            compress: true
        },
        pagebreak: {
            mode: ['avoid-all', 'css', 'legacy'],
            after: '.avoid-this-row'
        },
        onclone: function(clonedDoc) {
            // Add footer to each page
            const pages = clonedDoc.querySelectorAll('.page');
            pages.forEach((page, index) => {
                const footerDiv = clonedDoc.createElement('div');
                footerDiv.innerHTML = footer;
                footerDiv.querySelector('.pageNumber').textContent = index + 1;
                footerDiv.querySelector('.totalPages').textContent = pages.length;
                page.appendChild(footerDiv);
            });
        }
    };

    // Show loading indicator
    const pdfBtn = document.getElementById('pdfExportBtn');
    const originalText = pdfBtn.innerHTML;
    pdfBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
    pdfBtn.disabled = true;

    // Generate PDF
    html2pdf()
        .set(options)
        .from(pdfContainer)
        .toPdf()
        .get('pdf')
        .then(function(pdf) {
            // Add Arabic font support
            pdf.setFont('Arial', 'normal');
            pdf.save();
        })
        .catch(err => {
            console.error('PDF export error:', err);
            alert('حدث خطأ أثناء التصدير: ' + err.message);
        })
        .finally(() => {
            // Restore button state
            pdfBtn.innerHTML = originalText;
            pdfBtn.disabled = false;
        });
});
    </script>
   document.getElementById('pdfExportBtn').addEventListener('click', function() {
            // Clone the table to avoid modifying the original
            // Get the original table
            const originalTable = document.getElementById('employeesTable');

            // Clone the table to avoid modifying the original
            const tableClone = originalTable.cloneNode(true);

            // Create a container for the PDF content with RTL support
            const pdfContainer = document.createElement('div');
            pdfContainer.style.padding = '20px';
            pdfContainer.style.direction = 'rtl';
            pdfContainer.style.fontFamily = 'Arial, sans-serif';
            pdfContainer.style.textAlign = 'right';
            pdfContainer.style.whiteSpace = 'normal';
            pdfContainer.style.lineHeight = '1.8';

            // Add header with company logo and info
            const header = document.createElement('div');
            header.style.marginBottom = '30px';
            header.style.borderBottom = '2px solid #6e48aa';
            header.style.paddingBottom = '15px';
            header.style.display = 'flex';
            header.style.justifyContent = 'space-between';
            header.style.alignItems = 'center';

            // Company info (right side in RTL)
            const companyInfo = document.createElement('div');
            companyInfo.style.textAlign = 'right';

            const companyName = document.createElement('h1');
            companyName.textContent = 'شركة افاق الخليج';
            companyName.style.color = '#6e48aa';
            companyName.style.margin = '0 0 5px 0';
            companyName.style.fontSize = '24px';

            const reportTitle = document.createElement('h2');
            reportTitle.textContent = 'تقرير الموظفين';
            reportTitle.style.color = '#333';
            reportTitle.style.margin = '0 0 5px 0';
            reportTitle.style.fontSize = '20px';

            const reportDate = document.createElement('p');
            reportDate.textContent = 'تاريخ التقرير: ' + new Date().toLocaleDateString('ar-EG');
            reportDate.style.color = '#666';
            reportDate.style.margin = '0';
            reportDate.style.fontSize = '16px';

            companyInfo.appendChild(companyName);
            companyInfo.appendChild(reportTitle);
            companyInfo.appendChild(reportDate);

            // Add logo if available (left side in RTL)
            const logoContainer = document.createElement('div');
            logoContainer.style.width = '100px';

            // You can add your company logo here
            // const logo = document.createElement('img');
            // logo.src = '/path/to/logo.png';
            // logo.style.maxWidth = '100%';
            // logoContainer.appendChild(logo);

            header.appendChild(companyInfo);
            header.appendChild(logoContainer);

            // Prepare the table for PDF export
            tableClone.style.width = '100%';
            tableClone.style.borderCollapse = 'collapse';
            tableClone.style.marginTop = '20px';
            tableClone.style.direction = 'rtl';

            // Style table headers
            const thElements = tableClone.querySelectorAll('th');
            thElements.forEach(th => {
                th.style.backgroundColor = '#6e48aa';
                th.style.color = 'white';
                th.style.padding = '12px';
                th.style.border = '1px solid #ddd';
                th.style.textAlign = 'center';
                th.style.fontWeight = 'bold';
            });
            tableClone.querySelectorAll('th:first-child, td:first-child').forEach(el => {
                if (el.querySelector('input[type="checkbox"]')) {
                    el.remove();
                }
            });

            // Style table cells
            const tdElements = tableClone.querySelectorAll('td');
            tdElements.forEach(td => {
                td.style.padding = '8px';
                td.style.border = '1px solid #ddd';
                td.style.textAlign = 'right';
                td.style.whiteSpace = 'normal';
                td.style.lineHeight = '1.6';

                // Center-align specific columns (like IDs, numbers)
                if (td.classList.contains('text-center')) {
                    td.style.textAlign = 'center';
                }
            });

            // Handle images - ensure they're properly loaded
            const imgElements = tableClone.querySelectorAll('img');
            imgElements.forEach(img => {
                img.style.maxWidth = '50px';
                img.style.maxHeight = '50px';
                img.style.display = 'block';
                img.style.margin = '0 auto';
                img.style.borderRadius = '4px';

                // Convert relative URLs to absolute if needed
                if (img.src.startsWith('/')) {
                    img.src = window.location.origin + img.src;
                }

                // Add error handling
                img.onerror = function() {
                    this.style.display = 'none';
                    const errorSpan = document.createElement('span');
                    errorSpan.textContent = '(صورة غير متوفرة)';
                    errorSpan.style.fontSize = '10px';
                    errorSpan.style.color = 'red';
                    this.parentNode.appendChild(errorSpan);
                };
            });

            // Add elements to container
            pdfContainer.appendChild(header);
            pdfContainer.appendChild(tableClone);

            // Create footer
            const footer = document.createElement('div');
            footer.style.marginTop = '20px';
            footer.style.paddingTop = '10px';
            footer.style.borderTop = '1px solid #eee';
            footer.style.textAlign = 'center';
            footer.style.color = '#666';
            footer.style.fontSize = '12px';


            const pageInfo = document.createElement('p');
            pageInfo.textContent = 'صفحة 1 من 1'; // This will be updated by html2pdf

            const copyright = document.createElement('p');
            copyright.textContent = `© ${new Date().getFullYear()} جميع الحقوق محفوظة لشركة افاق الخليج`;

            footer.appendChild(pageInfo);
            footer.appendChild(copyright);

            pdfContainer.appendChild(footer);

            // PDF options with RTL support
            const options = {
                margin: [15, 15, 30, 15], // top, right, bottom, left
                filename: 'تقرير_الموظفين_' + new Date().toISOString().slice(0, 10) + '.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    logging: true,
                    useCORS: true,
                    allowTaint: true, // Allow external images
                    scrollX: 0,
                    scrollY: 0,
                    letterRendering: true,
                    onclone: function(clonedDoc) {
                        // Ensure RTL is maintained in the cloned document
                        clonedDoc.documentElement.dir = 'rtl';
                        clonedDoc.body.style.direction = 'rtl';
                        clonedDoc.body.style.textAlign = 'right';

                        // Update page numbers in footer
                        const pages = clonedDoc.querySelectorAll('.page');
                        pages.forEach((page, index) => {
                            const footer = page.querySelector('footer');
                            if (footer) {
                                footer.querySelector('p').textContent =
                                    `صفحة ${index + 1} من ${pages.length}`;
                            }
                        });
                    }
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a2',
                    orientation: 'landscape',
                    compress: true
                },
                pagebreak: {
                    mode: ['avoid-all', 'css', 'legacy'],
                    after: '.avoid-this-row'
                }
            };

            // Show loading indicator
            const pdfBtn = document.getElementById('pdfExportBtn');
            const originalText = pdfBtn.innerHTML;
            pdfBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
            pdfBtn.disabled = true;

            // Generate PDF
            html2pdf()
                .set(options)
                .from(pdfContainer)
                .toPdf()
                .get('pdf')
                .then(function(pdf) {
      return html2pdf().from(pdfContainer).set(options).save();
                    pdf.setFont('Arial', 'normal');
                    pdf.save();

                })
                .catch(err => {
                    console.error('PDF export error:', err);
                    alert('حدث خطأ أثناء التصدير: ' + err.message);
                })
                .finally(() => {
                    // Restore button state
                    pdfBtn.innerHTML = originalText;
                    pdfBtn.disabled = false;
                });

        });
