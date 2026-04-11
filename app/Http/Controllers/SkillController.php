<?php

namespace App\Http\Controllers;

use App\Enums\SkillLevelEnum;
use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Models\Skill;
use App\Services\CategoryService;
use App\Services\SkillService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SkillController extends Controller
{
    protected $skillService;
    protected $categoryService;

    public function __construct(SkillService $skillService, CategoryService $categoryService)
    {
        $this->skillService = $skillService;
        $this->categoryService = $categoryService;
    }

    public function index(): View
    {
        $skills = $this->skillService->getUserSkills(auth()->id());
        $categories = $this->categoryService->getActiveCategories();
        $levels = SkillLevelEnum::cases();

        return view('skills.index', compact('skills', 'categories', 'levels'));
    }

    public function store(StoreSkillRequest $request): RedirectResponse
    {
        $this->skillService->createSkill(auth()->id(), $request->validated());

        return redirect()
            ->route('skills.index')
            ->with('success', 'Skill created successfully.');
    }

    public function update(UpdateSkillRequest $request, Skill $skill): RedirectResponse
    {
        $this->skillService->updateSkill($skill, $request->validated());

        return redirect()
            ->route('skills.index')
            ->with('success', 'Skill updated successfully.');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        $this->skillService->deleteSkill($skill);

        return redirect()
            ->route('skills.index')
            ->with('success', 'Skill deleted successfully.');
    }
}
