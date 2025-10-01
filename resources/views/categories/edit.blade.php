@extends('layouts.app')

@section('content')
<div class="card">
  <div class="card-header"><h5 class="mb-0">Edit Category</h5></div>
  <div class="card-body">
    <form action="{{ route('categories.update', $category) }}" method="POST">
      @csrf @method('PUT')
      <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $category->name) }}" required>
        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <button class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
      <a href="{{ route('categories.index') }}" class="btn btn-light">Cancel</a>
    </form>
  </div>
</div>
@endsection