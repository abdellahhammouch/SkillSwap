<x-app-layout>
    <div class="bg-slate-950">
        <div class="min-h-screen">
            <main>
                <div class="mx-auto max-w-6xl px-6 py-10">
                    <h1 class="ss-title inline-block border-b-2 border-blue-500 pr-5 text-5xl text-slate-200">
                        Exchange Requests
                    </h1>

                    <div class="mt-12 grid gap-6 lg:grid-cols-2">
                        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                            <h2 class="text-xl font-semibold text-white">Received Requests</h2>

                            <div class="mt-6 space-y-4">
                                @forelse ($receivedRequests as $exchangeRequest)
                                    <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                        <p class="text-sm text-slate-400">{{ ucfirst(str_replace('_', ' ', $exchangeRequest->type)) }}</p>
                                        <p class="mt-2 text-lg font-semibold text-white">
                                            {{ $exchangeRequest->type === 'help_request' ? $exchangeRequest->learner->name : $exchangeRequest->helper->name }}
                                        </p>
                                        <p class="mt-2 text-sm text-slate-300">
                                            {{ $exchangeRequest->skill?->title ?: $exchangeRequest->need?->title ?: 'No title' }}
                                        </p>
                                        <p class="mt-2 text-sm text-slate-500">Status: {{ ucfirst($exchangeRequest->status) }}</p>

                                        <a href="{{ route('exchange-requests.show', $exchangeRequest) }}" class="mt-4 inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white">
                                            Details
                                        </a>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No received requests yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                            <h2 class="text-xl font-semibold text-white">Sent Requests</h2>

                            <div class="mt-6 space-y-4">
                                @forelse ($sentRequests as $exchangeRequest)
                                    <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                        <p class="text-sm text-slate-400">{{ ucfirst(str_replace('_', ' ', $exchangeRequest->type)) }}</p>
                                        <p class="mt-2 text-lg font-semibold text-white">
                                            {{ $exchangeRequest->type === 'help_request' ? $exchangeRequest->helper->name : $exchangeRequest->learner->name }}
                                        </p>
                                        <p class="mt-2 text-sm text-slate-300">
                                            {{ $exchangeRequest->skill?->title ?: $exchangeRequest->need?->title ?: 'No title' }}
                                        </p>
                                        <p class="mt-2 text-sm text-slate-500">Status: {{ ucfirst($exchangeRequest->status) }}</p>

                                        <a href="{{ route('exchange-requests.show', $exchangeRequest) }}" class="mt-4 inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white">
                                            Details
                                        </a>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No sent requests yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
