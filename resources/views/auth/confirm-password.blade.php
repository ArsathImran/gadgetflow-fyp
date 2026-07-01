<x-guest-layout
    title="Confirm your password"
    subtitle="This protected step keeps your GadgetFlow account secure before you continue."
    eyebrow="Secure Check"
>
    <div class="mb-5 text-sm leading-6 text-slate-300">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative" x-data="{ shown: false }">
                <x-text-input id="password" x-ref="confirmPassword" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" class="pr-12" />
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 transition hover:text-slate-200"
                    x-on:click="shown = !shown; $refs.confirmPassword.type = shown ? 'text' : 'password'"
                    x-on:keydown.enter.prevent="shown = !shown; $refs.confirmPassword.type = shown ? 'text' : 'password'"
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

        <div class="pt-2">
            <x-primary-button class="w-full">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
