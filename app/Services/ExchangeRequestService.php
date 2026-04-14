<?php

namespace App\Services;

use App\Enums\ExchangeRequestTypeEnum;
use App\Models\ExchangeRequest;
use App\Models\Need;
use App\Models\Skill;
use App\Repositories\ExchangeRequestRepository;
use Illuminate\Support\Facades\DB;

class ExchangeRequestService
{
    protected $exchangeRequestRepository;
    protected $conversationService;

    public function __construct(ExchangeRequestRepository $exchangeRequestRepository, ConversationService $conversationService)
    {
        $this->exchangeRequestRepository = $exchangeRequestRepository;
        $this->conversationService = $conversationService;
    }

    public function getSentRequests($userId)
    {
        return $this->exchangeRequestRepository->getSentRequests($userId);
    }

    public function getReceivedRequests($userId)
    {
        return $this->exchangeRequestRepository->getReceivedRequests($userId);
    }

    public function createRequest($userId, array $data)
    {
        return DB::transaction(function () use ($userId, $data) {
            $type = $data['type'];
            $skill = isset($data['skill_id']) ? Skill::findOrFail($data['skill_id']) : null;
            $need = isset($data['need_id']) ? Need::findOrFail($data['need_id']) : null;

            if ($type === ExchangeRequestTypeEnum::HELP_REQUEST->value) {
                $this->ensureHelpRequestIsValid($userId, $skill, $need);
                $learnerId = $userId;
                $helperId = $skill->user_id;
            } else {
                $this->ensureHelpOfferIsValid($userId, $skill, $need);
                $learnerId = $need->user_id;
                $helperId = $userId;
            }

            $exchangeRequest = $this->exchangeRequestRepository->create([
                'type' => $type,
                'learner_id' => $learnerId,
                'helper_id' => $helperId,
                'need_id' => $need?->id,
                'skill_id' => $skill?->id,
                'message' => $data['message'] ?? null,
                'status' => 'pending',
                'expires_at' => now()->addDays(7),
            ]);

            return $exchangeRequest;
        });
    }

    public function acceptRequest(ExchangeRequest $exchangeRequest)
    {
        $this->ensureRequestIsPending($exchangeRequest);
        $this->ensureCurrentUserCanAnswer($exchangeRequest);

        $exchangeRequest = $this->exchangeRequestRepository->update($exchangeRequest, [
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        $this->conversationService->createForAcceptedExchangeRequest($exchangeRequest);

        return $exchangeRequest;
    }

    public function refuseRequest(ExchangeRequest $exchangeRequest)
    {
        $this->ensureRequestIsPending($exchangeRequest);
        $this->ensureCurrentUserCanAnswer($exchangeRequest);

        return $this->exchangeRequestRepository->update($exchangeRequest, [
            'status' => 'refused',
        ]);
    }

    public function cancelRequest(ExchangeRequest $exchangeRequest)
    {
        $this->ensureRequestIsPending($exchangeRequest);
        $this->ensureCurrentUserCanCancel($exchangeRequest);

        return $this->exchangeRequestRepository->update($exchangeRequest, [
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    public function ensureCurrentUserCanView(ExchangeRequest $exchangeRequest)
    {
        if ($exchangeRequest->learner_id !== auth()->id() && $exchangeRequest->helper_id !== auth()->id()) {
            abort(403);
        }
    }

    private function ensureHelpRequestIsValid($userId, ?Skill $skill, ?Need $need)
    {
        if (! $skill) {
            abort(422, 'A skill is required when asking for help.');
        }

        if ($skill->user_id === $userId) {
            abort(422, 'You cannot ask yourself for help.');
        }

        if ($need && $need->user_id !== $userId) {
            abort(403);
        }
    }

    private function ensureHelpOfferIsValid($userId, ?Skill $skill, ?Need $need)
    {
        if (! $need) {
            abort(422, 'A need is required when offering help.');
        }

        if ($need->user_id === $userId) {
            abort(422, 'You cannot offer help to yourself.');
        }

        if ($skill && $skill->user_id !== $userId) {
            abort(403);
        }
    }

    private function ensureRequestIsPending(ExchangeRequest $exchangeRequest)
    {
        if ($exchangeRequest->status !== 'pending') {
            abort(422, 'Only pending requests can be updated.');
        }
    }

    private function ensureCurrentUserCanAnswer(ExchangeRequest $exchangeRequest)
    {
        if ($exchangeRequest->type === ExchangeRequestTypeEnum::HELP_REQUEST->value && $exchangeRequest->helper_id !== auth()->id()) {
            abort(403);
        }

        if ($exchangeRequest->type === ExchangeRequestTypeEnum::HELP_OFFER->value && $exchangeRequest->learner_id !== auth()->id()) {
            abort(403);
        }
    }

    private function ensureCurrentUserCanCancel(ExchangeRequest $exchangeRequest)
    {
        if ($exchangeRequest->type === ExchangeRequestTypeEnum::HELP_REQUEST->value && $exchangeRequest->learner_id !== auth()->id()) {
            abort(403);
        }

        if ($exchangeRequest->type === ExchangeRequestTypeEnum::HELP_OFFER->value && $exchangeRequest->helper_id !== auth()->id()) {
            abort(403);
        }
    }
}
