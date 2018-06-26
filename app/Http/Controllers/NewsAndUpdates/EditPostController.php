<?php

namespace App\Http\Controllers\NewsAndUpdates;

use App\Http\Requests\NewsAndUpdates\EditPostRequest;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EditPostController extends Controller
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
    public function index($id = 0)
    {
        $post = Post::findorfail($id);
        return view('theme.default.posts.post_edit', compact('post'));
    }

    public function update(EditPostRequest $request, $id = 0)
    {
        $post = Post::findorfail($id);
        Post::where('id', $post->id)->update(['title' => $request->title, 'content' => $request->content_message, 'is_pinned' => ($request->pinned == 'on' ?  1 : 0), 'is_public' => ($request->public == 'on' ?  1 : 0)]);
        return redirect()->back()->with('success', 'Post Updated.');
    }
}
