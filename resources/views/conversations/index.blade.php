<x-app-layout>
    <div class="bg-slate-950">
        <div class="h-screen">
            <div class="grid h-full grid-cols-1 lg:grid-cols-[360px_minmax(0,1fr)]">
                <section class="border-r border-slate-800 bg-slate-950/80">
                    <div class="border-b border-slate-800 px-5 py-5">
                        <h1 class="text-2xl font-semibold text-white">Messages</h1>
                    </div>

                    <div class="h-[calc(100vh-89px)] overflow-y-auto">
                        @forelse ($conversations as $conversation)
                            @php
                                $exchangeRequest = $conversation->exchangeRequest;
                                $otherUser = $exchangeRequest->learner_id === auth()->id()
                                    ? $exchangeRequest->helper
                                    : $exchangeRequest->learner;
                                $lastMessage = $conversation->messages->sortByDesc('created_at')->first();
                            @endphp

                            <a href="{{ route('conversations.show', $conversation) }}" class="block border-b border-slate-800 px-5 py-4 hover:bg-slate-900">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-base font-semibold text-white">{{ $otherUser->name }}</p>
                                        <p class="mt-1 truncate text-sm text-blue-400">
                                            {{ $lastMessage ? $lastMessage->content : 'No messages yet.' }}
                                        </p>
                                    </div>

                                    <span class="shrink-0 text-xs text-slate-500">
                                        {{ $lastMessage ? $lastMessage->created_at->format('H:i') : '' }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <p class="px-5 py-6 text-sm text-slate-500">No conversations yet.</p>
                        @endforelse
                    </div>
                </section>

                <section class="flex h-full items-center justify-center bg-slate-900/40 px-6">
                    <div class="text-center">
                        <h2 class="text-2xl font-semibold text-white">Open a conversation</h2>
                        <p class="mt-3 text-sm text-slate-400">Select a message thread from the left panel.</p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
