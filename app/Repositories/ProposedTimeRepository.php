<?php

namespace App\Repositories;

use App\Models\ExchangeRequest;
use App\Models\ProposedTime;

class ProposedTimeRepository
{
    public function getPendingForRequest($exchangeRequestId)
    {
        return ProposedTime::where('exchange_request_id', $exchangeRequestId)
            ->where('status', 'pending')
            ->orderBy('start_at')
            ->get();
    }

    public function getNextProposalGroup($exchangeRequestId)
    {
        return (int) ProposedTime::where('exchange_request_id', $exchangeRequestId)->max('proposal_group') + 1;
    }

    public function create(ExchangeRequest $exchangeRequest, array $data)
    {
        return $exchangeRequest->proposedTimes()->create($data);
    }

    public function unselectTimesForRequest($exchangeRequestId, $proposalGroup = null)
    {
        $query = ProposedTime::where('exchange_request_id', $exchangeRequestId);

        if ($proposalGroup !== null) {
            $query->where('proposal_group', $proposalGroup);
        }

        return $query
            ->update([
                'is_selected' => false,
            ]);
    }

    public function select(ProposedTime $proposedTime)
    {
        $proposedTime->update([
            'status' => 'selected',
            'is_selected' => true,
        ]);

        return $proposedTime;
    }

    public function markOtherTimesAsDenied($exchangeRequestId, $proposalGroup, $selectedProposedTimeId)
    {
        return ProposedTime::where('exchange_request_id', $exchangeRequestId)
            ->where('proposal_group', $proposalGroup)
            ->where('id', '!=', $selectedProposedTimeId)
            ->update([
                'status' => 'denied',
                'is_selected' => false,
            ]);
    }

    public function markGroupAsDenied($exchangeRequestId, $proposalGroup)
    {
        return ProposedTime::where('exchange_request_id', $exchangeRequestId)
            ->where('proposal_group', $proposalGroup)
            ->update([
                'status' => 'denied',
                'is_selected' => false,
            ]);
    }

    public function getLatestPendingGroup($exchangeRequestId)
    {
        return ProposedTime::where('exchange_request_id', $exchangeRequestId)
            ->where('status', 'pending')
            ->max('proposal_group');
    }
}
