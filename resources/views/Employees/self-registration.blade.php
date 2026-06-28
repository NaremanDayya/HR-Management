<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل بيانات {{ $roleLabel }} | {{ $project->name }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #F5F7FA;
            padding: 30px 0;
        }
        .form-card {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 40px;
        }
        .form-title {
            color: #740e0e;
            font-weight: 700;
        }
        .form-label { font-weight: 500; }
        .section-title {
            color: #740e0e;
            font-weight: 700;
            margin-top: 25px;
            margin-bottom: 15px;
            border-bottom: 2px solid #f0e0e0;
            padding-bottom: 8px;
        }
        .submit-btn {
            background-color: #740e0e;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 14px;
            font-weight: 600;
        }
        .submit-btn:hover { background-color: #5d0a0a; color: #fff; }
    </style>
</head>

<body>
    <div class="form-card">
        <div class="text-center mb-4">
            <h1 class="form-title">نموذج تسجيل بيانات {{ $roleLabel }}</h1>
            <p class="text-muted">مشروع: {{ $project->name }}</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('public.employee-register.store', [$project->id, $role]) }}" enctype="multipart/form-data">
            @csrf

            <h5 class="section-title">البيانات الشخصية</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">الاسم الكامل</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">رقم الهوية</label>
                    <input type="text" name="id_card" class="form-control" value="{{ old('id_card') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">تاريخ الميلاد</label>
                    <input type="date" id="birthday" name="birthday" class="form-control" value="{{ old('birthday') }}" required onchange="calculateAge()">
                </div>
                <div class="col-md-6">
                    <label class="form-label">العمر</label>
                    <input type="number" id="age" name="age" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الجنس</label>
                    <select name="gender" class="form-select" required>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الجنسية</label>
                    <input type="text" name="nationality" class="form-control" value="{{ old('nationality') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">مقر الإقامة</label>
                    <input type="text" name="residence" class="form-control" value="{{ old('residence') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الحي السكني</label>
                    <input type="text" name="residence_neighborhood" class="form-control" value="{{ old('residence_neighborhood') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">رقم الجوال</label>
                    <input type="tel" name="phone_number" class="form-control" value="{{ old('phone_number') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">نوع الجوال</label>
                    <select name="phone_type" class="form-select" required>
                        <option value="android" {{ old('phone_type') == 'android' ? 'selected' : '' }}>أندرويد</option>
                        <option value="iphone" {{ old('phone_type') == 'iphone' ? 'selected' : '' }}>آيفون</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">صورة شخصية</label>
                    <input type="file" name="personal_image" accept="image/*" class="form-control">
                </div>
            </div>

            <h5 class="section-title">البيانات الوظيفية والبنكية</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">المهنة في الهوية</label>
                    <input type="text" name="job" class="form-control" value="{{ old('job') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">تاريخ الإنضمام</label>
                    <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">منطقة العمل</label>
                    <input type="text" name="work_area" class="form-control" value="{{ old('work_area') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الراتب</label>
                    <input type="number" step="0.01" name="salary" class="form-control" value="{{ old('salary') }}" required>
                </div>
                @if ($role === 'shelf_stacker')
                    <div class="col-md-6">
                        <label class="form-label">المشرف المباشر</label>
                        <select name="supervisor" class="form-select" required>
                            <option value="" disabled selected>اختر المشرف</option>
                            @foreach ($supervisors as $id => $name)
                                <option value="{{ $id }}" {{ old('supervisor') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @if ($supervisors->isEmpty())
                            <small class="text-danger">لا يوجد مشرفون مسجلون لهذا المشروع بعد، يرجى التواصل مع مدير المشروع.</small>
                        @endif
                    </div>
                @elseif ($role === 'supervisor')
                    <div class="col-md-6">
                        <label class="form-label">مشرف المشرفين</label>
                        <select name="area_manager" class="form-select" required>
                            <option value="" disabled selected>اختر مشرف المشرفين</option>
                            @foreach ($areaManagers as $id => $name)
                                <option value="{{ $id }}" {{ old('area_manager') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @if ($areaManagers->isEmpty())
                            <small class="text-danger">لا يوجد مشرف مشرفين مسجل لهذا المشروع بعد، يرجى التواصل مع مدير المشروع.</small>
                        @endif
                    </div>
                @endif
                <div class="col-md-6">
                    <label class="form-label">هل لديك شهادة صحية (كرت بلدية)؟</label>
                    <select name="health_card" class="form-select" required>
                        <option value="" disabled selected>اختر</option>
                        <option value="1" {{ old('health_card') == '1' ? 'selected' : '' }}>نعم</option>
                        <option value="0" {{ old('health_card') == '0' ? 'selected' : '' }}>لا</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">اسم البنك</label>
                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">اسم صاحب الحساب البنكي</label>
                    <input type="text" name="owner_account_name" class="form-control" value="{{ old('owner_account_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">رقم الآيبان (22 رقم)</label>
                    <input type="text" name="iban" class="form-control" value="{{ old('iban') }}" maxlength="22" required>
                </div>
            </div>

            <h5 class="section-title">بيانات المركبة</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">نوع المركبة</label>
                    <input type="text" name="vehicle_type" class="form-control" value="{{ old('vehicle_type') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">موديل المركبة</label>
                    <input type="text" name="vehicle_model" class="form-control" value="{{ old('vehicle_model') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">رقم لوحة المركبة</label>
                    <input type="text" name="vehicle_ID" class="form-control" value="{{ old('vehicle_ID') }}" required>
                </div>
            </div>

            <h5 class="section-title">المقاسات والتعليم والحالة الاجتماعية</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">مقاس التيشيرت</label>
                    <select name="Tshirt_size" class="form-select">
                        @foreach ($shirtSizes as $value => $label)
                            <option value="{{ $value }}" {{ old('Tshirt_size') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">مقاس البنطال</label>
                    <select name="pants_size" class="form-select">
                        @foreach ($pantsSizes as $value => $label)
                            <option value="{{ $value }}" {{ old('pants_size') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">مقاس الحذاء</label>
                    <select name="Shoes_size" class="form-select">
                        @foreach ($shoesSizes as $value => $label)
                            <option value="{{ $value }}" {{ old('Shoes_size') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">نوع الشهادة</label>
                    <select name="certificate_type" class="form-select" required>
                        @foreach ($certificateTypes as $value => $label)
                            <option value="{{ $value }}" {{ old('certificate_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">مستوى اللغة الإنجليزية</label>
                    <select name="english_level" class="form-select" required>
                        @foreach ($englishLevels as $value => $label)
                            <option value="{{ $value }}" {{ old('english_level') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">الحالة الاجتماعية</label>
                    <select name="marital_status" class="form-select" required onchange="toggleMembersNumber(this.value)">
                        @foreach ($maritalStatuses as $value => $label)
                            <option value="{{ $value }}" {{ old('marital_status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4" id="members-number-container" style="display: none;">
                    <label class="form-label">عدد أفراد الأسرة</label>
                    <input type="number" name="members_number" class="form-control" min="0" value="{{ old('members_number') }}">
                </div>
            </div>

            <button type="submit" class="submit-btn w-100 mt-4">
                إرسال البيانات <i class="fas fa-paper-plane mr-2"></i>
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function calculateAge() {
            const birthdayInput = document.getElementById('birthday');
            const ageInput = document.getElementById('age');
            if (!birthdayInput.value) {
                ageInput.value = '';
                return;
            }
            const birthDate = new Date(birthdayInput.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            ageInput.value = age;
        }

        function toggleMembersNumber(status) {
            const container = document.getElementById('members-number-container');
            container.style.display = status !== 'single' ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById('birthday').value) calculateAge();
            toggleMembersNumber(document.querySelector('select[name="marital_status"]').value);
        });
    </script>
</body>

</html>
