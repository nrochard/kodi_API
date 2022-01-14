<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Like;

class PostsController extends Controller
{
    public function create(Request $request)
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
        $img_url = "";
        if ($request->has('img')) {

            $uploadedFileUrl = Cloudinary::upload($request->img->getRealPath())->getSecurePath();
            $img_url = $uploadedFileUrl;
        }

        $post = Post::insert([
            'user_id' => $user_id,
            'body' => $request->body,
            'img' => $img_url,
        ]);

        return response()->json([
            "post" => $request->body,
            "message" => "Votre post a bien été publié"
        ], 201);
    }
}
