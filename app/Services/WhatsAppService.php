<?php

namespace App\Services;

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $client;
    protected $defaultFrom;

    public function __construct()
    {
        $this->client = new Client(
            env('TWILIO_SID'),
            env('TWILIO_TOKEN')
        );
        $this->defaultFrom = env('TWILIO_WHATSAPP_FROM', 'whatsapp:+14155238886'); // Default to sandbox
    }

    public function send($to, $message, $from = null): array
    {
        try {
            // Format numbers
            $from = $from ? $this->formatNumber($from, true) : $this->defaultFrom;
            $to = $this->formatNumber($to);

            // Validate message length
            if (strlen($message) > 4096) {
                throw new \Exception('Message exceeds WhatsApp 4096 character limit');
            }

            $response = $this->client->messages->create($to, [
                'from' => $from,
                'body' => $message
            ]);

            return [
                'success' => true,
                'message_sid' => $response->sid,
                'status' => $response->status
            ];
        } catch (TwilioException $e) {
            Log::error('Twilio API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Twilio service error',
                'details' => $e->getMessage()
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Send Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Message processing error',
                'details' => $e->getMessage()
            ];
        }
    }

    protected function formatNumber($number, $isFrom = false): string
    {
        // If already formatted and it's not a from number
        if (str_starts_with($number, 'whatsapp:') && !$isFrom) {
            return $number;
        }

        // Clean the number
        $number = preg_replace('/[^\d+]/', '', $number);

        // Add country code if missing
        if (!str_starts_with($number, '+')) {
            $defaultCode = env('DEFAULT_COUNTRY_CODE', '+970');
            $number = $defaultCode . ltrim($number, '0');
        }

        // Add whatsapp: prefix if not a from number
        return $isFrom ? $number : 'whatsapp:' . $number;
    }

    public function formatAlertMessage(array $data): string
    {
        $companyParts = explode('-', $data['company_name'], 2);
        $companyName = $companyParts[0];
        $systemName = isset($companyParts[1]) ? "\n" . $companyParts[1] : '';
        $managerWhatsapp = isset($data['manager_whatsapp']) ?
            $this->formatWhatsAppUrl($data['manager_whatsapp']) :
            '';

        $whatsappAction = $managerWhatsapp ?
            "\n\n📍 *للرد مباشرة:*\n📲 [تواصل مع المدير على واتساب]($managerWhatsapp)" :
            '';

        return "🔔 *تنبيه رسمي من {$companyName}*{$systemName}\n" .
            "━━━━━━━━━━━━━━━━━━━━\n" .
            "👨‍💼 *المدير المسؤول:* {$data['manager_name']}\n" .
            "👤 *الموظف:* {$data['employee_name']}\n" .
            "⚠️ *نوع الإنذار:* {$data['alert_title']}\n" .
            "━━━━━━━━━━━━━━━━━━━━\n" .
            "*📜 تفاصيل الإنذار:*\n{$data['alert_message']}\n" .
            "━━━━━━━━━━━━━━━━━━━━\n" .
            "*🗓 تاريخ الإنذار:* " . now()->format('Y-m-d') . "\n" .
            "━━━━━━━━━━━━━━━━━━━━\n" .
            $whatsappAction;
    }
    public function sendImage($to, $mediaUrl, $caption = null, $from = null): array
{
    try {
        $from = $from ? $this->formatNumber($from, true) : $this->defaultFrom;
        $to = $this->formatNumber($to);

        $payload = [
            'from' => $from,
            'mediaUrl' => $mediaUrl,
        ];

        if ($caption) {
            $payload['body'] = $caption;
        }

        $response = $this->client->messages->create($to, $payload);

        return [
            'success' => true,
            'message_sid' => $response->sid,
            'status' => $response->status
        ];
    } catch (TwilioException $e) {
        Log::error('Twilio Media Error: ' . $e->getMessage());
        return [
            'success' => false,
            'error' => 'Twilio media send failed',
            'details' => $e->getMessage()
        ];
    } catch (\Exception $e) {
        Log::error('WhatsApp Media Send Error: ' . $e->getMessage());
        return [
            'success' => false,
            'error' => 'Media message failed',
            'details' => $e->getMessage()
        ];
    }
}
    protected function formatWhatsAppUrl($phoneNumber): string
    {
        // Clean and format number
        $cleanNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Ensure it starts with country code (default to Saudi +966)
        if (!str_starts_with($cleanNumber, '970')) {
            $cleanNumber = '970' . ltrim($cleanNumber, '0');
        }

        return "https://wa.me/$cleanNumber";
    }
}
