@extends('layouts.app')
@section('content')
<div class="card">
  <div class="card-header"><h5 class="mb-0">Create Task</h5></div>
  <div class="card-body">
    <form action="{{ route('tasks.store') }}" method="POST">
      @include('tasks._form')
    </form>
  </div>
</div>
@endsection