<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\ConversationService;
use App\Services\MessageService;
use Illuminate\View\View;

class ConversationController extends Controller
{
    protected $conversationService;
    protected $messageService;

    public function __construct(ConversationService $conversationService, MessageService $messageService)
    {
        $this->conversationService = $conversationService;
        $this->messageService = $messageService;
    }

    public function index(): View
    {
        $conversations = $this->conversationService->getUserConversations(auth()->id());

        return view('conversations.index', compact('conversations'));
    }

    public function show(Conversation $conversation): View
    {
        $conversation = $this->conversationService->getConversationForUser($conversation, auth()->id());
        $this->messageService->markMessagesAsRead($conversation, auth()->id());

        return view('conversations.show', compact('conversation'));
    }
}
