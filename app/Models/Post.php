<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $created_at
 * @property string $updated_at
 * @property string $content
 *
 * @property User $user
 * @property Collection|array<Photo> $photos
 * @property Collection|array<Tag> $tags
 *
 * @throws ModelNotFoundException
 * @method static Post findOrFail(int $id)
 * @method static array<Post> oldestFirst()
 */
class Post extends Model
{
    use HasFactory;

    private ?string $photo = null;

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

    /*public function getTitleAttribute(string $value): string
    {
        return strtolower($value);
    }

    public function setTitleAttribute(string $value)
    {
        $this->attributes['title'] = strtoupper($value);
    }*/

    public static function scopeOldestFirst(Builder $query)
    {
        return $query->orderBy('id', 'desc')->get()->all();
    }

    public function setPhoto(string $path): self
    {
        $this->photo = $path;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }
}
