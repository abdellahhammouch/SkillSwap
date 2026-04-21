<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">Explore</h2>
        <p class="mt-1 text-sm text-slate-400">Search students before sending an exchange request.</p>
    </x-slot>

    <div class="py-8">
        <div class="ss-container">
            <div class="ss-card">
                <form method="GET" action="{{ route('explore.index') }}" class="flex gap-3">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search by name, city, skill, or need"
                        class="ss-input w-full"
                    >

                    <button class="rounded-xl bg-blue-600 px-5 py-2 text-sm font-bold text-white">
                        Search
                    </button>
                </form>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                @forelse ($users as $user)
                    <div class="ss-card">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex gap-4">
                                @if ($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="Profile image" class="h-14 w-14 rounded-full object-cover">
                                @else
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-800 text-sm font-bold text-white">
                                        {{ strtoupper(substr($user->first_name, 0, 1).substr($user->last_name, 0, 1)) }}
                                    </div>
                                @endif

                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $user->name }}</h3>
                                    <p class="text-sm text-slate-400">{{ $user->city ?: 'No city' }}</p>
                                    <p class="mt-2 text-sm text-slate-300">{{ $user->bio ?: 'No bio yet.' }}</p>
                                </div>
                            </div>

                            <a href="{{ route('users.show', $user) }}" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white">
                                Profile
                            </a>
                        </div>

                        <div class="mt-5">
                            <p class="text-sm font-bold text-white">Skills</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @forelse ($user->skills->take(4) as $skill)
                                    <span class="rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-300">
                                        {{ $skill->title }}
                                    </span>
                                @empty
                                    <span class="text-sm text-slate-500">No skills yet.</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="text-sm font-bold text-white">Needs</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @forelse ($user->needs->where('status', 'open')->take(4) as $need)
                                    <span class="rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-300">
                                        {{ $need->title }}
                                    </span>
                                @empty
                                    <span class="text-sm text-slate-500">No open needs.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="ss-card md:col-span-2">
                        <p class="text-sm text-slate-400">No users found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
