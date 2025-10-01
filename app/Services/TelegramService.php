<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class TelegramService
{
    public function sendMessage($text, $chatId = null)
    {
        $token  = config('services.telegram.bot_token');
        $chatId = $chatId ?: config('services.telegram.chat_id');

        if (!$token || !$chatId) {
            Log::warning('Telegram: token/chat_id kosong', compact('token','chatId'));
            return ['ok' => false, 'reason' => 'missing_token_or_chat_id'];
        }

        $verify = filter_var(env('HTTP_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN);
        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        try {
            $resp = Http::withOptions([
                'timeout' => 15,
                'verify'  => $verify, // DEV bisa false, PROD wajib true atau file CA
            ])->post($url, [
                'chat_id'    => $chatId,
                'text'       => $text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            if ($resp->ok()) {
                return ['ok' => true, 'status' => $resp->status(), 'body' => $resp->json()];
            }

            Log::error('Telegram gagal', [
                'status' => $resp->status(),
                'body'   => $resp->body(),
            ]);

            return ['ok' => false, 'status' => $resp->status(), 'body' => $resp->body()];
        } catch (\Throwable $e) {
            Log::error('Telegram exception', ['msg' => $e->getMessage()]);
            return ['ok' => false, 'exception' => $e->getMessage()];
        }
    }
}