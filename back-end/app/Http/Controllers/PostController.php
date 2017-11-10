<?php

namespace App\Http\Controllers;

use App\Post;

class PostController extends Controller
{

    public function show(Post $post)
    {
        return view('post.show')->with('post', $post);
    }
}