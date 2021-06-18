<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostFormRequest;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


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
            'body' => $request->input('body'),
        ];

        $validator = Validator::make($data, [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('new-post')->withErrors('Title already exist or something else')->withInput();
        }else{
            return $this->postService->store($data);
        }
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

        $slug = Str::slug($data['title']);

        $validator = Validator::make($data, [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('edit/' . $slug)->withErrors('Title already exist or something else')->withInput();
        } else {
            $this->postService->update($request->input('post_id'), $data);
            return redirect('home')->with('message', 'Post updated successfully');
        }
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
