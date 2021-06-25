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

    public function index()
    {
        $data = $this->postService->index();


        $posts = $data['posts'];
        $title = $data['title'];

        return view('home', compact('posts', 'title'));
    }

    public function create(Request $request)
    {
        $canPost = $request->user()->canPost();

        if ($this->postService->create($canPost)) {
            return view('posts.create');
        } else {
            return redirect('/')->withErrors('You have not sufficient permissions for writing post');
        }
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
        } else {
            $this->postService->store($data);
            return redirect('home')->with('message', 'Post published successfully');
        }

    }

    public function show($slug)
    {
        $data = $this->postService->show($slug);

        $post = $data['post'];
        $comments = $data['comments'];

        return view('posts.show', compact('post', 'comments'));
    }

    public function edit(Request $request, $slug)
    {
        $data = [
            'id' => $request->user()->id,
            'isAdmin' => $request->user()->isAdmin(),
        ];

        $post = $this->postService->edit($data, $slug);

        return view('posts.edit')->with('post', $post);
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

        $this->postService->destroy($data, $id);

        return redirect('/')->with($data);
    }
}
