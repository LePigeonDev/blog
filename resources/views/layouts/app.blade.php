<!doctype html>
<html lang="fr" data-bs-theme="dark"> {{-- 👈 active le mode sombre Bootstrap --}}
<head>
  <meta charset="utf-8">
  <title>@yield('title','Blog')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Bootstrap (CDN) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Thème sombre séparé --}}
  <link href="{{ asset('css/theme-dark.css') }}" rel="stylesheet">

  @vite(['resources/js/app.js']) {{-- si nécessaire pour JS --}}
</head>
<body>

<header class="nav-glass">
  <nav class="container navbar navbar-expand-lg py-2">
    <a class="navbar-brand fw-semibold" href="{{ route('home') }}">Blog</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navMain" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('home')?'active':'' }}" href="{{ route('home') }}">Accueil</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact')?'active':'' }}" href="{{ route('contact') }}">Contact</a></li>
        @auth
          @if(auth()->user()->isAdmin())
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.posts.index')?'active':'' }}" href="{{ route('admin.posts.index') }}">Articles</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.posts.drafts')?'active':'' }}" href="{{ route('admin.posts.drafts') }}">Brouillons</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.comments.index')?'active':'' }}" href="{{ route('admin.comments.index') }}">Modération</a></li>
          @endif
        @endauth
      </ul>

      <div class="d-flex gap-2">
        @auth
          <a href="{{ route('account') }}" class="btn btn-outline-secondary btn-sm">Mon compte</a>
          <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf <button type="submit" class="btn btn-link btn-sm text-decoration-none">Déconnexion</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn btn-brand btn-sm">Connexion</a>
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-sm">Créer un compte</a>
          @endif
        @endauth
      </div>
    </div>
  </nav>
</header>

<main class="container my-4">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @yield('content')
</main>

<footer class="site-footer py-4 mt-auto">
  <div class="container d-flex justify-content-between align-items-center">
    <small>© {{ date('Y') }} — Ton blog</small>
    <small class="text-muted">Laravel • Bootstrap • Dark theme</small>
  </div>
</footer>

<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
