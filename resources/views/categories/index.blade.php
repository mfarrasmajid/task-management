@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Categories</h3>
  <a href="{{ route('categories.create') }}" class="btn btn-success">
    <i class="bi bi-plus-lg"></i> New Category
  </a>
</div>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table mb-0 table-hover">
      <thead class="thead-light">
        <tr>
          <th style="width:60%">Name</th>
          <th style="width:40%" class="text-right">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $cat)
          <tr>
            <td><strong>{{ $cat->name }}</strong></td>
            <td class="text-right">
              <a href="{{ route('categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Delete this category?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="2" class="text-center text-muted">No categories yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($categories->hasPages())
    <div class="card-footer">{{ $categories->links() }}</div>
  @endif
</div>
@endsection