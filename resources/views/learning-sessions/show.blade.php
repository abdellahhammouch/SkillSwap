<x-app-layout>
    @php
        $exchangeRequest = $learningSession->exchangeRequest;
        $sessionEnd = $learningSession->scheduled_at->copy()->addMinutes($learningSession->duration_minutes);
        $currentUserIsLearner = $exchangeRequest->learner_id === auth()->id();
        $currentUserHasConfirmed = $currentUserIsLearner
            ? $learningSession->confirmed_by_learner_at
            : $learningSession->confirmed_by_helper_at;
    @endphp

    <div class="bg-slate-950">
        <div class="min-h-screen">
            <main>
                <div class="mx-auto max-w-6xl px-6 py-10">
                    <h1 class="ss-title inline-block border-b-2 border-blue-500 pr-5 text-5xl text-slate-200">
                        Learning Session Details
                    </h1>

                    @if (session('success'))
                        <div class="ss-success mt-8">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mt-10 grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_minmax(320px,0.9fr)]">
                        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-8 shadow-2xl shadow-slate-950/40">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Session overview</p>
                                    <h2 class="mt-3 text-3xl font-semibold text-white">
                                        {{ $exchangeRequest->skill?->title ?: $exchangeRequest->need?->title ?: 'Learning session' }}
                                    </h2>
                                </div>

                                <span class="rounded-full border border-slate-700 bg-slate-950/70 px-4 py-2 text-sm font-semibold text-slate-200">
                                    {{ ucfirst(str_replace('_', ' ', $learningSession->status)) }}
                                </span>
                            </div>

                            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Scheduled at</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $learningSession->scheduled_at->format('Y-m-d H:i') }}</p>
                                </div>

                                <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Ends at</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $sessionEnd->format('Y-m-d H:i') }}</p>
                                </div>

                                <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Duration</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $learningSession->duration_minutes }} minutes</p>
                                </div>

                                <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Session value</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $learningSession->duration_minutes }} SS</p>
                                </div>
                            </div>

                            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Learner</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $exchangeRequest->learner->name }}</p>
                                </div>

                                <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Helper</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $exchangeRequest->helper->name }}</p>
                                </div>

                                <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Need</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $exchangeRequest->need?->title ?: 'No need selected' }}</p>
                                </div>

                                <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Skill</p>
                                    <p class="mt-2 text-lg font-semibold text-white">{{ $exchangeRequest->skill?->title ?: 'No skill selected' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-2xl shadow-slate-950/40">
                                <h3 class="text-xl font-semibold text-white">Completion confirmation</h3>

                                @if ($learningSession->status === 'scheduled')
                                    <p class="mt-4 text-sm leading-7 text-slate-400">
                                        This session will start automatically when the scheduled time arrives.
                                    </p>
                                @elseif ($learningSession->status === 'in_progress')
                                    <div class="mt-4 space-y-3 text-sm text-slate-300">
                                        <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                            <p class="text-slate-500">Learner confirmation</p>
                                            <p class="mt-2 font-semibold text-white">
                                                {{ $learningSession->confirmed_by_learner_at ? $learningSession->confirmed_by_learner_at->format('Y-m-d H:i') : 'Waiting' }}
                                            </p>
                                        </div>

                                        <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                            <p class="text-slate-500">Helper confirmation</p>
                                            <p class="mt-2 font-semibold text-white">
                                                {{ $learningSession->confirmed_by_helper_at ? $learningSession->confirmed_by_helper_at->format('Y-m-d H:i') : 'Waiting' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if (! $currentUserHasConfirmed)
                                        <form method="POST" action="{{ route('learning-sessions.confirm-completion', $learningSession) }}" class="mt-5">
                                            @csrf
                                            @method('PATCH')

                                            <button class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white">
                                                Mark this session as completed
                                            </button>
                                        </form>
                                    @else
                                        <p class="mt-5 text-sm leading-7 text-emerald-400">
                                            You already confirmed this session. We are waiting for the other student.
                                        </p>
                                    @endif
                                @elseif ($learningSession->status === 'completed')
                                    <p class="mt-4 text-sm leading-7 text-emerald-400">
                                        This session was completed on {{ $learningSession->completed_at?->format('Y-m-d H:i') }}.
                                    </p>

                                    @if ($learningSession->transactions->isNotEmpty())
                                        <div class="mt-5 space-y-3">
                                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Transactions</p>

                                            @foreach ($learningSession->transactions as $transaction)
                                                <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4 text-sm text-slate-300">
                                                    <span class="font-semibold text-white">{{ $transaction->user->name }}</span>
                                                    <span class="{{ $transaction->type === 'debit' ? 'text-rose-400' : 'text-emerald-400' }}">
                                                        {{ $transaction->type === 'debit' ? '-' : '+' }}{{ $transaction->amount_minutes }} SS
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <p class="mt-4 text-sm leading-7 text-rose-400">
                                        This session was cancelled.
                                    </p>
                                @endif
                            </div>

                            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-2xl shadow-slate-950/40">
                                <h3 class="text-xl font-semibold text-white">Quick actions</h3>

                                <div class="mt-5 flex flex-col gap-3">
                                    <a href="{{ route('exchange-requests.show', $exchangeRequest) }}" class="rounded-xl bg-blue-600 px-4 py-3 text-center text-sm font-bold text-white">
                                        View exchange request
                                    </a>

                                    @if ($exchangeRequest->conversation)
                                        <a href="{{ route('conversations.show', $exchangeRequest->conversation) }}" class="rounded-xl border border-slate-700 px-4 py-3 text-center text-sm font-semibold text-slate-200">
                                            Open chat
                                        </a>
                                    @endif

                                    <a href="{{ route('learning-sessions.index') }}" class="rounded-xl border border-slate-700 px-4 py-3 text-center text-sm font-semibold text-slate-400">
                                        Back to sessions
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
