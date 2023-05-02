<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            /** @var Post $post */
            $post = Post::factory()->create();

            $subDir = $post->id;
            if (strlen($subDir) === 1) {
                $subDir = '0' . $subDir;
            }
            $dir = "public/img/post/{$subDir}/{$post->id}";
            $photos = array_filter(scandir($dir), function (string $file) {
                return in_array($file, ['.', '..'], true) || str_contains($file, '-') ? null : $file;
            });

            DB::table('photos')->insert([
                'path' => current($photos),
                'image_able_id' => $post->id,
                'image_able_type' => Post::class,
            ]);
        }
    }
}
