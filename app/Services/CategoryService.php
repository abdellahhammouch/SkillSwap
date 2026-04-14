<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Str;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->getAll();
    }

    public function getActiveCategories()
    {
        return $this->categoryRepository->getActive();
    }

    public function createCategory(array $data)
    {
        $data['slug'] = $this->createUniqueSlug($data['name']);
        $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : true;

        return $this->categoryRepository->create($data);
    }

    public function updateCategory(Category $category, array $data)
    {
        $data['slug'] = $this->createUniqueSlug($data['name'], $category->id);
        $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : false;

        return $this->categoryRepository->update($category, $data);
    }

    public function deleteCategory(Category $category)
    {
        return $this->categoryRepository->delete($category);
    }

    private function createUniqueSlug($name, $ignoreCategoryId = null)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $number = 1;

        while ($this->categoryRepository->slugExists($slug, $ignoreCategoryId)) {
            $slug = $baseSlug.'-'.$number;
            $number++;
        }

        return $slug;
    }
}
