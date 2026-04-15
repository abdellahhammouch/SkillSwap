<?php

namespace App\Repositories;

use App\Models\ExchangeRequest;
use App\Models\ProposedTime;

class ProposedTimeRepository
{
    public function create(ExchangeRequest $exchangeRequest, array $data)
    {
        return $exchangeRequest->proposedTimes()->create($data);
    }

    public function unselectTimesForRequest($exchangeRequestId)
    {
        return ProposedTime::where('exchange_request_id', $exchangeRequestId)
            ->update([
                'is_selected' => false,
            ]);
    }

    public function select(ProposedTime $proposedTime)
    {
        $proposedTime->update([
            'is_selected' => true,
        ]);

        return $proposedTime;
    }
}
