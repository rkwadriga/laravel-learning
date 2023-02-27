<?php
declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    case GUEST = 'GUEST';
    case USER = 'USER';
    case MANAGER = 'MANAGER';
    case ADMIN = 'ADMIN';
}
