<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Conversation;
use App\Services\MessageService;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function store(StoreMessageRequest $request, Conversation $conversation): RedirectResponse
    {
        $this->messageService->sendMessage($conversation, auth()->id(), $request->validated());

        return redirect()
            ->route('conversations.show', $conversation)
            ->with('success', 'Message sent successfully.');
    }
}
