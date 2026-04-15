<?php

namespace App\Services;

use App\Models\LearningSession;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function processSessionTransactions(LearningSession $learningSession)
    {
        $learningSession->loadMissing('exchangeRequest.learner', 'exchangeRequest.helper');

        if ($learningSession->status !== 'completed') {
            abort(422, 'Transactions can be created only for a completed session.');
        }

        if ($this->transactionRepository->sessionHasTransactions($learningSession)) {
            return;
        }

        $learner = $learningSession->exchangeRequest->learner;
        $helper = $learningSession->exchangeRequest->helper;
        $amount = $learningSession->duration_minutes;

        DB::transaction(function () use ($learningSession, $learner, $helper, $amount) {
            $this->transactionRepository->create([
                'learning_session_id' => $learningSession->id,
                'user_id' => $learner->id,
                'type' => 'debit',
                'amount_minutes' => $amount,
                'description' => 'Session payment in SS for the learner.',
            ]);

            $this->transactionRepository->create([
                'learning_session_id' => $learningSession->id,
                'user_id' => $helper->id,
                'type' => 'credit',
                'amount_minutes' => $amount,
                'description' => 'Session reward in SS for the helper.',
            ]);

            $this->transactionRepository->updateUserBalance(
                $learner,
                max(0, $learner->credit_balance_minutes - $amount)
            );

            $this->transactionRepository->updateUserBalance(
                $helper,
                $helper->credit_balance_minutes + $amount
            );
        });
    }
}
