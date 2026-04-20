<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): View
    {
        return view('categories.index');
    }

    public function json(): JsonResponse
    {
        $categories = $this->categoryService->getActiveCategories();

        return response()->json($categories);
    }
}
