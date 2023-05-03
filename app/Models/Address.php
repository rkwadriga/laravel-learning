<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Address
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @throws ModelNotFoundException
 * @method static Builder|Address findOrFail(int $id)
 * @method static Builder|Address newModelQuery()
 * @method static Builder|Address newQuery()
 * @method static Builder|Address query()
 * @method static Builder|Address whereCreatedAt($value)
 * @method static Builder|Address whereId($value)
 * @method static Builder|Address whereName($value)
 * @method static Builder|Address whereUpdatedAt($value)
 * @method static Builder|Address whereUserId($value)
 * @mixin \Eloquent
 */
class Address extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
