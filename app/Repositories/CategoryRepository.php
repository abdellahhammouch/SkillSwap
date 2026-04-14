<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getAll()
    {
        return Category::orderBy('name')->get();
    }

    public function getActive()
    {
        return Category::where('is_active', true)->orderBy('name')->get();
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data)
    {
        $category->update($data);

        return $category;
    }

    public function slugExists($slug, $ignoreCategoryId = null)
    {
        $query = Category::where('slug', $slug);

        if ($ignoreCategoryId) {
            $query->where('id', '!=', $ignoreCategoryId);
        }

        return $query->exists();
    }

    public function delete(Category $category)
    {
        return $category->delete();
    }
}
