<?php


namespace App\Services;


use App\Models\Comment;

class CommentService
{
    public function store(array $data)
    {
        Comment::create($data);
    }
}