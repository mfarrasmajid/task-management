<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendDueTasksReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        $tasks = \App\Models\Task::with('user')
            ->whereIn('due_date', [$today, $tomorrow])
            ->where('status','!=','done')
            ->get();

        $count = 0;
        foreach ($tasks as $task) {
            // Email
            $task->user->notify(new \App\Notifications\TaskCreatedNotification($task));
            // Telegram
            app(\App\Services\TelegramService::class)->sendMessage(
                "⏰ Reminder: <b>{$task->title}</b>\nDue: ".$task->due_date->format('d M Y')
            );
            $count++;
        }

        $this->info("Sent reminders for {$count} tasks.");
    }
}
