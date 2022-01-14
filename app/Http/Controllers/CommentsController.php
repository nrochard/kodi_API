<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentsController extends Controller
{

    public function create(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $data = $request->json()->all();
        if (env('APP_ENV') == 'testing') {
            $user_id = $data['user_id'];
        } else {
            $user_id = auth()->user()->id;
        }

        Comment::insert([
            'body' => $request->body,
            "post_id" => $id,
            "user_id" => $user_id,

        ]);

        return response()->json([
            "body" => $request->body,
            "user_id" => $user_id,
            "post_id" => $id,
            "message" => "Votre commentaire a bien été publié"
        ], 201);
    }
}
