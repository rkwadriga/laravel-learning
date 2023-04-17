<?php declare(strict_types=1);
/**
 * Created 2023-04-16
 * Author Dmitry Kushneriov
 */

namespace App\Services;

use App\Models\Post;
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

    public function getPost(int $id, string $photoSize = ImgService::MEDIUM_SIZE): Post
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
        foreach ($posts as $post) {
            $this->setPhoto($post, $photoSize);
        }

        return $posts;
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
