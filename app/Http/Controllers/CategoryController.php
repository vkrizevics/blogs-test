<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\IsAuthorTrait;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CategoryController extends Controller
{
    use IsAuthorTrait;
    /**
     * Get autocompletion variants for a category fragment
     * @param string|null $categoryName
     * @return void
     */
    public function search(?string $categoryNameFragment)
    {
        return Category::select('name')
            ->where(
                'name',
                'like',
                addslashes(mb_strtolower($categoryNameFragment)) . '%'
            )
            ->orderBy('name')
            ->pluck('name');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category_is_stored = Category::where('name', $request->name)
            ->exists();

        if (!$category_is_stored) {
            $cat = new Category();
            $cat->fill($request->validated());
            $cat->name = str_replace(' ', '', mb_strtolower($cat->name));
            $success = $cat->save();
            $error = !$success ? 'Cannot store in DB' : '';
        } else {
            $success = false;
            $error = 'Already exists';
        }

        return compact('success', 'error');
    }

    /**
     * Display the specified resource.
     */
    public function show(?string $category_name)
    {
        $category_name_clean = str_replace(' ', '', strip_tags($category_name));
        $category = Category::where('name', $category_name_clean)
            ->first();

        $auth_user = Auth::check();

        if ($category) {
            $postsForLinks = $category->posts()
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $posts = $postsForLinks
                ->load('comments', 'user', 'categories');

            foreach ($posts as $i => $post) {
                $post->created_at_formatted = $post->getCreatedAtFormatted();
                $post->escaped_content = nl2br(htmlspecialchars($post->content), false);

                $post->is_author = static::isAuthor($post);
            }

            $links = $postsForLinks->toArray();
            unset($links['data'], $links['links']);
        } else {
            $posts = [];
            $links = (object)[];
            $category = (object)['name' => $category_name_clean];
        }


        return Inertia::render('Posts/Category', compact('auth_user', 'posts', 'links', 'category'));
    }
}
