<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $slug = Str::slug($request->title);

        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'status'  => 'required|in:draft,publish',
            'slug'    => 'unique:posts,slug,' . $slug,
        ]);
        // Validate slug riêng sau khi generate
       if (Post::where('slug', $slug)->exists()) {
            return back()
            ->withErrors(['slug' => 'Slug đã tồn tại.'])
            ->withInput();
        }

        Post::create([
            'title'   => $request->title,
            'slug'    => $slug,
            'content' => $request->content,
            'status'  => $request->status,
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $slug = Str::slug($request->title);

        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'status'  => 'required|in:draft,publish',
            'slug'    => 'unique:posts,slug,' . $post->id,
        ]);

        $post->update([
            'title'   => $request->title,
            'slug'    => $slug,
            'content' => $request->content,
            'status'  => $request->status,
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
