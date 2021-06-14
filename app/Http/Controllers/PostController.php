<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostFormRequest;
use App\Services\PostService;
use Illuminate\Http\Request;


class PostController extends Controller
{
    private PostService $postService;

    public function __construct()
    {
        $this->postService = new PostService();
    }

    public function index(): string
    {
        return $this->postService->index();
    }

    public function create(Request $request)
    {
        $canPost = $request->user()->canPost();
        return $this->postService->create($canPost);
    }

    public function store(PostFormRequest $request)
    {
        $data = [
            'id' => $request->user()->id,
            'title' => $request->input('title'),
            'body' => $request->input('body '),
        ];

        return $this->postService->store($data);
    }

    public function show($slug)
    {
        return $this->postService->show($slug);
    }

    public function edit(Request $request, $slug)
    {
        $data = [
            'id' => $request->user()->id,
            'isAdmin' => $request->user()->isAdmin(),
        ];

        return $this->postService->edit($data, $slug);
    }

    public function update(Request $request)
    {
        $data = [
            'id' => $request->user()->id,
            'isAdmin' => $request->user()->isAdmin(),
            'title' => $request->input('title'),
            'body' => $request->input('body'),
        ];

        return $this->postService->update($request->input('post_id'), $data);
    }

    public function destroy(Request $request, $id)
    {
        $data = [
            'id' => $request->user()->id,
            'isAdmin' => $request->user()->isAdmin(),
        ];

        return $this->postService->destroy($data, $id);
    }
}
