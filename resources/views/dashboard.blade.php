<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-white">Dashboard</h2>
                <p class="mt-1 text-sm text-slate-400">1 SS = 1 minute. Credits move after both students validate a session.</p>
            </div>

            <a href="{{ route('explore.index') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 hover:bg-blue-500">
                Find a profile
            </a>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="ss-container space-y-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="ss-card">
                    <p class="text-xs uppercase tracking-wider text-slate-500">Balance</p>
                    <p class="mt-2 text-3xl font-black text-white">{{ auth()->user()->creditBalance }}</p>
                    <p class="ss-muted">SS credits</p>
                </div>

                <div class="ss-card">
                    <p class="text-xs uppercase tracking-wider text-slate-500">Requests</p>
                    <p class="mt-2 text-3xl font-black text-white">{{ auth()->user()->sentExchangeRequests()->count() + auth()->user()->receivedExchangeRequests()->count() }}</p>
                    <p class="ss-muted">total exchanges</p>
                </div>

                <div class="ss-card">
                    <p class="text-xs uppercase tracking-wider text-slate-500">Sessions</p>
                    <p class="mt-2 text-3xl font-black text-white">{{ auth()->user()->learnerSessions()->count() + auth()->user()->helperSessions()->count() }}</p>
                    <p class="ss-muted">planned sessions</p>
                </div>

                <div class="ss-card">
                    <p class="text-xs uppercase tracking-wider text-slate-500">Reputation</p>
                    <p class="mt-2 text-3xl font-black text-white">{{ number_format(auth()->user()->reputation_score, 1) }}</p>
                    <p class="ss-muted">out of 5</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="ss-card lg:col-span-2">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">Quick actions</h3>
                        <span class="rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-300">Start here</span>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <a href="{{ route('explore.index') }}" class="rounded-2xl border border-slate-800 bg-slate-950/60 p-5 transition hover:border-blue-500">
                            <p class="font-bold text-white">Find a student</p>
                            <p class="mt-2 text-sm text-slate-400">Search a profile, then send an exchange request.</p>
                        </a>

                        <a href="{{ route('conversations.index') }}" class="rounded-2xl border border-slate-800 bg-slate-950/60 p-5 transition hover:border-blue-500">
                            <p class="font-bold text-white">Open conversations</p>
                            <p class="mt-2 text-sm text-slate-400">Chat is available after an accepted request.</p>
                        </a>

                        <a href="{{ route('learning-sessions.index') }}" class="rounded-2xl border border-slate-800 bg-slate-950/60 p-5 transition hover:border-blue-500">
                            <p class="font-bold text-white">Check sessions</p>
                            <p class="mt-2 text-sm text-slate-400">Confirm completed sessions to unlock SS transactions.</p>
                        </a>

                        <a href="{{ route('profile.edit') }}" class="rounded-2xl border border-slate-800 bg-slate-950/60 p-5 transition hover:border-blue-500">
                            <p class="font-bold text-white">Manage profile</p>
                            <p class="mt-2 text-sm text-slate-400">Update your public information and account settings.</p>
                        </a>
                    </div>
                </div>

                <div class="ss-card">
                    <h3 class="text-lg font-bold text-white">How SkillSwap works</h3>
                    <div class="mt-5 space-y-4 text-sm text-slate-400">
                        <p><span class="font-bold text-blue-400">1.</span> Create or accept an exchange request.</p>
                        <p><span class="font-bold text-blue-400">2.</span> Use chat to coordinate after acceptance.</p>
                        <p><span class="font-bold text-blue-400">3.</span> Plan a session using proposed times.</p>
                        <p><span class="font-bold text-blue-400">4.</span> Confirm completion and transfer SS credits.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
