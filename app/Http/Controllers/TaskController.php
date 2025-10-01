<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Task::with('category')
            ->where('user_id', auth()->id());

        // Filter: status, category, search, due_date range
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($x) use ($q){
                $x->where('title','like',"%$q%")
                ->orWhere('description','like',"%$q%");
            });
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('due_date', [$request->from, $request->to]);
        }

        $tasks = $query->latest()->paginate(10)->withQueryString();
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('tasks.index', compact('tasks','categories'));
    }

    public function create()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'status'=>'required|in:todo,in_progress,done',
            'category_id'=>'nullable|exists:categories,id',
            'due_date'=>'nullable|date',
        ]);
        $data['user_id'] = auth()->id();

        $task = \App\Models\Task::create($data);

        // Kirim notifikasi (email & Telegram)
        // auth()->user()->notify(new \App\Notifications\TaskCreatedNotification($task));
        // app(\App\Notifications\TaskCreatedNotification::class, ['task'=>$task])->sendTelegram();

        // setelah create task
        $notif = new \App\Notifications\TaskCreatedNotification($task);
        auth()->user()->notify($notif);

        // kirim Telegram (opsional)
        $chatId = auth()->user()->telegram_chat_id ?? \Config('telegram.chat_id'); // kalau kamu simpan per user
        app(\App\Services\TelegramService::class)->sendMessage(
            "📝 Task baru: {$task->title}\nDue: ".($task->due_date ? $task->due_date->format('d M Y') : '-'),
            $chatId // boleh null: fallback pakai TELEGRAM_CHAT_ID dari .env
        );

        return redirect()->route('tasks.index')->with('success','Task created.');
    }

    public function edit(Task $task)
    {
        $this->authorizeTask($task);
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('tasks.edit', compact('task','categories'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeTask($task);
        $data = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'status'=>'required|in:todo,in_progress,done',
            'category_id'=>'nullable|exists:categories,id',
            'due_date'=>'nullable|date',
        ]);

        $task->update($data);

        return redirect()->route('tasks.index')->with('success','Task updated.');
    }

    public function destroy(Task $task)
    {
        $this->authorizeTask($task);
        $task->delete();
        return back()->with('success','Task deleted.');
    }

    protected function authorizeTask(Task $task)
    {
        abort_unless($task->user_id === auth()->id(), 403);
    }
}
