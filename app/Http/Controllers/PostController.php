<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $query = Post::with(['author','categories','tags'])
            ->where('status','published');

        $filteredByPrefs = false;

        if (Auth::check() && !request()->boolean('all')) {
            $prefIds = Auth::user()->preferredCategories()->pluck('categories.id')->all();
            if (!empty($prefIds)) {
                $query->whereHas('categories', fn($q) => $q->whereIn('categories.id', $prefIds));
                $filteredByPrefs = true;
            }
        }

        $posts = $query->orderByDesc('published_at')->paginate(10)->withQueryString();

        return view('posts.index', compact('posts', 'filteredByPrefs'));
    }


    public function show(Post $post) // binding par slug (voir routes)
    {
        session(['url.intended' => route('posts.show', $post).'#comments']);
        abort_unless($post->status === 'published', 404);

        // Commentaires visibles si réglage OK, on ne montre que 'approved' aux invités
        $comments = collect();
        if (config('blog.guests_can_see_comments')) {
            $comments = $post->comments()
                ->with('user')
                ->where('status','approved') // seuls les approuvés
                ->latest()
                ->get();
        }
        return view('posts.show', [
            'post' => $post->load(['author','categories','tags']),
            'comments' => $comments,
            'prev' => $post->previous(),
            'next' => $post->next(),
        ]);
    }

    
}

