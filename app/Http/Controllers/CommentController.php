<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;   
use App\Models\Comment;  

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $this->authorize('create', Comment::class);

        $validated = $request->validate([
            'body' => ['required','string','min:500','max:1000'], // 500–1000 caractères
        ]);

        $comment = new Comment([
            'body' => $validated['body'],
            'status' => 'pending', // modération par admin
        ]);
        $comment->user()->associate($request->user());
        $comment->post()->associate($post);
        $comment->save();

        return back()->with('success','Commentaire envoyé (en attente de validation).');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'body' => ['required','string','min:500','max:1000'],
        ]);

        $comment->update(['body' => $validated['body']]);
        return back()->with('success','Commentaire mis à jour.');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return back()->with('success','Commentaire supprimé.');
    }
}

