<?php


namespace App\Services;


use App\Models\Post;
use App\Repositories\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function userPosts($id)
    {
        $posts = $this->userRepository->getUserPosts($id);
        $title = $this->userRepository->getUserName($id);

        return view('home',compact('posts', 'title'));
    }

    public function profile(int $id, array $user)
    {
        $data['user'] = $this->userRepository->getUserById($id);
        if (!$data['user'])
            return redirect('/');

        if ($user['user'] && $data['user']->id == $user['user']->id) {
            $data['author'] = true;
        } else {
            $data['author'] = false;
        }

        $data['id'] = $data['user']->id;
        $data['comments_count'] = $data['user']->comments->count();
        $data['posts_count'] = $this->userRepository->getPostsCount($id);
        $data['latest_posts'] = $data['user']->posts->where('active')->take(5);
        $data['latest_comments'] = $data['user']->comments;
        return view('admin.profile')->with('user', $data);
    }
}
