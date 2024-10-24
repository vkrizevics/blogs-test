<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users_with_posts = User::orderBy('id')
            ->take(10)
            ->get();

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
    }
}
