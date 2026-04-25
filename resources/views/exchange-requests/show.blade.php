<x-app-layout>
    @php
        $learningSessions = $exchangeRequest->learningSessions->sortByDesc('scheduled_at')->values();
        $activeLearningSession = $learningSessions->first(function ($learningSession) {
            return in_array($learningSession->status, ['scheduled', 'in_progress'], true);
        });
        $latestCompletedSession = $learningSessions->first(function ($learningSession) {
            return $learningSession->status === 'completed';
        });
        $canAnswer = $exchangeRequest->status === 'pending'
            && (
                ($exchangeRequest->type === 'help_request' && $exchangeRequest->helper_id === auth()->id())
                || ($exchangeRequest->type === 'help_offer' && $exchangeRequest->learner_id === auth()->id())
            );
        $canCancel = $exchangeRequest->status === 'pending'
            && (
                ($exchangeRequest->type === 'help_request' && $exchangeRequest->learner_id === auth()->id())
                || ($exchangeRequest->type === 'help_offer' && $exchangeRequest->helper_id === auth()->id())
            );
        $proposedTimes = $exchangeRequest->proposedTimes
            ->sortBy([
                ['proposal_group', 'asc'],
                ['start_at', 'asc'],
            ])
            ->groupBy('proposal_group');
    @endphp

    <div class="bg-slate-950">
        <div class="min-h-screen">
            <main>
                <div class="mx-auto max-w-6xl px-6 py-10">
                    <h1 class="ss-title inline-block border-b-2 border-blue-500 pr-5 text-5xl text-slate-200">
                        Exchange Request Details
                    </h1>

                    @if (session('success'))
                        <div class="ss-success mt-8">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mt-10 grid gap-6 lg:grid-cols-[minmax(0,1.5fr)_minmax(320px,0.95fr)]">
                        <div class="space-y-6">
                            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-8 shadow-2xl shadow-slate-950/40">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Exchange request</p>
                                        <h2 class="mt-3 text-3xl font-semibold text-white">
                                            {{ $exchangeRequest->type === 'help_request' ? 'Ask for help' : 'Offer help' }}
                                        </h2>
                                    </div>

                                    <span class="rounded-full border border-slate-700 bg-slate-950/70 px-4 py-2 text-sm font-semibold text-slate-200">
                                        {{ ucfirst($exchangeRequest->status) }}
                                    </span>
                                </div>

                                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                    <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Learner</p>
                                        <a href="{{ route('users.show', $exchangeRequest->learner) }}" class="mt-2 block text-lg font-semibold text-white hover:text-blue-400">
                                            {{ $exchangeRequest->learner->name }}
                                        </a>
                                    </div>

                                    <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Helper</p>
                                        <a href="{{ route('users.show', $exchangeRequest->helper) }}" class="mt-2 block text-lg font-semibold text-white hover:text-blue-400">
                                            {{ $exchangeRequest->helper->name }}
                                        </a>
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

                                <div class="mt-4 rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Message</p>
                                    <p class="mt-3 text-base leading-7 text-slate-300">{{ $exchangeRequest->message ?: 'No message' }}</p>
                                </div>

                                <div class="mt-4 rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Expires at</p>
                                    <p class="mt-2 text-base font-semibold text-white">{{ $exchangeRequest->expires_at?->format('Y-m-d H:i') ?: 'No expiration date' }}</p>
                                </div>
                            </div>

                            @if ($exchangeRequest->status === 'accepted')
                                <div class="rounded-2xl border border-slate-800 bg-slate-900 p-8 shadow-2xl shadow-slate-950/40">
                                    <div class="flex items-center justify-between gap-3">
                                        <h3 class="text-2xl font-semibold text-white">Proposed times</h3>

                                        @if ($exchangeRequest->learner_id === auth()->id() && ! $activeLearningSession)
                                            <a href="{{ route('proposed-times.create', $exchangeRequest) }}" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white">
                                                Propose times
                                            </a>
                                        @endif
                                    </div>

                                    <div class="mt-6 space-y-4">
                                        @forelse ($proposedTimes as $proposalGroup => $groupTimes)
                                            @php
                                                $selectedTime = $groupTimes->firstWhere('status', 'selected');
                                                $groupStatus = $selectedTime
                                                    ? 'selected'
                                                    : ($groupTimes->contains('status', 'pending') ? 'pending' : 'denied');
                                            @endphp

                                            <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-5">
                                                <div class="flex items-center justify-between gap-4">
                                                    <p class="text-sm font-semibold text-white">Proposal #{{ $proposalGroup }}</p>
                                                    <span class="text-sm {{ $groupStatus === 'selected' ? 'text-emerald-400' : ($groupStatus === 'denied' ? 'text-rose-400' : 'text-slate-400') }}">
                                                        {{ ucfirst($groupStatus) }}
                                                    </span>
                                                </div>

                                                <div class="mt-4 space-y-3">
                                                    @foreach ($groupTimes as $proposedTime)
                                                        <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 text-sm text-slate-300">
                                                            <p class="font-semibold text-white">{{ $proposedTime->start_at->format('Y-m-d H:i') }}</p>
                                                            <p class="mt-1">{{ $proposedTime->duration_minutes }} minutes ({{ $proposedTime->duration_minutes }} SS)</p>

                                                            @if ($proposedTime->status === 'selected')
                                                                <p class="mt-2 text-emerald-400">Chosen time</p>
                                                            @elseif ($proposedTime->status === 'denied')
                                                                <p class="mt-2 text-rose-400">Denied</p>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-sm text-slate-500">
                                                No proposed times yet. The learner should propose times after the request is accepted.
                                            </p>
                                        @endforelse
                                    </div>

                                    @if ($activeLearningSession)
                                        <div class="mt-5 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-200">
                                            A learning session is currently active for this exchange request.
                                            <a href="{{ route('learning-sessions.show', $activeLearningSession) }}" class="ml-2 font-semibold text-white underline">
                                                View session
                                            </a>
                                        </div>
                                    @elseif ($latestCompletedSession)
                                        <div class="mt-5 rounded-xl border border-blue-500/30 bg-blue-500/10 p-4 text-sm text-blue-200">
                                            The latest session is completed. The learner can propose new times to plan another session in the same conversation.
                                            <a href="{{ route('learning-sessions.show', $latestCompletedSession) }}" class="ml-2 font-semibold text-white underline">
                                                View latest session
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="space-y-6">
                            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-2xl shadow-slate-950/40">
                                <h3 class="text-xl font-semibold text-white">Actions</h3>

                                <div class="mt-5 flex flex-col gap-3">
                                    @if ($canAnswer)
                                        <form method="POST" action="{{ route('exchange-requests.accept', $exchangeRequest) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white">
                                                Accept
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('exchange-requests.refuse', $exchangeRequest) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button class="w-full rounded-xl border border-slate-700 px-4 py-3 text-sm font-semibold text-slate-200">
                                                Refuse
                                            </button>
                                        </form>
                                    @endif

                                    @if ($canCancel)
                                        <form method="POST" action="{{ route('exchange-requests.cancel', $exchangeRequest) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button class="w-full rounded-xl bg-rose-900/70 px-4 py-3 text-sm font-bold text-rose-100">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif

                                    @if ($exchangeRequest->status === 'accepted' && $exchangeRequest->conversation)
                                        <a href="{{ route('conversations.show', $exchangeRequest->conversation) }}" class="rounded-xl border border-slate-700 px-4 py-3 text-center text-sm font-semibold text-slate-200">
                                            Open conversation
                                        </a>
                                    @endif

                                    <a href="{{ route('exchange-requests.index') }}" class="rounded-xl border border-slate-700 px-4 py-3 text-center text-sm font-semibold text-slate-400">
                                        Back to exchange requests
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
