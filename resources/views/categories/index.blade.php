<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Available categories</h3>

                <p class="text-sm text-gray-600 mb-6">
                    These categories are loaded from a JSON file with native JavaScript.
                </p>

                <div id="categories-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <p class="text-sm text-gray-600">Loading categories...</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
