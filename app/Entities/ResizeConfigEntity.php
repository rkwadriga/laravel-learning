<?php declare(strict_types=1);
/**
 * Created 2023-04-17
 * Author Dmitry Kushneriov
 */

namespace App\Entities;

use App\Services\ImgService;

class ResizeConfigEntity extends AbstractEntity
{
    public string $size = ImgService::MEDIUM_SIZE;

    private ?int $width = null;

    private ?int $height = null;

    public bool $invertedSize = false;

    public int $backgroundR = 245;

    public int $backgroundG = 245;

    public int $backgroundB = 245;

    public int $backgroundAlpha = 127;

    private bool $inverted = false;

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;
        $this->inverted = false;

        return $this;
    }

    public function getSize(): string
    {
        if ($this->invertedSize && !$this->inverted) {
            [$this->height, $this->width] = $this->parseSize($this->size);
            $this->size = "{$this->width}x{$this->height}";
            $this->inverted = true;
        } else {
            [$this->width, $this->height] = $this->parseSize($this->size);
        }

        return $this->size;
    }

    public function getWidth(): int
    {
        if ($this->width === null) {
            $this->getSize();
        }

        return $this->width;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getHeight(): int
    {
        if ($this->height === null) {
            $this->getSize();
        }

        return $this->height;
    }

    /**
     * @param string $size
     * @return array<int>
     */
    private function parseSize(string $size): array
    {
        $sizes = explode('x', $size);

        return is_array($sizes) ? [(int) $sizes[0], (int) $sizes[1]] : [0, 0];
    }
}
