<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">
            {{ __('User Profile') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="ss-container max-w-5xl space-y-6">
            @if (session('success'))
                <div class="ss-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="ss-card">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                    @if ($user->avatar)
                        <img src="{{ $user->avatar }}" alt="Profile image" class="h-24 w-24 rounded-full object-cover">
                    @else
                        <div class="flex h-24 w-24 items-center justify-center rounded-full bg-slate-800 text-xl font-bold text-white">
                            {{ strtoupper(substr($user->first_name, 0, 1).substr($user->last_name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="space-y-3">
                        <h3 class="text-2xl font-black text-white">{{ $user->name }}</h3>
                        <p class="text-sm text-slate-400">{{ $user->email }}</p>
                        <p class="text-sm text-slate-300"><strong>City:</strong> {{ $user->city ?: 'No city provided' }}</p>
                        <p class="text-sm text-slate-300"><strong>Bio:</strong> {{ $user->bio ?: 'No bio provided' }}</p>
                        <p class="text-sm text-slate-300"><strong>Reputation:</strong> {{ number_format($user->reputation_score, 2) }}/5</p>
                    </div>
                </div>
            </div>

            @if (auth()->id() !== $user->id)
                <div class="ss-card">
                    <h3 class="text-lg font-bold text-white">Send an exchange request</h3>
                    <p class="mt-1 text-sm text-slate-400">Choose one action, then fill the simple form.</p>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <button type="button" onclick="showHelpRequestForm()" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white">
                            Demander de l'aide
                        </button>

                        <button type="button" onclick="showHelpOfferForm()" class="rounded-xl bg-slate-800 px-4 py-2 text-sm font-bold text-white">
                            Offrir de l'aide
                        </button>
                    </div>

                    <form id="help-request-form" method="POST" action="{{ route('exchange-requests.store') }}" class="mt-5 space-y-4">
                        @csrf
                        <input type="hidden" name="type" value="help_request">

                        <div>
                            <x-input-label for="skill_id" value="Skill de l'utilisateur" />
                            <select id="skill_id" name="skill_id" class="mt-1 block w-full" required>
                                <option value="">Choisir un skill</option>
                                @foreach ($user->skills->where('is_active', true) as $skill)
                                    <option value="{{ $skill->id }}">
                                        {{ $skill->title }} - {{ ucfirst($skill->level) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="need_id" value="Ton besoin" />
                            <select id="need_id" name="need_id" class="mt-1 block w-full">
                                <option value="">Aucun besoin précis</option>
                                @foreach ($myNeeds as $need)
                                    <option value="{{ $need->id }}">{{ $need->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="help_request_message" value="Message" />
                            <textarea id="help_request_message" name="message" rows="3" class="mt-1 block w-full">{{ old('message') }}</textarea>
                        </div>

                        <button class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white">
                            Envoyer la demande
                        </button>
                    </form>

                    <form id="help-offer-form" method="POST" action="{{ route('exchange-requests.store') }}" class="mt-5 hidden space-y-4">
                        @csrf
                        <input type="hidden" name="type" value="help_offer">

                        <div>
                            <x-input-label for="target_need_id" value="Besoin de l'utilisateur" />
                            <select id="target_need_id" name="need_id" class="mt-1 block w-full" required>
                                <option value="">Choisir un besoin</option>
                                @foreach ($user->needs->where('status', 'open') as $need)
                                    <option value="{{ $need->id }}">
                                        {{ $need->title }} - {{ ucfirst($need->target_level) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="helper_skill_id" value="Ton skill" />
                            <select id="helper_skill_id" name="skill_id" class="mt-1 block w-full">
                                <option value="">Aucun skill précis</option>
                                @foreach ($mySkills as $skill)
                                    <option value="{{ $skill->id }}">{{ $skill->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="help_offer_message" value="Message" />
                            <textarea id="help_offer_message" name="message" rows="3" class="mt-1 block w-full">{{ old('message') }}</textarea>
                        </div>

                        <button class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white">
                            Envoyer l'offre
                        </button>
                    </form>

                    <script>
                        function showHelpRequestForm() {
                            document.getElementById('help-request-form').classList.remove('hidden');
                            document.getElementById('help-offer-form').classList.add('hidden');
                        }

                        function showHelpOfferForm() {
                            document.getElementById('help-offer-form').classList.remove('hidden');
                            document.getElementById('help-request-form').classList.add('hidden');
                        }
                    </script>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="ss-card">
                        <h3 class="text-lg font-bold text-white">Skills</h3>
                        <div class="mt-3 space-y-2">
                            @forelse ($user->skills->where('is_active', true) as $skill)
                                <p class="text-sm text-slate-300">{{ $skill->title }} - {{ ucfirst($skill->level) }}</p>
                            @empty
                                <p class="text-sm text-slate-400">This user has no active skills.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="ss-card">
                        <h3 class="text-lg font-bold text-white">Needs</h3>
                        <div class="mt-3 space-y-2">
                            @forelse ($user->needs->where('status', 'open') as $need)
                                <p class="text-sm text-slate-300">{{ $need->title }} - {{ ucfirst($need->target_level) }}</p>
                            @empty
                                <p class="text-sm text-slate-400">This user has no open needs.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="ss-card">
                    <h3 class="text-lg font-semibold text-white">Add a reputation</h3>

                    @if ($existingRating)
                        <p class="mt-2 text-sm text-emerald-300">
                            You already rated this user with a score of {{ $existingRating->score }}/5.
                        </p>
                    @elseif ($canRate)
                        <form method="POST" action="{{ route('ratings.store', $user) }}" class="mt-4 space-y-4">
                            @csrf

                            <div>
                                <x-input-label for="score" :value="__('Score')" />
                                <select id="score" name="score" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Choose a score</option>
                                    @for ($score = 1; $score <= 5; $score++)
                                        <option value="{{ $score }}" @selected(old('score') == $score)>{{ $score }}/5</option>
                                    @endfor
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('score')" />
                            </div>

                            <div>
                                <x-input-label for="comment" :value="__('Comment')" />
                                <textarea id="comment" name="comment" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('comment') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                            </div>

                            <x-primary-button>{{ __('Save reputation') }}</x-primary-button>
                        </form>
                    @else
                        <p class="mt-2 text-sm text-gray-600">
                            You can rate this user only if you already have at least one accepted exchange request together.
                        </p>
                    @endif
                </div>
            @endif

            <div class="ss-card">
                <h3 class="text-lg font-semibold text-white">Received reputations</h3>

                <div class="mt-4 space-y-4">
                    @forelse ($receivedRatings as $rating)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $rating->author->name }} rated {{ $rating->score }}/5
                            </p>

                            <p class="mt-2 text-sm text-gray-600">
                                {{ $rating->comment ?: 'No comment' }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">
                            No reputations received yet.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
