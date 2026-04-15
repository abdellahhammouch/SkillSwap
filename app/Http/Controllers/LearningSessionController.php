<?php

namespace App\Http\Controllers;

use App\Models\LearningSession;
use App\Services\LearningSessionService;
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
}
