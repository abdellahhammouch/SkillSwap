<?php

namespace App\Repositories;

use App\Models\LearningSession;
use App\Models\Transaction;
use App\Models\User;

class TransactionRepository
{
    public function sessionHasTransactions(LearningSession $learningSession)
    {
        return Transaction::where('learning_session_id', $learningSession->id)->exists();
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function updateUserBalance(User $user, $newBalance)
    {
        $user->update([
            'credit_balance_minutes' => $newBalance,
        ]);

        return $user;
    }
}
