<button {{ $attributes->merge(['type' => 'submit', 'class' => 'bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded my-3 disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
