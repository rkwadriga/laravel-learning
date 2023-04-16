<?php declare(strict_types=1);
/**
 * Created 2023-04-16
 * Author Dmitry Kushneriov
 */

namespace App\Services;

use App\Exceptions\Img\FileNotFoundImgException;
use App\Exceptions\Img\ImgException;
use App\Exceptions\Img\InvalidFileExtensionImgException;
use App\Exceptions\Img\InvalidSizeFormatImgException;
use App\Exceptions\Img\NotAllowedSizeImgException;
use GdImage;

class ImgService
{
    public const BIG_SIZE = '600x300';

    public const MEDIUM_SIZE = '300x150';

    public const SMALL_SIZE = '150x75';

    public const MINIMUM_SIZE = '75x32';

    public const EXT_JPG = 'jpg';

    public const EXT_GPEG = 'jpg';

    public const EXT_PNG = 'png';

    public const EXT_GIF = 'gif';

    private const ALLOWED_SIZES = [
        self::BIG_SIZE,
        self::MEDIUM_SIZE,
        self::SMALL_SIZE,
        self::MINIMUM_SIZE,
    ];

    private const ALLOWED_EXTENSIONS = [
        self::EXT_JPG,
        self::EXT_GPEG,
        self::EXT_PNG,
        self::EXT_GIF,
    ];

    /**
     * @param string $sourcePath
     * @param string $size
     * @param bool $inversedSize
     * @return string
     * @throws FileNotFoundImgException
     * @throws InvalidFileExtensionImgException
     * @throws InvalidSizeFormatImgException
     * @throws NotAllowedSizeImgException
     */
    public function resize(string $sourcePath, string $size, bool $inversedSize = false): string
    {
        $this->checkSize($size);

        // If resized file already exist - just return it
        $targetPath = $this->getSizedPath($sourcePath, $size);
        if (file_exists($targetPath)) {
            return $targetPath;
        }

        if (!file_exists($sourcePath)) {
            throw new FileNotFoundImgException(sprintf('Image "%s" does not exist', $sourcePath));
        }

        // If original image size it the same like requested size - just return the original
        [$sourceW, $sourceH] = getimagesize($sourcePath);
        [$targetW, $targetH] = $this->getSize($size);
        if ($inversedSize) {
            $oldW = $targetW;
            $targetW = $targetH;
            $targetH = $oldW;
        }
        if ($targetW === $sourceW && $targetH === $sourceH) {
            return $sourcePath;
        }

        // Calculate new width and height
        [$newW, $newH, $newX, $newY] = [$sourceW, $sourceH, 0, 0];
        [$sourceK, $targetK] = [$sourceW / $sourceH, $targetW / $targetH];
        if ($sourceK < $targetK) {
            // Need to make an image wider to make it's width and height ratio as in requested image
            //$borderW = (int) (($sourceH * $targetK - $sourceW));
            $newW = (int) ($sourceH * $targetK);
            $newX = (int) (($sourceH * $targetK - $sourceW) / 2);
        } else if ($sourceK > $targetK) {
            // Need to make an image higher to make it's width and height ratio as in requested image
            //$borderH = (int) (($sourceW / $targetK - $sourceH));
            $newH = (int) ($sourceW / $targetK);
            $newY = (int) (abs($newH - $targetH) / 2);
        }

        dump("{$sourceW}x{$sourceH} => {$targetW}x{$targetH} => {$newW}x{$newH}; x: {$newX}, y: {$newY}; sourceK: {$sourceK}, targetK: {$targetK}");

        // Add borders to original image, resize it and save with a new name
        $src = $this->createImgFromFile($sourcePath);
        $target = imagecreatetruecolor($targetW, $targetH);
        imagecopyresampled(
            $target,
            $src,
            $newX,
            $newY,
            0,
            0,
            $targetW,
            $targetH,
            $newW,
            $newH
        );
        dump($src, $target);

        $this->saveImageToFile($target, $targetPath);

        return $targetPath;
    }

    private function getExt(string $path): string
    {
        preg_match("/^.+\.(\w+)$/", $path, $matches);

        return $matches[1];
    }

    private function createImgFromFile(string $path): GdImage
    {
        switch ($this->getExt($path)) {
            case self::EXT_JPG:
            case self::EXT_GPEG:
                $image = imagecreatefromjpeg($path);
                break;
            case self::EXT_PNG:
                $image = imagecreatefrompng($path);
                break;
            default:
                $image = imagecreatefromgif($path);
                break;
        }

        return $image;
    }

    private function saveImageToFile(GdImage $img, string $targetPath): void
    {
        switch ($this->getExt($targetPath)) {
            case self::EXT_JPG:
            case self::EXT_GPEG:
                imagejpeg($img, $targetPath);
                break;
            case self::EXT_PNG:
                imagepng($img, $targetPath);
                break;
            default:
                imagegif($img, $targetPath);
                break;
        }
    }

    /**
     * @param string $path
     * @param string $size
     * @param string $prefix
     * @return string
     * @throws InvalidFileExtensionImgException
     */
    private function getSizedPath(string $path, string $size, string $prefix = '-'): string
    {
        $allowedExtensions = implode('|', self::ALLOWED_EXTENSIONS);
        $pattern = "/^(.+)\\.({$allowedExtensions})$/";
        if (!preg_match($pattern, $path)) {
            throw new InvalidFileExtensionImgException(sprintf(
                'Invalid original file "%s" extension. Allowed extensions: %s',
                $path,
                $allowedExtensions
            ));
        }

        return preg_replace($pattern, "$1{$prefix}{$size}.$2", $path);
    }

    /**
     * @param string $size
     * @return void
     * @throws InvalidSizeFormatImgException
     * @throws NotAllowedSizeImgException
     */
    private function checkSize(string $size): void
    {
        if (!preg_match("/^\d+x\d+$/", $size)) {
            throw new InvalidSizeFormatImgException(sprintf('Invalid image size format: "%s"', $size));
        }

        if (!in_array($size, self::ALLOWED_SIZES, true)) {
            throw new NotAllowedSizeImgException(sprintf(
                'Not allowed image size: "%s", allowed sizes: %s',
                $size,
                implode(', ', self::ALLOWED_SIZES)
            ));
        }
    }

    /**
     * @param string $size
     * @return array<int>
     */
    private function getSize(string $size): array
    {
        $sizes = explode('x', $size);

        return [(int) $sizes[0], (int) $sizes[1]];
    }
}
