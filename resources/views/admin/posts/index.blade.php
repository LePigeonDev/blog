@extends('layouts.app')
@section('title','Articles')

@section('content')
<div class="container">
  <div class="d-flex align-items-end justify-content-between mb-3">
    <h1 class="h4 m-0">Articles</h1>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.posts.index') }}">Tous</a>
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.posts.drafts') }}">Brouillons</a>
      <a class="btn btn-brand btn-sm" href="{{ route('admin.posts.create') }}">Ajouter</a>
    </div>
  </div>

  <div class="card card-article p-0">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Titre</th>
            <th>Statut</th>
            <th>Date</th>
            <th style="width:280px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($posts as $post)
            <tr>
              <td><a class="link-primary text-decoration-none" href="{{ route('posts.show',$post) }}">{{ $post->title }}</a></td>
              <td><span class="badge {{ $post->status==='published'?'text-bg-success':'text-bg-secondary' }}">{{ $post->status }}</span></td>
              <td>{{ $post->published_at?->format('d/m/Y') }}</td>
              <td class="d-flex flex-wrap gap-2">
                <a class="btn btn-soft btn-sm" href="{{ route('admin.posts.edit',$post) }}">Modifier</a>
                @if($post->status==='published')
                  <form method="POST" action="{{ route('admin.posts.unpublish',$post) }}">@csrf
                    <button class="btn btn-outline-secondary btn-sm">Dépublier</button>
                  </form>
                @else
                  <form method="POST" action="{{ route('admin.posts.publish',$post) }}">@csrf
                    <button class="btn btn-brand btn-sm">Publier</button>
                  </form>
                @endif
                <form method="POST" action="{{ route('admin.posts.destroy',$post) }}"
                      onsubmit="return confirm('Supprimer définitivement ?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger btn-sm">Supprimer</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="4" class="text-secondary">Aucun article.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">{{ $posts->links() }}</div>
</div>
@endsection
