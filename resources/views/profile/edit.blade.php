<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-xl font-semibold text-ink leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 sm:py-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid gap-8 lg:grid-cols-[minmax(0,0.85fr)_minmax(0,1.65fr)] lg:items-start">
                {{-- Avatar card --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)] sm:p-8">
                    @include('profile.partials.update-avatar-form')
                </div>

                {{-- Profile information card --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)] sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Supporting Documents card --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)] sm:p-8">
                @include('profile.partials.update-id-document-form')
            </div>

            {{-- Change Password card --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)] sm:p-8">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Delete Account card --}}
            <div class="rounded-3xl border border-rose-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)] sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
