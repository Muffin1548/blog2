<?php


namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    public function index()
    {
        return Post::where('active', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
    }

    public function show($slug)
    {
        return Post::where('slug', $slug)->first();
    }

    public function post($post_id)
    {
        return Post::find($post_id);
    }
}