<?php declare(strict_types=1);
/**
 * Created 2023-04-16
 * Author Dmitry Kushneriov
 */

namespace App\Services;

use App\Entities\ResizeConfigEntity;
use App\Exceptions\Img\CanNotCreateBackGroundImgException;
use App\Exceptions\Img\CanNotCreateFromFileImgException;
use App\Exceptions\Img\CanNotGetSizeImgException;
use App\Exceptions\Img\CanNotResizeImgException;
use App\Exceptions\Img\CanNotSaveImgException;
use App\Exceptions\Img\FileNotFoundImgException;
use App\Exceptions\Img\ImgException;
use App\Exceptions\Img\InvalidFileExtensionImgException;
use App\Exceptions\Img\InvalidSizeFormatImgException;
use App\Exceptions\Img\NotAllowedSizeImgException;
use Exception;
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
     * @param string|array<string, mixed>|ResizeConfigEntity $size
     * @return string
     * @throws ImgException
     */
    public function resize(string $sourcePath, string|array|ResizeConfigEntity $size): string
    {
        $config = $this->createConfigEntity($size);
        $this->checkSize($config->size);

        // If resized file already exist - just return it
        $targetPath = $this->getSizedPath($sourcePath, $config->getSize());
        if (file_exists($targetPath)) {
            return $targetPath;
        }

        // Check the source file
        if (!file_exists($sourcePath)) {
            throw new FileNotFoundImgException(sprintf('Image "%s" does not exist', $sourcePath));
        }

        // If original image size it the same like requested size - just return the original
        [$sourceW, $sourceH] = $this->getImageSize($sourcePath);
        [$targetW, $targetH] = [$config->getWidth(), $config->getHeight()];
        if ($targetW === $sourceW && $targetH === $sourceH) {
            return $sourcePath;
        }

        // Calculate new width and height
        [$newW, $newH, $newX, $newY] = [$targetW, $targetH, 0, 0];
        [$sourceK, $targetK] = [$sourceW / $sourceH, $targetW / $targetH];
        if ($sourceK > $targetK) {
            // Need to make an image higher to make it's width and height ratio as in requested image
            $newH = $targetW / $sourceK;
            $newY = (int) (abs($targetH - $newH) / 2);
            $newH = (int) $newH;
        } else if ($sourceK < $targetK) {
            // Need to make an image wider to make it's width and height ratio as in requested image
            $newW = $targetH * $sourceK;
            $newX = (int) (abs($targetW - $newW) / 2);
            $newW = (int) $newW;
        }

        // Create the source image from source file and target image with target size
        $src = $this->createImgFromFile($sourcePath);
        $target = $this->createBackGroundImage($config);

        // Resize the source image and put it on the target
        $this->imposeImages(
            $target, $src,
            $newX, $newY,
            $newW, $newH,
            $sourceW, $sourceH
        );

        $this->saveImageToFile($target, $targetPath);

        return $targetPath;
    }

    /**
     * @param string $image
     * @return array<int>
     * @throws CanNotGetSizeImgException
     */
    private function getImageSize(string $image): array
    {
        $error = null;
        try {
            $result = getimagesize($image);
            if ($result === false) {
                $error = $this->getError();
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        if ($error !== null) {
            throw new CanNotGetSizeImgException(
                sprintf('Can not get the size of image "%s": %s', $image, $error),
                0,
                $e ?? null
            );
        }

        return [(int) $result[0], (int) $result[1]];
    }

    private function createConfigEntity(string|array|ResizeConfigEntity $size): ResizeConfigEntity
    {
        if (is_string($size)) {
            return new ResizeConfigEntity(['size' => $size]);
        } elseif (is_array($size)) {
            return new ResizeConfigEntity($size);
        } else {
            return $size;
        }
    }

    private function getExt(string $path): string
    {
        preg_match("/^.+\.(\w+)$/", $path, $matches);

        return $matches[1];
    }

    /**
     * @param string $path
     * @return GdImage
     * @throws CanNotCreateFromFileImgException
     */
    private function createImgFromFile(string $path): GdImage
    {
        $error = null;
        try {
            $image = match ($this->getExt($path)) {
                self::EXT_JPG, self::EXT_GPEG => imagecreatefromjpeg($path),
                self::EXT_PNG => imagecreatefrompng($path),
                default => imagecreatefromgif($path),
            };
            if ($image === false) {
                $error = $this->getError();
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        if ($error !== null) {
            throw new CanNotCreateFromFileImgException(
                sprintf('Can not create an image from file "%s": %s', $path, $error),
                0,
                $e ?? null
            );
        }

        return $image;
    }

    /**
     * @param ResizeConfigEntity $config
     * @return GdImage
     * @throws CanNotCreateBackGroundImgException
     */
    private function createBackGroundImage(ResizeConfigEntity $config): GdImage
    {
        try {
            $target = imagecreatetruecolor($config->getWidth(), $config->getHeight());
            if ($target === false) {
                throw new Exception($this->getError('imagecreatetruecolor error'));
            }

            // Set the target image background color
            $transparency = imagecolorallocatealpha(
                $target,
                $config->backgroundR,
                $config->backgroundG,
                $config->backgroundB,
                $config->backgroundAlpha
            );
            if ($transparency === false) {
                throw new Exception($this->getError('imagecolorallocatealpha error'));
            }

            $result = imagefill($target, 0, 0, $transparency);
            if ($result === false) {
                throw new Exception($this->getError('imagefill error'));
            }
        } catch (Exception $e) {
            throw new CanNotCreateBackGroundImgException(
                sprintf(
                    'Can not create background image with size "%sx%s"',
                    $config->getWidth(), $config->getHeight()
                ),
                0,
                $e
            );
        }

        return $target;
    }

    /**
     * @param GdImage $target
     * @param GdImage $source
     * @param int $newX
     * @param int $newY
     * @param int $newW
     * @param int $newH
     * @param int $sourceW
     * @param int $sourceH
     * @throws CanNotResizeImgException
     */
    private function imposeImages(
        GdImage $target,
        GdImage $source,
        int $newX,
        int $newY,
        int $newW,
        int $newH,
        int $sourceW,
        int $sourceH
    ): void {
        $error = null;
        try {
            $result = imagecopyresampled(
                $target, $source,
                $newX, $newY,
                0, 0,
                $newW, $newH,
                $sourceW, $sourceH
            );
            if ($result === false) {
                $error = $this->getError('imagecopyresampled error');
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        if ($error !== null) {
            throw new CanNotResizeImgException(
                sprintf(
                    'Can not resize image from "%sx%s" to ""%sx%s"": %s',
                    $sourceW,
                    $sourceH,
                    $newW,
                    $newH,
                    $error
                ),
                0,
                $e ?? null
            );
        }
    }

    /**
     * @param GdImage $img
     * @param string $path
     * @throws CanNotSaveImgException
     */
    private function saveImageToFile(GdImage $img, string $path): void
    {
        $error = null;
        try {
            $result = match ($this->getExt($path)) {
                self::EXT_JPG, self::EXT_GPEG => imagejpeg($img, $path),
                self::EXT_PNG => imagepng($img, $path),
                default => imagegif($img, $path),
            };
            if ($result === false) {
                $error = $this->getError();
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        if ($error !== null) {
            throw new CanNotSaveImgException(
                sprintf('Can not save image to file "%s": %s', $path, $error),
                0,
                $e ?? null
            );
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

    private function getError(string $defaultError = 'Unknown error'): string
    {
        $error = error_get_last();
        if (is_array($error) && isset($error['message'])) {
            return $error['message'];
        } else {
            return $defaultError;
        }
    }
}
