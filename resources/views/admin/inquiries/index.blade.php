<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-display text-xl font-semibold text-ink leading-tight">
                    {{ __('Inquiries') }}
                </h2>
                <p class="mt-1 font-body text-sm text-slate">Customer messages from the Contact form.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($inquiries->count())
                        <div class="overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-ink">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Customer</th>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Subject</th>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Submitted</th>
                                        <th class="px-6 py-3 text-left font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Status</th>
                                        <th class="px-6 py-3 text-right font-body text-xs font-semibold uppercase tracking-wider text-slate-200">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white">
                                    @foreach ($inquiries as $inquiry)
                                        <tr class="transition hover:bg-slate-50 {{ $inquiry->status === 'open' ? 'bg-amber-50/50' : '' }}">
                                            <td class="px-6 py-4 font-body text-sm text-slate-600">
                                                <div class="flex items-center gap-3">
                                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo font-display text-sm font-semibold text-white">
                                                        {{ strtoupper(substr($inquiry->name, 0, 1)) }}
                                                    </span>
                                                    <div>
                                                        <div class="font-medium text-ink">{{ $inquiry->name }}</div>
                                                        <div class="text-xs text-slate-500">{{ $inquiry->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="max-w-xs truncate font-display text-sm font-semibold text-ink" title="{{ $inquiry->subject }}">
                                                    {{ $inquiry->subject }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 font-body text-sm text-slate-600">
                                                {{ $inquiry->created_at->format('Y-m-d H:i') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                @if ($inquiry->status === 'open')
                                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 font-body text-xs font-semibold text-amber-800">
                                                        Waiting for Response
                                                    </span>
                                                @else
                                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 font-body text-xs font-semibold text-green-800">
                                                        Responded
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right font-body text-sm font-medium">
                                                <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="inline-flex items-center gap-1.5 rounded-md border border-indigo-200 px-3 py-2 text-indigo-700 transition hover:bg-indigo-50">
                                                    <x-icon-eye class="h-3.5 w-3.5" />
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $inquiries->links() }}
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-gray-300 px-6 py-12 text-center">
                            <p class="font-display text-base font-semibold text-ink">No inquiries yet.</p>
                            <p class="mt-2 font-body text-sm text-gray-500">Customer messages from the Contact form will show up here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
