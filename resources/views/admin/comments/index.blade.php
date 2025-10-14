@extends('layouts.app')
@section('title','Modération commentaires')

@section('content')
<div class="container">
  <div class="d-flex align-items-end justify-content-between mb-3">
    <h1 class="h4 m-0">Modération</h1>
  </div>

  <div class="card card-article p-0">
    <div class="list-group list-group-flush">
      @forelse($comments as $comment)
        <div class="list-group-item bg-transparent">
          <div class="d-flex justify-content-between align-items-start">
            <div class="u-readable u-break">   {{-- 👈 largeur & coupures --}}
              <div class="card-meta">
                Sur <a class="link-primary text-decoration-none" href="{{ route('posts.show',$comment->post) }}">{{ $comment->post->title }}</a>
                • par {{ $comment->user->name }} • {{ $comment->created_at->format('d/m/Y H:i') }}
                • Statut : <strong>{{ $comment->status }}</strong>
              </div>
              <div class="mt-2">{{ $comment->body }}</div>
            </div>
            <div class="d-flex flex-wrap gap-2 ms-3">
              <form method="POST" action="{{ route('admin.comments.approve',$comment) }}">@csrf
                <button class="btn btn-brand btn-sm">Approuver</button>
              </form>
              <form method="POST" action="{{ route('admin.comments.hide',$comment) }}">@csrf
                <button class="btn btn-outline-secondary btn-sm">Masquer</button>
              </form>
              <form method="POST" action="{{ route('admin.comments.destroy',$comment) }}"
                    onsubmit="return confirm('Supprimer ce commentaire ?');">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm">Supprimer</button>
              </form>
            </div>
          </div>
        </div>
      @empty
        <div class="p-4 text-secondary">Aucun commentaire.</div>
      @endforelse
    </div>
  </div>

  <div class="mt-3">{{ $comments->links() }}</div>
</div>
@endsection
