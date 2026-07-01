@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-slate-500 shadow-[inset_0_1px_0_rgba(255,255,255,0.05)] transition duration-200 focus:border-blue-400/60 focus:bg-white/10 focus:ring-4 focus:ring-blue-500/20']) }}>
