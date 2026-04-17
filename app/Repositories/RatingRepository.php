<?php

namespace App\Repositories;

use App\Models\Rating;
use App\Models\User;

class RatingRepository
{
    public function findBetweenUsers($authorId, $targetId)
    {
        return Rating::where('author_id', $authorId)
            ->where('target_id', $targetId)
            ->first();
    }

    public function create(array $data)
    {
        return Rating::create($data);
    }

    public function getReceivedRatingsForUser(User $user)
    {
        return Rating::with('author')
            ->where('target_id', $user->id)
            ->latest()
            ->get();
    }

    public function getAverageScoreForUser(User $user)
    {
        return (float) Rating::where('target_id', $user->id)->avg('score');
    }
}
