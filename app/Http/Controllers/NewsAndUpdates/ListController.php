<?php

namespace App\Http\Controllers\NewsAndUpdates;

use App\Http\Requests\NewsAndUpdates\DeletePostRequest;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.posts.post_list');
    }

    public function post_list()
    {
        $query = Post::PostList()->with('user')->selectRaw('posts.id, posts.title, posts.user_id, posts.is_pinned, posts.is_public, posts.updated_at, posts.created_at');
        return datatables()->eloquent($query)
            ->addColumn('check', '<input type="hidden" class="post_id" value="{{ $id }}">')
            ->addColumn('user', function (Post $post) {
                if(Auth::check()) {
                    if(auth()->user()->isAdmin()) {
                        return $post->user->username;
                    } else {
                        if($post->user_id == auth()->user()->id) {
                            return $post->user->username;
                        } else {
                            return '<span class="label label-' . $post->user->group->class . ' ">' . $post->user->group->name . '</span>';
                        }
                    }
                }
                return '<span class="label label-' . $post->user->group->class . ' ">' . $post->user->group->name . '</span>';
            })
            ->editColumn('title', function (Post $post) {
                return '<a href="' . route('news_and_updates.view', [$post->id, str_slug($post->title, '-')]) . '">' . $post->title . '</a>';
            })
            ->editColumn('is_public', function (Post $post) {
                if($post->is_public) {
                    return '<span class="label label-success">Public</span>';
                } else {
                    return '<span class="label label-danger">Private</span>';
                }
            })
            ->editColumn('updated_at', function (Post $post) {
                if($post->updated_at == $post->created_at) {
                    return 'Never';
                } else {
                    return $post->updated_at->diffForhumans();
                }
            })
            ->filterColumn('is_public', function ($query, $keyword) {
                if(str_contains('public', strtolower($keyword))) {
                    $query->where('is_public', 1);
                }
                if(str_contains('private', strtolower($keyword))) {
                    $query->where('is_public', 0);
                }
            })
            ->rawColumns(['check', 'title', 'user', 'is_public'])
            ->make(true);
    }

    public function delete(DeletePostRequest $request)
    {
        Post::whereIn('id', $request->post_ids)->delete();
        return redirect()->back()->with('success_delete', 'Selected Post Deleted.');
    }
}
