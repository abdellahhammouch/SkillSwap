<section>
    <header>
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-white">
                    {{ __('Personal Information') }}
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    {{ __('Paste an image URL, then update your public profile.') }}
                </p>
            </div>

            <button type="submit" form="profile-information-form" class="text-sm font-bold text-blue-400">
                Save Changes
            </button>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form id="profile-information-form" method="post" action="{{ route('profile.update') }}" class="mt-6">
        @csrf
        @method('patch')

        <div class="space-y-6">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-full border border-dashed border-slate-600 bg-slate-950/60">
                    <img id="avatar-preview" src="{{ $user->avatar }}" alt="Profile image" class="{{ $user->avatar ? '' : 'hidden' }} h-full w-full object-cover">

                    <span id="avatar-placeholder" class="{{ $user->avatar ? 'hidden' : '' }}">
                        <i class="fa-solid fa-camera text-xl text-slate-500"></i>
                    </span>
                </div>

                <div class="w-full">
                    <x-input-label for="avatar" :value="__('Profile Photo URL')" />
                    <p class="mt-1 text-xs text-slate-500">Paste an image link. No local upload.</p>
                    <x-text-input id="avatar" name="avatar" type="url" class="mt-2 block w-full" :value="old('avatar', $user->avatar)" placeholder="https://example.com/avatar.jpg" />
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autofocus autocomplete="given-name" />
                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                </div>

                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required autocomplete="family-name" />
                    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <p class="mt-2 text-sm text-slate-300">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="ss-link underline">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm font-medium text-emerald-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    @endif
                </div>

                <div>
                    <x-input-label for="city" :value="__('City')" />
                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $user->city)" />
                    <x-input-error class="mt-2" :messages="$errors->get('city')" />
                </div>
            </div>

            <div>
                <x-input-label for="bio" :value="__('Bio')" />
                <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full">{{ old('bio', $user->bio) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('bio')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save') }}</x-primary-button>

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-emerald-400"
                    >{{ __('Saved.') }}</p>
                @endif
            </div>
        </div>
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
</section>
