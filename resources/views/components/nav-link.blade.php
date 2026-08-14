@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'flex items-center rounded-2xl border border-[#7b5cff]/40 bg-gradient-to-r from-[#251b47] to-[#1a1830] px-3.5 py-3 text-sm font-medium text-white shadow-[0_10px_30px_rgba(123,92,255,0.18)] transition duration-150 ease-in-out'
        : 'flex items-center rounded-2xl px-3.5 py-3 text-sm font-medium text-slate-500 transition duration-150 ease-in-out hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-white/[0.04] dark:hover:text-white';

@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
