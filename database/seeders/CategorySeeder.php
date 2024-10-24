<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CategorySeeder extends Seeder
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
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
