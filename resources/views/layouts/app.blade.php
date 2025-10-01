<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name','LaravelTasks') }}</title>

  {{-- Bootswatch Theme (Minty) --}}
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootswatch@4.6.2/dist/minty/bootstrap.min.css">

  {{-- Optional: Icons --}}
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  @stack('styles')
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="{{ route('tasks.index') }}">
        <i class="bi bi-check2-square"></i> TaskManager
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div id="nav" class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
          @auth
            <li class="nav-item"><a class="nav-link" href="{{ route('tasks.index') }}">Tasks</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">Categories</a></li>
          @endauth
        </ul>
        <ul class="navbar-nav ml-auto">
          @guest
            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
          @else
            <li class="nav-item">
              <span class="nav-link">Hi, {{ Auth::user()->name }}</span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('logout') }}"
                 onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                Logout
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>

  <main class="py-4">
    <div class="container">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
      @endif
      @yield('content')
    </div>
  </main>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>