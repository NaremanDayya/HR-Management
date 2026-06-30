<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم إرسال الطلب بنجاح</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #F5F7FA;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .success-card {
            max-width: 500px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 50px 40px;
            text-align: center;
        }
        .success-icon { color: #28a745; font-size: 4rem; margin-bottom: 20px; }
    </style>
</head>

<body>
    <div class="container">
        <div class="success-card">
            <div class="success-icon"><i class="fas fa-check-circle"></i></div>
            <h2>تم إرسال طلبك بنجاح</h2>
            <p class="text-muted">
                تم استلام طلب تعديل البيانات البنكية للموظف "{{ $employee->name }}" وستتم مراجعته من قبل فريق
                الموارد البشرية قريبًا. سيتم تحديث بياناتك بعد الموافقة عليها.
            </p>
        </div>
    </div>
</body>

</html>
