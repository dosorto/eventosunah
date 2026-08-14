@extends('layouts.base')
@section('content')
    <x-banner />

    <div class="orbit-shell">
        <x-menu-navegaction />

        @if (isset($header))
            <header class="border-b border-white/6 bg-transparent">
                <div class="mx-auto px-4 py-6 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main>
            <div class="admin-content px-4 pb-8 pt-24 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-[1440px]">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
@endsection
