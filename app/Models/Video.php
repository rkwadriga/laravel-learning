<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * App\Models\Video
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property array<Tag> $tags
 * @property-read int|null $tags_count
 * @throws ModelNotFoundException
 * @method static Builder|Video findOrFail(int $id)
 * @method static Builder|Video newModelQuery()
 * @method static Builder|Video newQuery()
 * @method static Builder|Video query()
 * @method static Builder|Video whereCreatedAt($value)
 * @method static Builder|Video whereId($value)
 * @method static Builder|Video whereName($value)
 * @method static Builder|Video whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Video extends Model
{
    use HasFactory;

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
