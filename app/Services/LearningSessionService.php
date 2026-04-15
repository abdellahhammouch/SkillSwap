<?php

namespace App\Services;

use App\Models\LearningSession;
use App\Models\ProposedTime;
use App\Repositories\LearningSessionRepository;
use Illuminate\Support\Facades\DB;

class LearningSessionService
{
    protected $learningSessionRepository;
    protected $proposedTimeService;

    public function __construct(LearningSessionRepository $learningSessionRepository, ProposedTimeService $proposedTimeService)
    {
        $this->learningSessionRepository = $learningSessionRepository;
        $this->proposedTimeService = $proposedTimeService;
    }

    public function getUserSessions($userId)
    {
        return $this->learningSessionRepository->getUserSessions($userId);
    }

    public function getSessionForUser(LearningSession $learningSession, $userId)
    {
        $learningSession->load([
            'exchangeRequest.learner',
            'exchangeRequest.helper',
            'exchangeRequest.need',
            'exchangeRequest.skill',
        ]);

        $this->ensureUserCanView($learningSession, $userId);

        return $learningSession;
    }

    public function createFromSelectedTime(ProposedTime $proposedTime, $userId)
    {
        $proposedTime->load('exchangeRequest.learningSession');
        $exchangeRequest = $proposedTime->exchangeRequest;

        if ($exchangeRequest->status !== 'accepted') {
            abort(422, 'The exchange request must be accepted before planning a session.');
        }

        if ($exchangeRequest->helper_id !== $userId) {
            abort(403);
        }

        if ($exchangeRequest->learningSession) {
            abort(422, 'A learning session already exists for this exchange request.');
        }

        if ($proposedTime->start_at >= $proposedTime->end_at) {
            abort(422, 'The selected time is not valid.');
        }

        return DB::transaction(function () use ($proposedTime) {
            $this->proposedTimeService->selectTime($proposedTime);

            return $this->learningSessionRepository->createFromProposedTime($proposedTime);
        });
    }

    public function ensureUserCanView(LearningSession $learningSession, $userId)
    {
        $exchangeRequest = $learningSession->exchangeRequest;

        if ($exchangeRequest->learner_id !== $userId && $exchangeRequest->helper_id !== $userId) {
            abort(403);
        }
    }
}
