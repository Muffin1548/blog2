<?php


namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * @var Model
     *
     */
    protected $model;

    public function __construct()
    {
        $this->model=app($this->getModelClass());
    }

    /**
     * @return mixed
     *
     */
    abstract protected function getModelClass();


    /**
     * @return \Illuminate\Contracts\Foundation\Application|Model|mixed
     *
     */
    protected function startConditions()
    {
        return $this->model;
    }


}