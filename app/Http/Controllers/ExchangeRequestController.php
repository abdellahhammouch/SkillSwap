<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExchangeRequestRequest;
use App\Models\ExchangeRequest;
use App\Services\ExchangeRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExchangeRequestController extends Controller
{
    protected $exchangeRequestService;

    public function __construct(ExchangeRequestService $exchangeRequestService)
    {
        $this->exchangeRequestService = $exchangeRequestService;
    }

    public function index(): View
    {
        return view('exchange-requests.index', [
            'receivedRequests' => $this->exchangeRequestService->getReceivedRequests(auth()->id()),
            'sentRequests' => $this->exchangeRequestService->getSentRequests(auth()->id()),
        ]);
    }

    public function store(StoreExchangeRequestRequest $request): RedirectResponse
    {
        $exchangeRequest = $this->exchangeRequestService->createRequest(auth()->id(), $request->validated());

        return redirect()
            ->route('exchange-requests.show', $exchangeRequest)
            ->with('success', 'Exchange request created successfully.');
    }

    public function show(ExchangeRequest $exchangeRequest): View
    {
        $this->exchangeRequestService->ensureCurrentUserCanView($exchangeRequest);

        $exchangeRequest->load(['learner', 'helper', 'need', 'skill', 'proposedTimes', 'conversation', 'learningSessions']);

        return view('exchange-requests.show', compact('exchangeRequest'));
    }

    public function accept(ExchangeRequest $exchangeRequest): RedirectResponse
    {
        $this->exchangeRequestService->acceptRequest($exchangeRequest);

        return redirect()
            ->route('exchange-requests.show', $exchangeRequest)
            ->with('success', 'Exchange request accepted successfully.');
    }

    public function refuse(ExchangeRequest $exchangeRequest): RedirectResponse
    {
        $this->exchangeRequestService->refuseRequest($exchangeRequest);

        return redirect()
            ->route('exchange-requests.show', $exchangeRequest)
            ->with('success', 'Exchange request refused successfully.');
    }

    public function cancel(ExchangeRequest $exchangeRequest): RedirectResponse
    {
        $this->exchangeRequestService->cancelRequest($exchangeRequest);

        return redirect()
            ->route('exchange-requests.show', $exchangeRequest)
            ->with('success', 'Exchange request cancelled successfully.');
    }
}
