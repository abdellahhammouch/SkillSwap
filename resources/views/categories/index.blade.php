<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-extrabold tracking-tight text-white">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="ss-container">
            <div class="ss-card">
                <h3 class="text-lg font-semibold text-white mb-2">Available categories</h3>

                <p class="ss-muted mb-6">
                    These categories are loaded from the database with native JavaScript fetch.
                </p>

                <div id="categories-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <p class="ss-muted">Loading categories...</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
