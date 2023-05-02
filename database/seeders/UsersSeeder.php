<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var Role $userRole */
        $userRole = Role::where('name', RoleEnum::USER->value)->first();

        for ($i = 1; $i <= 5; $i++) {
            /** @var User $user */
            $user = User::factory()->create([
                'name' => "User {$i}",
                'email' => "user{$i}@mail.com",
                'password' => bcrypt('12345678'),
            ]);

            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $userRole->id,
            ]);

            $subDir = $user->id;
            if (strlen($subDir) === 1) {
                $subDir = '0' . $subDir;
            }
            $dir = "public/img/user/{$subDir}/{$user->id}";
            $photos = array_filter(scandir($dir), function (string $file) {
                return in_array($file, ['.', '..'], true) || str_contains($file, '-') ? null : $file;
            });

            DB::table('photos')->insert([
                'path' => current($photos),
                'image_able_id' => $user->id,
                'image_able_type' => User::class,
            ]);
        }
    }
}
