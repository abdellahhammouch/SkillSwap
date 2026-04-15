<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Learning Sessions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">My sessions</h3>

                <div class="space-y-4">
                    @forelse ($learningSessions as $learningSession)
                        @php
                            $exchangeRequest = $learningSession->exchangeRequest;
                            $otherUser = $exchangeRequest->learner_id === auth()->id()
                                ? $exchangeRequest->helper
                                : $exchangeRequest->learner;
                        @endphp

                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        Session with {{ $otherUser->name }}
                                    </p>

                                    <p class="text-sm text-gray-600">
                                        Date: {{ $learningSession->scheduled_at->format('Y-m-d H:i') }}
                                    </p>

                                    <p class="text-sm text-gray-600">
                                        Duration: {{ $learningSession->duration_minutes }} minutes
                                    </p>

                                    <p class="text-sm text-gray-600">
                                        Status: {{ ucfirst(str_replace('_', ' ', $learningSession->status)) }}
                                    </p>
                                </div>

                                <a href="{{ route('learning-sessions.show', $learningSession) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">
                            No learning sessions planned yet.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
