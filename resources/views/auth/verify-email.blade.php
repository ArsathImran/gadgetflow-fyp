<x-guest-layout
    title="Verify your email"
    subtitle="One quick verification unlocks your full GadgetFlow experience, secure bookings, and seamless rental management."
    eyebrow="Email Verification"
>
    <div class="mb-5 text-sm leading-6 text-slate-300">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm font-medium text-emerald-200">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-6 space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button class="w-full">
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="w-full rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-medium text-slate-200 transition duration-300 hover:bg-white/10 focus:outline-none focus:ring-4 focus:ring-white/10">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
