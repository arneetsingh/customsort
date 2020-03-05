<?php

namespace Arni\CustomSort\Tests\Controllers;


use Arni\CustomSort\Tests\Models\Post;
use Arni\CustomSort\Traits\HasCustomSortEndpoint;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class PostsController
{
    use HasCustomSortEndpoint;
    use ValidatesRequests;

    public $model = Post::class;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::orderByWithCustomSort($request->orderby ?? 'id')->get();
        return $posts;
    }
}
