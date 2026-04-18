<?php

namespace App\Services;

use App\Models\ExchangeRequest;
use App\Models\User;
use App\Repositories\RatingRepository;
use Illuminate\Support\Facades\DB;

class RatingService
{
    protected $ratingRepository;

    public function __construct(RatingRepository $ratingRepository)
    {
        $this->ratingRepository = $ratingRepository;
    }

    public function getProfileData(User $user, $viewerId)
    {
        $receivedRatings = $this->ratingRepository->getReceivedRatingsForUser($user);
        $existingRating = null;
        $canRate = false;

        if ($viewerId && $viewerId !== $user->id) {
            $canRate = $this->usersHaveAcceptedExchangeRequest($viewerId, $user->id);
            $existingRating = $this->ratingRepository->findBetweenUsers($viewerId, $user->id);
        }

        return [
            'receivedRatings' => $receivedRatings,
            'existingRating' => $existingRating,
            'canRate' => $canRate,
        ];
    }

    public function createRating(User $targetUser, $authorId, array $data)
    {
        $this->ensureUserCanRate($targetUser, $authorId);

        return DB::transaction(function () use ($targetUser, $authorId, $data) {
            $rating = $this->ratingRepository->create([
                'author_id' => $authorId,
                'target_id' => $targetUser->id,
                'score' => $data['score'],
                'comment' => $data['comment'] ?? null,
            ]);

            $this->refreshUserReputation($targetUser);

            return $rating;
        });
    }

    public function ensureUserCanRate(User $targetUser, $authorId)
    {
        if (! $authorId) {
            abort(403);
        }

        if ($targetUser->id === $authorId) {
            abort(422, 'You cannot rate yourself.');
        }

        if (! $this->usersHaveAcceptedExchangeRequest($authorId, $targetUser->id)) {
            abort(403);
        }

        if ($this->ratingRepository->findBetweenUsers($authorId, $targetUser->id)) {
            abort(422, 'You have already rated this user.');
        }
    }

    private function usersHaveAcceptedExchangeRequest($firstUserId, $secondUserId)
    {
        return ExchangeRequest::where('status', 'accepted')
            ->where(function ($query) use ($firstUserId, $secondUserId) {
                $query->where(function ($query) use ($firstUserId, $secondUserId) {
                    $query->where('learner_id', $firstUserId)
                        ->where('helper_id', $secondUserId);
                })->orWhere(function ($query) use ($firstUserId, $secondUserId) {
                    $query->where('learner_id', $secondUserId)
                        ->where('helper_id', $firstUserId);
                });
            })
            ->exists();
    }

    private function refreshUserReputation(User $user)
    {
        $averageScore = $this->ratingRepository->getAverageScoreForUser($user);

        $user->update([
            'reputation_score' => round($averageScore, 2),
        ]);
    }
}
