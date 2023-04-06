<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @property int $id
 * @property string $name
 * @property string $rights
 * @property string $description
 *
 * @throws ModelNotFoundException
 * @method static Role findOrFail(int $id)
 */
class Role extends Model
{
    use HasFactory;
}
