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
                <div class="space-y-3">
                    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $learningSession->status)) }}</p>
                    <p><strong>Scheduled at:</strong> {{ $learningSession->scheduled_at->format('Y-m-d H:i') }}</p>
                    <p><strong>Duration:</strong> {{ $learningSession->duration_minutes }} minutes</p>
                    <p><strong>Learner:</strong> {{ $learningSession->exchangeRequest->learner->name }}</p>
                    <p><strong>Helper:</strong> {{ $learningSession->exchangeRequest->helper->name }}</p>
                    <p><strong>Need:</strong> {{ $learningSession->exchangeRequest->need?->title ?: 'No need selected' }}</p>
                    <p><strong>Skill:</strong> {{ $learningSession->exchangeRequest->skill?->title ?: 'No skill selected' }}</p>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('exchange-requests.show', $learningSession->exchangeRequest) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                        View exchange request
                    </a>

                    <a href="{{ route('learning-sessions.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        Back to sessions
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
