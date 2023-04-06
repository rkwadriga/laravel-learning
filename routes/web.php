<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\PostController;

Route::get('/', [PostController::class, 'list'])->name('home');

/*
|--------------------------------------------------------------------------
| Post Routes
|--------------------------------------------------------------------------
*/
Route::get('/posts', [PostController::class, 'list'])->name('posts.list');

Route::get('/post/create', [PostController::class, 'create'])->name('post.create_form');

Route::post('/post', [PostController::class, 'create'])->name('post.create');

Route::get('/post/{id}/update', [PostController::class, 'update'])->name('post.update_form');

Route::put('/post/{id}', [PostController::class, 'update'])->name('post.update');

Route::delete('/post/{id}', [PostController::class, 'delete'])->name('post.delete');

Route::get('/post/{id}', [PostController::class, 'view'])->name('post.view');
