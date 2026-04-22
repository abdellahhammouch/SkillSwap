<x-guest-layout>
    <div class="ss-card mx-auto max-w-4xl px-12 py-10">
        <div class="text-center">
            <h1 class="text-2xl font-extrabold tracking-tight text-white">Create your account</h1>
            <p class="mt-2 text-sm text-slate-400">Join the community of skill exchange.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="mx-auto mt-14 max-w-2xl space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input id="first_name" class="mt-2 block w-full" type="text" name="first_name" :value="old('first_name')" placeholder="Jane" required autofocus autocomplete="given-name" />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input id="last_name" class="mt-2 block w-full" type="text" name="last_name" :value="old('last_name')" placeholder="Doe" required autocomplete="family-name" />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')" placeholder="jane@example.com" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="mt-2 block w-full" type="password" name="password" placeholder="••••••••" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="mt-2 block w-full" type="password" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="city" :value="__('City')" />
                <x-text-input id="city" class="mt-2 block w-full" type="text" name="city" :value="old('city')" placeholder="Casablanca" />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <x-primary-button class="mt-8 w-full py-3">
                Create Account
            </x-primary-button>
        </form>

        <div class="mx-auto mt-8 max-w-2xl border-t border-slate-800"></div>
    </div>
</x-guest-layout>
