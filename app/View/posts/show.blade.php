@extends('layouts.app')

@section('title', $post->title)

@section('content')
  <article>
    <h1 class="text-3xl font-bold">{{ $post->title }}</h1>
    <p class="text-sm text-gray-500">
      {{ $post->published_at?->format('d/m/Y') }} — {{ $post->author->name }}
      @if($post->categories->count())
        — Catégories:
        @foreach($post->categories as $c) <span>{{ $c->name }}</span>@if(!$loop->last),@endif @endforeach
      @endif
      @if($post->tags->count())
        — Tags:
        @foreach($post->tags as $t) <span>#{{ $t->name }}</span>@if(!$loop->last),@endif @endforeach
      @endif
    </p>
    @if($post->featured_image)
      <img class="my-4" src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}">
    @endif
    <div class="prose">{!! $post->content !!}</div>

    <nav class="flex justify-between mt-8">
      <div>
        @if($prev = $prev) <a href="{{ route('posts.show',$prev) }}">&larr; {{ $prev->title }}</a> @endif
      </div>
      <div>
        @if($next = $next) <a href="{{ route('posts.show',$next) }}">{{ $next->title }} &rarr;</a> @endif
      </div>
    </nav>

    <section id="comments" class="mt-10">
      <h2 class="text-2xl font-semibold mb-4">Commentaires</h2>

      @guest
        <p>
          @if(config('blog.guests_can_see_comments'))
            @forelse($comments as $comment)
              <div class="border rounded p-3 mb-3">
                <div class="text-sm text-gray-500">{{ $comment->user->name }} — {{ $comment->created_at->format('d/m/Y H:i') }}</div>
                <p>{{ $comment->body }}</p>
              </div>
            @empty
              <em>Pas de commentaires.</em>
            @endforelse
          @endif
        </p>
        <a class="btn" href="{{ route('login') }}">Connectez-vous pour commenter</a>
      @else
        {{-- Formulaire ajout commentaire --}}
        <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-6">
          @csrf
          <textarea name="body" rows="6" required minlength="500" maxlength="1000" class="w-full border p-3" placeholder="Votre commentaire (500–1000 caractères)">{{ old('body') }}</textarea>
          @error('body') <div class="text-red-600">{{ $message }}</div> @enderror
          <button type="submit" class="mt-3 btn">Publier</button>
        </form>

        {{-- Liste commentaires approuvés --}}
        @forelse($comments as $comment)
          <div class="border rounded p-3 mb-3">
            <div class="text-sm text-gray-500">{{ $comment->user->name }} — {{ $comment->created_at->format('d/m/Y H:i') }}</div>
            <p>{{ $comment->body }}</p>

            @can('update',$comment)
              <details class="mt-2">
                <summary>Éditer / Supprimer</summary>
                <form method="POST" action="{{ route('comments.update',$comment) }}">
                  @csrf @method('PUT')
                  <textarea name="body" rows="4" minlength="500" maxlength="1000" class="w-full border p-2">{{ old('body',$comment->body) }}</textarea>
                  <button class="btn mt-2">Mettre à jour</button>
                </form>
                <form method="POST" action="{{ route('comments.destroy',$comment) }}" onsubmit="return confirm('Supprimer ?');" class="mt-2">
                  @csrf @method('DELETE')
                  <button class="btn">Supprimer</button>
                </form>
              </details>
            @endcan
          </div>
        @empty
          <em>Pas de commentaires.</em>
        @endforelse
      @endguest
    </section>
  </article>
@endsection
