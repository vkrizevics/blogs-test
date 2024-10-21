<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CategoryPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category_ids_by_names = Category::all()
            ->mapWithKeys(fn (object $cat): array => [$cat->name => $cat->id])
            ->all();

        $posts = Post::all();
        foreach ($posts as $post) {
            $post_to_look_for_categories = mb_strtolower(
                str_replace(' ', '', $post->title . $post->content)
            );

            $post_categories = [];
            foreach (CategorySeeder::CATEGORIES_IN_TEXT as $category_name_searched_for) {
                if (strpos($post_to_look_for_categories, $category_name_searched_for) !== false) {
                    $post_categories[] = $category_ids_by_names[$category_name_searched_for];
                }
            }

            $categories_outer = Arr::shuffle(CategorySeeder::CATEGORIES_OUTER);
            $n_outer = random_int(0, count($categories_outer));
            for ($i_outer = 1; $i_outer <= $n_outer; $i_outer++) {
                $category_name_assigning = $categories_outer[$i_outer - 1];
                $post_categories[] = $category_ids_by_names[$category_name_assigning];
            }

            $post->categories()->sync($post_categories);
        }
    }
}
