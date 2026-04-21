<nav x-data="{ open: false }" class="border-b border-slate-800/80 bg-slate-950/85 backdrop-blur">
    <div class="ss-container">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo />
                </a>

                <div class="hidden items-center gap-6 md:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                    <x-nav-link :href="route('explore.index')" :active="request()->routeIs('explore.*')">Explore</x-nav-link>
                    <x-nav-link :href="route('learning-sessions.index')" :active="request()->routeIs('learning-sessions.*')">Sessions</x-nav-link>
                    <x-nav-link :href="route('conversations.index')" :active="request()->routeIs('conversations.*')">Messages</x-nav-link>
                    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">Profile</x-nav-link>

                    @if (Auth::user()->hasRole('admin'))
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">Admin</x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-4 md:flex">
                <div class="rounded-full border border-slate-800 bg-slate-900 px-4 py-2 text-xs text-slate-300">
                    <span class="mr-1 inline-block h-2 w-2 rounded-full bg-emerald-400"></span>
                    Balance: {{ Auth::user()->creditBalance }} SS
                </div>

                <x-dropdown align="right" width="48" contentClasses="py-1 bg-slate-900 border border-slate-800 rounded-xl">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 rounded-full text-sm font-semibold text-slate-100">
                            <span>{{ Auth::user()->name }}</span>
                            @if (Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar }}" alt="Profile image" class="h-9 w-9 rounded-full object-cover">
                            @else
                                <span class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-700 bg-slate-800 text-xs">
                                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1).substr(Auth::user()->last_name, 0, 1)) }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <button @click="open = ! open" class="inline-flex rounded-xl border border-slate-800 p-2 text-slate-300 md:hidden">
                <i :class="open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-lg"></i>
            </button>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-800 md:hidden">
        <div class="space-y-1 py-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('explore.index')" :active="request()->routeIs('explore.*')">Explore</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('learning-sessions.index')" :active="request()->routeIs('learning-sessions.*')">Sessions</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('conversations.index')" :active="request()->routeIs('conversations.*')">Messages</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">Profile</x-responsive-nav-link>

            @if (Auth::user()->hasRole('admin'))
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">Admin</x-responsive-nav-link>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    Log Out
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
