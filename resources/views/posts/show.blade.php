@extends('layouts.app')
@section('title', $post->title)

@section('content')
<div class="container">
  {{-- Header visuel --}}
  <div class="mb-4 hero p-4 p-md-5">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <h1 class="fw-bold mb-2">{{ $post->title }}</h1>
        <div class="card-meta">
          {{ $post->published_at?->format('d/m/Y') }}
          • {{ $post->author->name ?? 'Auteur' }}
        </div>
        <div class="mt-2 d-flex flex-wrap gap-1">
          @foreach($post->categories as $c)
            <span class="badge badge-cat">{{ $c->name }}</span>
          @endforeach
          @foreach($post->tags as $t)
            <span class="badge badge-tag">#{{ $t->name }}</span>
          @endforeach
        </div>
      </div>
      <div class="col-lg-5">
        @if($post->featured_image)
          <img src="{{ $post->featured_image }}" class="img-fluid rounded-3 border" alt="{{ $post->title }}">
        @endif
      </div>
    </div>
  </div>

  {{-- Contenu --}}
  <article class="card card-article p-4 mb-4">
    <div class="card-excerpt">
      {!! $post->content !!}
    </div>

    {{-- Nav précédent/suivant --}}
    <div class="d-flex justify-content-between gap-2 mt-4">
      <div>
        @if($prev)
          <a class="btn btn-outline-secondary btn-sm" href="{{ route('posts.show',$prev) }}">&larr; {{ $prev->title }}</a>
        @endif
      </div>
      <div>
        @if($next)
          <a class="btn btn-outline-secondary btn-sm" href="{{ route('posts.show',$next) }}">{{ $next->title }} &rarr;</a>
        @endif
      </div>
    </div>

    {{-- Actions admin (publier/dépublier/supprimer) --}}
    @auth
      @if(auth()->user()->isAdmin())
        <div class="mt-4 d-flex flex-wrap gap-2">
          <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-soft btn-sm">Modifier</a>
          @if($post->status === 'published')
            <form method="POST" action="{{ route('admin.posts.unpublish',$post) }}" class="d-inline">
              @csrf <button class="btn btn-outline-secondary btn-sm">Dépublier</button>
            </form>
          @else
            <form method="POST" action="{{ route('admin.posts.publish',$post) }}" class="d-inline">
              @csrf <button class="btn btn-brand btn-sm">Publier</button>
            </form>
          @endif
          <form method="POST" action="{{ route('admin.posts.destroy',$post) }}" class="d-inline"
                onsubmit="return confirm('Supprimer définitivement cet article ?');">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm">Supprimer</button>
          </form>
        </div>
      @endif
    @endauth
  </article>

  {{-- Commentaires --}}
  <section id="comments" class="mb-5">
    <h2 class="h5 mb-3">Commentaires</h2>

    @guest
      {{-- Affichage des coms invités si activé (ton contrôleur gère) --}}
      @if(config('blog.guests_can_see_comments'))
        @forelse($comments as $comment)
          <div class="card card-article p-3 mb-3">
            <div class="card-meta mb-1">{{ $comment->user->name }} • {{ $comment->created_at->format('d/m/Y H:i') }}</div>
            <div>{{ $comment->body }}</div>
          </div>
        @empty
          <div class="text-secondary">Pas de commentaires.</div>
        @endforelse
      @endif

      <div class="mt-3 d-flex gap-2">
        <a class="btn btn-brand" href="{{ route('login') }}">Connectez-vous pour commenter</a>
        @if(Route::has('register'))
          <a class="btn btn-outline-secondary" href="{{ route('register') }}">Créer un compte</a>
        @endif
      </div>
    @else
      {{-- Formulaire ajout --}}
      <div class="card card-article p-3 mb-4">
        <form method="POST" action="{{ route('comments.store',$post) }}" class="d-grid gap-3">
          @csrf
          <textarea name="body" rows="6" maxlength="1000" class="form-control" placeholder="Votre commentaire... (max 1000 caractères)">{{ old('body') }}</textarea>
          @error('body') <div class="text-danger small">{{ $message }}</div> @enderror
          <div><button class="btn btn-brand">Publier</button></div>
        </form>
      </div>

      {{-- Liste commentaires (approuvés/filtrés côté contrôleur) --}}
      @forelse($comments as $comment)
        <div class="card card-article p-3 mb-3">
          <div class="d-flex justify-content-between align-items-start">
            <div class="card-meta">{{ $comment->user->name }} • {{ $comment->created_at->format('d/m/Y H:i') }}</div>
            @can('update',$comment)
              <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                <div class="dropdown-menu dropdown-menu-end">
                  <button class="dropdown-item" data-bs-toggle="collapse" data-bs-target="#edit-{{ $comment->id }}">Éditer</button>
                  <form method="POST" action="{{ route('comments.destroy',$comment) }}"
                        onsubmit="return confirm('Supprimer ce commentaire ?');">
                    @csrf @method('DELETE')
                    <button class="dropdown-item text-danger">Supprimer</button>
                  </form>
                </div>
              </div>
            @endcan
          </div>
          <div class="mt-2">{{ $comment->body }}</div>

          @can('update',$comment)
            <div id="edit-{{ $comment->id }}" class="collapse mt-3">
              <form method="POST" action="{{ route('comments.update',$comment) }}" class="d-grid gap-2">
                @csrf @method('PUT')
                <textarea name="body" rows="4" maxlength="1000" class="form-control">{{ old('body', $comment->body) }}</textarea>
                <button class="btn btn-soft btn-sm">Mettre à jour</button>
              </form>
            </div>
          @endcan
        </div>
      @empty
        <div class="text-secondary">Pas de commentaires.</div>
      @endforelse
    @endguest
  </section>
</div>
@endsection
