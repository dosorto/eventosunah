@extends('layouts.base')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/evento.css') }}">
@endsection
<style>
        /* Estilos para la barra de navegación */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #00468B; /* Cambia el color de fondo */
            padding: 10px 20px;
            position: relative;
        }

        .logo {
            font-size: 1.5em;
            color: #fff; /* Color del texto */
            font-weight: bold;
            text-transform: uppercase;
        }

        .logo span {
            color: #FFD700; /* Diferente color para la parte 'UNAH' */
        }

        .menu-links {
            list-style: none;
            display: flex;
            gap: 15px;
        }</style>
@section('content')
    <section>
    <section>
        <header>
            <nav class="navbar">
                <a class="logo" href="#">EVENTOS <span>UNAH</span></a>
               
                <span id="hamburger-btn" class="hamburger-icon material-symbols-outlined"><div class="pt-5">
                        <a href="/login "
                            class="inline-flex items-center px-3 py-2 text-sm font-semibold text-center text-black bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                Registrarse
                                <svg class="w-6 h-6 text-gray-800 dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                    </div></span>

            </nav>
        </header>


        <section class="hero-section">
            <div class="content">
                <h2>Conferencias para el Evento: {{ $evento->nombreevento }}</h2>
            </div>
        </section>

        <section class="conference-section">
            <div class="content-wrapper">
                @if ($conferencias->isEmpty())
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
                            <a type="button" href="{{ route('welcome') }}"
                                class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-yellow-300 dark:text-gray-800 dark:hover:bg-yellow-400 dark:focus:ring-yellow-800">
                                Ver otros eventos
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid gap-4 p-2" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
                        @foreach ($conferencias as $conferencia)
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
                                                        d="M15 9h3m-3 3h3m-3 3h3m-6 1c-.306-.613-.933-1-1.618-1H7.618c-.685 0-1.312.387-1.618 1M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm7 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                                                </svg>
                                                Conferencista
                                            </td>
                                            <td class="px-6 py-2">
                                                @if ($conferencia->conferencista)
                                                    @if ($conferencia->conferencista->persona)
                                                        {{ $conferencia->conferencista->persona->nombre }}
                                                        {{ $conferencia->conferencista->persona->apellido ?? '' }}
                                                    @else
                                                        N/A
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white">
                                                <svg class="w-6 h-6 text-gray-900 dark:text-white" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                                        d="M4.37 7.657c2.063.528 2.396 2.806 3.202 3.87 1.07 1.413 2.075 1.228 3.192 2.644 1.805 2.289 1.312 5.705 1.312 6.705M20 15h-1a4 4 0 0 0-4 4v1M8.587 3.992c0 .822.112 1.886 1.515 2.58 1.402.693 2.918.351 2.918 2.334 0 .276 0 2.008 1.972 2.515 1.737.463 1.693 1.297 3.055 2.385 1.141.969 1.676 1.992 1.676 2.783" />
                                                </svg>
                                                Lugar
                                            </td>
                                            <td class="px-6 py-2">
                                                {{ $conferencia->lugar ?? 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white">
                                                <svg class="w-6 h-6 text-gray-900 dark:text-white" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                                                </svg>
                                                Fecha
                                            </td>
                                            <td class="px-6 py-2">
                                                {{ $conferencia->fecha }}
                                            </td>
                                        </tr>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white">
                                                <svg class="w-6 h-6 text-gray-900 dark:text-white" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                                        d="M4.37 7.657c2.063.528 2.396 2.806 3.202 3.87 1.07 1.413 2.075 1.228 3.192 2.644 1.805 2.289 1.312 5.705 1.312 6.705M20 15h-1a4 4 0 0 0-4 4v1M8.587 3.992c0 .822.112 1.886 1.515 2.58 1.402.693 2.918.351 2.918 2.334 0 .276 0 2.008 1.972 2.515 1.737.463 1.693 1.297 3.055 2.385 1.141.969 1.676 1.992 1.676 2.783" />
                                                </svg>
                                                Hora 
                                            </td>
                                            <td class="px-6 py-2">
                                                {{ $conferencia->horaInicio }} a {{ $conferencia->horaFin }}
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="pt-5">
                                        <a href="/login "
                                            class="inline-flex items-center px-3 py-2 text-sm font-semibold text-center text-black bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                            Inscribirse
                                            <svg class="w-6 h-6 text-gray-800 dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z" clip-rule="evenodd"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </section>
@endsection
