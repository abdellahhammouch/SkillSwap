<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">Profile Management</h2>
            <p class="mt-1 text-sm text-slate-400">Update your personal information and profile image.</p>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="ss-container grid grid-cols-1 gap-6 lg:grid-cols-10">
            <div class="space-y-6 lg:col-span-7">
                <div id="personal-info" class="ss-card">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="ss-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-white">My Skills</h3>
                            <p class="text-xs text-slate-500">Skills you are willing to teach or offer</p>
                        </div>

                        <a href="{{ route('skills.index') }}" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white">Add Skill</a>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-2">
                        @forelse ($user->skills->take(2) as $skill)
                            <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600/20 text-blue-400">
                                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                                        </span>

                                        <div>
                                        <p class="font-bold text-white">{{ $skill->title }}</p>
                                        <p class="text-xs text-slate-500">{{ ucfirst($skill->level) }}</p>
                                    </div>
                                    </div>

                                    <div class="flex items-center gap-4 text-xl">
                                        <a href="{{ route('skills.index', ['edit' => $skill->id]) }}" class="text-slate-400 hover:text-blue-400" title="Edit skill">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>

                                        <form method="POST" action="{{ route('skills.destroy', $skill) }}" onsubmit="return confirm('Delete this skill?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="text-slate-400 hover:text-rose-400" title="Delete skill">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">No skills yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="ss-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-white">My Needs</h3>
                            <p class="text-xs text-slate-500">Skills you want to learn or services you need</p>
                        </div>

                        <a href="{{ route('needs.index') }}" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white">Add Need</a>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse ($user->needs->take(2) as $need)
                            <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-500/20 text-cyan-300">
                                            <i class="fa-solid fa-code"></i>
                                        </span>

                                        <div>
                                        <p class="font-bold text-white">{{ $need->title }}</p>
                                        <p class="text-xs text-slate-500">{{ ucfirst($need->target_level) }}</p>
                                    </div>
                                    </div>

                                    <div class="flex items-center gap-4 text-xl">
                                        <a href="{{ route('needs.index', ['edit' => $need->id]) }}" class="text-slate-400 hover:text-blue-400" title="Edit need">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>

                                        <form method="POST" action="{{ route('needs.destroy', $need) }}" onsubmit="return confirm('Delete this need?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="text-slate-400 hover:text-rose-400" title="Delete need">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">No needs yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-3">
                <div class="ss-card">
                    <h3 class="text-lg font-bold text-white">Reputation</h3>
                    <p class="mt-4 text-4xl font-black text-white">{{ number_format($user->reputation_score, 1) }}</p>
                    <p class="mt-2 text-sm text-slate-400">Based on received reviews</p>

                    <a href="{{ route('users.show', $user) }}" class="ss-link mt-6 inline-block">
                        View public profile
                    </a>
                </div>

                <div class="ss-card">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="ss-card">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
