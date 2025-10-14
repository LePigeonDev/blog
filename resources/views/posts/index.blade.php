@extends('layouts.app')
@section('title','Accueil')

@section('content')
  {{-- HERO --}}
  <section class="hero p-4 p-md-5 mb-4">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <h1 class="display-6 fw-bold mb-2">
          Les derniers <span class="text-gradient">articles</span>
        </h1>
        <p class="lead text-secondary mb-3">
          Explore nos publications publiées récemment.
        </p>
        <div class="d-flex gap-2">
          <a href="#feed" class="btn btn-brand">Voir le flux</a>
          @auth
            @if(auth()->user()->isAdmin())
              <a href="{{ route('admin.posts.create') }}" class="btn btn-soft">Ajouter un article</a>
            @endif
          @endauth
        </div>
      </div>
      <div class="col-lg-5">
        {{-- tu peux mettre une image statique ici si tu veux, sinon laisse vide --}}
      </div>
    </div>
  </section>

  {{-- FEED --}}
  <section id="feed">
    @if($posts->count())
      <div class="row g-4">
        @foreach($posts as $post)
          <div class="col-12 col-sm-6 col-lg-4">
            <article class="card card-article h-100">
              @if($post->featured_image)
                <img class="img-16x9 card-img-top" src="{{ $post->featured_image }}" alt="{{ $post->title }}">
              @endif

              <div class="card-body d-flex flex-column">
                <div class="card-meta mb-1">
                  {{ $post->published_at?->format('d/m/Y') }}
                  @if($post->author) • {{ $post->author->name }} @endif
                </div>

                <h2 class="h5 card-title mb-2">{{ $post->title }}</h2>

                <div class="mb-2 d-flex flex-wrap gap-1">
                  @foreach($post->categories as $c)
                    <span class="badge badge-cat">{{ $c->name }}</span>
                  @endforeach
                  @foreach($post->tags as $t)
                    <span class="badge badge-tag">#{{ $t->name }}</span>
                  @endforeach
                </div>

                <p class="card-excerpt mb-3">
                  {{ \Illuminate\Support\Str::words($post->excerpt ?: strip_tags($post->content), 35) }}
                </p>

                <div class="mt-auto d-flex align-items-center justify-content-between">
                  <small class="text-muted">~ {{ str_word_count(strip_tags($post->content)) }} mots</small>
                  <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-secondary btn-sm">Lire l’article</a>
                </div>
              </div>
            </article>
          </div>
        @endforeach
      </div>

      <div class="mt-4">
        {{ $posts->onEachSide(1)->links() }}
      </div>
    @else
      <div class="p-5 text-center border border-2 border-dashed rounded-4 text-secondary">
        Aucun article publié pour le moment.
        @auth
          @if(auth()->user()->isAdmin())
            <div class="mt-3">
              <a href="{{ route('admin.posts.create') }}" class="btn btn-brand">Créer le premier article</a>
            </div>
          @endif
        @endauth
      </div>
    @endif
  </section>
@endsection
