<?php


namespace App\Repositories;

use App\Models\Post as Model;

class PostRepository extends BaseRepository
{
    /**
     * @return string
     *
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }

    public function getIndex()
    {
        return $this->startConditions()->all();
    }

    public function getPost($slug)
    {
        return $this->startConditions()->where("slug", $slug);
    }

    public function getUserPosts($id)
    {

        return $this->startConditions()->all()->where('author_id', $id);
    }
}