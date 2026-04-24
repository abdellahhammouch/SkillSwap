<x-app-layout>
    <div class="bg-slate-950">
        <div class="min-h-screen">
            <main>
                <div class="mx-auto max-w-5xl px-6 py-10">
                    <h1 class="ss-title inline-block border-b-2 border-blue-500 pr-5 text-5xl text-slate-200">
                        My Profile
                    </h1>

                    <div class="mt-16 rounded-2xl border border-slate-800 bg-slate-900/95 p-8 shadow-2xl shadow-indigo-950/20">
                        <div class="grid gap-8 md:grid-cols-3">
                            <div class="md:col-span-2">
                                <h2 class="ss-title text-5xl text-blue-400">{{ $user->name }}</h2>

                                <p class="mt-12 text-xs uppercase tracking-widest text-slate-500">Bio</p>
                                <p class="mt-4 max-w-md text-base leading-7 text-slate-300">
                                    {{ $user->bio ?: 'No bio provided yet.' }}
                                </p>
                            </div>

                            <div class="space-y-4">
                                <a href="{{ route('skills.index') }}" class="block rounded-lg bg-blue-500 px-5 py-3 text-center text-sm font-bold text-white">
                                    <i class="fa-solid fa-arrow-right-arrow-left mr-3"></i>
                                    Add Skill
                                </a>

                                <a href="{{ route('needs.index') }}" class="block rounded-lg bg-blue-500 px-5 py-3 text-center text-sm font-bold text-white">
                                    <i class="fa-solid fa-arrow-right-arrow-left mr-3"></i>
                                    Add Need
                                </a>

                                <button type="button" onclick="document.getElementById('profile-modal').classList.remove('hidden')" class="block w-full rounded-lg border border-slate-700 px-5 py-3 text-center text-sm text-slate-300">
                                    Update Profile
                                </button>

                                <p class="pt-4 text-sm text-slate-400">
                                    <i class="fa-solid fa-location-dot mr-2"></i>
                                    {{ $user->city ?: 'No city provided' }}
                                </p>

                                <p class="text-sm text-slate-400">
                                    <i class="fa-solid fa-star mr-2"></i>
                                    {{ number_format($user->reputation_score, 1) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 grid gap-6 md:grid-cols-2">
                        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                            <div class="flex items-center justify-between gap-4">
                                <h3 class="text-base text-slate-300">
                                    <i class="fa-solid fa-graduation-cap mr-2 text-blue-400"></i>
                                    Skills I Teach
                                </h3>

                                <a href="{{ route('skills.index') }}" class="rounded-full bg-blue-600 px-4 py-2 text-sm font-bold text-white">
                                    + Add Skill
                                </a>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-2">
                                @forelse ($user->skills as $skill)
                                    <span class="rounded-full bg-blue-900/50 px-3 py-1 text-xs text-blue-200">
                                        {{ $skill->title }}
                                        <a href="{{ route('skills.index', ['edit' => $skill->id]) }}" class="ml-2 text-blue-300">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                    </span>
                                @empty
                                    <span class="text-sm text-slate-500">No skills yet.</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                            <div class="flex items-center justify-between gap-4">
                                <h3 class="text-base text-slate-300">
                                    <i class="fa-solid fa-headset mr-2 text-teal-300"></i>
                                    Looking to Learn
                                </h3>

                                <a href="{{ route('needs.index') }}" class="rounded-full bg-teal-500 px-4 py-2 text-sm font-bold text-white">
                                    + Add Need
                                </a>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-2">
                                @forelse ($user->needs as $need)
                                    <span class="rounded-full bg-teal-900/40 px-3 py-1 text-xs text-teal-200">
                                        {{ $need->title }}
                                        <a href="{{ route('needs.index', ['edit' => $need->id]) }}" class="ml-2 text-teal-300">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                    </span>
                                @empty
                                    <span class="text-sm text-slate-500">No needs yet.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 rounded-xl border border-slate-800 bg-slate-900 p-6">
                        @include('profile.partials.delete-user-form')
                    </div>

                    <div id="profile-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 px-4 py-8">
                        <div class="mx-auto max-w-5xl rounded-xl border border-slate-800 bg-slate-900 p-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-bold text-white">Update Profile</h2>

                                <button type="button" onclick="document.getElementById('profile-modal').classList.add('hidden')" class="text-2xl text-slate-400">
                                    &times;
                                </button>
                            </div>

                            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                                <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-6">
                            @include('profile.partials.update-profile-information-form')
                                </div>

                                <div class="rounded-xl border border-slate-800 bg-slate-950/50 p-6">
                                    @include('profile.partials.update-password-form')
                                </div>
                            </div>
                        </div>
                    </div>

                    <footer class="mt-12 border-t border-slate-800 py-8 text-xs text-slate-500">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-xl font-semibold text-white">SkillSwap</p>
                                <p class="mt-2">© 2024 SkillSwap. Empowering Academic Excellence.</p>
                            </div>

                            <div class="flex flex-wrap gap-6">
                                <span>Terms of Service</span>
                                <span>Privacy Policy</span>
                                <span>Code of Conduct</span>
                                <span>Support</span>
                            </div>
                        </div>
                    </footer>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
