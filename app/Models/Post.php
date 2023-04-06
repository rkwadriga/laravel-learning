<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\ModelNotFoundException;


/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $created_at
 * @property string $updated_at
 * @property string $content
 *
 * @property User $user
 * @property array<Photo> $photos
 * @property array<Tag> $tags
 *
 * @throws ModelNotFoundException
 * @method static Post findOrFail(int $id)
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content'];

    public function user(): BelongsTo
     {
         return $this->belongsTo(User::class);
     }

    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'image_able');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
