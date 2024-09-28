<div>
    <div class="content-wrapper">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-2 ml-2">
            Eventos Disponibles
        </h2>
        @if($Eventos->isEmpty())
            <div class="p-4 mb-4 text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-500 dark:border-yellow-800"
                role="alert">
                <div class="flex items-center">
                    <svg class="flex-shrink-0 w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <h3 class="text-lg font-bold">No hay eventos disponibles.</h3>
                </div>
                <p>Por el momento no hay eventos disponibles, por favor intente más tarde.</p>
            </div>
        @else
                <div
                    class="evento-list dark:bg-gray-900 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">
                    @foreach($Eventos as $tarjetasEvento)
                            <div class="evento-card">
                                @php
                                    $inscripcion = Auth::user()->persona->inscripciones()->where('IdEvento', $tarjetasEvento->id)->first();
                                    $estadoInscripcion = $inscripcion ? $inscripcion->Status : null;
                                    $yaInscrito = $estadoInscripcion === 'Aceptado';
                                @endphp

                                <a href="{{ 
                                                                        ($tarjetasEvento->estado === 'Pagado' && !$yaInscrito)
                            ? route('subir-comprobante', ['evento' => $tarjetasEvento->id])
                            : route('vistaconferencia', ['evento' => $tarjetasEvento->id]) 
                                                                    }}">
                                    <div class="thumbnail-container">
                                        @if($tarjetasEvento->logo == "")
                                            <img src="http://www.puertopixel.com/wp-content/uploads/2011/03/Fondos-web-Texturas-web-abtacto-17.jpg"
                                                alt="Sin Foto De Evento">
                                        @else
                                            <img src="{{ asset(str_replace('public', 'storage', $tarjetasEvento->logo)) }}"
                                                alt="Sin Foto De Evento"
                                                class="bg-white dark:bg-gray-800 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white thumbnail">
                                        @endif
                                        <p class="marca">{{ $tarjetasEvento->modalidad->modalidad }}</p>
                                    </div>
                                    <div class="evento-info">
                                        <img src="{{ asset('Logo/EVENTIS LOGO.png') }}" alt="foto-creador" class="icon">
                                </a>
                                <div class="evento-details">
                                    <h2 class="name-evento">{{ $tarjetasEvento->nombreevento }}</h2>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="flex flex-col justify-center max-w-sm">
                                            <p class="evento-creador">{{ $tarjetasEvento->organizador }}</p>
                                            <p class="fecha-creacion">{{ $tarjetasEvento->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="flex flex-col justify-center max-w-sm">
                                            @if ($tarjetasEvento->estado === 'Pagado')
                                                                @php
                                                                    $evento = $tarjetasEvento;
                                                                    $yaInscrito = Auth::user()->persona->inscripciones()
                                                                        ->where('IdEvento', $evento->id)
                                                                        ->exists();
                                                                @endphp
                                                                @if ($yaInscrito)
                                                                    <p data-modal-target="inscrito-modal-{{ $tarjetasEvento->id }}"
                                                                        data-modal-toggle="inscrito-modal-{{ $tarjetasEvento->id }}"
                                                                        class="cursor-pointer inscribir-button inline-flex items-center px-3 py-2 text-sm font-semibold text-center text-black bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                                                        Inscribirse
                                                                        <svg class="w-6 h-6 text-gray-800 dark:text-gray-800" xmlns="http://www.w3.org/2000/svg"
                                                                            fill="currentColor" viewBox="0 0 24 24">
                                                                            <path fill-rule="evenodd"
                                                                                d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                    </p>
                                                                @else
                                                                    <p data-modal-target="progress-modal-{{ $tarjetasEvento->id }}"
                                                                        data-modal-toggle="progress-modal-{{ $tarjetasEvento->id }}"
                                                                        class="cursor-pointer inscribir-button inline-flex items-center px-3 py-2 text-sm font-semibold text-center text-black bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                                                        Inscribirse
                                                                        <svg class="w-6 h-6 text-gray-800 dark:text-gray-800" xmlns="http://www.w3.org/2000/svg"
                                                                            fill="currentColor" viewBox="0 0 24 24">
                                                                            <path fill-rule="evenodd"
                                                                                d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                    </p>
                                                                @endif
                                                                @if ($tarjetasEvento->estado === 'Pagado')
                                                                        <p class="inscripcion-status text-sm text-gray-600">
                                        
                                                                            <span class="{{ $estadoInscripcion === 'Aceptado' ? 'text-green-500' : 'text-red-500' }}">
                                                                                Estado: {{ $estadoInscripcion ?? 'No inscrito' }}
                                                                            </span>
                                                                        </p>
                                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Modal para ya inscrito -->
                                    <div id="inscrito-modal-{{ $tarjetasEvento->id }}" tabindex="-1" aria-hidden="true"
                                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <!-- Fondo opaco -->
                                        <div class="fixed inset-0 bg-black opacity-50"></div>
                                        <div class="relative p-4 w-full max-w-md max-h-full mx-auto">
                                            <!-- Modal content -->
                                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                <div class="p-4 md:p-5">
                                                    <h3 class="mb-1 text-xl font-bold text-gray-900 dark:text-white">
                                                        Ya estas inscrito a "{{ $tarjetasEvento->nombreevento }}"
                                                    </h3>
                                                    <p class="text-gray-500 dark:text-gray-400 mb-6">Si tu comprobante de pago ya fue
                                                        aceptado ya debes poder inscribirte a las conferencias de este evento.</p>
                                                    <!-- Modal footer -->
                                                    <div class="flex items-center mt-6 space-x-4 rtl:space-x-reverse">
                                                        <button data-modal-hide="inscrito-modal-{{ $tarjetasEvento->id }}" type="button"
                                                            class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-yellow-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cerrar</button>
                                                        @if ($estadoInscripcion == 'Aceptado')
                                                            <a href="{{ route('vistaconferencia', ['evento' => $tarjetasEvento->id]) }}"
                                                                data-modal-hide="inscrito-modal-{{ $tarjetasEvento->id }}" type="button"
                                                                class="text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">Ver
                                                                conferencias</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div id="progress-modal-{{ $tarjetasEvento->id}}" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="fixed inset-0 bg-black opacity-50"></div>
                            <div class="relative p-4 w-full max-w-md max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <div class="p-4 md:p-5">
                                        <h3 class="mb-1 text-xl font-bold text-gray-900 dark:text-white">
                                            "{{$tarjetasEvento->nombreevento}}" tiene un costo
                                        </h3>
                                        <p class="text-gray-500 dark:text-gray-400 mb-6">Por favor, sube tu comprobante de pago para
                                            completar tu
                                            inscripción.
                                        <p>
                                            <!-- Modal footer -->
                                        <div class="flex items-center mt-6 space-x-4 rtl:space-x-reverse">
                                            <button data-modal-hide="progress-modal-{{ $tarjetasEvento->id }}" type="button"
                                                class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-yellow-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cerrar</button>
                                            <a href="{{ route('recibo', ['evento' => $tarjetasEvento->id]) }}"
                                                data-modal-hide="progress-modal-{{ $tarjetasEvento->id }}" type="button"
                                                class="text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">Subir
                                                Comprobante</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
            </div>

            <br>
            {{ $Eventos->links() }}
            <br>
        @endif
</div>