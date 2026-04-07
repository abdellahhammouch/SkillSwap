<?php

namespace App\Repositories;

use App\Models\Skill;

class SkillRepository
{
    public function getUserSkills($userId)
    {
        return Skill::with('category')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function create(array $data)
    {
        return Skill::create($data);
    }

    public function update(Skill $skill, array $data)
    {
        $skill->update($data);

        return $skill;
    }

    public function delete(Skill $skill)
    {
        return $skill->delete();
    }
}
