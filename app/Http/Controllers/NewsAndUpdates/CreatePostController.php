<?php

namespace App\Http\Controllers\NewsAndUpdates;

use App\Http\Requests\NewsAndUpdates\CreatePostRequest;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreatePostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.manage_post']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.posts.post_add');
    }

    public function create(CreatePostRequest $request)
    {
        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content_message;
        $post->user_id = auth()->user()->id;
        $post->title = $request->title;
        $post->is_draft = 0;
        $post->is_pinned = $request->pinned == 'on' ?  1 : 0;
        $post->is_public = $request->public == 'on' ?  1 : 0;
        $post->save();
        return redirect()->back()->with('success', 'New Post Created.');
    }
}
