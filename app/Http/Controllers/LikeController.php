<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; 
use App\Models\Like; 

class LikeController extends Controller
{
    // like or unlike post
    public function likeOrUnlike( $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 403);
        }

        $like = Like::where('user_id', auth()->user()->id)
            ->where('post_id', $id)
            ->first();
        // Check if the user has already liked the post
        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Post unliked'], 200);
        } 
        // If the user has not liked the post, create a new like
        else {
            Like::create([
                'user_id' => auth()->user()->id,
                'post_id' => $id,
            ]);
            return response()->json(['message' => 'Post liked'], 201);
        }
    }
}
