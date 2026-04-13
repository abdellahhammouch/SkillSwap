<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Exchange Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('exchange-requests.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="type" :value="__('Request type')" />
                        <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Choose the type</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->value }}" @selected(old('type') === $type->value)>
                                    {{ $type->value === 'help_request' ? 'Ask for help' : 'Offer help' }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('type')" />
                        <p class="mt-2 text-sm text-gray-600">
                            Ask for help: choose another user's skill. Offer help: choose another user's need.
                        </p>
                    </div>

                    <div>
                        <x-input-label for="skill_id" :value="__('Skill')" />
                        <select id="skill_id" name="skill_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Choose a skill</option>

                            <optgroup label="My skills">
                                @foreach ($mySkills as $skill)
                                    <option value="{{ $skill->id }}" @selected(old('skill_id') == $skill->id)>
                                        {{ $skill->title }} - {{ ucfirst($skill->level) }}
                                    </option>
                                @endforeach
                            </optgroup>

                            <optgroup label="Other users skills">
                                @foreach ($otherSkills as $skill)
                                    <option value="{{ $skill->id }}" @selected(old('skill_id') == $skill->id)>
                                        {{ $skill->title }} by {{ $skill->user->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('skill_id')" />
                    </div>

                    <div>
                        <x-input-label for="need_id" :value="__('Need')" />
                        <select id="need_id" name="need_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Choose a need</option>

                            <optgroup label="My needs">
                                @foreach ($myNeeds as $need)
                                    <option value="{{ $need->id }}" @selected(old('need_id') == $need->id)>
                                        {{ $need->title }} - target {{ ucfirst($need->target_level) }}
                                    </option>
                                @endforeach
                            </optgroup>

                            <optgroup label="Other users needs">
                                @foreach ($otherNeeds as $need)
                                    <option value="{{ $need->id }}" @selected(old('need_id') == $need->id)>
                                        {{ $need->title }} by {{ $need->user->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('need_id')" />
                    </div>

                    <div>
                        <x-input-label for="duration_minutes" :value="__('Duration in minutes')" />
                        <x-text-input id="duration_minutes" name="duration_minutes" type="number" min="15" class="mt-1 block w-full" :value="old('duration_minutes')" />
                        <x-input-error class="mt-2" :messages="$errors->get('duration_minutes')" />
                    </div>

                    <div>
                        <x-input-label for="message" :value="__('Message')" />
                        <textarea id="message" name="message" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('message') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('message')" />
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-3">Proposed time</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="start_at" :value="__('Start at')" />
                                <x-text-input id="start_at" name="proposed_times[0][start_at]" type="datetime-local" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('proposed_times.0.start_at')" />
                            </div>

                            <div>
                                <x-input-label for="end_at" :value="__('End at')" />
                                <x-text-input id="end_at" name="proposed_times[0][end_at]" type="datetime-local" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('proposed_times.0.end_at')" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button>{{ __('Create Request') }}</x-primary-button>

                        <a href="{{ route('exchange-requests.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
