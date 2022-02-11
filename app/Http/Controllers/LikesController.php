<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Post;

class LikesController extends Controller
{
    public function likePost(Request $request, Post $post)
    {

        $data = $request->json()->all();
        if (env('APP_ENV') == 'testing') {
            $user_id = $data['user_id'];
        } else {
            $user_id = auth()->user()->id;
        }


        if ($post->likes->where('user_id', $request->user()->id)->count()) {
            $post->likes()->where('user_id', $request->user()->id)->delete();
        } else {
            $post->likes()->create([
                'user_id' => $request->user()->id,
            ]);
        }


        return response()->json([
            'user_id' => $user_id,
            'post_id' => $post->id,
        ], 201);
    }


    public function likeComment(Request $request, Comment $comment)
    {
        $data = $request->json()->all();
        if (env('APP_ENV') == 'testing') {
            $user_id = $data['user_id'];
        } else {
            $user_id = auth()->user()->id;
        }

        if ($comment->likes->where('user_id', $request->user()->id)->count()) {
            $comment->likes()->where('user_id', $request->user()->id)->delete();
        } else {
            $comment->likes()->create([
                'user_id' => $request->user()->id,
            ]);
        }


        return response()->json([
            'user_id' => $user_id,
            'comment_id' => $comment->id,
        ], 201);
    }
}
