<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Conversation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $exchangeRequest = $conversation->exchangeRequest;
                $otherUser = $exchangeRequest->learner_id === auth()->id()
                    ? $exchangeRequest->helper
                    : $exchangeRequest->learner;
            @endphp

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Chat with {{ $otherUser->name }}
                        </h3>

                        <p class="text-sm text-gray-600">
                            This conversation started after the exchange request was accepted.
                        </p>
                    </div>

                    <a href="{{ route('exchange-requests.show', $exchangeRequest) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                        View exchange request
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div id="messages-list" class="space-y-4">
                    @forelse ($conversation->messages as $message)
                        <div class="{{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                            <div class="inline-block max-w-2xl rounded-lg px-4 py-3 {{ $message->sender_id === auth()->id() ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-900' }}">
                                <p class="text-sm font-semibold">
                                    {{ $message->sender_id === auth()->id() ? 'Me' : $message->sender->name }}
                                </p>

                                <p class="mt-1 text-sm">
                                    {{ $message->content }}
                                </p>

                                <p class="mt-2 text-xs opacity-75">
                                    {{ $message->created_at->format('Y-m-d H:i') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p id="empty-messages" class="text-sm text-gray-600">
                            No messages yet. Start the conversation below.
                        </p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('messages.store', $conversation) }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="content" :value="__('Message')" />
                        <textarea id="content" name="content" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('content') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('content')" />
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button>{{ __('Send') }}</x-primary-button>

                        <a href="{{ route('conversations.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Back to conversations
                        </a>
                    </div>
                </form>
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
                    ? 'bg-gray-800 text-white'
                    : 'bg-gray-100 text-gray-900';

                wrapper.className = isMine ? 'text-right' : 'text-left';
                wrapper.innerHTML = `
                    <div class="inline-block max-w-2xl rounded-lg px-4 py-3 ${bubbleClasses}">
                        <p class="text-sm font-semibold">${isMine ? 'Me' : escapeHtml(event.sender_name)}</p>
                        <p class="mt-1 text-sm">${escapeHtml(event.content)}</p>
                        <p class="mt-2 text-xs opacity-75">${escapeHtml(event.created_at)}</p>
                    </div>
                `;

                messagesList.appendChild(wrapper);
            }

            window.Echo.private(`conversation.${conversationId}`)
                .listen('.message.sent', addMessage);
        });
    </script>
</x-app-layout>
