@extends('layouts.base')
@section('content')
    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <x-menu-navegaction />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="dark:bg-gray-900">
            <div class="p-4 sm:ml-64 mt-10">
                <div class=" mx-auto sm:px-6 lg:px-8 ">
                    {{ $slot }}
                </div>
            </div>
        </main>

    </div>
@endsection
