<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['author','categories','tags'])
            ->where('status','published')
            ->orderByDesc('published_at')
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post) // binding par slug (voir routes)
    {
        session(['url.intended' => route('posts.show', $post).'#comments']);
        abort_unless($post->status === 'published', 404);

        // Commentaires visibles si réglage OK, on ne montre que 'approved' aux invités
        $commentsQuery = $post->comments()->with('user');
        if (!Auth::check() && !config('blog.guests_can_see_comments')) {
            $comments = collect(); // vide
        } else {
            $comments = $commentsQuery->where('status','approved')->get();
        }

        return view('posts.show', [
            'post' => $post->load(['author','categories','tags']),
            'comments' => $comments,
            'prev' => $post->previous(),
            'next' => $post->next(),
        ]);
    }
}

