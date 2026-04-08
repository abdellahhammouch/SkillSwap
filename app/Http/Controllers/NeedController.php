<?php

namespace App\Http\Controllers;

use App\Enums\SkillLevelEnum;
use App\Http\Requests\StoreNeedRequest;
use App\Http\Requests\UpdateNeedRequest;
use App\Models\Need;
use App\Services\CategoryService;
use App\Services\NeedService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NeedController extends Controller
{
    protected $needService;
    protected $categoryService;

    public function __construct(NeedService $needService, CategoryService $categoryService)
    {
        $this->needService = $needService;
        $this->categoryService = $categoryService;
    }

    public function index(): View
    {
        $needs = $this->needService->getUserNeeds(auth()->id());
        $categories = $this->categoryService->getActiveCategories();
        $levels = SkillLevelEnum::cases();

        return view('needs.index', compact('needs', 'categories', 'levels'));
    }

    public function store(StoreNeedRequest $request): RedirectResponse
    {
        $this->needService->createNeed(auth()->id(), $request->validated());

        return redirect()
            ->route('needs.index')
            ->with('success', 'Need created successfully.');
    }

    public function update(UpdateNeedRequest $request, Need $need): RedirectResponse
    {
        $this->needService->updateNeed($need, $request->validated());

        return redirect()
            ->route('needs.index')
            ->with('success', 'Need updated successfully.');
    }

    public function close(Need $need): RedirectResponse
    {
        $this->needService->closeNeed($need);

        return redirect()
            ->route('needs.index')
            ->with('success', 'Need closed successfully.');
    }

    public function destroy(Need $need): RedirectResponse
    {
        $this->needService->deleteNeed($need);

        return redirect()
            ->route('needs.index')
            ->with('success', 'Need deleted successfully.');
    }
}
