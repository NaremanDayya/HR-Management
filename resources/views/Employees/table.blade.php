@extends('layouts.master')
@section('title', 'جدول الموظفين')
<style>
    #employeesTable {
        width: 100%;
    }

    #employeesTable th,
    #employeesTable td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    #employeesTable th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 10;
    }

    .has-alerts {
        box-shadow: inset 2px 0 0 #dc3545;
        transition: box-shadow 0.3s ease;
        position: relative;
    }

    .has-alerts:hover {
        box-shadow: inset 4px 0 0 #dc3545;
    }

    .has-alerts::after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 2px;
        background: #dc3545;
        opacity: 0.7;
    }

    /* Keep your existing table responsive behavior */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Adjust column widths for better display */
    #employeesTable th:nth-child(1),
    #employeesTable td:nth-child(1) {
        width: 40px;
        /* Checkbox column */
    }

    #employeesTable th:nth-child(3),
    #employeesTable td:nth-child(3) {
        width: 80px;
        /* Image column */
    }

    /* Add horizontal scrolling indicator */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 4px;
    }

    /* Keep your existing styles for other elements */
    .export-btn-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }

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
        margin-left: 20px;

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

    .dropdown {
        position: relative;
        display: inline-block;
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

    {{-- <pre>{{ print_r($employees, true) }}</pre> --}}

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
                            @if (($role && $role->hasPermissionTo('view_credentials')) || Auth::user()->role === 'admin')
                                <a href="{{ route('employees.credentials') }}" class="btn btn-icon-only"
                                    title="بيانات دخول الموظفين">
                                    <i class="fas fa-user-lock feature-icon"></i>
                                </a>
                            @endif

                            @if (($role && $role->hasPermissionTo('add_employee')) || Auth::user()->role === 'admin')
                                <button class="btn btn-purple" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">
                                    <i class="fas fa-plus"></i> إضافة موظف
                                </button>
                            @endif
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
                            <!-- Age Threshold Modal Button -->
                            <div x-data="{ open: false }">
                                <button @click="open = true"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow">
                                    تعديل العمر الأقصى
                                </button>
                                <div x-show="open" x-cloak
                                    class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                                    <div @click.away="open = false"
                                        class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">

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

                                            <x-bulk-action-modal action="activate" modal-title="تأكيد تفعيل الحساب"
                                                confirm-text="تفعيل الحساب" button-class="bg-green-600"
                                                modal-id="activate" :has-form="true">
                                            <div class="mb-4 text-right">
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1 text-right">تاريخ
                                                الالتحاق بالعمل مجددا
                                            </label>
                                            <input type="text" id="start_date" name="start_date"
                                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg text-right rtl"
                                                   placeholder="اختر التاريخ" required>
                                            </div>
                                            </x-bulk-action-modal>
                                        </li>

                                        <li>
                                            <x-bulk-action-modal action="deactivate" modal-title="تأكيد إيقاف الموظف"
                                                confirm-text="إيقاف الموظف" button-class="bg-red-600" modal-id="deactivate"
                                                :has-form="true">

                                                <div class="mb-4 text-right">
                                                    <label class="block mb-2 font-semibold text-gray-700">
                                                        سبب الإيقاف
                                                    </label>
                                                    <select name="stop_reason" required
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 rtl text-right mb-3"
                                                        id="stop-reason-select">
                                                        <option value="" selected disabled>اختر سبب الإيقاف</option>
                                                        <option value="إنهاء خدمة">إنهاء خدمة</option>
                                                        <option value="استقالة">استقالة</option>
                                                        <option value="سوء أداء">سوء أداء</option>
                                                        <option value="عدم التزام">عدم التزام</option>
                                                        <option value="انتهاء عقد">انتهاء عقد</option>
                                                        <option value="آخر">آخر (يرجى التحديد أدناه)</option>
                                                    </select>

                                                    <div id="other-stop-reason-container" class="hidden mt-3">
                                                        <label class="block mb-2 font-semibold text-gray-700">
                                                            سبب آخر
                                                        </label>
                                                        <input type="text" name="other_stop_reason"
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 rtl text-right"
                                                            placeholder="حدد السبب...">
                                                    </div>
                                                </div>


                                                <div class="mb-4 text-right">
                                                    <label class="block mb-2 font-semibold text-gray-700">
                                                        تفاصيل إضافية
                                                    </label>
                                                    <textarea name="stop_description" rows="4"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 rtl text-right"
                                                        placeholder="أضف أي تفاصيل إضافية هنا..."></textarea>
                                                </div>
                                                <div class="mb-4 text-right">

                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1 text-right">تاريخ
                                                        الإيقاف
                                                    </label>
                                                    <input type="text" id="stop_date" name="stop_date"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-right rtl"
                                                        placeholder="اختر التاريخ" required>
                                                </div>

                                            </x-bulk-action-modal>
                                        </li>
                                        @if (($role && $role->hasPermissionTo('change_employees_password')) || Auth::user()->role === 'admin')
                                            <li>
                                                <x-bulk-action-modal action="change-password"
                                                    modal-title="تأكيد تغيير كلمة المرور"
                                                    confirm-text="تغيير كلمة المرور " button-class="bg-red-600"
                                                    modal-id="change-password">

                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-1 text-right">كلمة
                                                            المرور الجديدة</label>
                                                        <input type="password" id="employeePassword"
                                                            name="employeePassword" required
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-right focus:ring-2 focus:ring-blue-500">
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-1 text-right">تأكيد
                                                            كلمة المرور</label>
                                                        <input type="password" id="employeePassword_confirmation"
                                                            name="employeePassword_confirmation" required
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-right focus:ring-2 focus:ring-blue-500">
                                                    </div>

                                                </x-bulk-action-modal>
                                            </li>
                                        @endif
                                        <li>
                                            <x-bulk-action-modal action="united_clothes" modal-title="طلب زي موحد"
                                                confirm-text="ملابس موحدة" button-class="bg-blue-600"
                                                modal-id="united-clothes" :has-form="true">
                                                <div class="space-y-4">
                                                    <!-- Header -->
                                                    <div class="text-right">
                                                        <h3 class="text-lg font-medium text-gray-800">اختيار أنواع
                                                            اللباس
                                                        </h3>

                                                    </div>

                                                    <!-- Checkbox Group -->
                                                    <div class="space-y-3">
                                                        <!-- T-shirt Option -->
                                                        <label
                                                            class="relative flex items-start py-2 px-3 bg-white rounded-lg border border-gray-200 hover:border-blue-400 transition-colors duration-200 cursor-pointer">
                                                            <div class="flex items-center h-5">
                                                                <input type="checkbox" name="clothing_types[]"
                                                                    value="tshirt"
                                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                            </div>
                                                            <div class="mr-3 text-right">
                                                                <span
                                                                    class="block text-sm font-medium text-gray-800">تيشيرت</span>

                                                            </div>
                                                        </label>

                                                        <!-- Pants Option -->
                                                        <label
                                                            class="relative flex items-start py-2 px-3 bg-white rounded-lg border border-gray-200 hover:border-blue-400 transition-colors duration-200 cursor-pointer">
                                                            <div class="flex items-center h-5">
                                                                <input type="checkbox" name="clothing_types[]"
                                                                    value="pants"
                                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                            </div>
                                                            <div class="mr-3 text-right">
                                                                <span
                                                                    class="block text-sm font-medium text-gray-800">بنطال</span>

                                                            </div>
                                                        </label>

                                                        <!-- Cap Option -->
                                                        <label
                                                            class="relative flex items-start py-2 px-3 bg-white rounded-lg border border-gray-200 hover:border-blue-400 transition-colors duration-200 cursor-pointer">
                                                            <div class="flex items-center h-5">
                                                                <input type="checkbox" name="clothing_types[]"
                                                                    value="cap"
                                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                            </div>
                                                            <div class="mr-3 text-right">
                                                                <span
                                                                    class="block text-sm font-medium text-gray-800">قبعة</span>

                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </x-bulk-action-modal>
                                        </li>
                                        <li>
                                            <x-bulk-action-modal action="tool_bag" modal-title="تأكيد طلب حقيبة أدوات"
                                                confirm-text="حقيبة أدوات" button-class="bg-red-600"
                                                modal-id="tool_bag" />
                                        </li>
                                        <li>
                                            <x-bulk-action-modal action="salary_advance" modal-title="طلب سلفة"
                                                confirm-text="سلفة راتب" button-class="bg-yellow-600"
                                                modal-id="salary-advance" :has-form="true">

                                                <!-- Hidden salary field -->
                                                <input type="hidden" id="total_selected_salary_advance" value="0">

                                                <!-- Current Salary Display -->
                                                <div class="bg-gray-100 p-3 rounded-lg text-right">
                                                    <p class="text-sm text-gray-600">الراتب الحالي</p>
                                                    <p class="text-lg font-bold" id="salary_display_advance">0 ر.س</p>
                                                </div>

                                                <!-- Advance Amount -->
                                                <div>
                                                    <label class="block mb-2 font-semibold text-gray-700 text-right">
                                                        قيمة السلفة (ر.س)
                                                    </label>
                                                    <input type="number" name="advance_amount" id="advance_amount"
                                                        min="100"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-right"
                                                        required>
                                                </div>

                                                <!-- Percentage Display -->
                                                <div>
                                                    <label class="block mb-2 font-semibold text-gray-700 text-right">
                                                        نسبة السلفة من الراتب
                                                    </label>
                                                    <input type="text" id="advance_percentage_display" readonly
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-right">
                                                    <input type="hidden" name="advance_percentage"
                                                        id="advance_percentage">
                                                </div>

                                                <!-- Months to Repay -->
                                                <div>
                                                    <label class="block mb-2 font-semibold text-gray-700 text-right">
                                                        عدد الأشهر للسداد
                                                    </label>
                                                    <input type="number" name="months_to_repay" min="1"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-right"
                                                        required>
                                                </div>

                                                <!-- Start Deduction Date -->
                                                <div>
                                                    <label class="block mb-2 font-semibold text-gray-700 text-right">
                                                        تاريخ بدء استرداد السلفة
                                                    </label>
                                                    <input type="text" id="start_deduction_at"
                                                        name="start_deduction_at"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_deduction_at') border-red-500 @enderror"
                                                        placeholder="اختر تاريخ بداية استرداد السلفة من الراتب" required>
                                                </div>
                                            </x-bulk-action-modal>
                                        </li>


                                        <li>
                                            <x-bulk-action-modal action="generate_health_card"
                                                modal-title="تأكيد طلب بطاقة صحية" confirm-text="طلب بطاقة صحية"
                                                button-class="bg-green-600" modal-id="generate_health_card" />

                                        </li>
                                        <li>

                                            <x-bulk-action-modal action="salary_increase" modal-title="طلب زيادة راتب"
                                                confirm-text="زيادة راتب" button-class="bg-yellow-600"
                                                modal-id="salary-increase" :has-form="true">
                                                <!-- Hidden salary field (you'll populate this dynamically) -->
                                                <input type="hidden" id="total_selected_salary" value="0">

                                                <div class="space-y-4">
                                                    <!-- Current Salary Display -->
                                                    <div class="bg-gray-100 p-3 rounded-lg text-right">
                                                        <p class="text-sm text-gray-600">الراتب الحالي</p>
                                                        <p class="text-lg font-bold" id="salary_display">0 ر.س</p>
                                                    </div>

                                                    <!-- Increase Type -->
                                                    <div>
                                                        <label
                                                            class="block mb-2 font-semibold text-gray-700 text-right">نوع
                                                            الزيادة</label>
                                                        <div
                                                            class="flex items-center space-x-4 space-x-reverse text-right">
                                                            <label class="inline-flex items-center">
                                                                <input type="radio" name="increase_type" value="static"
                                                                    checked onchange="toggleRewardMonthField()"
                                                                    class="form-radio text-blue-600">
                                                                <span class="mr-2">زيادة ثابتة</span>
                                                            </label>
                                                            <label class="inline-flex items-center">
                                                                <input type="radio" name="increase_type" value="reward"
                                                                    onchange="toggleRewardMonthField()"
                                                                    class="form-radio text-blue-600">
                                                                <span class="mr-2">مكافأة لمرة واحدة</span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- Reward Month (hidden by default) -->
                                                    <div id="reward_month_field" class="hidden">
                                                        <label
                                                            class="block mb-2 font-semibold text-gray-700 text-right">شهر
                                                            المكافأة</label>
                                                        <select name="reward_month" id="reward_month"
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-right">
                                                            @foreach (range(1, 12) as $month)
                                                                <option value="{{ $month }}"
                                                                    @if ($month == date('n')) selected @endif>
                                                                    {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Increase Amount -->
                                                    <div>
                                                        <label
                                                            class="block mb-2 font-semibold text-gray-700 text-right">مبلغ
                                                            الزيادة (ر.س)</label>
                                                        <input type="number" name="increase_amount" id="increase_amount"
                                                            min="100" oninput="calculatePercentage()"
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-right"
                                                            required>
                                                    </div>

                                                    <!-- Percentage Display (readonly) -->
                                                    <div>
                                                        <label
                                                            class="block mb-2 font-semibold text-gray-700 text-right">نسبة
                                                            الزيادة</label>
                                                        <input type="text" id="increase_percentage_display" readonly
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-right">
                                                        <input type="hidden" name="increase_percentage"
                                                            id="increase_percentage">
                                                    </div>

                                                    <!-- Reason -->
                                                    <div>
                                                        <label
                                                            class="block mb-2 font-semibold text-gray-700 text-right">سبب
                                                            الزيادة</label>
                                                        <textarea name="reason" rows="3" required
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-right"></textarea>
                                                    </div>
                                                </div>
                                            </x-bulk-action-modal>
                                        </li>
                                        <li>
                                            <x-bulk-action-modal action="add_alert" modal-title="إرسال إنذار"
                                                confirm-text="إرسال إنذار" button-class="bg-red-600" modal-id="add-alert"
                                                :has-form="true">
                                                <div class="bg-gray-100 p-3 rounded-lg text-right mb-4">
                                                    <p class="text-sm text-gray-600">رقم الإنذار الجديد</p>
                                                    <p class="text-lg font-bold" id="alert_number_display">0</p>
                                                </div>
                                                <!-- Alert Title -->
                                                <div class="mb-4">
                                                    <label class="block mb-2 font-semibold text-gray-700 text-right">
                                                        عنوان الإنذار
                                                    </label>
                                                    <input type="text" name="alert_title"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-right rtl"
                                                        placeholder="مثال: تأخير متكرر" required>
                                                </div>

                                                <!-- Alert Reason -->
                                                <div>
                                                    <label class="block mb-2 font-semibold text-gray-700 text-right">
                                                        سبب الإنذار
                                                    </label>
                                                    <textarea name="alert_reason" rows="3" required
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-right rtl"
                                                        placeholder="اكتب تفاصيل الإنذار هنا..."></textarea>
                                                </div>
                                            </x-bulk-action-modal>

                                        </li>
                                        <li>
                                            <x-bulk-action-modal action="replacement_request"
                                                modal-title="طلب استبدال موظف" confirm-text="استبدال موظف"
                                                button-class="bg-blue-600" modal-id="replacement-request"
                                                :has-form="true">

                                                <div class="mb-4 text-right">
                                                    <label class="block mb-2 font-semibold text-gray-700">
                                                        سبب طلب الاستبدال
                                                    </label>
                                                    <select name="replacement_reason" required
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 rtl text-right mb-3"
                                                        id="replacement-reason-select">
                                                        <option value="" selected disabled>اختر سبب الاستبدال
                                                        </option>
                                                        <option value="إنهاء خدمة">إنهاء خدمة</option>
                                                        <option value="استقالة">استقالة</option>
                                                        <option value="نقل داخلي">نقل داخلي</option>
                                                        <option value="أداء ضعيف">أداء ضعيف</option>
                                                        <option value="عدم التزام">عدم التزام</option>
                                                        <option value="آخر">آخر (يرجى التحديد أدناه)</option>
                                                    </select>

                                                    <div id="other-reason-container" class="hidden mt-3">
                                                        <label class="block mb-2 font-semibold text-gray-700">
                                                            سبب آخر
                                                        </label>
                                                        <input type="text" name="other_reason"
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 rtl text-right"
                                                            placeholder="حدد السبب...">
                                                    </div>
                                                </div>

                                                <div class="mb-4 text-right">
                                                    <label class="block mb-2 font-semibold text-gray-700">
                                                        تفاصيل إضافية
                                                    </label>
                                                    <textarea name="replacement_description" rows="4"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 rtl text-right"
                                                        placeholder="أضف أي تفاصيل إضافية هنا..."></textarea>
                                                </div>

                                            </x-bulk-action-modal>
                                        </li>
                                        <li>
                                            <x-bulk-action-modal action="add_deduction" modal-title="إضافة خصم"
                                                confirm-text="تطبيق الخصم" button-class="bg-red-700"
                                                modal-id="add-deduction" :has-form="true">

                                                <input type="hidden" id="total_selected_salary_deduction"
                                                    value="0">
                                                <div class="bg-gray-100 p-3 rounded-lg text-right mb-4">
                                                    <p class="text-sm text-gray-600">رقم الخصم الجديد</p>
                                                    <p class="text-lg font-bold" id="deduction_number_display">0</p>
                                                </div>
                                                <div class="bg-gray-100 p-3 rounded-lg text-right mb-4">
                                                    <p class="text-sm text-gray-600">الراتب الحالي</p>
                                                    <p class="text-lg font-bold" id="salary_display_deduction">0 ر.س
                                                    </p>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="block mb-2 font-semibold text-gray-700 text-right">
                                                        قيمة الخصم (ر.س)
                                                    </label>
                                                    <input type="number" name="deduction_amount" id="deduction_amount"
                                                        min="1" required
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-right rtl"
                                                        placeholder="أدخل مبلغ الخصم"
                                                        oninput="calculateDeductionPercentage()">
                                                </div>

                                                <div class="mb-4">
                                                    <label class="block mb-2 font-semibold text-gray-700 text-right">
                                                        نسبة الخصم من الراتب
                                                    </label>
                                                    <input type="text" id="deduction_percentage_display" readonly
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-right">
                                                    <input type="hidden" name="deduction_percentage"
                                                        id="deduction_percentage">
                                                </div>

                                                <div>
                                                    <label class="block mb-2 font-semibold text-gray-700 text-right">
                                                        سبب الخصم
                                                    </label>
                                                    <textarea name="deduction_reason" rows="3" required
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-right rtl"
                                                        placeholder="اكتب سبب الخصم هنا..."></textarea>
                                                </div>

                                            </x-bulk-action-modal>
                                        </li>
                                        <li>
                                            <x-bulk-action-modal action="temporary_assignment"
                                                modal-title="تكليف مؤقت في مشروع آخر" confirm-text="تكليف في مشروع مؤقتا"
                                                button-class="bg-yellow-500" modal-id="temporary-assignment"
                                                :has-form="true">

                                                <!-- المشروع الحالي -->
                                                <div class="mb-4">
                                                    <label class="block mb-2 font-semibold text-gray-700 text-right">
                                                        المشروع الحالي
                                                    </label>
                                                    <input type="text" name="current_project_name"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-right rtl bg-gray-100"
                                                        value="" readonly>
                                                </div>

                                                <!-- المشروع الجديد -->
                                                <div class="mb-4">
                                                    <label class="block mb-2 font-semibold text-gray-700 text-right">
                                                        المشروع المطلوب العمل به مؤقتًا
                                                    </label>
                                                    <select name="target_project_id"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-right rtl"
                                                        required>
                                                        <option value="">اختر المشروع</option>
                                                        @foreach ($projectsObjects as $project)
                                                            <option value="{{ $project->id }}"
                                                                {{ old('project') == $project->id ? 'selected' : '' }}>
                                                                {{ $project->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- المدة -->
                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-1 text-right">من
                                                            تاريخ</label>
                                                        <input type="text" id="start_date" name="start_date"
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-right rtl"
                                                            placeholder="اختر التاريخ" required>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-1 text-right">إلى
                                                            تاريخ</label>
                                                        <input type="text" id="end_date" name="end_date"
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-right rtl"
                                                            placeholder="اختر التاريخ" required>
                                                    </div>
                                                </div>

                                                <!-- السبب -->
                                                <div class="mb-4">
                                                    <label class="block mb-2 font-semibold text-gray-700 text-right">
                                                        سبب التكليف المؤقت
                                                    </label>
                                                    <textarea name="reason" rows="3" required
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-right rtl"
                                                        placeholder="اكتب سبب التكليف المؤقت هنا..."></textarea>
                                                </div>

                                            </x-bulk-action-modal>
                                        </li>



                                    </ul>
                                </div>
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
                                <input type="checkbox" value="role" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">الدور</span>
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
                        <div class="column-item">
                            <label class="column-checkbox">
                                <input type="checkbox" value="nationality" checked>
                                <span class="checkmark"></span>
                                <span class="column-name">الجنسية</span>
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
                        @include('Employees.employee-form-fields')
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
                <!-- Role Filter -->
                @php
                    if ($authRole === 'project_manager') {
                        $filteredRoleLabels = $allowedForProjectManager;
                    } elseif (in_array($authRole, ['hr_manager', 'hr_assistant'])) {
                        $filteredRoleLabels = $allowedForHrManager;
                    } else {
                        $filteredRoleLabels = $roleLabels;
                    }
                @endphp
                <div x-data="{
                    open: false,
                    selected: '{{ request('role') ?? '' }}',
                    options: {{ json_encode($filteredRoleLabels) }},
                    getSelectedLabel() {
                        if (!this.selected) return '{{ __('All Roles') }}';
                        return this.options[this.selected] ?? this.selected;
                    },
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
                        <span x-text="getSelectedLabel()"></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" @click.away="open = false"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg max-h-60 overflow-auto shadow-lg">
                        <li @click="selected = ''; open = false; applyFilter('', 'role')"
                            class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium">
                            {{ __('All Roles') }}
                        </li>
                        <template x-for="(label, value) in options" :key="value">
                            <li @click="selected = value; open = false; applyFilter(value, 'role')"
                                class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium">
                                <span x-text="label"></span>
                            </li>
                        </template>
                    </ul>
                </div>

                <!-- Account Status Filter -->
                <div x-data="{
                    open: false,
                    selected: '{{ request('account_status') ?? '' }}',
                    options: {
                        active: 'نشط',
                        inactive: 'غير نشط',
                        blacklist: 'في القائمة السوداء'
                    },
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
                            class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium">جميع الحالات</li>
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

                <!-- Residence Filter -->
                <div x-data="{
                    open: false,
                    selected: '{{ request('residence_neighborhood') ?? '' }}',
                    options: {{ json_encode($residence_neighborhood) }},
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
                        <span x-text="selected || 'جميع الأحياء السكنية'"></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" @click.away="open = false"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg max-h-60 overflow-auto shadow-lg">
                        <li @click="selected = ''; open = false; applyFilter('', 'residence_neighborhood')"
                            class="px-3 py-2 cursor-pointer hover:bg-blue-100 text-[15px] font-medium">
                            جميع الأحياء السكنية
                        </li>
                        <template x-for="label in options" :key="label">
                            <li @click="selected = label; open = false; applyFilter(label, 'residence_neighborhood')"
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
        <div class="bg-white rounded-lg shadow-md p-4 mb-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Total Salaries Card -->
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 flex-1 min-w-[250px]">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-800">إجمالي الرواتب</h3>
                            <p class="text-2xl font-bold text-blue-600" id="totalSalariesDisplay">
                                {{ $totalSalaries }} ر.س
                            </p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-wallet text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Average Salary Card -->
                <div class="bg-green-50 border border-green-100 rounded-lg p-4 flex-1 min-w-[250px]">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-green-800">متوسط الراتب</h3>
                            <p class="text-2xl font-bold text-green-600" id="avgSalaryDisplay">
                                {{ $avgSalaries }} ر.س
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-calculator text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-sm text-green-500 mt-2">لكل موظف</p>
                </div>

                <!-- Salary Range Card -->
                <div class="bg-purple-50 border border-purple-100 rounded-lg p-4 flex-1 min-w-[250px]">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-purple-800">مدى الرواتب</h3>
                            <p class="text-xl font-bold text-purple-600 flex space-x-2" id="salaryRangeDisplay">
                <span class="text-red-600 flex items-center">
                    {{ $minSalaries }} ر.س
                    <i class="fas fa-arrow-down ml-1 text-red-600"></i>
                </span>
                                <span class="text-purple-600">-</span>
                                <span class="text-green-600 flex items-center">
                    {{ $maxSalaries }} ر.س
                    <i class="fas fa-arrow-up ml-1 text-green-600"></i>
                </span>
                            </p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-sm text-purple-500 mt-2">أقل وأعلى راتب</p>
                </div>


                <!-- Employee Count Card (New) -->
                <div class="bg-amber-50 border border-amber-100 rounded-lg p-4 flex-1 min-w-[250px]">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-amber-800">عدد الموظفين</h3>
                            <p class="text-2xl font-bold text-amber-600" id="employeeCountDisplay">
                                {{ $employeesCount }}
                            </p>
                        </div>
                        <div class="bg-amber-100 p-3 rounded-full">
                            <i class="fas fa-users text-amber-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-sm text-amber-500 mt-2">إجمالي الموظفين</p>
                </div>
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
                                <th class="text-center text-uppercase">
                                    ID</th>
                                <th style="font-size:16px; font-weight:600; padding-right:25px;"
                                    class="text-right text-uppercase ">
                                    الصورة</th>
                                <th class="text-center text-uppercase">
                                    الاسم</th>
                                <th class="text-center text-uppercase"> الدور الوظيفي</th>

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

                                <tr class="@if ($employee['alerts_number'] > 0) has-alerts @endif">

                                    <td class="no-print w-8 text-center align-middle">
                                        <input type="checkbox"
                                            class="emp-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                            value="{{ $employee['id'] }}"
                                            data-status="{{ $employee['account_status'] }}"
                                            data-project="{{ $employee['project'] }}"
                                            data-salary="{{ $employee['salary'] }}"
                                            data-alert-number="{{ $employee['alerts_number'] }}"
                                            data-deduction-number="{{ $employee['deductions_number'] }}"
                                            onchange="updateSalaryDisplay(this); updateBulkActionsButton()">
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex align-items-center justify-content-center text-center gap-2">


                                            <a href="{{ route('employees.show', $employee['id']) }}"
                                                class="text-decoration-none text-dark text-sm fw-bold mb-0">
                                                {{ $employee['replaced_old_employee_id'] ?: $employee['id'] }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1 no-print">
                                            <div>
{{--                                                @php--}}
{{--                                                dd($employee['personal_image']);--}}
{{--                                                @endphp--}}
                                                <img src="{{ $employee['personal_image'] }}"
                                                    style="width: 60px; height: 60px; object-fit: cover;"
                                                    class="h-16 w-16 mx-auto rounded-full border object-cover rounded-5"
                                                    alt="user image">
                                            </div>
                                        </div>
                                    </td>
                                    @php
                                        $stopReason = trim($employee['stop_reason']);
                                        $isBadPerformance = in_array($stopReason, ['سوء اداء', 'سوء أداء']);
                                        $replacementCount = $employee['replacements_count'] ?? 0;
                                        $new_emp_replacements_count = $employee['new_emp_replacements_count'] ?? 0;
                                    @endphp
                                    @php
                                        $baseNationality = preg_replace('/(ة|ه)$/u', '', $employee['nationality']);
                                        $flagCode = null;

                                        if (isset($nationalityFlags[$employee['nationality']])) {
                                            $flagCode = $nationalityFlags[$employee['nationality']];
                                        } elseif (isset($nationalityFlags[$baseNationality])) {
                                            $flagCode = $nationalityFlags[$baseNationality];
                                        }
                                    @endphp

                                    <td class="align-middle text-center">
                                        <div class="d-flex align-items-center justify-content-center text-center gap-2">
                                            <div class="status-indicator {{ $employee['account_status'] }}"></div>

                                            <a href="{{ route('employees.show', $employee['id']) }}"
                                                class="text-decoration-none text-sm fw-bold mb-0"
                                                style="color: {{ $isBadPerformance ? '#dc3545' : ($employee['replaced_old_employee_id'] ? '#6f40c1' : '#212529') }};">

                                                {{ $employee['name'] }}
                                            </a>

                                            @if ($replacementCount > 0)
                                                <span class="badge bg-primary rounded-pill ms-1"
                                                    style="font-size: 0.75rem;">
                                                    <a
                                                        href="{{ route('employees.replacements', ['employee' => $employee['id']]) }}">

                                                        {{ $replacementCount }}
                                                    </a>

                                                </span>
                                            @elseif ($new_emp_replacements_count > 0)
                                                <span class="badge bg-primary rounded-pill ms-1"
                                                    style="font-size: 0.75rem;">
                                                    <a
                                                        href="{{ route('employees.show', ['employee' => $employee['replaced_old_employee_id']]) }}">
                                                        {{ $new_emp_replacements_count }}
                                                    </a>
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-secondary d-flex align-items-center justify-content-center mt-1"
                                            style="color: #000;">
                                            @if ($flagCode)
                                                <img src="https://flagcdn.com/24x18/{{ $flagCode }}.png"
                                                    alt="{{ $employee['nationality'] }}" class="me-1"
                                                    style="width: 20px; height: 15px;">
                                            @endif
                                            {{ $employee['job'] }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center min-w-[120px] max-w-[200px]">
                                        <div class="whitespace-normal break-words px-2">
                                            {{ __($employee['role']) ?? '-' }}
                                        </div>
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
                                        @if (!empty($employee['project']) || (!empty($employee['managed_projects']) && count($employee['managed_projects']) > 0))
                                            <div class="d-flex flex-column align-items-center gap-1">

                                                @if (
                                                    $employee['role'] === 'project_manager' &&
                                                        !empty($employee['managed_projects']) &&
                                                        count($employee['managed_projects']) > 0)
                                                    <div
                                                        class="d-flex align-items-center justify-content-center gap-1 small">
                                                        <span class="arabic-text"
                                                            style="font-family: 'Tahoma', sans-serif">
                                                            {{ implode('، ', $employee['managed_projects']) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <div class="d-flex flex-column align-items-center">
                                                    <div>{{ $employee['project'] }}</div>
                                                    <div class="text-muted small" style="color: #000;">

                                                        <a href="{{ route('employees.assignments', ['employee' => $employee['id']]) }}"
                                                            class="text-decoration-none">
                                                            <span
                                                                class="badge bg-white text-info border border-info rounded-pill"
                                                                style="font-size: 11px;">
                                                                🧳 {{ $employee['temporary_assignments'] }}
                                                            </span>
                                                        </a>
                                                        @if (!empty($employee['supervisor_name']))
                                                            {{ $employee['supervisor_name'] }}
                                                    </div>
                                        @endif
                                                    @if (!empty($employee['area_manager_name']))
                                                        {{ $employee['area_manager_name'] }}
                                                </div>
                                                    @endif

            </div>
        @else
            <span class="text-danger">-</span>
            @endif
            </td>


            <td class="align-middle text-start">
                <div style="font-size: 16px; font-weight: 500; color: #000;">
                    {{ number_format($employee['salary']) }} ر.س
                </div>

                <div style="display: flex; gap: 10px; margin-top: 4px;">
                    <a href="{{ route('employees.increases', ['employee' => $employee['id']]) }}"
                        style="text-decoration: none;">
                        <div style="font-size: 14px; color: #28a745;">
                            ⬆️ {{ number_format($employee['increases_number'] ?? 0) }}
                        </div>
                    </a>

                    <a href="{{ route('employees.advances', ['employee' => $employee['id']]) }}"
                        style="text-decoration: none;">
                        <div style="font-size: 14px; color: #dc3545;">
                            ⬇️ {{ number_format($employee['deductions_number'] ?? 0) }}
                        </div>
                    </a>
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
                    <a href="{{ route('employees.alerts', ['employee' => $employee['id']]) }}"
                        style="color: inherit; text-decoration: none;">
                        ⚠️ {{ $employee['alerts_number'] }}
                    </a>
                </div>
                <div style="font-size: 15px; font-weight: 500; color: #000;">
                    <a href="{{ route('employees.deductions', ['employee' => $employee['id']]) }}"
                        style="color: inherit; text-decoration: none;">
                        💸 {{ $employee['deductions_number'] }}
                    </a>
                </div>
            </td>

            <td class="align-middle text-center">
                <div style="display: flex; flex-direction: column; gap: 4px; align-items: center;">
                    <span class="text-secondary " style="display: flex; align-items: center; gap: 8px;">
                        {{ $employee['phone_number'] }}
                        @if (!empty($employee['whats_app_link']))
                            <a href="{{ $employee['whats_app_link'] }}" target="_blank" style="color: #25D366;"
                                title="Chat on WhatsApp">
                                <i class="fab fa-whatsapp" style="font-size: 18px;"></i>
                            </a>
                        @endif
                    </span>
                    <span class="text-secondary " style="display: flex; align-items: center; gap: 6px;">
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
                    <span class="badge bg-primary mb-1"
                        style="font-size: 0.75rem; font-weight: 600; padding: 0.25em 0.5em; display: inline-block; min-width: 40px;">
                        بنطال: {{ $employee['pants_size'] }}
                    </span>
                </div>
                <div>
                    <span class="badge bg-info"
                        style="font-size: 0.75rem; font-weight: 600; padding: 0.25em 0.5em; display: inline-block; min-width: 40px;">
                        حذاء: {{ $employee['Shoes_size'] }}
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
                    @php
                        $stopReason = trim($employee['stop_reason']);
                        $isBadPerformance = in_array($stopReason, ['سوء اداء', 'سوء أداء']);
                    @endphp

                    <span class="{{ $isBadPerformance ? 'text-danger' : 'text-secondary' }}">
                        {{ $employee['stop_reason'] }}
                    </span>
                @else
                    <span class="text-success">
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
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 h-16 w-16 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                    if (data.errors) return showValidationErrors(data.errors);
                    return showErrorAlert(data.message);
                }

                showSuccessAlert(data.message, {
                    redirect: "{{ route('employees.index') }}"
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
        flatpickr("#start_deduction_at", {
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
        flatpickr("#start_date", {
            locale: "ar",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            allowInput: true,
            defaultHour: 12,
        });

        flatpickr("#end_date", {
            locale: "ar",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            allowInput: true,
            defaultHour: 12,
        });
        flatpickr("#stop_date", {
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
            updateBulkActionsButton();
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
                'joining_date': 'تاريخ الالتحاق',
                'nationality': 'الجنسية',

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


        function getSelectedColumns() {
            const checkboxes = document.querySelectorAll('.column-checkbox input:checked');
            return Array.from(checkboxes).map(checkbox => checkbox.value);
        }

        // Helper function to prepare export data
        function prepareExportData(selectedColumns) {
            const exportColumns = selectedColumns.filter(col => col !== 'image');

            const columnMapping = {

                'name': {
                    header: 'اسم الموظف',
                    accessor: e => e.name
                },
                'role': {
                    header: 'الدور',
                    accessor: e => e.role || '-'
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
                },
                'nationality': {
                    header: 'الجنسية',
                    accessor: e => e.nationality
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
            updateColumnsBadge();
        });
    </script>


    <script>
        function showSuccessAlert(message, options = {}) {
            Swal.fire({
                icon: 'success',
                title: 'نجاح!',
                text: message || 'تمت العملية بنجاح',
                confirmButtonText: 'حسناً',
                customClass: {
                    confirmButton: 'btn btn-success'
                },
                timer: options.timer ?? 2000,
                timerProgressBar: true,
                willClose: () => {
                    if (options.redirect) {
                        window.location.href = options.redirect;
                    } else if (options.reload) {
                        window.location.reload();
                    }
                }
            });
        }

        function showErrorAlert(message) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ!',
                text: message || 'حدث خطأ غير متوقع',
                confirmButtonText: 'حسناً',
                customClass: {
                    confirmButton: 'btn btn-danger'
                }
            });
        }

        function showValidationErrors(errors) {
            let errorMessages = [];
            for (const field in errors) {
                errorMessages.push(...errors[field]);
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
        }
    </script>
    <script>
        function updateSalaryDisplay(checkbox) {
            const salaryDisplay = document.getElementById('salary_display');
            const advanceSalaryDisplay = document.getElementById('salary_display_advance');
            const deductionSalaryDisplay = document.getElementById('salary_display_deduction');
            const alertNumberDisplay = document.getElementById('alert_number_display');
            const deductionNumberDisplay = document.getElementById('deduction_number_display');
            const checkboxes = document.querySelectorAll('.emp-checkbox:checked');
            const currentProjectInput = document.querySelector('input[name="current_project_name"]');
            const selectedProjects = new Set();

            let totalSalary = 0;
            let maxAlertNumber = 0;
            let maxDeductionNumber = 0;

            checkboxes.forEach(cb => {
                totalSalary += parseFloat(cb.getAttribute('data-salary')) || 0;
                const alertNumber = parseInt(cb.getAttribute('data-alert-number')) || 0;
                if (alertNumber > maxAlertNumber) {
                    maxAlertNumber = alertNumber;
                }
                const deductionNumber = parseInt(cb.getAttribute('data-deduction-number')) || 0;
                if (deductionNumber > maxDeductionNumber) {
                    maxDeductionNumber = deductionNumber;
                }
                const project = cb.getAttribute('data-project');
                if (project) selectedProjects.add(project);

                if (selectedProjects.size === 1) {
                    // Single project selected
                    currentProjectInput.value = selectedProjects.values().next().value;
                } else if (selectedProjects.size > 1) {
                    // Multiple projects selected
                    currentProjectInput.value = 'موظفون من مشاريع متعددة';
                } else {
                    // No projects selected
                    currentProjectInput.value = '';
                }
            });

            const formattedSalary = new Intl.NumberFormat('ar-SA').format(totalSalary) + ' ر.س';
            salaryDisplay.textContent = formattedSalary;
            advanceSalaryDisplay.textContent = formattedSalary;
            deductionSalaryDisplay.textContent = formattedSalary;
            alertNumberDisplay.textContent = maxAlertNumber + 1;
            deductionNumberDisplay.textContent = maxDeductionNumber + 1;

            // Update hidden fields for both modals
            document.getElementById('total_selected_salary').value = totalSalary;
            document.getElementById('total_selected_salary_advance').value = totalSalary;
            document.getElementById('total_selected_salary_deduction').value = totalSalary;

            // Auto-calculate percentages if amounts are entered
            if (document.getElementById('increase_amount').value) {
                calculatePercentage();
            }
            if (document.getElementById('advance_amount').value) {
                calculateAdvancePercentage();
            }
            if (document.getElementById('deduction_amount').value) {
                calculateDeductionPercentage();
            }
        }

        function calculatePercentage() {
            // Get the total salary from selected employees instead of individual salary
            const totalSalary = parseFloat(document.getElementById('total_selected_salary').value);
            const amount = parseFloat(document.getElementById('increase_amount').value);

            if (totalSalary > 0 && amount > 0) {
                const percentage = (amount / totalSalary * 100).toFixed(2);
                document.getElementById('increase_percentage_display').value = percentage + '%';
                document.getElementById('increase_percentage').value = percentage;
            }
        }

        // Initialize with first selected employee's salary
        document.addEventListener('DOMContentLoaded', function() {
            const firstSelected = document.querySelector('.emp-checkbox:checked');
            if (firstSelected) {
                const salary = firstSelected.dataset.salary || '5000';
                document.getElementById('employee_salary').value = salary;
                document.getElementById('salary_display').textContent =
                    new Intl.NumberFormat('ar-SA').format(salary) + ' ر.س';
            }
        });
    </script>
    <script>
        // Export to Excel function
        document.getElementById('excelExportBtn').addEventListener('click', function() {
            const selectedColumns = getSelectedColumns().filter(col => col !== 'image');

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

        document.getElementById('pdfExportBtn').addEventListener('click', function() {
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
            header.style.justifyContent = 'center'; // Center the content
            header.style.alignItems = 'center';
            header.style.position = 'relative'; // For absolute positioning of logo

            // Company info (centered)
            const companyInfo = document.createElement('div');
            companyInfo.style.textAlign = 'center';
            companyInfo.style.flexGrow = '1';

            const companyName = document.createElement('h1');
            companyName.textContent = 'شركة افاق الخليج';
            companyName.style.color = '#6e48aa';
            companyName.style.margin = '0 0 5px 0';
            companyName.style.fontSize = '24px';
            companyName.style.fontWeight = 'bold';

            const reportTitle = document.createElement('h2');
            reportTitle.textContent = 'تقرير الموظفين';
            reportTitle.style.color = '#333';
            reportTitle.style.margin = '0 0 5px 0';
            reportTitle.style.fontSize = '20px';
            reportTitle.style.fontWeight = '600';

            const reportDate = document.createElement('p');
            reportDate.textContent = 'تاريخ التقرير: ' + new Date().toLocaleDateString('ar-EG');
            reportDate.style.color = '#666';
            reportDate.style.margin = '0';
            reportDate.style.fontSize = '16px';

            companyInfo.appendChild(companyName);
            companyInfo.appendChild(reportTitle);
            companyInfo.appendChild(reportDate);

            // Add logo (right side, larger size)
            const logoContainer = document.createElement('div');
            logoContainer.style.position = 'absolute';
            logoContainer.style.left = '20px'; // Position on the right (RTL)
            logoContainer.style.top = '50%';
            logoContainer.style.transform = 'translateY(-50%)';
            logoContainer.style.width = '150px'; // Larger width
            logoContainer.style.height = 'auto';

            // Company logo - adjust path as needed
            const logo = document.createElement('img');
            logo.src = '{{ asset('build/assets/img/logo.png') }}';
            logo.style.maxWidth = '100%';
            logo.style.maxHeight = '100px'; // Larger height
            logo.style.objectFit = 'contain';
            logo.alt = 'شعار الشركة';

            // Fallback if logo fails to load
            logo.onerror = function() {
                this.style.display = 'none';
                const fallbackText = document.createElement('div');
                fallbackText.textContent = 'الشعار';
                fallbackText.style.width = '150px';
                fallbackText.style.height = '100px';
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

            // Prepare the table for PDF export
            tableClone.style.width = '100%';
            tableClone.style.borderCollapse = 'collapse';
            tableClone.style.marginTop = '20px';
            tableClone.style.direction = 'rtl';

            // Remove checkbox column
            tableClone.querySelectorAll('th:first-child, td:first-child').forEach(el => {
                if (el.querySelector('input[type="checkbox"]')) {
                    el.remove();
                }
            });

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
            pageInfo.textContent = 'صفحة 1 من 1';

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
                    allowTaint: true,
                    scrollX: 0,
                    scrollY: 0,
                    letterRendering: true,
                    onclone: function(clonedDoc) {
                        clonedDoc.documentElement.dir = 'rtl';
                        clonedDoc.body.style.direction = 'rtl';
                        clonedDoc.body.style.textAlign = 'right';

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
                    const totalPages = pdf.internal.getNumberOfPages();
                    for (let i = 1; i <= totalPages; i++) {
                        pdf.setPage(i);
                        pdf.setFont('Arial', 'normal');
                        pdf.setTextColor(100);
                        pdf.setFontSize(10);
                        pdf.text(
                            `صفحة ${i} من ${totalPages}`,
                            pdf.internal.pageSize.getWidth() - 20,
                            pdf.internal.pageSize.getHeight() - 10
                        );
                    }
                })
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
    </script>
    <script>
        function bulkActionModal(modalId, formAction, hasForm = false) {
            return {
                showModal: false,
                formAction: formAction,
                containerId: modalId + 'IdsContainer',

                async submitForm() {
                    const container = document.getElementById(this.containerId);
                    container.innerHTML = '';

                    const selectedCheckboxes = document.querySelectorAll('.emp-checkbox:checked');
                    if (selectedCheckboxes.length === 0) {
                        showErrorAlert('يرجى تحديد موظف واحد على الأقل');
                        return;
                    }

                    // Build hidden input fields for employee IDs
                    selectedCheckboxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        container.appendChild(input);
                    });

                    // Get form element
                    const form = container.closest('form');
                    const formData = new FormData(form);

                    try {
                        const response = await fetch(this.formAction, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            if (data.errors) {
                                showValidationErrors(data.errors);
                            } else {
                                showErrorAlert(data.message ?? 'فشل في تنفيذ العملية');
                            }
                            return;
                        }

                        if (data.success) {
                            this.showModal = false;
                            showSuccessAlert(data.message, {
                                reload: true
                            });
                        } else {
                            showErrorAlert(data.message ?? 'فشل في تنفيذ العملية');
                        }

                    } catch (err) {
                        console.error(err);
                        showErrorAlert('حدث خطأ غير متوقع');
                    }
                }
            }
        }
    </script>
    <script>
        function toggleRewardMonthField() {
            const rewardType = document.querySelector('input[name="increase_type"]:checked').value;
            const rewardMonthField = document.getElementById('reward_month_field');

            if (rewardType === 'reward') {
                rewardMonthField.classList.remove('hidden');
            } else {
                rewardMonthField.classList.add('hidden');
            }
        }

        function calculateAdvancePercentage() {
            const totalSalary = parseFloat(document.getElementById('total_selected_salary_advance').value);
            const amount = parseFloat(document.getElementById('advance_amount').value);

            if (totalSalary > 0 && amount > 0) {
                const percentage = (amount / totalSalary * 100).toFixed(2);
                document.getElementById('advance_percentage_display').value = percentage + '%';
                document.getElementById('advance_percentage').value = percentage;
            } else {
                document.getElementById('advance_percentage_display').value = '0%';
                document.getElementById('advance_percentage').value = 0;
            }
        }

        function calculateDeductionPercentage() {
            const totalSalary = parseFloat(document.getElementById('total_selected_salary_deduction').value);
            const amount = parseFloat(document.getElementById('deduction_amount').value);

            if (totalSalary > 0 && amount > 0) {
                const percentage = (amount / totalSalary * 100).toFixed(2);
                document.getElementById('deduction_percentage_display').value = percentage + '%';
                document.getElementById('deduction_percentage').value = percentage;
            } else {
                document.getElementById('deduction_percentage_display').value = '0%';
                document.getElementById('deduction_percentage').value = 0;
            }
        }
        document.getElementById('advance_amount').addEventListener('input', calculateAdvancePercentage);
        document.getElementById('deduction_amount').addEventListener('input', calculateDeductionPercentage);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reasonSelect = document.getElementById('replacement-reason-select');
            const otherReasonContainer = document.getElementById('other-reason-container');

            reasonSelect.addEventListener('change', function() {
                if (this.value === 'آخر') {
                    otherReasonContainer.classList.remove('hidden');
                    otherReasonContainer.querySelector('input').setAttribute('required', 'true');
                } else {
                    otherReasonContainer.classList.add('hidden');
                    otherReasonContainer.querySelector('input').removeAttribute('required');
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stopReasonSelect = document.getElementById('stop-reason-select');
            const otherStopReasonContainer = document.getElementById('other-stop-reason-container');

            stopReasonSelect.addEventListener('change', function() {
                if (this.value === 'آخر') {
                    otherStopReasonContainer.classList.remove('hidden');
                    otherStopReasonContainer.querySelector('input').setAttribute('required', 'true');
                } else {
                    otherStopReasonContainer.classList.add('hidden');
                    otherStopReasonContainer.querySelector('input').removeAttribute('required');
                }
            });
        });
    </script>
@endpush
