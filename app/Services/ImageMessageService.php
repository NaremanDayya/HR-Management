<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;


class ImageMessageService
{
    protected $chromePaths = [
        '/usr/bin/google-chrome',
        '/usr/bin/chromium',
        '/usr/bin/chromium-browser',
        '/usr/bin/google-chrome-stable'
    ];

    public function generateAlertImage(array $messageData): string
    {
        try {
            $html = $this->generateAlertHtml($messageData);

            // Ensure directory exists - modified for Windows compatibility
            $directory = storage_path('app/public/alerts');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $baseName = 'alert_' . now()->format('Ymd_His');
            $htmlPath = $directory . DIRECTORY_SEPARATOR . $baseName . '.html';

            // Write file with error handling
            if (file_put_contents($htmlPath, $html) === false) {
                throw new \Exception("Failed to write HTML file to: " . $htmlPath);
            }

            $chromePath = $this->findChromePath();
            if (!$chromePath) {
                throw new \Exception("Chrome or Chromium browser not found");
            }

            $imagePath = str_replace('.html', '.png', $htmlPath);
            $command = sprintf(
                '%s --headless --disable-gpu --screenshot="%s" --window-size=600,800 --timeout=30000 "file://%s"',
                escapeshellarg($chromePath),
                escapeshellarg($imagePath),
                escapeshellarg($htmlPath)
            );

            $output = shell_exec($command . ' 2>&1');

            if (!file_exists($imagePath)) {
                throw new \Exception("Image generation failed. Command: $command. Output: " . $output);
            }

            // Optionally delete the HTML file after use
            @unlink($htmlPath);

            return Storage::url('alerts/' . basename($imagePath));
        } catch (\Exception $e) {
            Log::error('Image generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    protected function findChromePath(): ?string
    {
        foreach ($this->chromePaths as $path) {
            if (is_executable($path)) {
                return $path;
            }
        }
        return null;
    }


    protected function generateAlertHtml(array $messageData): string
    {
        $companyParts = explode('-', $messageData['company_name'], 2);
        $companyName = htmlspecialchars($companyParts[0] ?? '', ENT_QUOTES, 'UTF-8');
        $systemName = isset($companyParts[1]) ? htmlspecialchars($companyParts[1], ENT_QUOTES, 'UTF-8') : '';

        $employeeName = htmlspecialchars($messageData['employee_name'] ?? '', ENT_QUOTES, 'UTF-8');
        $managerName = htmlspecialchars($messageData['manager_name'] ?? '', ENT_QUOTES, 'UTF-8');
        $alertTitle = htmlspecialchars($messageData['alert_title'] ?? '', ENT_QUOTES, 'UTF-8');
        $alertMessage = nl2br(htmlspecialchars($messageData['alert_message'] ?? '', ENT_QUOTES, 'UTF-8'));
        $alertDate = htmlspecialchars($messageData['alert_date'] ?? now()->format('Y-m-d'), ENT_QUOTES, 'UTF-8');
        $managerWhatsapp = isset($messageData['manager_whatsapp']) ?
            htmlspecialchars($messageData['manager_whatsapp'], ENT_QUOTES, 'UTF-8') :
            'غير متوفر';

        return <<<HTML
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

        .message-container {
            width: 600px;
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

        .company-name {
            font-weight: 700;
            color: white;
            font-size: 18px;
            margin-top: 10px;
        }

        .system-name {
            color: #f0f0f0;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <div class="header">
            <h1>🔔 إنذار رسمي</h1>
            <div class="company-name">{$companyName}</div>
            <div class="system-name">{$systemName}</div>
        </div>

        <div class="content">
            <p>السيد/ة <strong>{$employeeName}</strong>,</p>

            <div class="alert-box">
                <div class="alert-title">⚠️ {$alertTitle}</div>
                <p>{$alertMessage}</p>
            </div>

            <div class="divider"></div>

            <div class="info-item">
                <span class="info-label">المدير المسؤول:</span>
                <span class="info-value">{$managerName}</span>
            </div>

            <div class="info-item">
                <span class="info-label">تاريخ الإنذار:</span>
                <span class="info-value">{$alertDate}</span>
            </div>

            <div class="divider"></div>

            <p>يمكنك التواصل مع المدير للاستفسار أو المناقشة:</p>
            <div class="info-item">
                <span class="info-label">واتساب:</span>
                <span class="info-value">{$managerWhatsapp}</span>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
