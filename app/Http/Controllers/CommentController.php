<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $commentService;

    public function __construct()
    {
        $this->commentService = new CommentService();
    }

    public function store(Request $request)
    {
        $data = [
            "from_user" => $request->user()->id,
            "body" => $request->input('body'),
            'on_post' => $request->input('on_post'),
        ];

        $slug = $request->input('slug');
        $this->commentService->store($data);

        return redirect($slug)->with('message', 'Comment published');
    }
}
