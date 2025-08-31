<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنذار رسمي</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 25px;
            text-align: center;
            border-bottom: 4px solid #922b21;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .content {
            padding: 25px;
        }

        .alert-box {
            background-color: #fef2f2;
            border-left: 4px solid #e74c3c;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .alert-title {
            color: #c0392b;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #e0e0e0, transparent);
            margin: 25px 0;
        }

        .info-item {
            display: flex;
            margin-bottom: 12px;
        }

        .info-label {
            font-weight: 700;
            color: #7f8c8d;
            min-width: 120px;
        }

        .info-value {
            color: #2c3e50;
        }

        .whatsapp-btn {
            display: inline-block;
            background-color: #25D366;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .whatsapp-btn:hover {
            background-color: #128C7E;
            transform: translateY(-2px);
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
            border-top: 1px solid #e0e0e0;
        }

        .company-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 18px;
        }

        .system-name {
            color: #0f1010;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>🔔 إنذار رسمي</h1>
            <div class="company-name">{{ explode('-', $messageData['company_name'], 2)[0] }}</div>
            @if (isset(explode('-', $messageData['company_name'], 2)[1]))
                <div class="system-name">{{ explode('-', $messageData['company_name'], 2)[1] }}</div>
            @endif
        </div>

        <div class="content">
            <p>السيد/ة <strong>{{ $messageData['employee_name'] }}</strong>,</p>

            <div class="alert-box">
                <div class="alert-title">⚠️ {{ $messageData['alert_title'] }}</div>
                <p>{{ $messageData['alert_message'] }}</p>
            </div>

            <div class="divider"></div>

            <div class="info-item">
                <span class="info-label">المدير المسؤول:</span>
                <span class="info-value">{{ $messageData['manager_name'] }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">تاريخ الإنذار:</span>
                <span class="info-value">{{ now()->format('Y-m-d') }}</span>
            </div>

            @if (isset($messageData['manager_whatsapp']))
                <div class="divider"></div>
                <p>يمكنك التواصل مع المدير للاستفسار أو المناقشة:</p>
                @php
                    $number = ltrim($messageData['manager_whatsapp'], '0'); // remove leading 0
                    $formattedNumber = '966' . $number;
                @endphp

                <a href="https://wa.me/{{ $formattedNumber }}" class="whatsapp-btn">
                    📲 تواصل عبر واتساب
                </a>
            @endif
        </div>

        <div class="footer">
            <p>هذه رسالة آلية، يرجى عدم الرد على هذا البريد الإلكتروني</p>
            <p>© {{ now()->format('Y') }} {{ explode('-', $messageData['company_name'], 2)[0] }}. جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>

</html>
