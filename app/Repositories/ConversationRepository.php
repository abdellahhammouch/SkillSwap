<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\ExchangeRequest;

class ConversationRepository
{
    public function getUserConversations($userId)
    {
        return Conversation::with([
            'exchangeRequest.learner',
            'exchangeRequest.helper',
            'exchangeRequest.need',
            'exchangeRequest.skill',
            'messages.sender',
        ])
            ->whereHas('exchangeRequest', function ($query) use ($userId) {
                $query->where('learner_id', $userId)
                    ->orWhere('helper_id', $userId);
            })
            ->latest()
            ->get();
    }

    public function getConversationDetails(Conversation $conversation)
    {
        return $conversation->load([
            'exchangeRequest.learner',
            'exchangeRequest.helper',
            'exchangeRequest.need',
            'exchangeRequest.skill',
            'messages.sender',
        ]);
    }

    public function createForExchangeRequest(ExchangeRequest $exchangeRequest)
    {
        return Conversation::firstOrCreate([
            'exchange_request_id' => $exchangeRequest->id,
        ]);
    }
}
