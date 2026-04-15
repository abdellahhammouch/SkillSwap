<?php

namespace App\Http\Controllers;

use App\Models\LearningSession;
use App\Services\LearningSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LearningSessionController extends Controller
{
    protected $learningSessionService;

    public function __construct(LearningSessionService $learningSessionService)
    {
        $this->learningSessionService = $learningSessionService;
    }

    public function index(): View
    {
        $learningSessions = $this->learningSessionService->getUserSessions(auth()->id());

        return view('learning-sessions.index', compact('learningSessions'));
    }

    public function show(LearningSession $learningSession): View
    {
        $learningSession = $this->learningSessionService->getSessionForUser($learningSession, auth()->id());

        return view('learning-sessions.show', compact('learningSession'));
    }

    public function confirmCompletion(LearningSession $learningSession): RedirectResponse
    {
        $learningSession = $this->learningSessionService->confirmCompletion($learningSession, auth()->id());

        if ($learningSession->status === 'completed') {
            return redirect()
                ->route('learning-sessions.show', $learningSession)
                ->with('success', 'Session completed by both students.');
        }

        return redirect()
            ->route('learning-sessions.show', $learningSession)
            ->with('success', 'Your completion confirmation has been saved.');
    }
}
