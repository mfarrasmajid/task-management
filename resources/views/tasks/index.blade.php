@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">My Tasks</h3>
  <a href="{{ route('tasks.create') }}" class="btn btn-success">
    <i class="bi bi-plus-lg"></i> New Task
  </a>
</div>

<form class="card card-body mb-3">
  <div class="form-row">
    <div class="col-md-3">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search title/desc">
    </div>
    <div class="col-md-2">
      <select name="status" class="form-control">
        <option value="">-- Status --</option>
        @foreach(['todo'=>'To Do','in_progress'=>'In Progress','done'=>'Done'] as $k=>$v)
          <option value="{{ $k }}" @selected(request('status')==$k)>{{ $v }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <select name="category_id" class="form-control">
        <option value="">-- Category --</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <input type="date" name="from" class="form-control" value="{{ request('from') }}" placeholder="From">
    </div>
    <div class="col-md-2">
      <input type="date" name="to" class="form-control" value="{{ request('to') }}" placeholder="To">
    </div>
  </div>
  <div class="mt-2">
    <button class="btn btn-primary btn-sm"><i class="bi bi-funnel"></i> Filter</button>
    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
  </div>
</form>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead class="thead-light">
        <tr>
          <th>Title</th>
          <th>Category</th>
          <th>Due</th>
          <th>Status</th>
          <th class="text-right">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tasks as $t)
          <tr @class(['table-success'=> $t->status==='done'])>
            <td>
              <strong>{{ $t->title }}</strong>
              @if($t->description)
                <div class="text-muted small">{{ Str::limit($t->description, 80) }}</div>
              @endif
            </td>
            <td>{{ optional($t->category)->name ?? '-' }}</td>
            <td>{{ $t->due_date? $t->due_date->format('d M Y'):'-' }}</td>
            <td>
              @php
                $badge = ['todo'=>'secondary','in_progress'=>'info','done'=>'success'][$t->status] ?? 'light';
              @endphp
              <span class="badge badge-{{ $badge }}">{{ Str::headline($t->status) }}</span>
            </td>
            <td class="text-right">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('tasks.edit',$t) }}">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('tasks.destroy',$t) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Delete this task?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted">No tasks found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">{{ $tasks->links() }}</div>
</div>
@endsection