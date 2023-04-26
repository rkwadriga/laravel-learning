<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

            DB::table('photos')->insert([
                'path' => scandir($dir)[2],
                'image_able_id' => $post->id,
                'image_able_type' => Post::class,
            ]);
        }
    }
}
