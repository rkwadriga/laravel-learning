<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;

class PostController extends Controller
{
    /**
     * GET /posts
     */
    public function index()
    {
        /** @var User $user */
        $user = User::all()->find(1);

        /** @var Post $post */
        $post = Post::all()->find(1);

        /** @var Country $country */
        $country = Country::all()->find(2);

        /** @var Tag $tag */
        $tag = Tag::all()->where('name', '#fishing')->first();


        return $tag?->videos ?? 'NULL';
    }

    /**
     * GET /post/{id}
     *
     * @param int $id
     * @return View
     */
    public function view(int $id): View
    {
        return view('post', ['id' => $id]);
    }
}
