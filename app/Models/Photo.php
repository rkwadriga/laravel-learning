<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Photo
 *
 * @property int $id
 * @property string $path
 * @property int $image_able_id
 * @property string $image_able_type
 * @property string $created_at
 * @property string $updated_at
 * @property-read Model|\Eloquent $imageAble
 * @throws ModelNotFoundException
 * @method static Builder|Photo findOrFail(int $id)
 * @method static Builder|Photo newModelQuery()
 * @method static Builder|Photo newQuery()
 * @method static Builder|Photo query()
 * @method static Builder|Photo whereCreatedAt($value)
 * @method static Builder|Photo whereId($value)
 * @method static Builder|Photo whereImageAbleId($value)
 * @method static Builder|Photo whereImageAbleType($value)
 * @method static Builder|Photo wherePath($value)
 * @method static Builder|Photo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Photo extends Model
{
    use HasFactory;

    public function imageAble(): MorphTo
    {
        return $this->morphTo();
    }
}
