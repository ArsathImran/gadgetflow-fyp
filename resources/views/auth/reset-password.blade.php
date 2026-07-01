<x-guest-layout
    title="Set a new password"
    subtitle="Create a fresh password to restore access to your GadgetFlow account and continue managing rentals securely."
    eyebrow="Reset Access"
>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative" x-data="{ shown: false }">
                <x-text-input id="password" x-ref="resetPassword" type="password" name="password" required autocomplete="new-password" placeholder="Create a new password" class="pr-12" />
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 transition hover:text-slate-200"
                    x-on:click="shown = !shown; $refs.resetPassword.type = shown ? 'text' : 'password'"
                    x-on:keydown.enter.prevent="shown = !shown; $refs.resetPassword.type = shown ? 'text' : 'password'"
                    x-bind:aria-label="shown ? 'Hide password' : 'Show password'"
                >
                    <svg x-show="!shown" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z"/>
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg x-show="shown" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.585 10.587A2 2 0 0 0 13.414 13.414"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 5.09A10.94 10.94 0 0 1 12 5c4.478 0 8.268 2.943 9.542 7a11.05 11.05 0 0 1-4.132 5.411"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.61 6.609A11.002 11.002 0 0 0 2.458 12c1.274 4.057 5.065 7 9.542 7a10.96 10.96 0 0 0 5.39-1.39"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative" x-data="{ shown: false }">
                <x-text-input id="password_confirmation" x-ref="resetPasswordConfirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your new password" class="pr-12" />
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 transition hover:text-slate-200"
                    x-on:click="shown = !shown; $refs.resetPasswordConfirmation.type = shown ? 'text' : 'password'"
                    x-on:keydown.enter.prevent="shown = !shown; $refs.resetPasswordConfirmation.type = shown ? 'text' : 'password'"
                    x-bind:aria-label="shown ? 'Hide password' : 'Show password'"
                >
                    <svg x-show="!shown" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z"/>
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg x-show="shown" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.585 10.587A2 2 0 0 0 13.414 13.414"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 5.09A10.94 10.94 0 0 1 12 5c4.478 0 8.268 2.943 9.542 7a11.05 11.05 0 0 1-4.132 5.411"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.61 6.609A11.002 11.002 0 0 0 2.458 12c1.274 4.057 5.065 7 9.542 7a10.96 10.96 0 0 0 5.39-1.39"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="space-y-4 pt-2">
            <x-primary-button class="w-full">
                {{ __('Reset Password') }}
            </x-primary-button>

            <p class="text-center text-sm text-slate-400">
                <a class="font-medium text-blue-200 transition hover:text-white" href="{{ route('login') }}">{{ __('Back to login') }}</a>
            </p>
        </div>
    </form>
</x-guest-layout>
