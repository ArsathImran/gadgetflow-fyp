<x-guest-layout
    title="Create your account"
    subtitle="Join GadgetFlow to reserve premium devices faster, track rentals in real time, and unlock a refined gadget experience."
    eyebrow="New Member"
>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Your full name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative" x-data="{ shown: false }">
                <x-text-input id="password" x-ref="registerPassword" type="password" name="password" required autocomplete="new-password" placeholder="Create a strong password" class="pr-12" />
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 transition hover:text-slate-200"
                    x-on:click="shown = !shown; $refs.registerPassword.type = shown ? 'text' : 'password'"
                    x-on:keydown.enter.prevent="shown = !shown; $refs.registerPassword.type = shown ? 'text' : 'password'"
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
                <x-text-input id="password_confirmation" x-ref="registerPasswordConfirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" class="pr-12" />
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 transition hover:text-slate-200"
                    x-on:click="shown = !shown; $refs.registerPasswordConfirmation.type = shown ? 'text' : 'password'"
                    x-on:keydown.enter.prevent="shown = !shown; $refs.registerPasswordConfirmation.type = shown ? 'text' : 'password'"
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
                {{ __('Register') }}
            </x-primary-button>

            <p class="text-center text-sm text-slate-400">
                {{ __('Already registered?') }}
                <a class="font-medium text-blue-200 transition hover:text-white" href="{{ route('login') }}">{{ __('Log in') }}</a>
            </p>
        </div>
    </form>
</x-guest-layout>
