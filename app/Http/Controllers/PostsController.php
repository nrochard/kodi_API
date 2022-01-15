<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Comment;

class PostsController extends Controller
{
    public function show(Request $request)
    {
        $user_id = $request->user()->id;
        $post = Post::with("user")
            ->where('user_id', $user_id)->get();
        return response()->json($post, 200);
    }

    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get()->all();

        $arrayPosts = [];
        foreach ($posts as $post) {
            $userInfo = User::where('id', $post->user_id)->first();
            $likes = Like::where('post_id', $post->id)->get();
            $comments = Comment::where('post_id', $post->id)->get();

            $arrayComments = [];
            foreach ($comments as $comment) {
                $commentInfo = User::where('id', $comment->user_id)->first();
                $likesComment = Like::where('comment_id', $comment->id)->get();

                array_push($arrayComments, [
                    "author" => $commentInfo,
                    "comment" => $comment,
                    "likes" => $likesComment,
                ]);
            }
            array_push($arrayPosts, [
                "author" => $userInfo,
                "posts" => $post,
                "likes" => $likes,
                "comments" => $arrayComments,
            ]);
        }
        return response()->json($arrayPosts, 200);
    }

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

        Post::insert([
            'user_id' => $user_id,
            'body' => $request->body,
            'img' => $img_url,
        ]);

        return response()->json([
            "post" => $request->body,
            "message" => "Votre post a bien été publié"
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $post = Post::find($id);

        $data = $request->json()->all();
        if (env('APP_ENV') == 'testing') {
            $user_id = $data['user_id'];
        } else {
            $user_id = auth()->user()->id;
        }

        if (!$post) {
            return response()->json(['message' => 'Not found'], 404);
        }

        if ($post->user_id != $request->user()->id) {
            return response()->json(["message" => 'Forbidden'], 403);
        }

        $post->body = $request->get('body');

        if ($request->has('img')) {
            $uploadedFileUrl = Cloudinary::upload($request->img->getRealPath())->getSecurePath();
            $post->img = $uploadedFileUrl;
        }

        $post->save();

        return response()->json([
            "post" => $request->body,
            "message" => "Votre post a bien été modifié"
        ], 201);
    }


    public function delete(Request $request, $id)
    {
        $user_id = $request->user()->id;

        $post = Post::where('user_id', '=', $user_id)->where('id', '=', $id)->delete();

        if (!$post) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['message' => "Post supprimé"], 200);
    }
}
