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
        return LearningSession::create([
            'exchange_request_id' => $proposedTime->exchange_request_id,
            'scheduled_at' => $proposedTime->start_at,
            'duration_minutes' => $proposedTime->duration_minutes,
            'status' => 'scheduled',
        ]);
    }

    public function save(LearningSession $learningSession)
    {
        $learningSession->save();

        return $learningSession;
    }
}
