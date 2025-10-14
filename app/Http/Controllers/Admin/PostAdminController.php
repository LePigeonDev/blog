<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class PostAdminController extends Controller
{
    public function index()
    {
        $posts = Post::with('author')->orderByDesc('published_at')->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    public function drafts()
    {
        $posts = Post::with('author')->where('status','draft')->orderByDesc('updated_at')->paginate(20);
        return view('admin.posts.index', compact('posts')); // réutilise la même vue
    }

    public function create()
    {
        return view('admin.posts.create', [
            'categories' => Category::orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => ['required','string','max:255'],
            'slug'           => ['nullable','string','max:255','unique:posts,slug'],
            'excerpt'        => ['nullable','string','max:500'],
            'featured_image' => ['nullable','url'],
            'content'        => ['required','string'],
            'status'         => ['required','in:draft,published'],
            'published_at'   => ['required','date_format:Y-m-d'],
            'categories'     => ['array'],
            'categories.*'   => ['integer','exists:categories,id'],
            'tags'           => ['array'],
            'tags.*'         => ['integer','exists:tags,id'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['user_id'] = $request->user()->id;
        $data['published_at'] = Carbon::createFromFormat('Y-m-d', $data['published_at'])->startOfDay();

        $post = Post::create($data);
        $post->categories()->sync($request->input('categories', []));
        $post->tags()->sync($request->input('tags', []));

        return redirect()->route('posts.show', $post)->with('success','Article créé.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', [
            'post'       => $post->load('categories','tags'),
            'categories' => Category::orderBy('name')->get(),
            'tags'       => Tag::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title'          => ['required','string','max:255'],
            'slug'           => ['required','string','max:255','unique:posts,slug,'.$post->id],
            'excerpt'        => ['nullable','string','max:500'],
            'featured_image' => ['nullable','url'],
            'content'        => ['required','string'],
            'status'         => ['required','in:draft,published'],
            'published_at'   => ['required','date_format:Y-m-d'],
            'categories'     => ['array'],
            'categories.*'   => ['integer','exists:categories,id'],
            'tags'           => ['array'],
            'tags.*'         => ['integer','exists:tags,id'],
        ]);

        $data['published_at'] = Carbon::createFromFormat('Y-m-d', $data['published_at'])->startOfDay();

        $post->update($data);
        $post->categories()->sync($request->input('categories', []));
        $post->tags()->sync($request->input('tags', []));

        // redirige selon statut : si publié → page article ; sinon → liste brouillons
        return $post->status === 'published'
            ? redirect()->route('posts.show', $post)->with('success','Article mis à jour.')
            : redirect()->route('admin.posts.drafts')->with('success','Brouillon mis à jour.');
    }

    public function publish(Post $post)
    {
        $post->update([
            'status' => 'published',
            'published_at' => $post->published_at ?? now()->startOfDay(),
        ]);

        return redirect()->route('posts.show', $post)->with('success','Article publié.');
    }

    public function unpublish(Post $post)
    {
        $post->update(['status' => 'draft']);
        // ✅ redirection vers l’accueil (pas back()) pour éviter le 404
        return redirect()->route('home')->with('success','Article dépublié.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('home')->with('success','Article supprimé.');
    }
}
