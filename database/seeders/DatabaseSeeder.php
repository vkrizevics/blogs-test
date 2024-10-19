<?php

namespace Database\Seeders;

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
        $usersWithPosts = User::factory(10)
            ->hasPosts(random_int(1, 20))
            ->create();

        $userWithoutPosts = User::factory(2)
            ->create(); // with no posts

        $allUsers = [$usersWithPosts, $userWithoutPosts];
        $posts = Post::all();
        foreach ($posts as $post) {
            $comments_needed = random_int(0, 3);
            if (!$comments_needed) {
                continue;
            }

            $usersNeeded = [];
            foreach ($allUsers as $usersPart) {
                foreach ($usersPart as $user) {
                    $per_user = random_int(0, 3);
                    for ($i = 0; $i < $per_user; $i++) {
                        $usersNeeded[] = $user;
                    }
                }
            }

            $usersRandomized = Arr::shuffle($usersNeeded);
            foreach ($usersRandomized as $userR) {
                Comment::factory(1)
                    ->for($userR)
                    ->for($post)
                    ->create();
            }
        }
    }
}
