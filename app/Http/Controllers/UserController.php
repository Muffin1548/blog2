<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function userPosts($id)
    {
        return $this->userService->userPosts($id);
    }

    public function profile(Request $request, $id)
    {
        $data = [
            'user' => $request->user(),
        ];

        return $this->userService->profile($id, $data);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('home');
    }
}
