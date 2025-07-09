<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreatePostAction;
use App\Actions\DeletePostAction;
use App\Actions\EditPostAction;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;

final class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->get();

        return view('posts.index', ['posts' => $posts]);
    }

    public function create()
    {
        $categories = Category::all();

        return view('posts.create', ['categories' => $categories]);
    }

    public function store(StorePostRequest $request, CreatePostAction $action)
    {
        $action->execute($request);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function show(Post $post)
    {
        return view('posts.show', ['post' => $post]);
    }

    public function edit(Post $post)
    {
        $categories = Category::all();

        return view('posts.edit', ['post' => $post, 'categories' => $categories]);
    }

    public function update(UpdatePostRequest $request, EditPostAction $action, Post $post)
    {
        $action->execute($request, $post);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(DeletePostAction $action, Post $post)
    {
        $action->execute($post);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
