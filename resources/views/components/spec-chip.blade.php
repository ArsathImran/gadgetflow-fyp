@props(['icon' => null])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 font-mono text-xs font-medium tracking-tight text-slate-700']) }}>
    @if ($icon)
        <span class="shrink-0 text-slate-400">{{ $icon }}</span>
    @endif
    {{ $slot }}
</span>
