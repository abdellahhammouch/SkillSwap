<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Learning Session Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @php
                    $exchangeRequest = $learningSession->exchangeRequest;
                    $sessionEnd = $learningSession->scheduled_at->copy()->addMinutes($learningSession->duration_minutes);
                    $currentUserIsLearner = $exchangeRequest->learner_id === auth()->id();
                    $currentUserHasConfirmed = $currentUserIsLearner
                        ? $learningSession->confirmed_by_learner_at
                        : $learningSession->confirmed_by_helper_at;
                @endphp

                <div class="space-y-3">
                    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $learningSession->status)) }}</p>
                    <p><strong>Scheduled at:</strong> {{ $learningSession->scheduled_at->format('Y-m-d H:i') }}</p>
                    <p><strong>Ends at:</strong> {{ $sessionEnd->format('Y-m-d H:i') }}</p>
                    <p><strong>Duration:</strong> {{ $learningSession->duration_minutes }} minutes</p>
                    <p><strong>Session value:</strong> {{ $learningSession->duration_minutes }} SS</p>
                    <p><strong>Learner:</strong> {{ $exchangeRequest->learner->name }}</p>
                    <p><strong>Helper:</strong> {{ $exchangeRequest->helper->name }}</p>
                    <p><strong>Need:</strong> {{ $exchangeRequest->need?->title ?: 'No need selected' }}</p>
                    <p><strong>Skill:</strong> {{ $exchangeRequest->skill?->title ?: 'No skill selected' }}</p>
                </div>

                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900">Completion confirmation</h3>

                    @if ($learningSession->status === 'scheduled')
                        <p class="mt-2 text-sm text-gray-600">
                            This session will start automatically when the scheduled time arrives.
                        </p>
                    @elseif ($learningSession->status === 'in_progress')
                        <div class="mt-3 space-y-2 text-sm text-gray-700">
                            <p>
                                Learner confirmation:
                                {{ $learningSession->confirmed_by_learner_at ? $learningSession->confirmed_by_learner_at->format('Y-m-d H:i') : 'Waiting' }}
                            </p>

                            <p>
                                Helper confirmation:
                                {{ $learningSession->confirmed_by_helper_at ? $learningSession->confirmed_by_helper_at->format('Y-m-d H:i') : 'Waiting' }}
                            </p>
                        </div>

                        @if (! $currentUserHasConfirmed)
                            <form method="POST" action="{{ route('learning-sessions.confirm-completion', $learningSession) }}" class="mt-4">
                                @csrf
                                @method('PATCH')

                                <x-primary-button>
                                    Mark this session as completed
                                </x-primary-button>
                            </form>
                        @else
                            <p class="mt-4 text-sm text-green-700">
                                You already confirmed that this session is completed. Waiting for the other student.
                            </p>
                        @endif
                    @elseif ($learningSession->status === 'completed')
                        <p class="mt-2 text-sm text-green-700">
                            This session was completed on {{ $learningSession->completed_at?->format('Y-m-d H:i') }}.
                        </p>

                        @if ($learningSession->transactions->isNotEmpty())
                            <div class="mt-4 space-y-2 text-sm text-gray-700">
                                <p class="font-semibold text-gray-900">Transactions</p>

                                @foreach ($learningSession->transactions as $transaction)
                                    <p>
                                        {{ $transaction->user->name }}:
                                        {{ $transaction->type === 'debit' ? '-' : '+' }}{{ $transaction->amount_minutes }} SS
                                    </p>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <p class="mt-2 text-sm text-red-700">
                            This session was cancelled.
                        </p>
                    @endif
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('exchange-requests.show', $exchangeRequest) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                        View exchange request
                    </a>

                    @if ($exchangeRequest->conversation)
                        <a href="{{ route('conversations.show', $exchangeRequest->conversation) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                            Open chat
                        </a>
                    @endif

                    <a href="{{ route('learning-sessions.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        Back to sessions
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
