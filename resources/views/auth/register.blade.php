@extends('layouts.app')
@section('title','Créer un compte')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
  <div class="w-100" style="max-width: 640px;">
    <div class="hero p-4 p-md-5 mb-4 text-center">
      <h1 class="h4 fw-bold mb-1">Bienvenue !</h1>
      <p class="text-secondary m-0">Créez votre compte pour commenter et personnaliser l’accueil.</p>
    </div>

    <div class="card card-article p-4">
      <form method="POST" action="{{ route('register') }}" class="row g-3">
        @csrf

        <div class="col-md-6">
          <label for="name" class="form-label">Nom</label>
          <input id="name" type="text" name="name" value="{{ old('name') }}"
                 class="form-control @error('name') is-invalid @enderror" required autocomplete="name">
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="email" class="form-label">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}"
                 class="form-control @error('email') is-invalid @enderror" required autocomplete="username">
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="password" class="form-label">Mot de passe</label>
          <input id="password" type="password" name="password"
                 class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="password_confirmation" class="form-label">Confirmation</label>
          <input id="password_confirmation" type="password" name="password_confirmation"
                 class="form-control @error('password_confirmation') is-invalid @enderror" required autocomplete="new-password">
          @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Catégories préférées si fournies par le contrôleur --}}
        @isset($categories)
        <div class="col-12">
          <label class="form-label">Catégories préférées (facultatif)</label>
          <div class="d-flex flex-wrap gap-2">
            @foreach($categories as $c)
              <label class="form-check">
                <input class="form-check-input" type="checkbox" name="preferred_categories[]" value="{{ $c->id }}"
                       {{ in_array($c->id, old('preferred_categories', [])) ? 'checked' : '' }}>
                <span class="form-check-label">{{ $c->name }}</span>
              </label>
            @endforeach
          </div>
          <div class="form-text">Elles serviront à personnaliser la page d’accueil.</div>
          @error('preferred_categories') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        @endisset

        <div class="col-12 d-grid gap-2">
          <button class="btn btn-brand">Créer mon compte</button>
          <div class="text-center text-secondary">
            Déjà inscrit ? <a class="link-primary text-decoration-none" href="{{ route('login') }}">Se connecter</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
