<?php

namespace App\Services;

use App\Models\ExchangeRequest;
use App\Repositories\ProposedTimeRepository;
use Illuminate\Support\Facades\DB;

class ProposedTimeService
{
    protected $proposedTimeRepository;

    public function __construct(ProposedTimeRepository $proposedTimeRepository)
    {
        $this->proposedTimeRepository = $proposedTimeRepository;
    }

    public function createTimes(ExchangeRequest $exchangeRequest, $userId, array $data)
    {
        $this->ensureUserCanProposeTimes($exchangeRequest, $userId);

        return DB::transaction(function () use ($exchangeRequest, $data) {
            $proposalGroup = $this->proposedTimeRepository->getNextProposalGroup($exchangeRequest->id);

            foreach ($data['proposed_times'] as $proposedTime) {
                if ((int) $proposedTime['duration_minutes'] <= 0) {
                    abort(422, 'The duration must be greater than zero.');
                }

                $this->proposedTimeRepository->create($exchangeRequest, [
                    'proposal_group' => $proposalGroup,
                    'start_at' => $proposedTime['start_at'],
                    'duration_minutes' => $proposedTime['duration_minutes'],
                    'status' => 'pending',
                    'is_selected' => false,
                ]);
            }

            return $exchangeRequest->fresh(['proposedTimes']);
        });
    }

    public function getPendingTimesForRequest(ExchangeRequest $exchangeRequest)
    {
        return $this->proposedTimeRepository->getPendingForRequest($exchangeRequest->id);
    }

    public function ensureUserCanProposeTimes(ExchangeRequest $exchangeRequest, $userId)
    {
        $exchangeRequest->loadMissing('learningSessions');

        if ($exchangeRequest->status !== 'accepted') {
            abort(422, 'Times can be proposed only after the exchange request is accepted.');
        }

        if ($exchangeRequest->learner_id !== $userId) {
            abort(403);
        }

        if ($exchangeRequest->learningSessions->contains(function ($learningSession) {
            return in_array($learningSession->status, ['scheduled', 'in_progress'], true);
        })) {
            abort(422, 'A learning session is already active for this exchange request.');
        }
    }

    public function selectTime($proposedTime)
    {
        $this->proposedTimeRepository->unselectTimesForRequest(
            $proposedTime->exchange_request_id,
            $proposedTime->proposal_group
        );
        $selectedProposedTime = $this->proposedTimeRepository->select($proposedTime);
        $this->proposedTimeRepository->markOtherTimesAsDenied(
            $proposedTime->exchange_request_id,
            $proposedTime->proposal_group,
            $proposedTime->id
        );

        return $selectedProposedTime;
    }

    public function denyPendingTimes(ExchangeRequest $exchangeRequest, $userId)
    {
        if ($exchangeRequest->status !== 'accepted') {
            abort(422, 'Times can be denied only after the exchange request is accepted.');
        }

        if ($exchangeRequest->helper_id !== $userId) {
            abort(403);
        }

        $latestPendingGroup = $this->proposedTimeRepository->getLatestPendingGroup($exchangeRequest->id);

        if (! $latestPendingGroup) {
            return 0;
        }

        return $this->proposedTimeRepository->markGroupAsDenied($exchangeRequest->id, $latestPendingGroup);
    }
}
