<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Needs') }}
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
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Create Need</h3>

                @if ($categories->isEmpty())
                    <p class="text-sm text-gray-600">
                        No active categories are available. An admin must create categories first.
                    </p>
                @else
                    <form method="POST" action="{{ route('needs.store') }}" class="space-y-4">
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
                            <x-input-label for="target_level" :value="__('Target level')" />
                            <select id="target_level" name="target_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Choose a target level</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->value }}" @selected(old('target_level') === $level->value)>
                                        {{ ucfirst($level->value) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('target_level')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-primary-button>{{ __('Create Need') }}</x-primary-button>
                        </div>
                    </form>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">My Need List</h3>

                <div class="space-y-4">
                    @forelse ($needs as $need)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div>
                                <h4 class="text-base font-semibold text-gray-900">{{ $need->title }}</h4>
                                <p class="text-sm text-gray-500">
                                    {{ $need->category->name }} - target: {{ ucfirst($need->target_level) }}
                                </p>
                                <p class="mt-2 text-sm text-gray-700">{{ $need->description ?: 'No description' }}</p>
                                <p class="mt-2 text-xs {{ $need->status === 'open' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ucfirst($need->status) }}
                                </p>
                            </div>

                            <form method="POST" action="{{ route('needs.update', $need) }}" class="mt-4 space-y-3">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <x-input-label :for="'title_'.$need->id" :value="__('Update title')" />
                                    <x-text-input :id="'title_'.$need->id" name="title" type="text" class="mt-1 block w-full" :value="$need->title" required />
                                </div>

                                <div>
                                    <x-input-label :for="'category_'.$need->id" :value="__('Update category')" />
                                    <select id="{{ 'category_'.$need->id }}" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected($need->category_id === $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label :for="'target_level_'.$need->id" :value="__('Update target level')" />
                                    <select id="{{ 'target_level_'.$need->id }}" name="target_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        @foreach ($levels as $level)
                                            <option value="{{ $level->value }}" @selected($need->target_level === $level->value)>
                                                {{ ucfirst($level->value) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label :for="'status_'.$need->id" :value="__('Status')" />
                                    <select id="{{ 'status_'.$need->id }}" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="open" @selected($need->status === 'open')>Open</option>
                                        <option value="closed" @selected($need->status === 'closed')>Closed</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label :for="'description_'.$need->id" :value="__('Update description')" />
                                    <textarea id="{{ 'description_'.$need->id }}" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $need->description }}</textarea>
                                </div>

                                <div>
                                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                                </div>
                            </form>

                            <div class="mt-3 flex gap-3">
                                @if ($need->status === 'open')
                                    <form method="POST" action="{{ route('needs.close', $need) }}">
                                        @csrf
                                        @method('PATCH')

                                        <x-secondary-button>{{ __('Close') }}</x-secondary-button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('needs.destroy', $need) }}">
                                    @csrf
                                    @method('DELETE')

                                    <x-danger-button>{{ __('Delete') }}</x-danger-button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">You have not added any needs yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
