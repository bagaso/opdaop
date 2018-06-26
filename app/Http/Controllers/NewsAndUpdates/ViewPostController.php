<?php

namespace App\Http\Controllers\NewsAndUpdates;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class ViewPostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $id = $request->id;
        $post = Post::findorfail($id);
        if(!$post->is_public) {
            $this->middleware(['auth']);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
        $post = Post::findorfail($id);
        return view('theme.default.posts.post_view', compact('post'));
    }
}
