<?php

namespace App\Services;

use App\Models\Conversation;
use App\Repositories\MessageRepository;

class MessageService
{
    protected $messageRepository;
    protected $conversationService;

    public function __construct(MessageRepository $messageRepository, ConversationService $conversationService)
    {
        $this->messageRepository = $messageRepository;
        $this->conversationService = $conversationService;
    }

    public function sendMessage(Conversation $conversation, $userId, array $data)
    {
        $this->conversationService->ensureUserCanAccess($conversation, $userId);

        $message = $this->messageRepository->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'content' => $data['content'],
        ]);

        $conversation->touch();

        return $message;
    }

    public function markMessagesAsRead(Conversation $conversation, $userId)
    {
        $this->conversationService->ensureUserCanAccess($conversation, $userId);

        return $this->messageRepository->markMessagesAsRead($conversation, $userId);
    }
}
