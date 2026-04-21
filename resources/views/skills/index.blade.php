<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-extrabold tracking-tight text-white">My Skills</h2>
        <p class="mt-1 text-sm text-slate-400">Create, edit, or delete the skills you can offer.</p>
    </x-slot>

    <div class="pb-12">
        <div class="ss-container space-y-6">
            @if (session('success'))
                <div class="ss-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="ss-card">
                @if ($skillToEdit)
                    <h3 class="text-lg font-bold text-white">Edit Skill</h3>
                    <p class="mt-1 text-sm text-slate-400">Update the old information, then save.</p>

                    <form method="POST" action="{{ route('skills.update', $skillToEdit) }}" class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="title" value="Title" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $skillToEdit->title)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="category_id" value="Category" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $skillToEdit->category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="level" value="Level" />
                            <select id="level" name="level" class="mt-1 block w-full" required>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->value }}" @selected(old('level', $skillToEdit->level) === $level->value)>
                                        {{ ucfirst($level->value) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('level')" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full">{{ old('description', $skillToEdit->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-slate-700 bg-slate-950 text-blue-600" @checked(old('is_active', $skillToEdit->is_active))>
                            <span class="text-sm text-slate-300">Active</span>
                        </label>

                        <div class="flex gap-3">
                            <x-primary-button>Update Skill</x-primary-button>
                            <a href="{{ route('skills.index') }}" class="rounded-xl border border-slate-700 px-4 py-2 text-sm font-bold text-slate-300">
                                Cancel
                            </a>
                        </div>
                    </form>
                @else
                    <h3 class="text-lg font-bold text-white">Create Skill</h3>
                    <p class="mt-1 text-sm text-slate-400">Add a new skill you can teach or offer.</p>

                    @if ($categories->isEmpty())
                        <p class="mt-5 text-sm text-slate-400">No categories are available.</p>
                    @else
                        <form method="POST" action="{{ route('skills.store') }}" class="mt-5 space-y-4">
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
                                <x-input-label for="level" value="Level" />
                                <select id="level" name="level" class="mt-1 block w-full" required>
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
                                <x-input-label for="description" value="Description" />
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full">{{ old('description') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-700 bg-slate-950 text-blue-600">
                                <span class="text-sm text-slate-300">Active</span>
                            </label>

                            <div>
                                <x-primary-button>Create Skill</x-primary-button>
                            </div>
                        </form>
                    @endif
                @endif
            </div>

            <div class="ss-card">
                <h3 class="text-lg font-bold text-white">My Skill List</h3>

                <div class="mt-5 space-y-3">
                    @forelse ($skills as $skill)
                        <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-800 bg-slate-950/50 p-4">
                            <div>
                                <p class="font-bold text-white">{{ $skill->title }}</p>
                                <p class="text-sm text-slate-400">{{ $skill->category->name }} - {{ ucfirst($skill->level) }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $skill->description ?: 'No description' }}</p>
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
                    @empty
                        <p class="text-sm text-slate-400">You have not added any skills yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
