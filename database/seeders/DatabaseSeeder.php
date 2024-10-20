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
    const CATEGORIES_IN_TEXT = [
        'alice',
        'carroll',
        'whiterabbit',
        'hatter',
        'mockturtle',
        'dormouse',
        'beautifulsoup'
    ];

    const CATEGORIES_OUTER = [
        'lewiscarroll',
        'aliceinthewonderland',
        'aliceliddell',
    ];

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

            $category_ids_by_names = [];
            foreach (static::CATEGORIES_IN_TEXT as $category_name) {
                $category = new Category;
                $category->name = $category_name;
                $category->save();

                $category_ids_by_names[$category->name] = $category->id;
            }

            foreach (static::CATEGORIES_OUTER as $category_name_outer) {
                $category = new Category;
                $category->name = $category_name_outer;
                $category->save();

                $category_ids_by_names[$category->name] = $category->id;
            }

            foreach ($posts as $post) {
                $post_to_look_for_categories = mb_strtolower(
                    str_replace(' ', '', $post->title . $post->content)
                );

                $post_categories = [];
                foreach (static::CATEGORIES_IN_TEXT as $category_name_searched_for) {
                    if (strpos($post_to_look_for_categories, $category_name_searched_for) !== false) {
                        $post_categories[] = $category_ids_by_names[$category_name_searched_for];
                    }
                }

                $categories_outer = Arr::shuffle(static::CATEGORIES_OUTER);
                $n_outer = random_int(0, count($categories_outer));
                for ($i_outer = 1; $i_outer <= $n_outer; $i_outer++) {
                    $category_name_assigning = $categories_outer[$i_outer - 1];
                    $post_categories[] = $category_ids_by_names[$category_name_assigning];
                }

                $post->categories()->sync($post_categories);
            }
        }
    }
}
