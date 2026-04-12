<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\ExchangeRequest;
use App\Repositories\ConversationRepository;

class ConversationService
{
    protected $conversationRepository;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function getUserConversations($userId)
    {
        return $this->conversationRepository->getUserConversations($userId);
    }

    public function getConversationForUser(Conversation $conversation, $userId)
    {
        $conversation = $this->conversationRepository->getConversationDetails($conversation);

        $this->ensureUserCanAccess($conversation, $userId);

        return $conversation;
    }

    public function createForAcceptedExchangeRequest(ExchangeRequest $exchangeRequest)
    {
        if ($exchangeRequest->status !== 'accepted') {
            abort(422, 'The exchange request must be accepted before creating a conversation.');
        }

        return $this->conversationRepository->createForExchangeRequest($exchangeRequest);
    }

    public function ensureUserCanAccess(Conversation $conversation, $userId)
    {
        $conversation->loadMissing('exchangeRequest');

        $exchangeRequest = $conversation->exchangeRequest;

        if ($exchangeRequest->learner_id !== $userId && $exchangeRequest->helper_id !== $userId) {
            abort(403);
        }
    }
}
