<?php

namespace App\Repositories;

use App\Models\Need;

class NeedRepository
{
    public function getUserNeeds($userId)
    {
        return Need::with('category')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function create(array $data)
    {
        return Need::create($data);
    }

    public function update(Need $need, array $data)
    {
        $need->update($data);

        return $need;
    }

    public function delete(Need $need)
    {
        return $need->delete();
    }
}
