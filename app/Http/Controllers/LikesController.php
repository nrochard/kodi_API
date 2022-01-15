<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;

class LikesController extends Controller
{
    public function likePost(Request $request, $id)
    {
        $data = $request->json()->all();
        if (env('APP_ENV') == 'testing') {
            $user_id = $data['user_id'];
        } else {
            $user_id = auth()->user()->id;
        }

        $like = Like::where('user_id', '=', $user_id)
            ->where('post_id', '=', $id)
            ->first();



        if (isset($like)) {
            $like->delete();
        } else {
            Like::insert([
                'user_id' => $user_id,
                'post_id' => $id,
            ]);
        }


        return response()->json([
            'user_id' => $user_id,
            'post_id' => $id,
        ], 201);
    }


    public function likeComment(Request $request, $id)
    {
        $data = $request->json()->all();
        if (env('APP_ENV') == 'testing') {
            $user_id = $data['user_id'];
        } else {
            $user_id = auth()->user()->id;
        }

        Like::insert([
            'user_id' => $user_id,
            'post_id' => $request->post_id,
            'comment_id' => $id,
        ]);

        return response()->json([
            'user_id' => $user_id,
            'post_id' => $request->post_id,
            'comment_id' => $id,
        ], 201);
    }
}
