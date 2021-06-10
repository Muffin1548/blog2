<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function userPosts($id)
    {
        $posts = Post::where('author_id', $id)->where('active', '1')->orderBy('created_at', 'desc')->paginate(5);
        $tittle = User::find($id)->name;
        return view('home')->with('posts',$posts)->with('title', $tittle);
    }

    public function userPostsAll(Request $request)
    {
        $user = $request->user();
        $posts = Post::where('author_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        $title = $user->name;
        return view('home')->with('posts',$posts)->with('title', $title);
    }
    /**
     * profile for user
     */
    public function profile(Request $request, $id)
    {
        $data['user'] = User::find($id);
        if (!$data['user'])
            return redirect('/');

        if ($request->user() && $data['user']->id == $request->user()->id) {
            $data['author'] = true;
        } else {
            $data['author'] = null;
        }

        $data['id'] = $data['user']->id;
        $data['comments_count'] = $data['user']->comments->count();
        $data['posts_count'] = Post::withCount('author')->get()->where("author_id", $data['user']->id)->count();
        $data['latest_posts'] = $data['user']->posts->where('active')->take(5);
        $data['latest_comments'] = $data['user']->comments;
        return view('admin.profile')->with('user', $data);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('home');
    }
}
