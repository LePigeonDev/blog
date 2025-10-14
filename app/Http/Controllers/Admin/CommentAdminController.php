<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function index()
    {
        $comments = Comment::with(['post','user'])->latest()->paginate(20);
        return view('admin.comments.index', compact('comments'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['status' => 'approved']);
        return back()->with('success','Commentaire approuvé');
    }

    public function hide(Comment $comment)
    {
        $comment->update(['status' => 'hidden']);
        return back()->with('success','Commentaire masqué');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success','Commentaire supprimé');
    }
}

