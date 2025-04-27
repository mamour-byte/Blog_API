<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; 
use App\Models\Comment; 
use App\Models\User; 

class CommentController extends Controller
{
    // get all comments
    public function index($id)
    {
        $pots = Post::find($id);
        if (!$pots) {
            return response()->json(['message' => 'Post not found'], 403);
        }
        
        return response([
            'comments' => $pots->comments()
            ->with('user:id,name,image')
            ->get(),
        ], 200);
    }

    // create comment
    public function store(Request $request , $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 403);
        }

        // Validation fields
        $attrs = $request->validate([
            'comment' => 'required|string',
        ]);

        Comment::create([
            'user_id' => auth()->user()->id,
            'post_id' => $id,
            'comment' => $attrs['comment'],
        ]);
        return response()->json([
            'message' => 'Comment created successfully',
        ], 201);
    }


    // uupdate comment
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response([
                'message' => 'Comment not found'
            ], 403);
        }
        if(comment->user_id != auth()->user()->id) {
            return response([
                'message' => 'You are not authorized to update this comment'
            ], 403);
        }

        // Validation fields
        $attrs = $request->validate([
            'comment' => 'required|string',
        ]);
        $comment->update([
            'comment' => $attrs['comment'],
        ]);


        return response([
            'message' => 'Comment updated successfully',
        ], 200);
    }

    // delete comment
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response([
                'message' => 'Comment not found'
            ], 403);
        }
        if(comment->user_id != auth()->user()->id) {
            return response([
                'message' => 'You are not authorized to delete this comment'
            ], 403);
        }

        $comment->delete();
        return response([
            'message' => 'Comment deleted successfully',
        ], 200);
    }

}
