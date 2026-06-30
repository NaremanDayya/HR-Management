<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تعديل البيانات البنكية | {{ $employee->name }}</title>

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
            max-width: 700px;
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
        .bank-warning {
            background: #fff8e1;
            border: 1px solid #f0c14b;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 0.85rem;
            color: #6b5200;
            margin-bottom: 15px;
        }
        .submit-btn {
            background-color: #740e0e;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 14px;
            font-weight: 600;
            width: 100%;
        }
        .submit-btn:hover { background-color: #5d0a0a; color: #fff; }
    </style>
</head>

<body>
    <div class="form-card">
        <div class="text-center mb-4">
            <h1 class="form-title">نموذج تعديل البيانات البنكية</h1>
            <p class="text-muted mb-0">المشروع: {{ $employee->project?->name ?? 'غير محدد' }}</p>
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

        <form method="POST" action="{{ route('public.bank-update.store', $employee->id) }}" enctype="multipart/form-data">
            @csrf

            <h5 class="section-title">البيانات الشخصية</h5>
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">الاسم الثلاثي</label>
                    <input type="text" name="full_name" class="form-control"
                        value="{{ old('full_name', $employee->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">حالة الموظف</label>
                    <select name="account_status" class="form-select" required>
                        <option value="">اختر...</option>
                        <option value="active" {{ old('account_status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ old('account_status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">رقم الهوية</label>
                    <input type="text" name="id_card_number" class="form-control"
                        value="{{ old('id_card_number') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">رقم الجوال</label>
                    <input type="tel" name="mobile_number" class="form-control"
                        value="{{ old('mobile_number') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">المدينة</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                </div>
            </div>

            <h5 class="section-title">البيانات البنكية</h5>
            <div class="bank-warning">
                يجب أن يكون الحساب في <strong>البنك الأهلي</strong>. في حال التسجيل في بنك آخر سيتم خصم رسوم تحويل
                قدرها <strong>5.25 ريال</strong> من الراتب. يرجى تجنب التسجيل في (بنك الإنماء - البنك العربي - بنك
                البلاد - STC Bank).
            </div>
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">اسم البنك</label>
                    <input type="text" name="new_bank_name" class="form-control" value="{{ old('new_bank_name') }}" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">رقم الحساب البنكي (الآيبان المكون من 22 رقم ويبدأ بـ SA)</label>
                    <input type="text" name="new_iban" class="form-control" value="{{ old('new_iban') }}" required
                        placeholder="SA0000000000000000000000" maxlength="34">
                </div>
                <div class="col-md-12">
                    <label class="form-label">اسم صاحب الحساب</label>
                    <input type="text" name="new_owner_account_name" class="form-control"
                        value="{{ old('new_owner_account_name') }}" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">صورة الهوية الواضحة</label>
                    <input type="file" name="id_card_images[]" class="form-control" accept="image/*" multiple required>
                    <small class="text-muted">يمكن رفع حتى 5 صور، بحد أقصى 4 ميجابايت لكل صورة.</small>
                </div>
                <div class="col-md-12">
                    <label class="form-label">ملاحظات (اختياري)</label>
                    <textarea name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="submit-btn">إرسال الطلب</button>
            </div>
        </form>
    </div>
</body>

</html>
