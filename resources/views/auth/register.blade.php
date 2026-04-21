<x-guest-layout>
    <div class="ss-card mx-auto max-w-2xl">
        <div class="text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-white">Create your account</h1>
            <p class="mt-2 text-sm text-slate-400">Join the community of skill exchange.</p>

            <div class="mx-auto mt-8 flex h-20 w-20 items-center justify-center overflow-hidden rounded-full border border-dashed border-slate-600 bg-slate-950/60 text-2xl text-slate-500">
                <img id="avatar-preview" src="" alt="" class="hidden h-full w-full object-cover">
                <span id="avatar-placeholder">
                    <i class="fa-solid fa-camera text-xl text-slate-500"></i>
                </span>
            </div>
            <p class="mt-3 text-sm font-medium text-slate-200">Upload Profile Photo</p>
            <p class="text-xs text-slate-500">Paste an image URL and it will appear here.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <x-input-label for="avatar" :value="__('Profile image URL')" />
                <x-text-input id="avatar" class="mt-2 block w-full" type="url" name="avatar" :value="old('avatar')" placeholder="https://example.com/avatar.jpg" />
                <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
            </div>

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

            <p class="text-xs text-slate-500">
                By creating an account, you agree to SkillSwap community guidelines.
            </p>

            <x-primary-button class="w-full py-3">
                Create Account
            </x-primary-button>
        </form>

        <script>
            const avatarInput = document.getElementById('avatar');
            const avatarPreview = document.getElementById('avatar-preview');
            const avatarPlaceholder = document.getElementById('avatar-placeholder');

            avatarInput.addEventListener('input', function () {
                if (avatarInput.value) {
                    avatarPreview.src = avatarInput.value;
                    avatarPreview.classList.remove('hidden');
                    avatarPlaceholder.classList.add('hidden');
                } else {
                    avatarPreview.classList.add('hidden');
                    avatarPlaceholder.classList.remove('hidden');
                }
            });
        </script>
    </div>
</x-guest-layout>
