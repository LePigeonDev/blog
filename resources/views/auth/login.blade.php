@extends('layouts.app')
@section('title','Connexion')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
  <div class="w-100" style="max-width: 460px;">
    <div class="hero p-4 p-md-5 mb-4 text-center">
      <h1 class="h4 fw-bold mb-1">Heureux de vous revoir</h1>
      <p class="text-secondary m-0">Connectez-vous pour commenter et gérer votre compte.</p>
    </div>

    <div class="card card-article p-4">
      <form method="POST" action="{{ route('login') }}" class="d-grid gap-3">
        @csrf

        <div>
          <label for="email" class="form-label">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}"
                 class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="username">
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div>
          <label for="password" class="form-label">Mot de passe</label>
          <input id="password" type="password" name="password"
                 class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">Se souvenir de moi</label>
          </div>

          @if (Route::has('password.request'))
            <a class="link-primary small text-decoration-none" href="{{ route('password.request') }}">
              Mot de passe oublié ?
            </a>
          @endif
        </div>

        <button class="btn btn-brand">Se connecter</button>

        <div class="text-center text-secondary">
          Pas de compte ?
          @if (Route::has('register'))
            <a class="link-primary text-decoration-none" href="{{ route('register') }}">Créer un compte</a>
          @endif
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
