@extends('layouts.master')
@section('title', 'تحميل ملف الموظفين')
@section('content')

    <div class="p-6 bg-white rounded-lg shadow-md">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">تحميل نموذج إضافة الموظفين</h2>
            <p class="text-gray-600">قم بتحميل النموذج واملأ البيانات المطلوبة ثم قم برفع الملف</p>
        </div>

        <!-- Response Messages Container -->
        <div id="responseMessage" class="hidden mb-4 p-4 rounded-lg"></div>

        <!-- Download Template Section -->
        <div class="mb-8 p-6 border border-gray-200 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">تحميل النموذج</h3>

            <a href="{{ route('employees.template.download') }}">
                <!-- Excel Download -->
                <button
                    class="inline-flex items-center px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    تحميل نموذج Excel
                </button>
            </a>
        </div>

        <!-- Upload Section -->
        <div class="mb-8 p-6 border border-gray-200 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">رفع الملف بعد تعبئة البيانات</h3>

            <form id="importForm" action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="flex items-center space-x-4 space-x-reverse">
                    <input
                        type="file"
                        name="employee_file"
                        id="employee_file"
                        accept=".csv,.xlsx,.xls"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        required
                    >

                    <button
                        type="submit"
                        id="submitBtn"
                        class="inline-flex items-center px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <span id="submitText">رفع الملف وإنشاء الموظفين</span>
                        <div id="loadingSpinner" class="hidden ml-2">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                        </div>
                    </button>
                </div>

                <p class="text-sm text-gray-600 mt-2">
                    يمكنك رفع ملف CSV أو Excel بعد تعبئة البيانات. سيتم إنشاء الموظفين تلقائياً.
                </p>
            </form>
        </div>

        <!-- Instructions Section -->
        <div class="mt-8 p-6 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">تعليمات الاستخدام</h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-800 mb-2">الحقول المطلوبة:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>الاسم الكامل</li>
                            <li>رقم الهوية</li>
                            <li>الجنسية</li>
                            <li>تاريخ الميلاد</li>
                            <li>الجنس</li>
                            <li>مقر الإقامة</li>
                            <li>الحي السكني</li>
                            <li>نوع المركبة</li>
                            <li>موديل المركبة</li>
                            <li>رقم لوحة المركبة</li>
                            <li>البريد الإلكتروني</li>
                            <li>رقم الجوال</li>
                            <li>نوع الجوال</li>
                            <li>الدور الوظيفي</li>
                            <li>منطقة العمل</li>
                            <li>تاريخ الإنضمام</li>
                            <li>هل لدى الموظف شهادة صحية</li>
                            <li>الراتب</li>
                        </ul>
                    </div>

                    <div>
                        <ul class="list-disc list-inside space-y-1">
                            <li>اسم صاحب الحساب</li>
                            <li>اسم البنك</li>
                            <li>المهنة في الهوية</li>
                            <li>رقم الآيبان</li>
                            <li>كلمة المرور</li>
                            <li>مقاس التي شيرت</li>
                            <li>مقاس البنطال</li>
                            <li>مقاس الحذاء</li>
                            <li>نوع الشهادة</li>
                            <li>عدد أفراد الأسرة</li>
                            <li>المشروع</li>
                            <li>المشرف</li>
                            <li>مدير المنطقة</li>
                            <li>مستوى اللغة الإنجليزية</li>
                        </ul>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <h4 class="font-medium text-gray-800 mb-2">ملاحظات هامة:</h4>
                    <ul class="list-disc list-inside space-y-1">
                        <li><strong>صيغة التواريخ:</strong> YYYY-MM-DD (مثال: 2024-01-15)</li>
                        <li><strong>إذا لم تقم بإدخال كلمة مرور:</strong> سيتم استخدام كلمة مرور افتراضية</li>
                        <li class="text-red-500"><strong>ملاحظة:</strong> إذا كان البريد الإلكتروني موجود مسبقاً، لن يتم إضافة الموظف</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('importForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const responseMessage = document.getElementById('responseMessage');

            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'جاري الرفع...';
            loadingSpinner.classList.remove('hidden');

            // Clear previous messages
            responseMessage.classList.add('hidden');
            responseMessage.innerHTML = '';

            // Send AJAX request
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    // Show success message
                    showMessage(data.message, 'success');

                    // If there are imported count details, show them
                    if (data.imported_count !== undefined) {
                        showMessage(`تم استيراد ${data.imported_count} موظف بنجاح`, 'success');
                    }

                    // If there are errors, show them
                    if (data.errors && data.errors.length > 0) {
                        showMessage(`تم الاستيراد مع بعض الأخطاء: ${data.errors.join(', ')}`, 'warning');
                    }

                    // Reset form on success
                    if (data.success) {
                        form.reset();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    if (error.errors) {
                        // Validation errors
                        const errorMessages = Object.values(error.errors).flat().join('<br>');
                        showMessage(errorMessages, 'error');
                    } else if (error.message) {
                        // General error
                        showMessage(error.message, 'error');
                    } else {
                        // Unknown error
                        showMessage('حدث خطأ غير متوقع أثناء رفع الملف', 'error');
                    }
                })
                .finally(() => {
                    // Reset loading state
                    submitBtn.disabled = false;
                    submitText.textContent = 'رفع الملف وإنشاء الموظفين';
                    loadingSpinner.classList.add('hidden');
                });
        });

        function showMessage(message, type) {
            const responseMessage = document.getElementById('responseMessage');

            // Set message and styling based on type
            responseMessage.innerHTML = message;
            responseMessage.classList.remove('hidden', 'bg-green-100', 'border-green-400', 'text-green-700', 'bg-red-100', 'border-red-400', 'text-red-700', 'bg-yellow-100', 'border-yellow-400', 'text-yellow-700');

            switch(type) {
                case 'success':
                    responseMessage.classList.add('bg-green-100', 'border-green-400', 'text-green-700', 'border');
                    break;
                case 'error':
                    responseMessage.classList.add('bg-red-100', 'border-red-400', 'text-red-700', 'border');
                    break;
                case 'warning':
                    responseMessage.classList.add('bg-yellow-100', 'border-yellow-400', 'text-yellow-700', 'border');
                    break;
            }

            // Auto-hide success messages after 5 seconds
            if (type === 'success') {
                setTimeout(() => {
                    responseMessage.classList.add('hidden');
                }, 5000);
            }
        }

        // Also handle traditional form submission errors (if any)
        @if(session('success'))
        showMessage("{{ session('success') }}", 'success');
        @endif

        @if($errors->any())
        showMessage("{{ $errors->first() }}", 'error');
        @endif
    </script>

    <style>
        #responseMessage {
            transition: all 0.3s ease;
        }

        #loadingSpinner {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>

@endsection
