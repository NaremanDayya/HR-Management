@extends('layouts.master')
@section('title', 'جدول المشاريع')
@push('styles')
    <style>
        #pdfExportBtn {
            background: linear-gradient(195deg, #e74c3c 0%, #c0392b 100%);
        }

        #pdfExportBtn:hover {
            background: linear-gradient(195deg, #d62c1a 0%, #a82b1d 100%);
        }

        #excelExportBtn {
            background: linear-gradient(195deg, #2ecc71 0%, #27ae60 100%);
        }

        #excelExportBtn:hover {
            background: linear-gradient(195deg, #25b765 0%, #1e8449 100%);
        }

        .export-btn-group {
            display: flex;
            gap: 10px;
            align-items: center;
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
        }

        .export-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 6px;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 14px;
            font-weight: 600;
        }

        .export-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .export-btn .btn-icon {
            font-size: 14px;
        }

        .export-btn .btn-text {
            margin-right: 5px;
        }

        .thead th {
            font-size: 18px;
            font-weight: 700;
        }


        .tbody {
            font-size: 18px;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
            font-weight: 600;
        }

        .bg-success {
            background: linear-gradient(195deg, #66BB6A 0%, #43A047 100%);
        }

        .bg-danger {
            background: linear-gradient(195deg, #EF5350 0%, #E53935 100%);
        }

        .bg-primary {
            background: linear-gradient(195deg, #49a3f1 0%, #1A73E8 100%);
        }

        .bg-info {
            background: linear-gradient(195deg, #26C6DA 0%, #00ACC1 100%);
        }

        .bg-warning {
            background: linear-gradient(195deg, #FFA726 0%, #FB8C00 100%);
        }

        .btn-purple {
            background: linear-gradient(195deg, #6e48aa 0%, #9c50b6 100%);
            color: white;
            border: none;
        }

        .btn-purple:hover {
            background: linear-gradient(195deg, #5a3a8a 0%, #7d3c98 100%);
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4 shadow-sm rounded-2xl border-0">
                    <div
                        class="card-header p-4 position-relative z-index-2 d-flex flex-column md:flex-row md:items-center md:justify-between w-100 bg-white">

                        <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-4">
                            <!-- Search Bar (right side) -->
                            <div class="position-relative" style="min-width: 250px; max-width: 320px; width: 100%;">
                                <div class="position-absolute end-0 top-50 translate-middle-y pe-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" id="searchInput" class="form-control ps-4 pe-10"
                                    placeholder="ابحث عن مشروع أو مدير..." oninput="searchProjects()"
                                    style="border-radius: 0.5rem;">
                            </div>
                            <!-- Buttons (left side) -->
                            <div class="d-flex align-items-center gap-2 flex-grow-0">
                                <button class="btn btn-purple d-flex align-items-center gap-2" data-bs-toggle="modal"
                                    data-bs-target="#createProjectModal">
                                    <i class="fas fa-plus"></i>
                                    <span>إضافة مشروع</span>
                                </button>

                                <div class="export-btn-group no-print d-flex gap-2">
                                    <button id="pdfExportBtn" class="btn export-btn"
                                        style="background: linear-gradient(195deg, #e74c3c 0%, #c0392b 100%); font-size: 14px; font-weight: 600;">
                                        <i class="fas fa-file-pdf"></i> تصدير PDF
                                    </button>

                                    <button id="excelExportBtn" class="btn export-btn"
                                        style="background: linear-gradient(195deg, #2ecc71 0%, #27ae60 100%); font-size: 14px; font-weight: 600;">
                                        <i class="fas fa-file-excel"></i> تصدير Excel
                                    </button>
                                </div>
                            </div>



                        </div>

                    </div>


                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            @if (count($projects) > 0)
                                <table id="projectsTable" class="table align-items-center mb-0 w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th style="font-size:14px; font-weight: 700;"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">
                                                ID</th>
                                            <th style="font-size:14px; font-weight: 700;"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">
                                                اسم المشروع</th>
                                            <th style="font-size:14px; font-weight: 700;"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">
                                                المدير المسؤول</th>
                                            <th style="font-size:14px; font-weight: 700;"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">
                                                الموظفين النشطين</th>
                                            <th style="font-size:14px; font-weight: 700;"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">
                                                الموظفين غير النشطين</th>
                                            <th style="font-size:14px; font-weight: 700;"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">
                                                إجمالي الموظفين</th>
                                            <th style="font-size:14px; font-weight: 700;"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">
                                                الوصف</th>
                                            <th style="font-size:14px; font-weight: 700;"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">
                                                الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($projects as $project)
                                            <tr class="hover:bg-gray-50 transition-colors duration-150"
                                                style="font-size:14px; font-weight: 700;">
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                    {{ $project->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="text-blue-600 font-semibold">{{ $project->name }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if ($project->manager)
                                                        <span class="text-gray-800">{{ $project->manager->name }}</span>
                                                    @else
                                                        <span class="text-red-500">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="badge bg-success">
                                                        {{ $project->active_employees_count }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="badge bg-danger">
                                                        {{ $project->inactive_employees_count }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="badge bg-primary">
                                                        {{ $project->employees->count() }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center max-w-xs">
                                                    <div class="text-gray-500 truncate">
                                                        {{ $project->description ?? '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="flex justify-center gap-2">
                                                        <a href="{{ route('employees.index') }}?project={{ $project->id }}"
                                                            class="btn btn-sm btn-info" data-toggle="tooltip"
                                                            title="عرض موظفي المشروع">
                                                            <i class="fas fa-users"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-warning edit-project-btn"
                                                            data-id="{{ $project->id }}" data-name="{{ $project->name }}"
                                                            data-description="{{ $project->description }}"
                                                            data-manager-name="{{ $project->manager ? $project->manager->name : '' }}"
                                                            data-bs-toggle="modal" data-bs-target="#editProjectModal">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('projects.destroy', $project->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('هل أنت متأكد من حذف هذا المشروع؟')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center py-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 h-16 w-16 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-gray-500 text-lg font-semibold">لا توجد مشاريع مسجلة</p>
                                    <p class="text-gray-400 mt-2">يمكنك إضافة مشروع جديد باستخدام الزر أعلاه</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Project Modal -->
    <div class="modal fade" id="createProjectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة مشروع جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createProjectForm" action="{{ route('projects.store') }}" method="POST"
                    class="bg-white rounded-xl shadow-md p-6 space-y-6 w-full max-w-lg mx-auto">
                    @csrf

                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">إضافة مشروع جديد</h2>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">اسم المشروع</label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>

                    <div>
                        <label for="manager_name" class="block text-sm font-medium text-gray-700 mb-1">مدير
                            المشروع</label>
                        <input type="text" id="manager_name" name="manager_name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t">
                        <button type="button" data-bs-dismiss="modal"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            إلغاء
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            حفظ
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div class="modal fade" id="editProjectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل المشروع</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProjectForm" method="POST"
                    class="bg-white rounded-xl shadow-md p-6 space-y-6 w-full max-w-lg mx-auto">
                    @csrf
                    @method('PUT')

                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">تعديل المشروع</h2>

                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">اسم المشروع</label>
                        <input type="text" id="edit_name" name="name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>

                    <div>
                        <label for="edit_manager_name" class="block text-sm font-medium text-gray-700 mb-1">مدير
                            المشروع</label>
                        <input type="text" id="edit_manager_name" name="manager_name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    </div>

                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                        <textarea id="edit_description" name="description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t">
                        <button type="button" data-bs-dismiss="modal"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            إلغاء
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            حفظ
                        </button>
                    </div>
                </form>
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

            // Initialize DataTable
            if (document.querySelector('#projectsTable')) {
                $('#projectsTable').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Arabic.json"
                    },
                    "responsive": true,
                    "autoWidth": false,
                    "paging": false,
                    "searching": false,
                    "info": false,
                    "order": [
                        [0, "desc"]
                    ]
                });
            }

            // Edit project modal setup
            document.querySelectorAll('.edit-project-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const projectId = this.getAttribute('data-id');
                    const projectName = this.getAttribute('data-name');
                    const projectDescription = this.getAttribute('data-description');
                    const projectManagerName = this.getAttribute(
                        'data-manager-name'); // Make sure to add this data attribute to your button

                    document.getElementById('edit_name').value = projectName;
                    document.getElementById('edit_description').value = projectDescription || '';
                    document.getElementById('edit_manager_name').value = projectManagerName || '';

                    document.getElementById('editProjectForm').action = `/projects/${projectId}`;
                });
            });

            // Edit form submission
            $('#editProjectForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const submitButton = form.find('button[type="submit"]');
                const originalText = submitButton.html();

                submitButton.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        جاري التحديث...
    `);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize() + '&_method=PUT',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'نجاح!',
                            text: response.message,
                            confirmButtonText: 'حسناً',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            },
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                location.reload();
                            }
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'حدث خطأ غير متوقع';

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('<br>');

                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ في التحقق',
                                html: errorMessage,
                                confirmButtonText: 'حسناً',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        } else {
                            errorMessage = xhr.responseJSON?.message || errorMessage;

                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: errorMessage,
                                confirmButtonText: 'حسناً',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).html(originalText);
                    }
                });
            });
            // Create form submission
            $('#createProjectForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const submitButton = form.find('button[type="submit"]');
                const originalText = submitButton.html();

                submitButton.prop('disabled', true).html(`
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    جاري الحفظ...
                `);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'نجاح!',
                            text: response.message,
                            confirmButtonText: 'حسناً',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            },
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                location.reload();
                            }
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'حدث خطأ غير متوقع';

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('<br>');

                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ في التحقق',
                                html: errorMessage,
                                confirmButtonText: 'حسناً',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        } else {
                            errorMessage = xhr.responseJSON?.message || errorMessage;

                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: errorMessage,
                                confirmButtonText: 'حسناً',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Excel Export
            document.getElementById('excelExportBtn').addEventListener('click', function() {
                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
                btn.disabled = true;

                try {
                    const table = document.getElementById('projectsTable');
                    const rows = table.querySelectorAll('tbody tr');
                    const headers = Array.from(table.querySelectorAll('thead th'))
                        .map(th => th.textContent.trim())
                        .filter(text => text !== 'الإجراءات');

                    // Prepare data
                    const data = [
                        headers,
                        ...Array.from(rows).map(row => {
                            return Array.from(row.querySelectorAll('td'))
                                .filter((td, index) => index < headers.length)
                                .map(td => {
                                    let content = td.textContent.trim();
                                    if (td.querySelector('.badge')) {
                                        content = td.querySelector('.badge').textContent.trim();
                                    }
                                    return content;
                                });
                        })
                    ];

                    // Create workbook
                    const wb = XLSX.utils.book_new();
                    const ws = XLSX.utils.aoa_to_sheet(data);
                    XLSX.utils.book_append_sheet(wb, ws, "المشاريع");
                    XLSX.writeFile(wb, 'المشاريع_' + new Date().toISOString().slice(0, 10) + '.xlsx');
                } catch (error) {
                    console.error('Excel export error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'حدث خطأ أثناء تصدير ملف Excel',
                        confirmButtonText: 'حسناً'
                    });
                } finally {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            });

            // PDF Export
            document.getElementById('pdfExportBtn').addEventListener('click', async function() {
                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
                btn.disabled = true;

                try {
                    const table = document.getElementById('projectsTable');
                    const tableClone = table.cloneNode(true);

                    // Remove action column
                    const actionHeaderIndex = Array.from(tableClone.querySelectorAll('thead th'))
                        .findIndex(th => th.textContent.trim() === 'الإجراءات');

                    if (actionHeaderIndex !== -1) {
                        tableClone.querySelectorAll('thead th')[actionHeaderIndex].remove();
                        tableClone.querySelectorAll('tbody tr').forEach(row => {
                            row.querySelectorAll('td')[actionHeaderIndex]?.remove();
                        });
                    }

                    // Create PDF container
                    const pdfContainer = document.createElement('div');
                    pdfContainer.style.padding = '20px';
                    pdfContainer.style.direction = 'rtl';
                    pdfContainer.style.fontFamily = 'Arial, sans-serif';
                    pdfContainer.style.textAlign = 'right';
                    pdfContainer.style.lineHeight = '1.6';

                    // Add header
                    const header = document.createElement('div');
                    header.style.marginBottom = '30px';
                    header.style.borderBottom = '2px solid #6e48aa';
                    header.style.paddingBottom = '15px';
                    header.style.textAlign = 'center';

                    const companyName = document.createElement('h1');
                    companyName.textContent = 'شركة افاق الخليج';
                    companyName.style.color = '#6e48aa';
                    companyName.style.margin = '0 0 5px 0';
                    companyName.style.fontSize = '24px';
                    companyName.style.fontWeight = 'bold';

                    const reportTitle = document.createElement('h2');
                    reportTitle.textContent = 'تقرير المشاريع';
                    reportTitle.style.color = '#333';
                    reportTitle.style.margin = '0 0 5px 0';
                    reportTitle.style.fontSize = '20px';
                    reportTitle.style.fontWeight = '600';

                    const reportDate = document.createElement('p');
                    reportDate.textContent = 'تاريخ التقرير: ' + new Date().toLocaleDateString('ar-EG');
                    reportDate.style.color = '#666';
                    reportDate.style.margin = '0';
                    reportDate.style.fontSize = '16px';

                    header.appendChild(companyName);
                    header.appendChild(reportTitle);
                    header.appendChild(reportDate);

                    // Style the table
                    tableClone.style.width = '100%';
                    tableClone.style.borderCollapse = 'collapse';
                    tableClone.style.marginTop = '20px';
                    tableClone.style.direction = 'rtl';
                    tableClone.style.wordBreak = 'break-word';

                    // Style headers and cells
                    tableClone.querySelectorAll('th').forEach(th => {
                        th.style.backgroundColor = '#6e48aa';
                        th.style.color = 'white';
                        th.style.padding = '12px';
                        th.style.border = '1px solid #ddd';
                        th.style.textAlign = 'center';
                        th.style.fontWeight = 'bold';
                    });

                    tableClone.querySelectorAll('td').forEach(td => {
                        td.style.padding = '8px';
                        td.style.border = '1px solid #ddd';
                        td.style.textAlign = 'right';
                        td.style.wordBreak = 'break-word';

                        if (td.classList.contains('text-center')) {
                            td.style.textAlign = 'center';
                        }
                    });

                    // Add footer
                    const footer = document.createElement('div');
                    footer.style.marginTop = '20px';
                    footer.style.paddingTop = '10px';
                    footer.style.borderTop = '1px solid #eee';
                    footer.style.textAlign = 'center';
                    footer.style.color = '#666';
                    footer.style.fontSize = '12px';

                    const copyright = document.createElement('p');
                    copyright.textContent =
                        `© ${new Date().getFullYear()} جميع الحقوق محفوظة لشركة افاق الخليج`;
                    footer.appendChild(copyright);

                    // Build the PDF content
                    pdfContainer.appendChild(header);
                    pdfContainer.appendChild(tableClone);
                    pdfContainer.appendChild(footer);

                    // PDF options
                    const options = {
                        margin: [15, 15, 30, 15],
                        filename: 'تقرير_المشاريع_' + new Date().toISOString().slice(0, 10) +
                            '.pdf',
                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 2,
                            logging: true,
                            useCORS: true,
                            scrollX: 0,
                            scrollY: 0,
                            letterRendering: true
                        },
                        jsPDF: {
                            unit: 'mm',
                            format: 'a2',
                            orientation: 'landscape'
                        }
                    };

                    // Generate PDF
                    await html2pdf().set(options).from(pdfContainer).save();

                } catch (error) {
                    console.error('PDF export error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: 'حدث خطأ أثناء تصدير ملف PDF',
                        confirmButtonText: 'حسناً'
                    });
                } finally {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Custom search function
            window.searchProjects = function() {
                const input = document.getElementById('searchInput');
                const filter = input.value.toUpperCase();
                const table = document.getElementById('projectsTable');
                const rows = table.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const projectName = cells[1].textContent.toUpperCase(); // Project name column
                    const managerName = cells[2].textContent.toUpperCase(); // Manager name column

                    if (projectName.includes(filter) || managerName.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Initialize empty search on load
            searchProjects();

            // Add event listener for better compatibility
            document.getElementById('searchInput').addEventListener('input', searchProjects);
        });
    </script>
@endpush
