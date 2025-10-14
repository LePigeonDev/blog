<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>@yield('title','Blog')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
<header>
  <nav class="container mx-auto py-4 flex gap-6">
    <a href="{{ route('home') }}">Accueil</a>
    <a href="{{ route('contact') }}">Contact</a>
    @auth
      <a href="{{ route('account') }}">Mon compte</a>
      <form method="POST" action="{{ route('logout') }}" class="inline">
        @csrf <button type="submit">Déconnexion</button>
      </form>
    @else
      <a href="{{ route('login') }}">Connexion</a>
    @endauth
  </nav>
</header>

<main class="container mx-auto py-8">
  @if(session('success')) <div class="bg-green-100 p-3">{{ session('success') }}</div> @endif
  @yield('content')
</main>

<footer class="container mx-auto py-6">© {{ date('Y') }}</footer>
</body>
</html>
