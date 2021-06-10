<?php


namespace App\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'posted_at' => $this->posted_at->toIso8601String(),
            'author_id' => $this->author_id,
            'comments_count' => $this->comments_count ?? $this->comments()->count(),
        ];
    }
}
