<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="space-y-4">
                    @forelse ($users as $user)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <div class="space-y-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                    <p class="text-sm text-gray-600">Role: {{ $user->roles->pluck('name')->join(', ') ?: 'No role' }}</p>
                                    <p class="text-sm text-gray-600">Status: {{ ucfirst($user->account_status) }}</p>
                                    <p class="text-sm text-gray-600">SS balance: {{ $user->credit_balance_minutes }}</p>
                                    <p class="text-sm text-gray-600">Reputation: {{ number_format($user->reputation_score, 2) }}/5</p>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                        View profile
                                    </a>

                                    @if ($user->account_status === 'active')
                                        <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                                            @csrf
                                            @method('PATCH')

                                            <x-danger-button>Ban</x-danger-button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.reactivate', $user) }}">
                                            @csrf
                                            @method('PATCH')

                                            <x-primary-button>Reactivate</x-primary-button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">No users found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
