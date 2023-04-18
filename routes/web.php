<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;

Route::group(['middleware' => 'web'], function () {
    Route::get('/', [PostController::class, 'list'])->name('home');

    /*
    |--------------------------------------------------------------------------
    | Post Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/posts', [PostController::class, 'list'])->name('posts.list');

    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');

    Route::post('/post', [PostController::class, 'store'])->name('post.store');

    Route::get('/post/{id}/update', [PostController::class, 'update'])->name('post.update');

    Route::put('/post/{id}', [PostController::class, 'edit'])->name('post.edit');

    Route::delete('/post/{id}', [PostController::class, 'delete'])->name('post.delete');

    Route::get('/post/{id}', [PostController::class, 'view'])->name('post.view');
});


Auth::routes();
