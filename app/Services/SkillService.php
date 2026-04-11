<?php

namespace App\Services;

use App\Models\Skill;
use App\Repositories\SkillRepository;

class SkillService
{
    protected $skillRepository;

    public function __construct(SkillRepository $skillRepository)
    {
        $this->skillRepository = $skillRepository;
    }

    public function getUserSkills($userId)
    {
        return $this->skillRepository->getUserSkills($userId);
    }

    public function createSkill($userId, array $data)
    {
        $data['user_id'] = $userId;
        $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : true;

        return $this->skillRepository->create($data);
    }

    public function updateSkill(Skill $skill, array $data)
    {
        $this->ensureUserOwnsSkill($skill);

        $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : false;

        return $this->skillRepository->update($skill, $data);
    }

    public function deleteSkill(Skill $skill)
    {
        $this->ensureUserOwnsSkill($skill);

        return $this->skillRepository->delete($skill);
    }

    private function ensureUserOwnsSkill(Skill $skill)
    {
        if ($skill->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
