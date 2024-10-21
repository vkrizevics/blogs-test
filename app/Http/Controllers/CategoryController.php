<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\IsAuthorTrait;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\User;
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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        $category = Category::where('name', str_replace(' ', '', $category_name))
            ->first();

        $postsForLinks = $category->posts()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $posts = $postsForLinks
            ->load('comments', 'user', 'categories');

        $posts_count = count($posts);
        foreach ($posts as $i => $post) {
            $post->more_classes = '';

            $post->created_at_formatted = $post->getCreatedAtFormatted();
            $post->escaped_content = nl2br(htmlspecialchars($post->content), false);

            $post->is_author = static::isAuthor($post);

            if ($i === $posts_count - 1) {
                $posts->more_classes = 'pb-12';
            }
        }

        $auth_user = Auth::check();
        $links = $postsForLinks->toArray();
        unset($links['data'], $links['links']);


        return Inertia::render('Posts/Category', compact('auth_user', 'posts', 'links', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
