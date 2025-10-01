<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public function sendMessage(string $text): bool
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');
        if(!$token || !$chatId) return false;

        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $resp = Http::post($url, [
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'HTML',
        ]);

        return $resp->ok();
    }
}