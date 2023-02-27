<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $path
 * @property int $image_able_id
 * @property string $image_able_type
 * @property string $created_at
 * @property string $updated_at
 */
class Photo extends Model
{
    use HasFactory;

    public function imageAble(): MorphTo
    {
        return $this->morphTo();
    }
}
