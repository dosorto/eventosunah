@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'bg-yellow-400 flex transition duration-10 ease-in-out dark:hover:bg-gray-700 dark:bg-gray-700 items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group'
        : 'bg-gray-0 flex dark:hover:bg-gray-700 transition duration-10 ease-in-out items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-yellow-400 dark:hover:bg-gray-700 group';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
