<x-guest-layout
    title="Forgot your password?"
    subtitle="Enter the email linked to your GadgetFlow account and we'll send you a secure reset link right away."
    eyebrow="Password Recovery"
>
    <div class="mb-5 text-sm leading-6 text-slate-300">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="space-y-4 pt-2">
            <x-primary-button class="w-full">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>

            <p class="text-center text-sm text-slate-400">
                <a class="font-medium text-blue-200 transition hover:text-white" href="{{ route('login') }}">{{ __('Back to login') }}</a>
            </p>
        </div>
    </form>
</x-guest-layout>
