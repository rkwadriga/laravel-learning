<?php declare(strict_types=1);
/**
 * Created 2023-04-16
 * Author Dmitry Kushneriov
 */

namespace App\Services;

use App\Models\Photo;
use App\Models\Post;
use Illuminate\Http\Request;

class PhotoService
{
    private const BASE_PATH = 'img';

    public function __construct(
        private readonly ImgService $imgService
    ) {
    }

    public function uploadPhoto(Request $request, Post $post, $attributeName = 'photo'): ?Photo
    {
        $file = $request->file($attributeName);
        if ($file === null) {
            return null;
        }

        $photo = new Photo();
        $photo->image_able_type = Post::class;
        $photo->image_able_id = $post->id;
        $photo->path = $this->generateFileName();

        $fullPath = $this->getPhotoPath($photo);
        $file->move(dirname($fullPath), $photo->path);

        $photo->save();

        return $photo;
    }

    public function getUrl(Photo $photo, string|array $size): string
    {
        return '/' . $this->imgService->resize($this->getPhotoPath($photo), $size);
    }

    public function getPhotoPath(Photo $photo): string
    {
        $typeParts = explode('\\', $photo->image_able_type);

        return implode(DIRECTORY_SEPARATOR, [
            $this->getBasePath(),
            strtolower(last($typeParts)),
            substr((string) $photo->image_able_id, 0, 2),
            $photo->image_able_id,
            $photo->path
        ]);
    }

    public function getBasePath(): string
    {
        return self::BASE_PATH;
    }

    private function generateFileName(): string
    {
        return uniqid() . '.jpg';
    }
}
