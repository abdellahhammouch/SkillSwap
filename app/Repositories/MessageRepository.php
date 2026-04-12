<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\Message;

class MessageRepository
{
    public function create(array $data)
    {
        return Message::create($data);
    }

    public function markMessagesAsRead(Conversation $conversation, $userId)
    {
        return Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);
    }
}
