<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Request as Http;
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
     * GET|POST /post/create
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->getMethod() === Http::METHOD_GET) {
            return view('post.create');
        }

        $post = new Post();
        $post->user_id = 1;
        $post->fill($request->all(['title', 'content']));
        $post->save();

        return redirect()->action([self::class, 'view'], ['id' => $post->id]);
    }

    /**
     * PUT /post/{id}
     *
     * @param Request $request
     * @param int $id
     * @return View|RedirectResponse
     */
    public function update(Request $request, int $id): View|RedirectResponse
    {
        $post = Post::findOrFail($id);

        if ($request->getMethod() === Http::METHOD_GET) {
            return view('post.update', ['post' => $post]);
        }

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
