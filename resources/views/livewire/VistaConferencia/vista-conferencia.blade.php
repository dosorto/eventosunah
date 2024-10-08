<div>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-3 ml-2">
        Conferencias para el Evento: {{ $evento->nombreevento }}
    </h2>

    @if (session()->has('success'))
        <div class="alert alert-success">

            <div id="alert-4"
                class="flex items-center p-4 mb-4  text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-500 dark:border-yellow-800s"
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

    @if($conferencias->isEmpty())
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
        <div class="grid gap-4 p-2" style=" grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">

            @foreach ($conferencias as $conferencia)
                <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <a href="#">
                        @if($conferencia->foto)
                            <img src="{{ asset(str_replace('public', 'storage', $conferencia->foto)) }}" alt="Logo del Evento"
                                class="w-full h-60 object-cover rounded-t-lg">
                        @else
                            <img src="https://th.bing.com/th/id/R.653172c106ff8be48c9881731a77cf82?rik=SPJhwr7DH8CK0A&riu=http%3a%2f%2fwww.puertopixel.com%2fwp-content%2fuploads%2f2011%2f03%2fFondos-web-Texturas-web-abtacto-7.jpg&ehk=jq2ET132JWRHBPfnU8ZZR5pOfWyPfrDZQmlNxKipMqc%3d&risl=&pid=ImgRaw&r=0&sres=1&sresct=1"
                                alt="Imagen Conferencia" class="rounded-t-lg" />
                        @endif

                    </a>
                    <div class="p-5">
                        <a href="#">
                            <h5 class="mb-2 text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                {{ $conferencia->nombre }}
                            </h5>
                        </a>
                        <table class="w-full text-sm  text-right rtl:text-left text-gray-500 dark:text-gray-400">
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
                                            d="M4.37 7.657c2.063.528 2.396 2.806 3.202 3.87 1.07 1.413 2.075 1.228 3.192 2.644 1.805 2.289 1.312 5.705 1.312 6.705M20 15h-1a4 4 0 0 0-4 4v1M8.587 3.992c0 .822.112 1.886 1.515 2.58 1.402.693 2.918.351 2.918 2.334 0 .276 0 2.008 1.972 2.008 2.026.031 2.026-1.678 2.026-2.008 0-.65.527-.9 1.177-.9H20M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Lugar
                                </td>
                                <td class="px-6 py-2">
                                    {{ $conferencia->lugar }}
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
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Hora
                                </td>
                                <td class="px-6 py-2">
                                    De {{ $conferencia->horaInicio }} a {{ $conferencia->horaFin }}
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.213 9.787a3.391 3.391 0 0 0-4.795 0l-3.425 3.426a3.39 3.39 0 0 0 4.795 4.794l.321-.304m-.321-4.49a3.39 3.39 0 0 0 4.795 0l3.424-3.426a3.39 3.39 0 0 0-4.794-4.795l-1.028.961" />
                                    </svg>
                                    Link
                                </td>
                                <td class="px-6 py-2">

                                    <button type="button" data-modal-target="course-modal" data-modal-toggle="course-modal"
                                        class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                                        <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M17.5 3A3.5 3.5 0 0 0 14 7L8.1 9.8A3.5 3.5 0 0 0 2 12a3.5 3.5 0 0 0 6.1 2.3l6 2.7-.1.5a3.5 3.5 0 1 0 1-2.3l-6-2.7a3.5 3.5 0 0 0 0-1L15 9a3.5 3.5 0 0 0 6-2.4c0-2-1.6-3.5-3.5-3.5Z" />
                                        </svg>
                                        Copiar link
                                    </button>

                                    <!-- Main modal -->
                                    <div id="course-modal" tabindex="-1" aria-hidden="true"
                                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-lg max-h-full">
                                            <!-- Modal content -->
                                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                                <!-- Modal header -->
                                                <div class="flex items-center justify-between p-4 md:p-5">
                                                    <h3 class="text-lg text-gray-500 dark:text-gray-400">
                                                        Link de la reunión virtual
                                                    </h3>
                                                    <button type="button"
                                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-700 dark:hover:text-white"
                                                        data-modal-toggle="course-modal">
                                                        <svg class="w-3 h-3" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2"
                                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                </div>
                                                <!-- Modal body -->
                                                <div class="px-4 pb-4 md:px-5 md:pb-5">
                                                    <label for="course-url"
                                                        class="text-sm font-medium text-gray-900 dark:text-white mb-2 block">Con
                                                        este link puedes acceder a la reunión de la conferencia:</label>
                                                    <div class="relative mb-4">
                                                        <input id="course-url" type="text"
                                                            class="col-span-6 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                            value=" {{ $conferencia->linkreunion }}" disabled readonly>
                                                        <button data-copy-to-clipboard-target="course-url"
                                                            data-tooltip-target="tooltip-course-url"
                                                            class="absolute end-2 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg p-2 inline-flex items-center justify-center">
                                                            <span id="default-icon-course-url">
                                                                <svg class="w-3.5 h-3.5" aria-hidden="true"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                                    viewBox="0 0 18 20">
                                                                    <path
                                                                        d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z" />
                                                                </svg>
                                                            </span>
                                                            <span id="success-icon-course-url" class="hidden items-center">
                                                                <svg class="w-3.5 h-3.5 text-blue-700 dark:text-blue-500"
                                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                    fill="none" viewBox="0 0 16 12">
                                                                    <path stroke="currentColor" stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M1 5.917 5.724 10.5 15 1.5" />
                                                                </svg>
                                                            </span>
                                                        </button>
                                                        <div id="tooltip-course-url" role="tooltip"
                                                            class="absolute z-20 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                            <span id="default-tooltip-message-course-url">Copiado en
                                                                portapapeles</span>
                                                            <span id="success-tooltip-message-course-url"
                                                                class="hidden">Copiado!</span>
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    <button type="button" data-modal-hide="course-modal"
                                                        class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-yellow-500 rounded-lg hover:bg-yellow-600  focus:z-10 focus:ring-4  dark:bg-yellow-500 dark:text-gray-900 dark:hover:bg-yellow-600">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        </table>
                        <div class="pt-5">
                            <a href="#" wire:click.prevent="confirmInscription({{ $conferencia->id }})"
                                class="inline-flex items-center px-3 py-2 text-sm font-semibold text-center text-black bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                Inscribirse
                                <svg class="w-6 h-6 text-gray-800 dark:text-gray-800" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <!-- Modal de mostrar la confirmación de conferencia -->
    <div x-data="{ open: @entangle('showConfirmModal') }" x-cloak x-show="open" @keydown.escape.window="open = false"
        class="fixed inset-0 flex items-center justify-center z-50 overflow-y-auto ease-out duration-400"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="flex items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 11-10 10A10 10 0 0112 2z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Confirmación de
                                Inscripción</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">¿Estás segura de que quieres inscribirte a la
                                    conferencia?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 flex flex-row-reverse">
                    <button wire:click="inscribirse" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Sí, inscribirme
                    </button>
                    <button @click="open = false; $wire.cancel()" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de mostrar que ya se ha suscrito -->
    <div x-data="{ open: @entangle('SuscripciónYaRealizada') }" x-cloak x-show="open"
        @keydown.escape.window="open = false"
        class="fixed inset-0 flex items-center justify-center z-50 overflow-y-auto ease-out duration-400"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="flex items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:h-10 sm:w-10">
                            <svg class="h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M4 12a8 8 0 1116 0A8 8 0 014 12z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Ya estás inscrito
                                en la conferencia</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Ya estás inscrito en esta conferencia.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 flex flex-row-reverse">
                    <button @click="open = false" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>