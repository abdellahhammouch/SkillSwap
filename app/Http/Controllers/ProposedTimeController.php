<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProposedTimesRequest;
use App\Models\ExchangeRequest;
use App\Models\ProposedTime;
use App\Services\LearningSessionService;
use App\Services\ProposedTimeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProposedTimeController extends Controller
{
    protected $proposedTimeService;
    protected $learningSessionService;

    public function __construct(ProposedTimeService $proposedTimeService, LearningSessionService $learningSessionService)
    {
        $this->proposedTimeService = $proposedTimeService;
        $this->learningSessionService = $learningSessionService;
    }

    public function create(ExchangeRequest $exchangeRequest): View
    {
        $this->proposedTimeService->ensureUserCanProposeTimes($exchangeRequest, auth()->id());

        return view('proposed-times.create', compact('exchangeRequest'));
    }

    public function store(StoreProposedTimesRequest $request, ExchangeRequest $exchangeRequest): RedirectResponse
    {
        $this->proposedTimeService->createTimes($exchangeRequest, auth()->id(), $request->validated());

        return redirect()
            ->route('exchange-requests.show', $exchangeRequest)
            ->with('success', 'Proposed times added successfully.');
    }

    public function select(ProposedTime $proposedTime): RedirectResponse
    {
        $learningSession = $this->learningSessionService->createFromSelectedTime($proposedTime, auth()->id());

        return redirect()
            ->route('learning-sessions.show', $learningSession)
            ->with('success', 'Learning session planned successfully.');
    }
}
