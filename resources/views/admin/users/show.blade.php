<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin User Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="space-y-3">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>City:</strong> {{ $user->city ?: 'No city provided' }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($user->account_status) }}</p>
                    <p><strong>Role:</strong> {{ $user->roles->pluck('name')->join(', ') ?: 'No role' }}</p>
                    <p><strong>SS balance:</strong> {{ $user->credit_balance_minutes }}</p>
                    <p><strong>Reputation:</strong> {{ number_format($user->reputation_score, 2) }}/5</p>
                    <p><strong>Bio:</strong> {{ $user->bio ?: 'No bio provided' }}</p>
                    <p><strong>Skills count:</strong> {{ $user->skills->count() }}</p>
                    <p><strong>Needs count:</strong> {{ $user->needs->count() }}</p>
                    <p><strong>Transactions count:</strong> {{ $user->transactions->count() }}</p>
                    <p><strong>Received reputations:</strong> {{ $user->receivedRatings->count() }}</p>
                </div>

                <div class="mt-6">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        Back to users
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
