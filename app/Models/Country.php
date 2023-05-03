<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use
    Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Models\Country
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property array<Post> $posts
 * @throws ModelNotFoundException
 * @property-read int|null $posts_count
 * @throws ModelNotFoundException
 * @method static Builder|Country findOrFail(int $id)
 * @method static Builder|Country newModelQuery()
 * @method static Builder|Country newQuery()
 * @method static Builder|Country query()
 * @method static Builder|Country whereCode($value)
 * @method static Builder|Country whereId($value)
 * @method static Builder|Country whereName($value)
 * @mixin \Eloquent
 */
class Country extends Model
{
    use HasFactory;

    public function posts(): HasManyThrough
    {
        return $this->hasManyThrough(Post::class, User::class);
    }
}
