<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTokenController;
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


Route::post('auth/register', [ApiTokenController::class, 'register']);
Route::post('auth/login', [ApiTokenController::class, 'login']);
Route::middleware('auth:sanctum')->post('auth/me', [ApiTokenController::class, 'me']);
Route::middleware('auth:sanctum')->post('auth/logout', [ApiTokenController::class, 'logout']);