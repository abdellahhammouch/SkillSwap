<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Skills') }}
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
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Create Skill</h3>

                @if ($categories->isEmpty())
                    <p class="text-sm text-gray-600">
                        No active categories are available. An admin must create categories first.
                    </p>
                @else
                    <form method="POST" action="{{ route('skills.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Choose a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="level" :value="__('Level')" />
                            <select id="level" name="level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Choose a level</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->value }}" @selected(old('level') === $level->value)>
                                        {{ ucfirst($level->value) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('level')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Active</span>
                        </label>

                        <div>
                            <x-primary-button>{{ __('Create Skill') }}</x-primary-button>
                        </div>
                    </form>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">My Skill List</h3>

                <div class="space-y-4">
                    @forelse ($skills as $skill)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div>
                                <h4 class="text-base font-semibold text-gray-900">{{ $skill->title }}</h4>
                                <p class="text-sm text-gray-500">
                                    {{ $skill->category->name }} - {{ ucfirst($skill->level) }}
                                </p>
                                <p class="mt-2 text-sm text-gray-700">{{ $skill->description ?: 'No description' }}</p>
                                <p class="mt-2 text-xs {{ $skill->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $skill->is_active ? 'Active' : 'Inactive' }}
                                </p>
                            </div>

                            <form method="POST" action="{{ route('skills.update', $skill) }}" class="mt-4 space-y-3">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <x-input-label :for="'title_'.$skill->id" :value="__('Update title')" />
                                    <x-text-input :id="'title_'.$skill->id" name="title" type="text" class="mt-1 block w-full" :value="$skill->title" required />
                                </div>

                                <div>
                                    <x-input-label :for="'category_'.$skill->id" :value="__('Update category')" />
                                    <select id="{{ 'category_'.$skill->id }}" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected($skill->category_id === $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label :for="'level_'.$skill->id" :value="__('Update level')" />
                                    <select id="{{ 'level_'.$skill->id }}" name="level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        @foreach ($levels as $level)
                                            <option value="{{ $level->value }}" @selected($skill->level === $level->value)>
                                                {{ ucfirst($level->value) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label :for="'description_'.$skill->id" :value="__('Update description')" />
                                    <textarea id="{{ 'description_'.$skill->id }}" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $skill->description }}</textarea>
                                </div>

                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked($skill->is_active)>
                                    <span class="text-sm text-gray-700">Active</span>
                                </label>

                                <div>
                                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('skills.destroy', $skill) }}" class="mt-3">
                                @csrf
                                @method('DELETE')

                                <x-danger-button>{{ __('Delete') }}</x-danger-button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">You have not added any skills yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
