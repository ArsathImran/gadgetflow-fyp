<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                {{ __('Contact Us') }}
            </h2>
            <p class="font-body text-sm text-slate">
                Have a question about a rental, combo package, or your account? We&rsquo;re happy to help.
            </p>
        </div>
    </x-slot>

    <div class="py-12 sm:py-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.3fr)]">
                {{-- Contact details --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-[0_18px_40px_rgba(15,23,42,0.06)]">
                    <h3 class="font-display text-lg font-semibold text-ink">Get in Touch</h3>
                    <p class="mt-2 font-body text-sm leading-6 text-slate">Reach out directly, or send us a message and we&rsquo;ll follow up as soon as we can.</p>

                    <div class="mt-8 space-y-6">
                        <div class="flex items-start gap-4">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo/10 text-indigo">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="14" rx="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6" />
                                </svg>
                            </span>
                            <div>
                                <p class="font-body text-xs font-semibold uppercase tracking-wide text-slate-400">Email</p>
                                <p class="mt-1 font-display text-sm font-semibold text-ink">support@gadgetflow.test</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo/10 text-indigo">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 4h3l1.5 4-2 1.5a12 12 0 0 0 6.5 6.5l1.5-2 4 1.5v3a2 2 0 0 1-2 2c-8 0-14.5-6.5-14.5-14.5a2 2 0 0 1 2-2Z" />
                                </svg>
                            </span>
                            <div>
                                <p class="font-body text-xs font-semibold uppercase tracking-wide text-slate-400">Phone</p>
                                <p class="mt-1 font-display text-sm font-semibold text-ink">+60 7-234 5678</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo/10 text-indigo">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21Z" />
                                    <circle cx="12" cy="9.5" r="2.3" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <div>
                                <p class="font-body text-xs font-semibold uppercase tracking-wide text-slate-400">Address</p>
                                <p class="mt-1 font-display text-sm font-semibold text-ink">Jalan Molek 1/28, Taman Molek,<br>81100 Johor Bahru, Johor</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo/10 text-indigo">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <circle cx="12" cy="12.5" r="8" stroke-linecap="round" stroke-linejoin="round" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 2" />
                                </svg>
                            </span>
                            <div>
                                <p class="font-body text-xs font-semibold uppercase tracking-wide text-slate-400">Support Hours</p>
                                <p class="mt-1 font-display text-sm font-semibold text-ink">Mon &ndash; Sat, 9:00 AM &ndash; 7:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact form --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-[0_18px_40px_rgba(15,23,42,0.06)]">
                    <h3 class="font-display text-lg font-semibold text-ink">Send a Message</h3>
                    <p class="mt-2 font-body text-sm leading-6 text-slate">Fill out the form and our team will get back to you soon.</p>

                    <form method="POST" action="{{ route('contact.store') }}" class="mt-8 grid gap-5 sm:grid-cols-2">
                        @csrf

                        <div>
                            <label for="contact-name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input
                                id="contact-name"
                                type="text"
                                name="name"
                                value="{{ old('name', auth()->user()->name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact-email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input
                                id="contact-email"
                                type="email"
                                name="email"
                                value="{{ old('email', auth()->user()->email) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="contact-subject" class="block text-sm font-medium text-gray-700">Subject</label>
                            <input
                                id="contact-subject"
                                type="text"
                                name="subject"
                                value="{{ old('subject') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            @error('subject')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="contact-message" class="block text-sm font-medium text-gray-700">Message</label>
                            <textarea
                                id="contact-message"
                                name="message"
                                rows="5"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo px-6 py-3 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
