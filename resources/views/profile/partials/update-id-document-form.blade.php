<section>
    <header>
        <h2 class="font-display text-lg font-semibold text-ink">
            {{ __('Supporting Documents') }}
        </h2>

        <p class="mt-1 font-body text-sm text-slate">
            {{ __('Upload a proof of address or government ID (PDF, JPG, or PNG, up to 8MB). This is used to verify delivery orders and only needs to be uploaded once.') }}
        </p>
    </header>

    <div class="mt-6">
        @if ($user->id_document_path)
            <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-cloud px-4 py-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo/10 text-indigo">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.5h6M9 15.5h6M9 9.5h2M7 20.5h10a2 2 0 0 0 2-2v-11l-5-4H7a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z" />
                        </svg>
                    </span>
                    <div>
                        <p class="font-display text-sm font-semibold text-ink">{{ basename($user->id_document_path) }}</p>
                        <p class="mt-0.5 font-body text-xs text-slate">{{ __('Uploaded on :date', ['date' => $user->id_document_uploaded_at?->format('M j, Y')]) }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a
                        href="{{ asset('storage/' . $user->id_document_path) }}"
                        target="_blank"
                        rel="noopener"
                        class="font-body text-sm font-semibold text-indigo transition hover:text-indigo-700"
                    >
                        {{ __('View') }}
                    </a>

                    <a
                        href="#"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-id-document-removal')"
                        class="font-body text-sm font-semibold text-red-600 transition hover:text-red-700"
                    >
                        {{ __('Remove') }}
                    </a>
                </div>
            </div>
        @endif

        <form method="post" action="{{ route('profile.id-document.update') }}" enctype="multipart/form-data" class="mt-4">
            @csrf

            <label
                for="id_document"
                class="inline-flex cursor-pointer items-center justify-center rounded-xl px-4 py-2.5 text-sm font-body font-semibold text-white transition {{ $user->id_document_path ? 'bg-ink hover:bg-slate-900' : 'bg-indigo hover:bg-indigo-500' }}"
            >
                {{ $user->id_document_path ? __('Replace Document') : __('Upload Document') }}
            </label>
            <input
                id="id_document"
                name="id_document"
                type="file"
                accept=".pdf,.jpg,.jpeg,.png"
                class="hidden"
                onchange="this.form.submit()"
            >
        </form>

        <x-modal name="confirm-id-document-removal" focusable>
            <form method="post" action="{{ route('profile.id-document.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="font-display text-lg font-semibold text-ink">
                    {{ __('Remove supporting document?') }}
                </h2>

                <p class="mt-1 font-body text-sm text-slate">
                    {{ __('This will permanently delete your uploaded document. You will need to re-upload it before placing a delivery order.') }}
                </p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close-modal', 'confirm-id-document-removal')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3">
                        {{ __('Remove Document') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>

        @error('id_document', 'idDocumentUpdate')
            <p class="mt-2 font-body text-sm text-red-600">{{ $message }}</p>
        @enderror

        @if (session('status') === 'id-document-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="mt-2 font-body text-sm text-emerald-600"
            >{{ __('Document uploaded.') }}</p>
        @endif

        @if (session('status') === 'id-document-removed')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="mt-2 font-body text-sm text-emerald-600"
            >{{ __('Document removed.') }}</p>
        @endif
    </div>
</section>
