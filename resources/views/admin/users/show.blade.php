<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">Admin User Profile</h2>
            <p class="mt-1 text-sm text-slate-400">Detailed account information for moderation.</p>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="ss-container">
            <div class="ss-card">
                <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-600 text-lg font-black text-white">
                            {{ strtoupper(substr($user->first_name, 0, 1).substr($user->last_name, 0, 1)) }}
                        </div>

                        <div>
                            <h3 class="text-2xl font-black text-white">{{ $user->name }}</h3>
                            <p class="text-sm text-slate-400">{{ $user->email }}</p>
                        </div>
                    </div>

                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $user->account_status === 'active' ? 'bg-emerald-500/10 text-emerald-300' : 'bg-rose-500/10 text-rose-300' }}">
                        {{ strtoupper($user->account_status) }}
                    </span>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <p class="rounded-xl bg-slate-950/60 p-4 text-sm text-slate-300"><strong class="text-white">City:</strong> {{ $user->city ?: 'No city provided' }}</p>
                    <p class="rounded-xl bg-slate-950/60 p-4 text-sm text-slate-300"><strong class="text-white">Role:</strong> {{ $user->roles->pluck('name')->join(', ') ?: 'No role' }}</p>
                    <p class="rounded-xl bg-slate-950/60 p-4 text-sm text-slate-300"><strong class="text-white">SS balance:</strong> {{ $user->creditBalance }}</p>
                    <p class="rounded-xl bg-slate-950/60 p-4 text-sm text-slate-300"><strong class="text-white">Reputation:</strong> {{ number_format($user->reputation_score, 2) }}/5</p>
                    <p class="rounded-xl bg-slate-950/60 p-4 text-sm text-slate-300"><strong class="text-white">Skills:</strong> {{ $user->skills->count() }}</p>
                    <p class="rounded-xl bg-slate-950/60 p-4 text-sm text-slate-300"><strong class="text-white">Needs:</strong> {{ $user->needs->count() }}</p>
                    <p class="rounded-xl bg-slate-950/60 p-4 text-sm text-slate-300"><strong class="text-white">Transactions:</strong> {{ $user->transactions->count() }}</p>
                    <p class="rounded-xl bg-slate-950/60 p-4 text-sm text-slate-300"><strong class="text-white">Received reputations:</strong> {{ $user->receivedRatings->count() }}</p>
                </div>

                <div class="mt-6 rounded-xl bg-slate-950/60 p-4">
                    <p class="text-sm text-slate-300"><strong class="text-white">Bio:</strong> {{ $user->bio ?: 'No bio provided' }}</p>
                </div>

                <div class="mt-6">
                    <a href="{{ route('admin.users.index') }}" class="ss-link">Back to users</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
