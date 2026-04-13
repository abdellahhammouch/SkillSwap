<?php

namespace App\Http\Controllers;

use App\Enums\ExchangeRequestTypeEnum;
use App\Http\Requests\StoreExchangeRequestRequest;
use App\Models\ExchangeRequest;
use App\Models\Need;
use App\Models\Skill;
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
        $sentRequests = $this->exchangeRequestService->getSentRequests(auth()->id());
        $receivedRequests = $this->exchangeRequestService->getReceivedRequests(auth()->id());

        return view('exchange-requests.index', compact('sentRequests', 'receivedRequests'));
    }

    public function create(): View
    {
        $myNeeds = Need::where('user_id', auth()->id())->where('status', 'open')->get();
        $mySkills = Skill::where('user_id', auth()->id())->where('is_active', true)->get();
        $otherNeeds = Need::with('user')->where('user_id', '!=', auth()->id())->where('status', 'open')->get();
        $otherSkills = Skill::with('user')->where('user_id', '!=', auth()->id())->where('is_active', true)->get();
        $types = ExchangeRequestTypeEnum::cases();

        return view('exchange-requests.create', compact('myNeeds', 'mySkills', 'otherNeeds', 'otherSkills', 'types'));
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

        $exchangeRequest->load(['learner', 'helper', 'need', 'skill', 'proposedTimes']);

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
