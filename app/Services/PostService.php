<?php declare(strict_types=1);
/**
 * Created 2023-04-16
 * Author Dmitry Kushneriov
 */

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostService
{
    public function __construct(
        private readonly PhotoService $photoService
    ) {
    }

    public function save(Post $post, Request $request): void
    {
        $post->fill($request->all(['title', 'content']));
        $post->save();
        $this->photoService->uploadPhoto($request, $post);
    }

    public function getPost(int $id, string $photoSize = ImgService::GIANT_SIZE): Post
    {
        $post = Post::findOrFail($id);
        $this->setPhoto($post, $photoSize);

        return $post;
    }

    /**
     * @return array<Post>
     */
    public function getAll($photoSize = ImgService::MINIMUM_SIZE): array
    {
        $posts = Post::oldestFirst();
        $this->prepare($posts, $photoSize);

        return $posts;
    }

    public function getByUser(User $user, $photoSize = ImgService::MINIMUM_SIZE): array
    {
        /** @var array<Post> $posts */
        $posts = Post::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get()->all();
        $this->prepare($posts, $photoSize);

        return $posts;
    }

    /**
     * @param array<Post> $posts
     * @param string $photoSize
     * @return void
     */
    private function prepare(array $posts, string $photoSize): void
    {
        foreach ($posts as $post) {
            $this->setPhoto($post, $photoSize);
        }
    }

    private function setPhoto(Post $post, string $size, ?bool $invertedSize = null): void
    {
        // If there is needed to inverse the image size - convert "size" param to array with resize configuration
        if ($invertedSize !== null) {
            $size = [
                'size' => $size,
                'invertedSize' => $invertedSize,
            ];
        }

        if ($post->photos->count() > 0) {
            $post->setPhoto($this->photoService->getUrl($post->photos[0], $size));
        }
    }
}
