<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::with('exchangeRequest')->find($conversationId);

    if (! $conversation) {
        return false;
    }

    $exchangeRequest = $conversation->exchangeRequest;

    return $exchangeRequest->learner_id === $user->id
        || $exchangeRequest->helper_id === $user->id;
});
