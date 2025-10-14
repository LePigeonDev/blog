@extends('layouts.app')
@section('title','Contact')

@section('content')
<div class="container">
  <div class="hero p-4 p-md-5 mb-4">
    <h1 class="fw-bold mb-2">Nous contacter</h1>
    <p class="text-secondary m-0">Une question, une remarque ? Écrivez-nous.</p>
  </div>

  <div class="card card-article p-4">
    <form method="POST" action="{{ route('contact.send') }}" class="row g-3">
      @csrf
      <div class="col-md-6">
        <label class="form-label">Nom</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-12">
        <label class="form-label">Sujet (optionnel)</label>
        <input type="text" name="subject" class="form-control" value="{{ old('subject') }}">
        @error('subject') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-12">
        <label class="form-label">Message</label>
        <textarea name="message" rows="6" class="form-control" required>{{ old('message') }}</textarea>
        @error('message') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-12">
        <button class="btn btn-brand">Envoyer</button>
      </div>
    </form>
  </div>
</div>
@endsection
