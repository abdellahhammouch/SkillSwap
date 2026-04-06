<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (auth()->user()->hasRole('admin'))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Create Category</h3>

                    <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
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
                            <x-primary-button>{{ __('Create Category') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Category List</h3>

                <div class="space-y-4">
                    @forelse ($categories as $category)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900">{{ $category->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $category->slug }}</p>
                                    <p class="mt-2 text-sm text-gray-700">{{ $category->description ?: 'No description' }}</p>
                                    <p class="mt-2 text-xs {{ $category->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </p>
                                </div>
                            </div>

                            @if (auth()->user()->hasRole('admin'))
                                <form method="POST" action="{{ route('categories.update', $category) }}" class="mt-4 space-y-3">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <x-input-label :for="'name_'.$category->id" :value="__('Update name')" />
                                        <x-text-input :id="'name_'.$category->id" name="name" type="text" class="mt-1 block w-full" :value="$category->name" required />
                                    </div>

                                    <div>
                                        <x-input-label :for="'description_'.$category->id" :value="__('Update description')" />
                                        <textarea id="{{ 'description_'.$category->id }}" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $category->description }}</textarea>
                                    </div>

                                    <label class="inline-flex items-center gap-2">
                                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" @checked($category->is_active)>
                                        <span class="text-sm text-gray-700">Active</span>
                                    </label>

                                    <div class="flex items-center gap-3">
                                        <x-primary-button>{{ __('Update') }}</x-primary-button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="mt-3">
                                    @csrf
                                    @method('DELETE')

                                    <x-danger-button>{{ __('Delete') }}</x-danger-button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">No categories found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
