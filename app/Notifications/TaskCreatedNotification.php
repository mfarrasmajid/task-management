<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Http;

class TaskCreatedNotification extends Notification
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task; // <-- assign manual (tanpa property promotion)
    }

    public function via($notifiable)
    {
        return ['database']; // simpan ke DB juga (opsional)
    }

    public function toMail($notifiable)
    {
        // return (new MailMessage)
        //     ->subject('New Task: '.$this->task->title)
        //     ->greeting('Hello '.$notifiable->name.' 👋')
        //     ->line('A new task has been created.')
        //     ->line('Title: '.$this->task->title)
        //     ->line('Due: '.optional($this->task->due_date)->format('d M Y') ?? '-')
        //     ->action('Open Task', url(route('tasks.edit', $this->task)))
        //     ->line('Thank you!');
        return 1;
    }

    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title'   => $this->task->title,
            'due'     => optional($this->task->due_date)->toDateString(),
        ];
    }

    // Kirim Telegram segera setelah notifikasi dibuat
    public function sendMessage($text, $chatId = null)
    {
        $token  = config('services.telegram.bot_token');
        $chatId = $chatId ?: config('services.telegram.chat_id');

        if (!$token || !$chatId) {
            return false;
        }

        $url  = "https://api.telegram.org/bot{$token}/sendMessage";
        $http = Http::withOptions(['timeout' => 10]);
        if (app()->environment('local')) {
            $http = $http->withoutVerifying(); // <- bypass verifikasi SSL (dev only)
        }
        $resp = $http->post($url, [
            'chat_id'    => $chatId,
            'text'       => $text,
            'parse_mode' => 'HTML',
        ]);

        return $resp->ok();
    }

    public function afterCommit(): void
    {
        // Laravel 8 tidak punya hook ini di Notification; panggil manual di Controller setelah notify()
    }
}
