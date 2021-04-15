<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PassportAuthController;
use App\Http\Controllers\API\ProductController;

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

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
  
Route::middleware('auth:api')->group(function () {
    Route::get('/user/detail', [PassportAuthController::class, 'details']);
    Route::get('/user/update/{id}', [PassportAuthController::class, 'show']);
    Route::put('/user/update/{id}', [PassportAuthController::class, 'update']);
    Route::delete('/user/delete/{id}', [PassportAuthController::class, 'destroy']);
    Route::post('/logout', [PassportAuthController::class, 'logout']);
 
});
