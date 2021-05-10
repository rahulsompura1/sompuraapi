<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

use MongoDB\BSON\ObjectId;

class PostController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $post=Post::get();

        return response()->json($post, 200);
    }

    public function details($id)
    {
        $post =  Post::find($id);       
        return response()->json($post, 200);
    }
}
