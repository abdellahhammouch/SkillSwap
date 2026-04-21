<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl border border-transparent bg-rose-600 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-rose-500 active:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2 focus:ring-offset-slate-950']) }}>
    {{ $slot }}
</button>
