<?php


namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Support\Str;


class PostService
{
    private $postRepository;

    public function __construct()
    {
        $this->postRepository = new PostRepository();
    }

    public function index()
    {
        $posts = $this->postRepository->index();
        $title = 'Latest posts';

        return view('home', compact('posts', 'title'));
    }

    public function store(array $data)
    {
        $post = new Post();

        $post->title = $data['title'];
        $post->body = $data['body'];
        $post->slug = Str::slug($post->title);
        $post->author_id = $data['id'];
        $post->active = 1;

        $post->save();

        return redirect('home');
    }

    public function show(string $slug)
    {
        $post = $this->postRepository->show($slug);
        $comments = $post->comments;

        return view('posts.show', compact('post', 'comments'));
    }

    public function edit(array $data, $slug)
    {
        $post = $this->postRepository->show($slug);
        if ($post && ($data['id'] == $post->author_id || $data['isAdmin']))
            return view('posts.edit')->with('post', $post);
        return redirect('/')->withErrors('you have not sufficient permissions');
    }

    public function update(int $postId, array $data)
    {
        $post = $this->postRepository->post($postId);

        if ($post && ($post->author_id == $data['id'] || $data['isAdmin'])) {
            $title = $data['title'];
            $slug = Str::slug($title);
            $duplicate = $this->postRepository->show($slug);
            if ($duplicate) {
                return redirect('edit/' . $post->slug)->withErrors('Title already exist')->withInput();
            } else {
                $post->slug = $slug;
            }
            $post->title = $title;
            $post->body = $data['body'];

            $post->active = 1;
            $message = 'Post updated successfully';
            $landing = $post->slug;

            $post->save();
            return redirect($landing)->with('message', $message);
        } else {
            return redirect('/')->withErrors('you have not sufficient permissions');
        }
    }

    public function destroy(array $data, $id)
    {
        $post = $this->postRepository->post($id);
        if ($post && ($post->author_id == $data['id'] || $data['isAdmin'])) {
            $post->delete();
            $data['message'] = 'Post deleted Successfully';
        } else {
            $data['errors'] = 'Invalid Operation. You have not sufficient permissions';
        }
        return redirect('/')->with($data);
    }

    public function create(bool $canPost)
    {
        if ($canPost) {
            return view('posts.create');
        } else {
            return redirect('/')->withErrors('You have not sufficient permissions for writing post');
        }
    }
}