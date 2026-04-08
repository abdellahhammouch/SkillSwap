<?php

namespace App\Services;

use App\Models\Need;
use App\Repositories\NeedRepository;

class NeedService
{
    protected $needRepository;

    public function __construct(NeedRepository $needRepository)
    {
        $this->needRepository = $needRepository;
    }

    public function getUserNeeds($userId)
    {
        return $this->needRepository->getUserNeeds($userId);
    }

    public function createNeed($userId, array $data)
    {
        $data['user_id'] = $userId;
        $data['status'] = 'open';

        return $this->needRepository->create($data);
    }

    public function updateNeed(Need $need, array $data)
    {
        $this->ensureUserOwnsNeed($need);

        return $this->needRepository->update($need, $data);
    }

    public function closeNeed(Need $need)
    {
        $this->ensureUserOwnsNeed($need);

        return $this->needRepository->update($need, [
            'status' => 'closed',
        ]);
    }

    public function deleteNeed(Need $need)
    {
        $this->ensureUserOwnsNeed($need);

        return $this->needRepository->delete($need);
    }

    private function ensureUserOwnsNeed(Need $need)
    {
        if ($need->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
