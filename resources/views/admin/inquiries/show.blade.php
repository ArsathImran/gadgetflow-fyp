<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                    {{ __('Inquiry') }} #{{ $inquiry->id }}
                </h2>
                <p class="mt-1 font-body text-sm text-slate">{{ $inquiry->subject }}</p>
            </div>

            <a href="{{ route('admin.inquiries.index') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-body font-semibold text-slate-700 shadow-sm transition hover:bg-gray-50">
                Back to Inquiries
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @if (session('success'))
                    <div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)] sm:p-8">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-indigo font-display text-base font-semibold text-white">
                                {{ strtoupper(substr($inquiry->name, 0, 1)) }}
                            </span>
                            <div>
                                <p class="font-display text-base font-semibold text-ink">{{ $inquiry->name }}</p>
                                <p class="font-body text-sm text-slate-500">{{ $inquiry->email }}</p>
                            </div>
                        </div>

                        @if ($inquiry->status === 'open')
                            <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 font-body text-xs font-semibold text-amber-800">
                                Waiting for Response
                            </span>
                        @else
                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 font-body text-xs font-semibold text-green-800">
                                Responded
                            </span>
                        @endif
                    </div>

                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <p class="font-body text-xs font-semibold uppercase tracking-wide text-slate-400">Subject</p>
                        <p class="mt-1 font-display text-lg font-semibold text-ink">{{ $inquiry->subject }}</p>

                        <p class="mt-4 font-mono text-xs text-slate-500">Submitted {{ $inquiry->created_at->format('Y-m-d H:i') }}</p>

                        <p class="mt-4 font-body text-sm leading-6 text-slate-700 whitespace-pre-line">{{ $inquiry->message }}</p>
                    </div>
                </div>

                @if ($inquiry->status === 'responded')
                    <div class="rounded-3xl border border-indigo-100 bg-indigo-50/60 p-6 shadow-[0_18px_40px_rgba(79,70,229,0.06)] sm:p-8">
                        <div class="flex items-center gap-2">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo font-display text-[11px] font-semibold text-white">GF</span>
                            <p class="font-body text-xs font-semibold uppercase tracking-wide text-indigo-700">Your Reply</p>
                        </div>
                        <p class="mt-2 font-body text-sm leading-6 text-slate-700 whitespace-pre-line">{{ $inquiry->admin_reply }}</p>
                        <p class="mt-2 font-mono text-xs text-slate-500">Replied {{ $inquiry->replied_at?->format('Y-m-d H:i') }}</p>
                    </div>
                @endif

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)] sm:p-8">
                    <h3 class="font-display text-lg font-semibold text-ink">
                        {{ $inquiry->status === 'responded' ? 'Send Another Reply' : 'Send Reply' }}
                    </h3>
                    <p class="mt-1 font-body text-sm text-slate">The customer will see this on their My Inquiries page.</p>

                    <form method="POST" action="{{ route('admin.inquiries.reply', $inquiry) }}" class="mt-5">
                        @csrf
                        @method('PATCH')

                        <textarea
                            name="admin_reply"
                            rows="5"
                            class="block w-full rounded-md border-gray-300 font-body shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Write your reply to {{ $inquiry->name }}..."
                        >{{ old('admin_reply', $inquiry->status === 'open' ? '' : $inquiry->admin_reply) }}</textarea>
                        @error('admin_reply')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-indigo px-4 py-2 text-sm font-body font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                                <x-icon-send class="h-4 w-4" />
                                Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
