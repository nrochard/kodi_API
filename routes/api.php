<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\LikesController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->get('auth/posts', [PostsController::class, 'index']);
Route::middleware('auth:sanctum')->get('auth/post', [PostsController::class, 'show']);
Route::middleware('auth:sanctum')->post('auth/post', [PostsController::class, 'create']);
Route::middleware('auth:sanctum')->post('auth/post/{id}', [PostsController::class, 'update']);
Route::middleware('auth:sanctum')->post('auth/post/comment/{id}', [CommentsController::class, 'create']);
Route::middleware('auth:sanctum')->delete('auth/post/delete/{id}', [PostsController::class, 'delete']);

Route::middleware('auth:sanctum')->post('auth/post/like/post/{id}', [LikesController::class, 'likePost']);
Route::middleware('auth:sanctum')->post('auth/post/like/comment/{id}', [LikesController::class, 'likeComment']);


Route::post('auth/register', [UserController::class, 'register']);
Route::post('auth/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->post('auth/update/{id}', [UserController::class, 'update']);
Route::middleware('auth:sanctum')->post('auth/me', [UserController::class, 'me']);
Route::middleware('auth:sanctum')->get('auth/user/{id}', [UserController::class, 'userInfo']);
Route::middleware('auth:sanctum')->post('auth/logout', [UserController::class, 'logout']);
