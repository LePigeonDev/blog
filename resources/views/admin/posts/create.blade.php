@extends('layouts.app')
@section('title','Ajouter un article')

@section('content')
<div class="container">
  <div class="d-flex align-items-end justify-content-between mb-3">
    <h1 class="h4 m-0">Ajouter un article</h1>
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">Retour</a>
  </div>

  <div class="card card-article p-4">
    <form method="POST" action="{{ route('admin.posts.store') }}" class="row g-3">
      @csrf
      <div class="col-md-8">
        <label class="form-label">Titre</label>
        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
        @error('title') <div class="text-danger small">{{ $message }}</div> @enderror>
      </div>
      <div class="col-md-4">
        <label class="form-label">Slug (optionnel)</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
        @error('slug') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-12">
        <label class="form-label">Extrait</label>
        <textarea name="excerpt" rows="2" class="form-control">{{ old('excerpt') }}</textarea>
        @error('excerpt') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-12">
        <label class="form-label">Image à la une (URL)</label>
        <input type="url" name="featured_image" class="form-control" value="{{ old('featured_image') }}">
        @error('featured_image') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-12">
        <label class="form-label">Contenu</label>
        <textarea name="content" rows="10" class="form-control" required>{{ old('content') }}</textarea>
        @error('content') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-3">
        <label class="form-label">Statut</label>
        <select name="status" class="form-select" required>
          <option value="draft" {{ old('status')==='draft'?'selected':'' }}>Brouillon</option>
          <option value="published" {{ old('status')==='published'?'selected':'' }}>Publié</option>
        </select>
        @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-3">
        <label class="form-label">Date de publication</label>
        <input type="date" name="published_at" class="form-control" value="{{ old('published_at') }}" required>
        @error('published_at') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Catégories</label>
        <div class="d-flex flex-wrap gap-2">
          @foreach($categories as $c)
            <label class="form-check">
              <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $c->id }}">
              <span class="form-check-label">{{ $c->name }}</span>
            </label>
          @endforeach
        </div>
        @error('categories') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-12">
        <label class="form-label">Tags</label>
        <div class="d-flex flex-wrap gap-2">
          @foreach($tags as $t)
            <label class="form-check">
              <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $t->id }}">
              <span class="form-check-label">#{{ $t->name }}</span>
            </label>
          @endforeach
        </div>
        @error('tags') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="col-12">
        <button class="btn btn-brand">Créer</button>
      </div>
    </form>
  </div>
</div>
@endsection
