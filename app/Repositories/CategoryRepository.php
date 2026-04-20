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
}
