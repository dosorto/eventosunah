<!-- resources/views/event-details.blade.php -->

@extends('layouts.app')

@section('title', 'Detalles del Evento')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">{{ $evento->nombreevento }}</h1>
    <p><strong>Descripción:</strong> {{ $evento->descripcion }}</p>
    <p><strong>Organizador:</strong> {{ $evento->organizador }}</p>
    <p><strong>Fecha de Inicio:</strong> {{ $evento->fechainicio }}</p>
    <p><strong>Fecha de Finalización:</strong> {{ $evento->fechafinal }}</p>
    <p><strong>Hora de Inicio:</strong> {{ $evento->horainicio }}</p>
    <p><strong>Hora de Finalización:</strong> {{ $evento->horafin }}</p>
    <p><strong>Modalidad:</strong> {{ $evento->modalidad->modalidad }}</p>
    <p><strong>Localidad:</strong> {{ $evento->localidad->nombre }}</p>

    <h2 class="text-xl font-bold mt-4">Conferencias</h2>
    @if (session()->has('success'))
        <div class="alert alert-success">
            <div id="alert-4"
                class="flex items-center p-4 mb-4 text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-500 dark:border-yellow-800"
                role="alert">
                <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="sr-only">Info</span>
                <div class="ms-3 text-sm font-medium">
                    {{ session('success') }}
                </div>
                <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-yellow-50 text-yellow-500 rounded-lg focus:ring-2 focus:ring-yellow-400 p-1.5 hover:bg-yellow-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-yellow-300 dark:hover:bg-gray-700"
                    data-dismiss-target="#alert-4" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if($evento->conferencias->isEmpty())
        <div id="alert-additional-content-4"
            class="p-4 mb-4 text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-500 dark:border-yellow-800"
            role="alert">
            <div class="flex items-center">
                <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="sr-only">Info</span>
                <h3 class="text-lg font-bold">No hay conferencias.</h3>
            </div>
            <div class="mt-2 mb-4 text-sm">
                Este evento todavía no tiene conferencias disponibles para mostrar.
            </div>
            <div class="flex">
                <a type="button" href="{{ route('eventoVista') }}"
                    class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-yellow-300 dark:text-gray-800 dark:hover:bg-yellow-400 dark:focus:ring-yellow-800">
                    <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 14">
                        <path
                            d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
                    </svg>
                    Ver otros eventos
                </a>
            </div>
        </div>
    @else
        <div class="grid gap-4 p-2" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
            @foreach ($evento->conferencias as $conferencia)
                <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <a href="#">
                        <img src="https://th.bing.com/th/id/R.653172c106ff8be48c9881731a77cf82?rik=SPJhwr7DH8CK0A&riu=http%3a%2f%2fwww.puertopixel.com%2fwp-content%2fuploads%2f2011%2f03%2fFondos-web-Texturas-web-abtacto-7.jpg&ehk=jq2ET132JWRHBPfnU8ZZR5pOfWyPfrDZQmlNxKipMqc%3d&risl=&pid=ImgRaw&r=0&sres=1&sresct=1"
                            alt="Imagen Conferencia" class="rounded-t-lg" />
                    </a>
                    <div class="p-5">
                        <a href="#">
                            <h5 class="mb-2 text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                {{ $conferencia->nombre }}
                            </h5>
                        </a>
                        <table class="w-full text-sm text-right rtl:text-left text-gray-500 dark:text-gray-400">
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 9h3m-3 3h3m-3 3h3m-6 1c-.306-.613-.933-1-1.618-1H7.618c-.685 0-1.312.387-1.618 1M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Zm8 9a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" />
                                    </svg>
                                    <div class="pl-3 text-sm font-normal">Precio</div>
                                </td>
                                <td class="py-4 text-gray-900 font-bold dark:text-white">{{ $conferencia->precio }}
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 9v3.25a3.75 3.75 0 0 1 3.75 3.75m-3.75-7A3.75 3.75 0 1 1 8.25 9H12Zm0 6a3.75 3.75 0 0 0-3.75 3.75M12 15v3m7-14H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Z" />
                                    </svg>
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 5h2a2 2 0 0 1 2 2v2H3V5Zm0 8h4v2a2 2 0 0 1-2 2H3v-4Zm18-8v4h-4V7a2 2 0 0 1 2-2h2Zm0 8v4h-2a2 2 0 0 1-2-2v-2h4Zm-7-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm-4-3v2a4 4 0 1 0 8 0V7a4 4 0 1 0-8 0Zm4 11v3" />
                                    </svg>
                                    <div class="pl-3 text-sm font-normal">Fecha</div>
                                </td>
                                <td class="py-4 text-gray-900 font-bold dark:text-white">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 9v3.25a3.75 3.75 0 0 1 3.75 3.75m-3.75-7A3.75 3.75 0 1 1 8.25 9H12Zm0 6a3.75 3.75 0 0 0-3.75 3.75M12 15v3m7-14H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Z" />
                                    </svg>
                                    {{ $conferencia->fecha }}
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M7.5 10a4.5 4.5 0 0 1 9 0c0 1.99-1.206 3.365-2.266 4.498-.814.91-1.734 1.938-2.234 3.002M12 19h.008M19.5 10c0-3.89-3.036-7.5-7.5-7.5S4.5 6.11 4.5 10a7.286 7.286 0 0 0 1.266 4.002c.814 1.206 1.734 2.234 2.234 3.498a.499.499 0 0 0 .5.5h3.5a.499.499 0 0 0 .5-.5c.5-1.064 1.42-2.092 2.234-3.002A7.285 7.285 0 0 0 19.5 10Z" />
                                    </svg>
                                    <div class="pl-3 text-sm font-normal">Hora</div>
                                </td>
                                <td class="py-4 text-gray-900 font-bold dark:text-white">
                                    <svg class="w-6 h-6 text-gray-900 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 9v3.25a3.75 3.75 0 0 1 3.75 3.75m-3.75-7A3.75 3.75 0 1 1 8.25 9H12Zm0 6a3.75 3.75 0 0 0-3.75 3.75M12 15v3m7-14H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Z" />
                                    </svg>
                                    {{ $conferencia->hora }}
                                </td>
                            </tr>
                        </table>
                        <a href="{{ route('conferencia.detalle', ['conferencia' => $conferencia->id]) }}"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Ver detalles
                            <svg class="w-4 h-4 ml-2 -mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m12 6 6 4-6 4V6zM2 18V2" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
