<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Profile') }}
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
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>City:</strong> {{ $user->city ?: 'No city provided' }}</p>
                    <p><strong>Bio:</strong> {{ $user->bio ?: 'No bio provided' }}</p>
                    <p><strong>Reputation score:</strong> {{ number_format($user->reputation_score, 2) }}/5</p>
                </div>
            </div>

            @if (auth()->id() !== $user->id)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Add a reputation</h3>

                    @if ($existingRating)
                        <p class="mt-2 text-sm text-green-700">
                            You already rated this user with a score of {{ $existingRating->score }}/5.
                        </p>
                    @elseif ($canRate)
                        <form method="POST" action="{{ route('ratings.store', $user) }}" class="mt-4 space-y-4">
                            @csrf

                            <div>
                                <x-input-label for="score" :value="__('Score')" />
                                <select id="score" name="score" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Choose a score</option>
                                    @for ($score = 1; $score <= 5; $score++)
                                        <option value="{{ $score }}" @selected(old('score') == $score)>{{ $score }}/5</option>
                                    @endfor
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('score')" />
                            </div>

                            <div>
                                <x-input-label for="comment" :value="__('Comment')" />
                                <textarea id="comment" name="comment" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('comment') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                            </div>

                            <x-primary-button>{{ __('Save reputation') }}</x-primary-button>
                        </form>
                    @else
                        <p class="mt-2 text-sm text-gray-600">
                            You can rate this user only if you already have at least one accepted exchange request together.
                        </p>
                    @endif
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900">Received reputations</h3>

                <div class="mt-4 space-y-4">
                    @forelse ($receivedRatings as $rating)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $rating->author->name }} rated {{ $rating->score }}/5
                            </p>

                            <p class="mt-2 text-sm text-gray-600">
                                {{ $rating->comment ?: 'No comment' }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">
                            No reputations received yet.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
