<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use
    Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @property array<Post> $posts
 *
 * @throws ModelNotFoundException
 * @method static Country findOrFail(int $id)
 * @method static Builder where(string $attribute, mixed $value)
 */
class Country extends Model
{
    use HasFactory;

    public function posts(): HasManyThrough
    {
        return $this->hasManyThrough(Post::class, User::class);
    }
}
