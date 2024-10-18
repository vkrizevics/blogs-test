<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
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
    public function show(Category $category)
    {
        //
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
