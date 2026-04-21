<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-white">Platform Admin</h2>
                <p class="mt-1 text-sm text-slate-400">Manage accounts and keep the community healthy.</p>
            </div>

            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white hover:bg-blue-500">
                Manage users
            </a>
        </div>
    </x-slot>

    <div class="pb-12">
        <div class="ss-container">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="ss-card">
                    <p class="text-xs uppercase tracking-wider text-slate-500">Admin scope</p>
                    <p class="mt-2 text-2xl font-black text-white">Users</p>
                    <p class="ss-muted">Ban or reactivate accounts.</p>
                </div>

                <div class="ss-card">
                    <p class="text-xs uppercase tracking-wider text-slate-500">Categories</p>
                    <p class="mt-2 text-2xl font-black text-white">Seeded</p>
                    <p class="ss-muted">Loaded from JSON, not edited by admin.</p>
                </div>

                <div class="ss-card">
                    <p class="text-xs uppercase tracking-wider text-slate-500">Status</p>
                    <p class="mt-2 text-2xl font-black text-emerald-400">Operational</p>
                    <p class="ss-muted">Admin panel ready.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
