<aside class="fixed inset-y-0 left-0 z-40 flex h-screen w-80 flex-col overflow-hidden border-r border-slate-800 bg-slate-900/95 px-7 py-6 backdrop-blur">
    <div class="shrink-0">
        <a href="{{ route('dashboard') }}" class="min-w-0">
            <x-application-logo />
        </a>

        <div class="mt-6 rounded-3xl border border-slate-700/70 bg-slate-950/80 px-5 py-4 shadow-2xl shadow-slate-950/40">
            <p class="text-[11px] font-medium uppercase tracking-[0.3em] text-slate-500">Balance</p>
            <div class="mt-3 flex items-end justify-between">
                <span class="text-4xl font-semibold leading-none text-white">{{ Auth::user()->creditBalance }}</span>
                <span class="rounded-2xl bg-blue-600/15 px-3 py-2 text-sm font-semibold tracking-[0.2em] text-blue-300">SS</span>
            </div>
        </div>
    </div>

    <div class="mt-12 flex-1 space-y-5 overflow-hidden text-slate-400">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-4 rounded-2xl px-4 py-4 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/40' : 'hover:bg-slate-800 hover:text-white' }}">
            <span class="flex h-10 w-10 items-center justify-center rounded-full {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : 'bg-slate-800 text-blue-400' }}">
                <i class="fa-solid fa-house"></i>
            </span>
            <span class="ss-sidebar-label">Dashboard</span>
        </a>

        <a href="{{ route('exchange-requests.index') }}" class="flex items-center gap-4 rounded-2xl px-4 py-4 {{ request()->routeIs('exchange-requests.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/40' : 'hover:bg-slate-800 hover:text-white' }}">
            <span class="flex h-10 w-10 items-center justify-center rounded-full {{ request()->routeIs('exchange-requests.*') ? 'bg-blue-500 text-white' : 'bg-slate-800 text-blue-400' }}">
                <i class="fa-regular fa-envelope-open"></i>
            </span>
            <span class="ss-sidebar-label">Exchange Requests</span>
        </a>

        <a href="{{ route('explore.index') }}" class="flex items-center gap-4 rounded-2xl px-4 py-4 {{ request()->routeIs('explore.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/40' : 'hover:bg-slate-800 hover:text-white' }}">
            <span class="flex h-10 w-10 items-center justify-center rounded-full {{ request()->routeIs('explore.*') ? 'bg-blue-500 text-white' : 'bg-slate-800 text-blue-400' }}">
                <i class="fa-solid fa-user-group"></i>
            </span>
            <span class="ss-sidebar-label">Network</span>
        </a>

        <a href="{{ route('conversations.index') }}" class="flex items-center gap-4 rounded-2xl px-4 py-4 {{ request()->routeIs('conversations.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/40' : 'hover:bg-slate-800 hover:text-white' }}">
            <span class="flex h-10 w-10 items-center justify-center rounded-full {{ request()->routeIs('conversations.*') ? 'bg-blue-500 text-white' : 'bg-slate-800 text-blue-400' }}">
                <i class="fa-solid fa-message"></i>
            </span>
            <span class="ss-sidebar-label">Messages</span>
        </a>

        <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 rounded-2xl px-4 py-4 {{ request()->routeIs('profile.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/40' : 'hover:bg-slate-800 hover:text-white' }}">
            <span class="flex h-10 w-10 items-center justify-center rounded-full {{ request()->routeIs('profile.*') ? 'bg-blue-500 text-white' : 'bg-slate-800 text-blue-400' }}">
                <i class="fa-solid fa-gear"></i>
            </span>
            <span class="ss-sidebar-label">My Profile</span>
        </a>

        @if (Auth::user()->hasRole('admin'))
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 rounded-2xl px-4 py-4 {{ request()->routeIs('admin.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/40' : 'hover:bg-slate-800 hover:text-white' }}">
                <span class="flex h-10 w-10 items-center justify-center rounded-full {{ request()->routeIs('admin.*') ? 'bg-blue-500 text-white' : 'bg-slate-800 text-blue-400' }}">
                    <i class="fa-solid fa-shield-halved"></i>
                </span>
                <span class="ss-sidebar-label">Admin</span>
            </a>
        @endif
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-auto shrink-0 pt-10">
        @csrf
        <button class="flex w-full items-center gap-4 rounded-2xl bg-rose-900/60 px-4 py-4 text-left text-sm font-bold text-rose-300">
            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-950/70">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </span>
            <span class="ss-sidebar-label">Logout</span>
        </button>
    </form>
</aside>
