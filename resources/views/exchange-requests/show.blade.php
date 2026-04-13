<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Exchange Request Details') }}
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
                <div class="space-y-3">
                    <p><strong>Type:</strong> {{ $exchangeRequest->type === 'help_request' ? 'Ask for help' : 'Offer help' }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($exchangeRequest->status) }}</p>
                    <p><strong>Learner:</strong> {{ $exchangeRequest->learner->name }}</p>
                    <p><strong>Helper:</strong> {{ $exchangeRequest->helper->name }}</p>
                    <p><strong>Need:</strong> {{ $exchangeRequest->need?->title ?: 'No need selected' }}</p>
                    <p><strong>Skill:</strong> {{ $exchangeRequest->skill?->title ?: 'No skill selected' }}</p>
                    <p><strong>Duration:</strong> {{ $exchangeRequest->duration_minutes ? $exchangeRequest->duration_minutes.' minutes' : 'Not defined' }}</p>
                    <p><strong>Message:</strong> {{ $exchangeRequest->message ?: 'No message' }}</p>
                    <p><strong>Expires at:</strong> {{ $exchangeRequest->expires_at?->format('Y-m-d H:i') }}</p>
                </div>

                <div class="mt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">Proposed times</h3>

                    <div class="space-y-2">
                        @foreach ($exchangeRequest->proposedTimes as $proposedTime)
                            <div class="border border-gray-200 rounded p-3 text-sm text-gray-700">
                                {{ $proposedTime->start_at->format('Y-m-d H:i') }}
                                to
                                {{ $proposedTime->end_at->format('Y-m-d H:i') }}
                            </div>
                        @endforeach
                    </div>
                </div>

                @php
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
                @endphp

                <div class="mt-6 flex flex-wrap gap-3">
                    @if ($canAnswer)
                        <form method="POST" action="{{ route('exchange-requests.accept', $exchangeRequest) }}">
                            @csrf
                            @method('PATCH')

                            <x-primary-button>{{ __('Accept') }}</x-primary-button>
                        </form>

                        <form method="POST" action="{{ route('exchange-requests.refuse', $exchangeRequest) }}">
                            @csrf
                            @method('PATCH')

                            <x-secondary-button>{{ __('Refuse') }}</x-secondary-button>
                        </form>
                    @endif

                    @if ($canCancel)
                        <form method="POST" action="{{ route('exchange-requests.cancel', $exchangeRequest) }}">
                            @csrf
                            @method('PATCH')

                            <x-danger-button>{{ __('Cancel') }}</x-danger-button>
                        </form>
                    @endif

                    @if ($exchangeRequest->status === 'accepted' && $exchangeRequest->conversation)
                        <a href="{{ route('conversations.show', $exchangeRequest->conversation) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Open conversation
                        </a>
                    @endif

                    <a href="{{ route('exchange-requests.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                        Back to requests
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
