<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $posts = Post::all();
        $all_users = User::all();

        foreach ($posts as $post) {
            $comments_needed = random_int(0, 3);
            if (!$comments_needed) {
                continue;
            }

            $users_needed = [];
            foreach ($all_users as $user) {
                $per_user = random_int(0, 3);

                for ($i = 0; $i < $per_user; $i++) {
                    $users_needed[] = $user;
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
