<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users_with_posts = User::factory(10)
            ->create();

        $users_without_posts = User::factory(random_int(2, 10))
            ->create(); // with no posts

        $this->call([
            PostSeeder::class,
            CommentSeeder::class,
            CategorySeeder::class,
            CategoryPostSeeder::class,
        ]);
    }
}
