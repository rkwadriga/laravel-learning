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
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property int $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property array<Role> $roles
 * @property array<Post> $posts
 * @property array<Photo> $photos
 * @property array<Address> $addresses
 * @property int|null $country_id
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $photos_count
 * @property-read int|null $posts_count
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @throws ModelNotFoundException
 * @method static Builder|User findOrFail(int $id)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCountryId($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
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
