<?php
declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property int $remember_token
 * @property string $created_at
 * @property string $updated_at
 *
 * @property array<Role> $roles
 * @property array<Post> $posts
 * @property array<Photo> $photos
 * @property array<Address> $addresses
 *
 * @throws ModelNotFoundException
 * @method static User findOrFail(int $id)
 * @method static Builder where(string $attribute, mixed $value)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var array<RoleEnum>|null
     */
    private ?array $rolesEnum = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'image_able');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function hasRole(RoleEnum $role): bool
    {
        return in_array($role, $this->getRoles(), true);
    }

    /**
     * @return array<RoleEnum>
     */
    public function getRoles(): array
    {
        if ($this->rolesEnum == null) {
            $this->rolesEnum = [RoleEnum::GUEST];
            foreach ($this->roles as $role) {
                $this->rolesEnum[] = $role->getEnumValue();
            }
        }

        return $this->rolesEnum;
    }
}
