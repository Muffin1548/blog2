<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Http\Requests\PostFormRequest;

class PostController extends Controller
{
    private $postRepository;


    public function __construct()
    {
        $this->postRepository = app(PostRepository::class);
    }

    public function index(): string
    {
        $posts = $this->postRepository->getIndex();
        //$posts = Post::where('active',1)->orderBy('created_at','desc')->paginate(5);

        $title = 'Latest posts';
        //dd($posts);
        return view('home', compact('posts', 'title'));
    }

    public function create(Request $request)
    {
        if ($request->user()->canPost()) {
            return view('posts.create');
        } else {
            return redirect('/')->withErrors('You have not sufficient permissions for writing post');
        }
    }

    public function store(PostFormRequest $request)
    {
        $post = new Post();
        $post->title = $request->get('title');
        $post->body = $request->get('body');
        $post->slug = Str::slug($post->title);

        $duplicate = Post::where('slug', $post->slug)->first();

        if ($duplicate) {
            return redirect('new-post')->withErrors('Title already exists')->withInput();
        }

        $post->author_id = $request->user()->id;
        if ($request->has('save')) {
            $post->active = 0;
            $message = 'Post saved successfully';
        } else {
            $post->active = 1;
            $message = 'Post published successfully';
        }

        $post->save();

        return redirect('home');
    }

    public function show($slug)
    {
        $post = $this->postRepository->getPost($slug)->first();
        $comments = $post->comments;

        return view('posts.show', compact('post', 'comments'));
    }

    public function edit(Request $request, $slug)
    {
        $post = $this->postRepository->getPost($slug)->first();
        if ($post && ($request->user()->id == $post->author_id || $request->user()->isAdmin()))
            return view('posts.edit')->with('post', $post);
        return redirect('/')->withErrors('you have not sufficient permissions');
    }

    public function update(Request $request)
    {
        $post_id = $request->input('post_id');
        $post = Post::find($post_id);
        if ($post && ($post->author_id == $request->user()->id || $request->user()->isAdmin())) {
            $title = $request->input('title');
            $slug = Str::slug($title);
            $duplicate = Post::where('slug', $slug)->first();
            if ($duplicate) {
                return redirect('edit/' . $post->slug)->withErrors('Title already exist')->withInput();
            } else {
                $post->slug = $slug;
            }
            $post->title = $title;
            $post->body = $request->input('body');

            if ($request->has('save')) {
                $post->active = 0;
                $message = 'Post saved successfully';
                $landing = 'edit/' . $post->slug;
            } else {
                $post->active = 1;
                $message = 'Post updated successfully';
                $landing = $post->slug;
            }
            $post->save();
            return redirect($landing)->with('message', $message);
        } else {
            return redirect('/')->withErrors('you have not sufficient permissions');
        }
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::find($id);
        if ($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
            $post->delete();
            $data['message'] = 'Post deleted Successfully';
        } else {
            $data['errors'] = 'Invalid Operation. You have not sufficient permissions';
        }
        return redirect('/')->with($data);
    }
}
