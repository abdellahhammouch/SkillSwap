<?php

namespace App\Repositories;

use App\Models\LearningSession;
use App\Models\ProposedTime;

class LearningSessionRepository
{
    public function getUserSessions($userId)
    {
        return LearningSession::with([
            'exchangeRequest.learner',
            'exchangeRequest.helper',
            'exchangeRequest.need',
            'exchangeRequest.skill',
        ])
            ->whereHas('exchangeRequest', function ($query) use ($userId) {
                $query->where('learner_id', $userId)
                    ->orWhere('helper_id', $userId);
            })
            ->latest('scheduled_at')
            ->get();
    }

    public function createFromProposedTime(ProposedTime $proposedTime)
    {
        $durationMinutes = (int) $proposedTime->start_at->diffInMinutes($proposedTime->end_at);

        return LearningSession::firstOrCreate(
            [
                'exchange_request_id' => $proposedTime->exchange_request_id,
            ],
            [
                'scheduled_at' => $proposedTime->start_at,
                'duration_minutes' => $durationMinutes,
                'status' => 'scheduled',
                'confirmed_by_helper_at' => now(),
            ]
        );
    }
}
