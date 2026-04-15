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
    protected $transactionService;

    public function __construct(
        LearningSessionRepository $learningSessionRepository,
        ProposedTimeService $proposedTimeService,
        TransactionService $transactionService
    )
    {
        $this->learningSessionRepository = $learningSessionRepository;
        $this->proposedTimeService = $proposedTimeService;
        $this->transactionService = $transactionService;
    }

    public function getUserSessions($userId)
    {
        $learningSessions = $this->learningSessionRepository->getUserSessions($userId);

        foreach ($learningSessions as $learningSession) {
            $this->refreshStatusFromSchedule($learningSession);
        }

        return $learningSessions;
    }

    public function getSessionForUser(LearningSession $learningSession, $userId)
    {
        $learningSession->load([
            'exchangeRequest.learner',
            'exchangeRequest.helper',
            'exchangeRequest.need',
            'exchangeRequest.skill',
            'exchangeRequest.conversation',
            'transactions.user',
        ]);

        $this->ensureUserCanView($learningSession, $userId);
        $this->refreshStatusFromSchedule($learningSession);

        return $learningSession;
    }

    public function createFromSelectedTime(ProposedTime $proposedTime, $userId)
    {
        $proposedTime->load('exchangeRequest.learningSessions', 'exchangeRequest.learner');
        $exchangeRequest = $proposedTime->exchangeRequest;

        if ($exchangeRequest->status !== 'accepted') {
            abort(422, 'The exchange request must be accepted before planning a session.');
        }

        if ($exchangeRequest->helper_id !== $userId) {
            abort(403);
        }

        if ($exchangeRequest->learningSessions->contains(function ($learningSession) {
            return in_array($learningSession->status, ['scheduled', 'in_progress'], true);
        })) {
            abort(422, 'A learning session is already active for this exchange request.');
        }

        if ((int) $proposedTime->duration_minutes <= 0) {
            abort(422, 'The selected duration is not valid.');
        }

        if ($proposedTime->start_at <= now()) {
            abort(422, 'The selected time is already in the past.');
        }

        if ($exchangeRequest->learner->credit_balance_minutes < $proposedTime->duration_minutes) {
            abort(422, 'The learner does not have enough SS to plan this session.');
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

    public function confirmCompletion(LearningSession $learningSession, $userId)
    {
        $learningSession->load('exchangeRequest');

        $this->ensureUserCanView($learningSession, $userId);
        $this->refreshStatusFromSchedule($learningSession);

        if ($learningSession->status === 'completed') {
            return $learningSession;
        }

        if ($learningSession->status === 'cancelled') {
            abort(422, 'A cancelled session cannot be completed.');
        }

        if ($learningSession->status !== 'in_progress') {
            abort(422, 'The session must start before it can be completed.');
        }

        return DB::transaction(function () use ($learningSession, $userId) {
            if ($learningSession->exchangeRequest->learner_id === $userId) {
                $learningSession->confirmed_by_learner_at = $learningSession->confirmed_by_learner_at ?: now();
            }

            if ($learningSession->exchangeRequest->helper_id === $userId) {
                $learningSession->confirmed_by_helper_at = $learningSession->confirmed_by_helper_at ?: now();
            }

            if ($learningSession->confirmed_by_learner_at && $learningSession->confirmed_by_helper_at) {
                $learningSession->status = 'completed';
                $learningSession->completed_at = now();

                $learningSession->exchangeRequest->update([
                    'completed_at' => $learningSession->completed_at,
                ]);
            }

            $learningSession = $this->learningSessionRepository->save($learningSession);

            if ($learningSession->status === 'completed') {
                $this->transactionService->processSessionTransactions($learningSession);
            }

            return $learningSession;
        });
    }

    public function refreshStatusFromSchedule(LearningSession $learningSession)
    {
        if ($learningSession->status === 'scheduled' && $learningSession->scheduled_at <= now()) {
            $learningSession->status = 'in_progress';

            return $this->learningSessionRepository->save($learningSession);
        }

        return $learningSession;
    }
}
