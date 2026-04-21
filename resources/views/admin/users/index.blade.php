<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">Users Management</h2>
            <p class="mt-1 text-sm text-slate-400">Review account status and keep SkillSwap safe.</p>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="ss-container space-y-6">
            @if (session('success'))
                <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-300">
                    {{ session('error') }}
                </div>
            @endif

            <div class="ss-card">
                <div class="space-y-4">
                    @forelse ($users as $user)
                        <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-4">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-800 text-sm font-bold text-slate-100">
                                        {{ strtoupper(substr($user->first_name, 0, 1).substr($user->last_name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="font-bold text-white">{{ $user->name }}</p>
                                        <p class="text-sm text-slate-400">{{ $user->email }}</p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $user->roles->pluck('name')->join(', ') ?: 'No role' }} · {{ $user->creditBalance }} SS · {{ number_format($user->reputation_score, 2) }}/5
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $user->account_status === 'active' ? 'bg-emerald-500/10 text-emerald-300' : 'bg-rose-500/10 text-rose-300' }}">
                                        {{ strtoupper($user->account_status) }}
                                    </span>

                                    <a href="{{ route('admin.users.show', $user) }}" class="rounded-xl bg-slate-800 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white hover:bg-slate-700">
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
                        <p class="text-sm text-slate-400">No users found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
