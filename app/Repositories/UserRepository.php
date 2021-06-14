<?php


namespace App\Repositories;


use App\Models\Post;
use App\Models\User;

class UserRepository
{
    public function getUserPosts($id)
    {
        return Post::where('author_id', $id)->where('active', '1')->orderBy('created_at', 'desc')->paginate(5);
    }

    public function getUserName($id)
    {
        return User::find($id)->name;
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function getPostsCount($id): int
    {
        return Post::where("author_id", $this->getUserById($id)->id)->count();
    }
}