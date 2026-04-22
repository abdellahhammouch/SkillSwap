<x-guest-layout>
    <div class="text-center">
        <h1 class="text-3xl font-extrabold tracking-tight text-white">Welcome back</h1>
        <p class="mt-2 text-sm text-slate-400">Master new skills through exchange.</p>
    </div>

    <div class="ss-card mt-8 px-8 py-8">
        <x-auth-session-status class="mb-4 text-emerald-400" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email address')" />
                <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')" placeholder="name@example.com" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <x-input-label for="password" :value="__('Password')" />
                    @if (Route::has('password.request'))
                        <a class="ss-link text-xs" href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>

                <x-text-input id="password" class="mt-2 block w-full" type="password" name="password" placeholder="••••••••" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <x-primary-button class="w-full py-3">
                Sign in
            </x-primary-button>
        </form>
    </div>

    @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-slate-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300">Create account</a>
        </p>
    @endif
</x-guest-layout>
