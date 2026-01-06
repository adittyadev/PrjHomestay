<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected $token;
    protected $baseUrl;

    public function __construct()
    {
        // 🔐 Token dari .env
        $this->token = env('WA_TOKEN');

        // 🌐 URL API Gateway (SESUIKAN DENGAN PROVIDER KAMU)
        $this->baseUrl = env('WA_BASE_URL');
    }

    /**
     * 📲 Kirim Pesan WhatsApp
     */
    public function sendMessage($phone, $message)
    {
        // FORMAT NOMOR
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        try {
            $response = Http::asForm() // ⬅️ PENTING
                ->withHeaders([
                    'Authorization' => $this->token
                ])
                ->post($this->baseUrl, [
                    'target'  => $phone,
                    'message' => $message,
                    'delay'   => 1
                ]);

            // LOG FULL RESPONSE
            Log::info('FONNTE RESPONSE', [
                'status' => $response->status(),
                'body'   => $response->body()
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('FONNTE ERROR', [
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}
