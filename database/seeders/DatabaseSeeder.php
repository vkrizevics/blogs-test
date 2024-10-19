<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $usersWithPosts = User::factory(10)
            ->hasPosts(random_int(1, 20))
            ->create();

        $userWithoutPosts = User::factory(2)
            ->create(); // with no posts

        $allUsers = [$usersWithPosts, $userWithoutPosts];
        $posts = Post::all();
        foreach ($posts as $post) {
            foreach ($allUsers as $usersPart) {
                foreach ($usersPart as $user) {
                    $per_user = random_int(0, 3);
                    if ($per_user > 0) {
                        Comment::factory($per_user)
                            ->for($user)
                            ->for($post)
                            ->create();
                    }
                }
            }
        }
    }
}
