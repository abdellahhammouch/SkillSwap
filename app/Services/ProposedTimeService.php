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
            foreach ($data['proposed_times'] as $proposedTime) {
                if (strtotime($proposedTime['end_at']) <= strtotime($proposedTime['start_at'])) {
                    abort(422, 'The proposed end time must be after the proposed start time.');
                }

                $this->proposedTimeRepository->create($exchangeRequest, [
                    'start_at' => $proposedTime['start_at'],
                    'end_at' => $proposedTime['end_at'],
                    'is_selected' => false,
                ]);
            }

            return $exchangeRequest->fresh(['proposedTimes']);
        });
    }

    public function ensureUserCanProposeTimes(ExchangeRequest $exchangeRequest, $userId)
    {
        $exchangeRequest->loadMissing('learningSession');

        if ($exchangeRequest->status !== 'accepted') {
            abort(422, 'Times can be proposed only after the exchange request is accepted.');
        }

        if ($exchangeRequest->learner_id !== $userId) {
            abort(403);
        }

        if ($exchangeRequest->learningSession) {
            abort(422, 'A learning session already exists for this exchange request.');
        }
    }

    public function selectTime($proposedTime)
    {
        $this->proposedTimeRepository->unselectTimesForRequest($proposedTime->exchange_request_id);

        return $this->proposedTimeRepository->select($proposedTime);
    }
}
