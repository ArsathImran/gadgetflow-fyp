<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                    {{ __('My Inquiries') }}
                </h2>
                <p class="font-body text-sm text-slate">Messages you've sent to our support team, and their replies.</p>
            </div>

            <a href="{{ route('contact') }}" class="inline-flex items-center gap-1.5 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-body font-semibold text-slate-700 shadow-sm transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700">
                <x-icon-mail class="h-4 w-4" />
                Send a Message
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if ($inquiries->count())
                <div class="space-y-4">
                    @foreach ($inquiries as $inquiry)
                        @php
                            $isNew = session('new_inquiry_id') === $inquiry->id;
                            $isResponded = $inquiry->status === 'responded';
                        @endphp
                        <div class="rounded-2xl border bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.06)] transition {{ $isNew ? 'border-indigo-300 ring-2 ring-indigo-200' : 'border-slate-200' }}">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="font-display text-base font-semibold text-ink">{{ $inquiry->subject }}</h3>
                                        @if ($isNew)
                                            <span class="inline-flex rounded-full bg-indigo-100 px-2.5 py-0.5 font-body text-xs font-semibold text-indigo-700">
                                                Just submitted
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-1 font-mono text-xs text-slate-500">Submitted {{ $inquiry->created_at->format('Y-m-d H:i') }}</p>
                                </div>

                                @if ($isResponded)
                                    <span class="inline-flex shrink-0 rounded-full bg-green-100 px-3 py-1 font-body text-xs font-semibold text-green-800">
                                        Responded
                                    </span>
                                @else
                                    <span class="inline-flex shrink-0 rounded-full bg-amber-100 px-3 py-1 font-body text-xs font-semibold text-amber-800">
                                        Waiting for Response
                                    </span>
                                @endif
                            </div>

                            <p class="mt-4 font-body text-sm leading-6 text-slate-700">{{ $inquiry->message }}</p>

                            @if ($isResponded)
                                <div class="mt-5 rounded-xl border border-indigo-100 bg-indigo-50/60 p-4">
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo font-display text-[11px] font-semibold text-white">GF</span>
                                        <p class="font-body text-xs font-semibold uppercase tracking-wide text-indigo-700">Support Team Reply</p>
                                    </div>
                                    <p class="mt-2 font-body text-sm leading-6 text-slate-700">{{ $inquiry->admin_reply }}</p>
                                    <p class="mt-2 font-mono text-xs text-slate-500">Replied {{ $inquiry->replied_at?->format('Y-m-d H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $inquiries->links() }}
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-gray-300 px-6 py-12 text-center">
                    <p class="font-display text-base font-semibold text-ink">No inquiries yet.</p>
                    <p class="mt-2 font-body text-sm text-gray-500">Have a question? Send us a message and we'll get back to you.</p>
                    <a href="{{ route('contact') }}" class="mt-5 inline-flex items-center gap-1.5 rounded-md bg-indigo px-4 py-2 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                        <x-icon-mail class="h-4 w-4" />
                        Send a Message
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
