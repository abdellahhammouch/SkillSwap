<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Models\User;
use App\Services\RatingService;
use Illuminate\Http\RedirectResponse;

class RatingController extends Controller
{
    protected $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function store(StoreRatingRequest $request, User $user): RedirectResponse
    {
        $this->ratingService->createRating($user, auth()->id(), $request->validated());

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'Reputation added successfully.');
    }
}
