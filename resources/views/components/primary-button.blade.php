<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-2xl border border-blue-300/20 bg-gradient-to-r from-blue-500 via-indigo-500 to-violet-500 px-5 py-3 text-sm font-semibold text-white shadow-[0_20px_50px_rgba(59,130,246,0.35)] transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_24px_60px_rgba(99,102,241,0.4)] focus:outline-none focus:ring-4 focus:ring-blue-400/30 active:translate-y-0']) }}>
    {{ $slot }}
</button>
