<x-app-layout>
    @php
        $exchangeRequest = $conversation->exchangeRequest;
        $otherUser = $exchangeRequest->learner_id === auth()->id()
            ? $exchangeRequest->helper
            : $exchangeRequest->learner;
        $pendingProposedTimes = $exchangeRequest->proposedTimes
            ->where('status', 'pending')
            ->sortBy('start_at')
            ->values();
        $proposedTimeGroups = $exchangeRequest->proposedTimes
            ->sortBy([
                ['proposal_group', 'asc'],
                ['start_at', 'asc'],
            ])
            ->groupBy('proposal_group');
        $hasActiveSession = $exchangeRequest->learningSessions->contains(function ($learningSession) {
            return in_array($learningSession->status, ['scheduled', 'in_progress'], true);
        });
        $canSendTimes = $exchangeRequest->status === 'accepted'
            && $exchangeRequest->learner_id === auth()->id()
            && ! $hasActiveSession
            && $pendingProposedTimes->isEmpty();
        $canAnswerTimes = $exchangeRequest->status === 'accepted'
            && $exchangeRequest->helper_id === auth()->id()
            && $pendingProposedTimes->isNotEmpty()
            && ! $hasActiveSession;
        $timelineItems = $conversation->messages->map(function ($message) {
            return [
                'type' => 'message',
                'created_at' => $message->created_at,
                'data' => $message,
            ];
        });

        foreach ($proposedTimeGroups as $proposalGroup => $groupTimes) {
            $timelineItems->push([
                'type' => 'proposed_times',
                'created_at' => $groupTimes->max('created_at'),
                'data' => $groupTimes->values(),
                'proposal_group' => $proposalGroup,
            ]);
        }

        $timelineItems = $timelineItems->sortBy('created_at')->values();
    @endphp

    <div class="bg-slate-950">
        <div class="h-screen overflow-hidden">
            <div class="grid h-full grid-cols-1 lg:grid-cols-[360px_minmax(0,1fr)]">
                <section class="border-r border-slate-800 bg-slate-950/80">
                    <div class="border-b border-slate-800 px-5 py-5">
                        <h1 class="text-2xl font-semibold text-white">Messages</h1>
                    </div>

                    <div class="ss-hide-scrollbar h-[calc(100vh-89px)] overflow-y-auto">
                        @foreach ($conversations as $item)
                            @php
                                $itemRequest = $item->exchangeRequest;
                                $itemOtherUser = $itemRequest->learner_id === auth()->id()
                                    ? $itemRequest->helper
                                    : $itemRequest->learner;
                                $lastMessage = $item->messages->sortByDesc('created_at')->first();
                                $isActive = $item->id === $conversation->id;
                            @endphp

                            <a href="{{ route('conversations.show', $item) }}" class="block border-b border-slate-800 px-5 py-4 {{ $isActive ? 'border-l-4 border-l-blue-500 bg-slate-900' : 'hover:bg-slate-900' }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-base font-semibold text-white">{{ $itemOtherUser->name }}</p>
                                        <p class="mt-1 truncate text-sm {{ $isActive ? 'text-blue-400' : 'text-slate-500' }}">
                                            {{ $lastMessage ? $lastMessage->content : 'No messages yet.' }}
                                        </p>
                                    </div>

                                    <span class="shrink-0 text-xs text-slate-500">
                                        {{ $lastMessage ? $lastMessage->created_at->format('H:i') : '' }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="flex h-full flex-col overflow-hidden bg-slate-900/40">
                    <div class="border-b border-slate-800 bg-slate-900 px-5 py-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-lg font-semibold text-white">{{ $otherUser->name }}</p>
                                <p class="text-sm text-slate-400">
                                    {{ $exchangeRequest->skill?->title ?: $exchangeRequest->need?->title ?: 'Conversation' }}
                                </p>
                            </div>

                            @if ($canSendTimes)
                                <a href="{{ route('proposed-times.create', $exchangeRequest) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white">
                                    Send times
                                </a>
                            @endif
                        </div>
                    </div>

                    <div id="messages-list" class="ss-hide-scrollbar flex-1 space-y-6 overflow-y-auto px-5 py-6">
                        @forelse ($timelineItems as $item)
                            @if ($item['type'] === 'message')
                                @php $message = $item['data']; @endphp
                                <div class="{{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                    <div class="inline-block max-w-xl rounded-2xl px-5 py-4 {{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-slate-700 text-slate-100' }}">
                                        <p class="text-sm leading-7">{{ $message->content }}</p>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500">{{ $message->created_at->format('H:i') }}</p>
                                </div>
                            @else
                                <div class="text-center">
                                    <div class="mx-auto max-w-xl rounded-2xl border border-slate-700 bg-slate-800/80 p-5 text-left">
                                        <p class="text-center text-sm font-semibold text-white">Proposed times</p>

                                        <div class="mt-4 space-y-3">
                                            @php
                                                $groupTimes = $item['data'];
                                                $selectedProposedTime = $groupTimes->first(function ($proposedTime) use ($exchangeRequest) {
                                                    return $exchangeRequest->learningSessions->contains(function ($learningSession) use ($proposedTime) {
                                                        return $learningSession->scheduled_at?->equalTo($proposedTime->start_at);
                                                    });
                                                });
                                                $selectedSession = $selectedProposedTime
                                                    ? $exchangeRequest->learningSessions->first(function ($learningSession) use ($selectedProposedTime) {
                                                        return $learningSession->scheduled_at?->equalTo($selectedProposedTime->start_at);
                                                    })
                                                    : null;
                                                $groupStatus = $selectedSession
                                                    ? 'selected'
                                                    : ($groupTimes->contains('status', 'pending') ? 'pending' : 'denied');
                                            @endphp

                                            @foreach ($groupTimes as $proposedTime)
                                                <label class="flex items-start gap-3 rounded-xl border border-slate-700 bg-slate-900/70 px-4 py-3 text-sm text-slate-200">
                                                    @if ($canAnswerTimes && $groupStatus === 'pending')
                                                        <input type="radio" name="proposed_time_id" value="{{ $proposedTime->id }}" form="choose-proposed-time-form" class="mt-1">
                                                    @endif

                                                    <div>
                                                        <p>{{ $proposedTime->start_at->format('Y-m-d H:i') }}</p>
                                                        <p class="mt-1 text-xs text-slate-400">{{ $proposedTime->duration_minutes }} minutes</p>
                                                        @if ($selectedProposedTime && $selectedProposedTime->id === $proposedTime->id)
                                                            <p class="mt-1 text-xs font-semibold text-emerald-400">Chosen time</p>
                                                        @elseif ($proposedTime->status === 'denied')
                                                            <p class="mt-1 text-xs font-semibold text-rose-400">Denied</p>
                                                        @endif
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>

                                        @if ($canAnswerTimes && $groupStatus === 'pending')
                                            <div class="mt-5 flex items-center justify-center gap-3">
                                                <form id="choose-proposed-time-form" method="POST" action="{{ route('proposed-times.submit-selection', $exchangeRequest) }}">
                                                    @csrf
                                                    <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white">
                                                        Submit
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('proposed-times.deny-all', $exchangeRequest) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="rounded-lg bg-slate-700 px-4 py-2 text-sm font-bold text-white">
                                                        Deny all
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif ($groupStatus === 'selected')
                                            <div class="mt-5 text-center">
                                                <p class="text-sm font-medium text-emerald-400">One time was chosen.</p>
                                                @if ($selectedSession)
                                                    <a href="{{ route('learning-sessions.show', $selectedSession) }}" class="mt-3 inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white">
                                                        Session details
                                                    </a>
                                                @endif
                                            </div>
                                        @elseif ($groupStatus === 'denied')
                                            <p class="mt-5 text-center text-sm font-medium text-rose-400">These proposed times were denied.</p>
                                        @else
                                            <p class="mt-5 text-center text-xs text-slate-500">Waiting for the helper to choose one time or deny all.</p>
                                        @endif

                                        <p class="mt-3 text-center text-xs text-slate-500">{{ $item['created_at']->format('H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p id="empty-messages" class="text-sm text-slate-500">No messages yet. Start the conversation below.</p>
                        @endforelse
                    </div>

                    <div class="border-t border-slate-800 bg-slate-900 px-4 py-5">
                        <form method="POST" action="{{ route('messages.store', $conversation) }}" class="flex items-center gap-3">
                            @csrf

                            <textarea id="content" name="content" rows="1" class="ss-input min-h-[52px] flex-1 resize-none px-4 py-3" placeholder="Type your message..." required>{{ old('content') }}</textarea>

                            <button class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>

                        <x-input-error class="mt-2" :messages="$errors->get('content')" />
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (! window.Echo) {
                return;
            }

            const conversationId = @json($conversation->id);
            const currentUserId = @json(auth()->id());
            const messagesList = document.getElementById('messages-list');

            function escapeHtml(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function addMessage(event) {
                const emptyMessages = document.getElementById('empty-messages');

                if (emptyMessages) {
                    emptyMessages.remove();
                }

                const isMine = Number(event.sender_id) === Number(currentUserId);
                const wrapper = document.createElement('div');
                const bubbleClasses = isMine
                    ? 'bg-blue-600 text-white'
                    : 'bg-slate-700 text-slate-100';

                wrapper.className = isMine ? 'text-right' : 'text-left';
                wrapper.innerHTML = `
                    <div class="inline-block max-w-xl rounded-2xl px-5 py-4 ${bubbleClasses}">
                        <p class="text-sm leading-7">${escapeHtml(event.content)}</p>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">${escapeHtml(event.created_at.slice(-5))}</p>
                `;

                messagesList.appendChild(wrapper);
                messagesList.scrollTop = messagesList.scrollHeight;
            }

            window.Echo.private(`conversation.${conversationId}`)
                .listen('.message.sent', addMessage);
        });
    </script>
</x-app-layout>
