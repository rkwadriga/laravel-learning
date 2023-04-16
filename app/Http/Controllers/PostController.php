<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\PostService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
    /**
     * GET /posts
     *
     * @param PostService $postService
     * @return View
     */
    public function list(PostService $postService): View
    {
        $posts = $postService->getAll();

        return view('post.list', compact('posts'));
    }

    /**
     * GET /post/{id}
     *
     * @param PostService $postService
     * @param int $id
     * @return View
     */
    public function view(PostService $postService, int $id): View
    {
        $post = $postService->getPost($id);

        return view('post.view', compact('post'));
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
     * @param PostService $postService
     * @return RedirectResponse
     */
    public function store(CreatePostRequest $request, PostService $postService): RedirectResponse
    {
        $post = new Post();
        $post->user_id = 1;
        $postService->save($post, $request);

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
     * @param PostService $postService
     * @param int $id
     * @return RedirectResponse
     */
    public function edit(UpdatePostRequest $request, PostService $postService, int $id): RedirectResponse
    {
        $post = Post::findOrFail($id);
        $postService->save($post, $request);

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
