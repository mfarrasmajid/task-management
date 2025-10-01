<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;
use App\Services\TelegramService;

class TaskCreatedNotification extends Notification
{
     use Queueable;

    public function __construct(public Task $task){}

    public function via($notifiable)
    {
        return ['mail', 'database']; // simpan ke DB juga (opsional)
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
    public function sendTelegram()
    {
        app(TelegramService::class)->sendMessage(
            "<b>New Task</b>\n".
            "Title: {$this->task->title}\n".
            "Due: ".($this->task->due_date? $this->task->due_date->format('d M Y'):'-')
        );
    }

    public function afterCommit(): void
    {
        // Laravel 8 tidak punya hook ini di Notification; panggil manual di Controller setelah notify()
    }
}
