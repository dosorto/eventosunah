@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'bg-yellow-400 flex transition duration-100 ease-in-out dark:hover:bg-yellow-400 dark:bg-yellow-400 items-center p-2 text-black rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-yellow-400 group'
        : 'bg-yellow-0 flex dark:hover:bg-yellow-400 transition duration-100 ease-in-out items-center p-2 text-black rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-yellow-400 group';

@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>