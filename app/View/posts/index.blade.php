@extends('layouts.app')

@section('title','Accueil')

@section('content')
  <h1 class="text-2xl font-bold mb-6">Derniers articles</h1>

  <div class="grid gap-8">
    @foreach($posts as $post)
      <article class="border p-4 rounded">
        @if($post->featured_image)
          <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="mb-3">
        @endif
        <h2 class="text-xl font-semibold">
          <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
        </h2>
        <p class="text-sm text-gray-500">
          Publié le {{ $post->published_at?->format('d/m/Y') }} par {{ $post->author->name }}
        </p>
        <p class="mt-3">
          {{ \Illuminate\Support\Str::words(strip_tags($post->excerpt ?: $post->content), 35) }}
        </p>
        <a class="text-blue-600" href="{{ route('posts.show', $post) }}">Lire la suite</a>
      </article>
    @endforeach
  </div>

  <div class="mt-6">{{ $posts->links() }}</div>
@endsection
