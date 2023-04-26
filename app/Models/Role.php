<?php
declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $name
 * @property string $rights
 * @property string $description
 *
 * @throws ModelNotFoundException
 * @method static Role findOrFail(int|array $id)
 *
 * @method static Builder where(string $attribute, mixed $value)
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'rights', 'description'];

    private ?RoleEnum $enumValue = null;

    public function getEnumValue(): RoleEnum
    {
        if ($this->enumValue === null) {
            $this->enumValue = match($this->name) {
                RoleEnum::USER->value => RoleEnum::USER,
                RoleEnum::MANAGER->value => RoleEnum::MANAGER,
                RoleEnum::ADMIN->value => RoleEnum::ADMIN,
                default => RoleEnum::GUEST
            };
        }

        return $this->enumValue;
    }
}
