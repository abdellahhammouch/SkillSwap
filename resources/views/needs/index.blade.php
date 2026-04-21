<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold tracking-tight text-white">My Needs</h2>
        <p class="mt-1 text-sm text-slate-400">Create, edit, or delete what you want to learn.</p>
    </x-slot>

    <div class="pb-12">
        <div class="ss-container space-y-6">
            @if (session('success'))
                <div class="ss-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="ss-card">
                @if ($needToEdit)
                    <h3 class="text-lg font-bold text-white">Edit Need</h3>
                    <p class="mt-1 text-sm text-slate-400">Update the old information, then save.</p>

                    <form method="POST" action="{{ route('needs.update', $needToEdit) }}" class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="title" value="Title" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $needToEdit->title)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="category_id" value="Category" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $needToEdit->category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="target_level" value="Target level" />
                            <select id="target_level" name="target_level" class="mt-1 block w-full" required>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->value }}" @selected(old('target_level', $needToEdit->target_level) === $level->value)>
                                        {{ ucfirst($level->value) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('target_level')" />
                        </div>

                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" class="mt-1 block w-full" required>
                                <option value="open" @selected(old('status', $needToEdit->status) === 'open')>Open</option>
                                <option value="closed" @selected(old('status', $needToEdit->status) === 'closed')>Closed</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full">{{ old('description', $needToEdit->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex gap-3">
                            <x-primary-button>Update Need</x-primary-button>
                            <a href="{{ route('needs.index') }}" class="rounded-xl border border-slate-700 px-4 py-2 text-sm font-bold text-slate-300">
                                Cancel
                            </a>
                        </div>
                    </form>
                @else
                    <h3 class="text-lg font-bold text-white">Create Need</h3>
                    <p class="mt-1 text-sm text-slate-400">Add a new thing you want to learn.</p>

                    @if ($categories->isEmpty())
                        <p class="mt-5 text-sm text-slate-400">No categories are available.</p>
                    @else
                        <form method="POST" action="{{ route('needs.store') }}" class="mt-5 space-y-4">
                            @csrf

                            <div>
                                <x-input-label for="title" value="Title" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="category_id" value="Category" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full" required>
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
                                <x-input-label for="target_level" value="Target level" />
                                <select id="target_level" name="target_level" class="mt-1 block w-full" required>
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
                                <x-input-label for="description" value="Description" />
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full">{{ old('description') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div>
                                <x-primary-button>Create Need</x-primary-button>
                            </div>
                        </form>
                    @endif
                @endif
            </div>

            <div class="ss-card">
                <h3 class="text-lg font-bold text-white">My Need List</h3>

                <div class="mt-5 space-y-3">
                    @forelse ($needs as $need)
                        <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                            <div>
                                <p class="font-bold text-white">{{ $need->title }}</p>
                                <p class="text-sm text-slate-400">{{ $need->category->name }} - {{ ucfirst($need->target_level) }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $need->description ?: 'No description' }}</p>
                                <p class="mt-1 text-xs {{ $need->status === 'open' ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ ucfirst($need->status) }}
                                </p>
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
                    @empty
                        <p class="text-sm text-slate-400">You have not added any needs yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
