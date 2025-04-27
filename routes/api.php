<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;

// public routes 
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// protected routes 
Route::group(['middleware'=>'auth:sanctum'], function () {
    // user
    Route::get('/user', [AuthController::class,'user']);
    Route::put('/user', [AuthController::class,'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //Post 
    Route::get('/posts', [PostController::class, 'index']); // all Posts  
    Route::get('/posts/{id}', [PostController::class, 'show']); // get single Post
    Route::post('/posts', [PostController::class, 'store']); // create Post
    Route::put('/posts/{id}', [PostController::class, 'update']); // update Post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']); // delete Post

    // Comment
    // Get comments for a specific post
    Route::get('/posts/{id}/comments', [CommentController::class, 'index']);// all Comments
    Route::post('/posts/{id}/comments', [CommentController::class, 'store']); // create Comment
    Route::put('/comments/{id}', [CommentController::class, 'update']); // update Comment
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']); // delete Comment

    // Like
    Route::post('/posts/{id}/likes', [LikeController::class, 'likeOrUnlike']); // like or dislike Post
    

});