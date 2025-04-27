<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; 
use App\Models\User; 
use App\Models\Comment;
use App\Models\Like;

class PostController extends Controller
{
    public function index()
    {
        return response([
            'posts' => Post::OrderBy('created_at', 'desc')
                ->with('user:id,name,image')
                ->withCount(['comments', 'likes'])
                ->get(),
            ], 200);
    }

    // get Single Post
    public function show($id)
    {
        return response([
        $post = Post::where('id', $id)
            ->withCount(['comments', 'likes'])
            ->get(),
            ], 200);
    }

    // create Post
    public function store(Request $request)
    {
        $attrs = $request->validate([
            'body' => 'required|string',
        ]);

        $image = $this->saveImage($request->image, 'posts');
    
        $post = Post::create([
            'body' => $attrs['body'],
            'user_id' => auth()->id(),
            'image' => $image,
        ]);

        return response([
            'post' => $post,
            'message' => 'Post created successfully',
        ], 200);
    }



    // update Post
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'Post not found',
            ], 403);
        }
        if ($post->user_id != auth()->id()) {
            return response([
                'message' => 'You are not authorized to update this post',
            ], 403);
        }


        $attrs = $request->validate([
            'body' => 'required|string',
        ]);
    
        $post->update([
            'body' => $attrs['body'],
        ]);
    
        return response([
            'post' => $post,
            'message' => 'Post updated successfully',
        ], 200);
    }


    // delete Post
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'Post not found',
            ], 403);
        }
        if ($post->user_id != auth()->id()) {
            return response([
                'message' => 'You are not authorized to delete this post',
            ], 403);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();
    
        return response([
            'message' => 'Post deleted successfully',
        ], 200);
    }

}
