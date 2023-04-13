<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
    /**
     * GET /posts
     *
     * @return View
     */
    public function list(): View
    {
        return view('post.list', ['posts' => Post::all()]);
    }

    /**
     * GET /post/{id}
     *
     * @param int $id
     * @return View
     */
    public function view(int $id): View
    {
        return view('post.view', ['post' => Post::findOrFail($id)]);
    }

    /**
     * GET /post/create
     *
     * @return View
     */
    public function create(): View
    {
        return view('post.create');
    }

    /**
     * POST /post
     *
     * @param CreatePostRequest $request
     * @return RedirectResponse
     */
    public function store(CreatePostRequest $request): RedirectResponse
    {
        $post = new Post();
        $post->user_id = 3;
        $post->fill($request->all(['title', 'content']));
        $post->save();

        return redirect()->action([self::class, 'view'], ['id' => $post->id]);
    }

    /**
     * GET /post/{id}/update
     *
     * @param int $id
     * @return View
     */
    public function update(int $id): View
    {
        return view('post.update', ['post' => Post::findOrFail($id)]);
    }

    /**
     * PUT /post/{id}
     *
     * @param UpdatePostRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(UpdatePostRequest $request, int $id): RedirectResponse
    {
        $post = Post::findOrFail($id);

        $post->fill($request->all(['title', 'content']));
        $post->save();

        return redirect()->action([self::class, 'view'], ['id' => $post->id]);
    }

    /**
     * DELETE /post/{id}
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->action([self::class, 'list']);
    }
}
