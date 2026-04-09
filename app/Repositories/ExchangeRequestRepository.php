<?php

namespace App\Repositories;

use App\Enums\ExchangeRequestTypeEnum;
use App\Models\ExchangeRequest;

class ExchangeRequestRepository
{
    public function getSentRequests($userId)
    {
        return ExchangeRequest::with(['learner', 'helper', 'need', 'skill', 'proposedTimes'])
            ->where(function ($query) use ($userId) {
                $query->where(function ($query) use ($userId) {
                    $query->where('type', ExchangeRequestTypeEnum::HELP_REQUEST->value)
                        ->where('learner_id', $userId);
                })->orWhere(function ($query) use ($userId) {
                    $query->where('type', ExchangeRequestTypeEnum::HELP_OFFER->value)
                        ->where('helper_id', $userId);
                });
            })
            ->latest()
            ->get();
    }

    public function getReceivedRequests($userId)
    {
        return ExchangeRequest::with(['learner', 'helper', 'need', 'skill', 'proposedTimes'])
            ->where(function ($query) use ($userId) {
                $query->where(function ($query) use ($userId) {
                    $query->where('type', ExchangeRequestTypeEnum::HELP_REQUEST->value)
                        ->where('helper_id', $userId);
                })->orWhere(function ($query) use ($userId) {
                    $query->where('type', ExchangeRequestTypeEnum::HELP_OFFER->value)
                        ->where('learner_id', $userId);
                });
            })
            ->latest()
            ->get();
    }

    public function create(array $data)
    {
        return ExchangeRequest::create($data);
    }

    public function update(ExchangeRequest $exchangeRequest, array $data)
    {
        $exchangeRequest->update($data);

        return $exchangeRequest;
    }
}
