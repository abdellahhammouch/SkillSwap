<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Conversations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">My conversations</h3>

                <div class="space-y-4">
                    @forelse ($conversations as $conversation)
                        @php
                            $exchangeRequest = $conversation->exchangeRequest;
                            $otherUser = $exchangeRequest->learner_id === auth()->id()
                                ? $exchangeRequest->helper
                                : $exchangeRequest->learner;
                            $lastMessage = $conversation->messages->sortByDesc('created_at')->first();
                        @endphp

                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        Conversation with {{ $otherUser->name }}
                                    </p>

                                    <p class="text-sm text-gray-600">
                                        Exchange: {{ $exchangeRequest->type === 'help_request' ? 'Ask for help' : 'Offer help' }}
                                    </p>

                                    <p class="text-sm text-gray-600">
                                        Need: {{ $exchangeRequest->need?->title ?: 'No need selected' }}
                                    </p>

                                    <p class="text-sm text-gray-600">
                                        Skill: {{ $exchangeRequest->skill?->title ?: 'No skill selected' }}
                                    </p>

                                    <p class="mt-2 text-sm text-gray-500">
                                        Last message:
                                        {{ $lastMessage ? $lastMessage->content : 'No messages yet.' }}
                                    </p>
                                </div>

                                <a href="{{ route('conversations.show', $conversation) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Open
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">
                            No conversations yet. A conversation will be created after an exchange request is accepted.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
