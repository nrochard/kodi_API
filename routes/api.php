<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
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


Route::middleware('auth:sanctum')->post('auth/post', [PostsController::class, 'create']);

Route::post('auth/register', [UserController::class, 'register']);
Route::post('auth/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->put('auth/update/{id}', [UserController::class, 'update']);
Route::middleware('auth:sanctum')->post('auth/me', [UserController::class, 'me']);
Route::middleware('auth:sanctum')->post('auth/logout', [UserController::class, 'logout']);
