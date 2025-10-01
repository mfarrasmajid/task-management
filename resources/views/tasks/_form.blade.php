@csrf
<div class="form-group">
  <label>Title</label>
  <input type="text" name="title" class="form-control" value="{{ old('title', $task->title ?? '') }}" required>
</div>
<div class="form-group">
  <label>Description</label>
  <textarea name="description" rows="3" class="form-control">{{ old('description', $task->description ?? '') }}</textarea>
</div>
<div class="form-row">
  <div class="form-group col-md-4">
    <label>Status</label>
    <select name="status" class="form-control" required>
      @foreach(['todo'=>'To Do','in_progress'=>'In Progress','done'=>'Done'] as $k => $v)
        <option value="{{ $k }}" {{ old('status', $task->status ?? 'todo') == $k ? 'selected' : '' }}>
          {{ $v }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="form-group col-md-4">
    <label>Category</label>
    <select name="category_id" class="form-control">
      <option value="">-- None --</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" {{ old('category_id', $task->category_id ?? '') == $c->id ? 'selected' : '' }}>
          {{ $c->name }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="form-group col-md-4">
    <label>Due Date</label>
    <input type="date" name="due_date" class="form-control"
           value="{{ old('due_date', optional($task->due_date ?? null)->toDateString()) }}">
  </div>
</div>
<button class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
<a href="{{ route('tasks.index') }}" class="btn btn-light">Cancel</a>