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

    public function index(): array
    {
        $posts = $this->postRepository->list(5);
        $title = 'Latest posts';

        return array(
            'posts' => $posts,
            'title' => $title,
        );
    }

    public function store(array $data)
    {
        $post = new Post();

        $post->title = $data['title'];
        $post->body = $data['body'];
        $post->slug = Str::slug($post->title);
        $post->author_id = $data['id'];
        $post->active = 1;

        return $post->save();

    }

    public function show(string $slug): array
    {
        $post = $this->postRepository->findPostBySlug($slug);
        $comments = $post->comments;

        return array(
            'post' => $post,
            'comments' => $comments
        );
    }

    public function edit(array $data, $slug)
    {
        $post = $this->postRepository->findPostBySlug($slug);
        if ($post && ($data['id'] == $post->author_id || $data['isAdmin'])) {
            return $post;
        }else
            return false;
    }

    public function update(int $postId, array $data): bool
    {
        $post = $this->postRepository->findPostById($postId);

        if ($post && ($post->author_id == $data['id'] || $data['isAdmin'])) {
            $title = $data['title'];
            $slug = Str::slug($title);
            $post['slug'] = $slug;
            $post['title'] = $title;
            $post['body'] = $data['body'];
            $post['active']= 1;

            return $this->postRepository->savePost($post);
        } else {
            return false;
        }
    }

    public function destroy(array $data, $id): string
    {
        $post = $this->postRepository->findPostById($id);
        if ($post && ($post->author_id == $data['id'] || $data['isAdmin'])) {
            $post->delete();
            return $data['message'] = 'Post deleted Successfully';
        } else {
            return $data['errors'] = 'Invalid Operation. You have not sufficient permissions';
        }
    }

    public function create(bool $canPost): bool
    {
        return $canPost;
    }
}