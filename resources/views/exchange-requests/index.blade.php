<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Exchange Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Create a new request</h3>

                    <a href="{{ route('exchange-requests.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        New request
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Sent Requests</h3>

                    <div class="space-y-4">
                        @forelse ($sentRequests as $exchangeRequest)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $exchangeRequest->type === 'help_request' ? 'Asked for help' : 'Offered help' }}
                                </p>
                                <p class="text-sm text-gray-600">Status: {{ ucfirst($exchangeRequest->status) }}</p>
                                <p class="text-sm text-gray-600">Learner: {{ $exchangeRequest->learner->name }}</p>
                                <p class="text-sm text-gray-600">Helper: {{ $exchangeRequest->helper->name }}</p>

                                <a href="{{ route('exchange-requests.show', $exchangeRequest) }}" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-900">
                                    View details
                                </a>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">No sent requests yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Received Requests</h3>

                    <div class="space-y-4">
                        @forelse ($receivedRequests as $exchangeRequest)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $exchangeRequest->type === 'help_request' ? 'Asked for help' : 'Offered help' }}
                                </p>
                                <p class="text-sm text-gray-600">Status: {{ ucfirst($exchangeRequest->status) }}</p>
                                <p class="text-sm text-gray-600">Learner: {{ $exchangeRequest->learner->name }}</p>
                                <p class="text-sm text-gray-600">Helper: {{ $exchangeRequest->helper->name }}</p>

                                <a href="{{ route('exchange-requests.show', $exchangeRequest) }}" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-900">
                                    View details
                                </a>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">No received requests yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
