<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $slug = $request->input('slug');
        Comment::create([
            "from_user" => $request->user()->id,
            "body" => $request->input('body'),
        ]);
        return redirect($slug)->with('message', 'Comment published');
    }
}
