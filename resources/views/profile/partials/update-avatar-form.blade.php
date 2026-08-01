<section class="text-center">
    @if ($user->avatar_path)
        <img
            src="{{ asset('storage/' . $user->avatar_path) }}"
            alt="{{ $user->name }}"
            class="mx-auto h-32 w-32 rounded-full object-cover ring-4 ring-cloud"
        >
    @else
        <span class="mx-auto flex h-32 w-32 items-center justify-center rounded-full bg-indigo font-display text-4xl font-semibold text-white ring-4 ring-cloud">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </span>
    @endif

    <p class="mt-5 font-display text-xl font-bold text-ink">{{ $user->name }}</p>
    <p class="mt-1 font-body text-sm text-slate">{{ $user->email }}</p>

    <form method="post" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="mt-6">
        @csrf

        <label for="avatar" class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-ink px-4 py-2.5 text-sm font-body font-semibold text-white transition hover:bg-slate-900">
            {{ __('Change avatar') }}
        </label>
        <input
            id="avatar"
            name="avatar"
            type="file"
            accept="image/*"
            class="hidden"
            onchange="this.form.submit()"
        >
    </form>

    @error('avatar', 'avatarUpdate')
        <p class="mt-2 font-body text-sm text-red-600">{{ $message }}</p>
    @enderror

    @if (session('status') === 'avatar-updated')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 2000)"
            class="mt-2 font-body text-sm text-emerald-600"
        >{{ __('Avatar updated.') }}</p>
    @endif
</section>
