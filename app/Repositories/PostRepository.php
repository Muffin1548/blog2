<?php


namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    public function list(int $paginate)
    {
        return Post::where('active', 1)
            ->orderBy('created_at', 'desc')
            ->paginate($paginate);
    }

    public function findPostBySlug($slug)
    {
        return Post::where('slug', $slug)->first();
    }

    public function findPostById($id)
    {
        return Post::find($id);
    }

}