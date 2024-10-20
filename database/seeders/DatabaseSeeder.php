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
        $users_with_posts = User::factory(10)
            ->create();

        $users_without_posts = User::factory(random_int(2, 10))
            ->create(); // with no posts

        $users_for_posts = [];
        foreach ($users_with_posts as $user) {
            $has_n_posts = random_int(1, 20);
            for ($n = 0; $n < $has_n_posts; $n++) {
                $users_for_posts[] = $user;
            }
        }

        $users_for_posts_randomized = Arr::shuffle($users_for_posts);
        foreach ($users_for_posts_randomized as $user_p_r) {
            Post::factory(1)
                ->for($user_p_r)
                ->create();
        }

        $posts = Post::all();
        $all_users = [$users_with_posts, $users_without_posts];
        foreach ($posts as $post) {
            $comments_needed = random_int(0, 3);
            if (!$comments_needed) {
                continue;
            }

            $users_needed = [];
            foreach ($all_users as $users_part) {
                foreach ($users_part as $user) {
                    $per_user = random_int(0, 3);
                    for ($i = 0; $i < $per_user; $i++) {
                        $users_needed[] = $user;
                    }
                }
            }

            $users_randomized = Arr::shuffle($users_needed);
            foreach ($users_randomized as $user_r) {
                Comment::factory(1)
                    ->for($user_r)
                    ->for($post)
                    ->create();
            }
        }
    }
}
