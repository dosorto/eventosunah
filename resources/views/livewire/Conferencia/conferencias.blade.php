<div>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-7">
        Conferencias
    </h2>

    <div class="dark:bg-gray-900">
        <div
            class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 dark:bg-gray-800 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
            @if (session()->has('message'))
                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                    role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($isOpen)
                @include('livewire.Conferencia.create')
            @endif

            <div class="relative overflow-x-auto sm:rounded-lg dark:bg-gray-800">
                <div
                    class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
                    <div>
                        <button wire:click="create()"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded my-3">Nuevo</button>
                    </div>
                    <label for="table-search" class="sr-only">Buscar</label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input wire:model.live="search" type="text" id="table-search-users"
                            class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Buscar...">
                    </div>
                </div>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-white">
                        <tr>
                            <th scope="col" class="px-6 py-3">No.</th>
                            <th scope="col" class="px-6 py-3">Imagen</th>
                            <th scope="col" class="px-6 py-3">Evento</th>
                            <th scope="col" class="px-6 py-3">Nombre</th>
                            <th scope="col" class="px-6 py-3">Lugar</th>
                            <th scope="col" class="px-6 py-3">Conferencista</th>
                            <th scope="col" class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($conferencias as $conferencia)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4 font-medium whitespace-nowrap dark:text-white">
                                    {{ $conferencia->id }}</td>
                                    <td class="px-6 py-4">
                                        @if($conferencia->foto)
                                            <img src="{{ asset(str_replace('public', 'storage', $conferencia->foto)) }}"
                                                alt="Logo del Evento" class="w-12 h-12 object-cover">
                                        @else
                                        <img src="{{ asset('images/default-profile.png') }}" alt="Imagen" class="w-12 h-12 object-cover">
                                        @endif
                                    </td>

                                <td class="px-6 py-4">{{ $conferencia->evento->nombreevento }}</td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap">
                                    {{ $conferencia->nombre }}</td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap">
                                    {{ $conferencia->lugar }}</td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap">
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
                                <td class="px-6 py-4">
                                        <button wire:click="viewDetails({{ $conferencia->id }})"
                                            class="mb-1 w-28 h-10 px-3 py-2 text-sm font-medium text-white inline-flex items-center bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-width="2"
                                                    d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                                <path stroke="currentColor" stroke-width="2"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            Ver más
                                        </button>
                                       <!-- En la vista de conferencias -->
                                        <a href="{{ route('asistencias-Conferencia', ['conferencia' => $conferencia->id]) }}"
                                        class="mb-1 w-28 h-10 px-3 py-2 text-sm font-medium text-white inline-flex items-center bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-300 rounded-lg text-center dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800">
                                        <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M18 14a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2v-2Z"
                                        clip-rule="evenodd" />
                                    <path fill-rule="evenodd"
                                        d="M15.026 21.534A9.994 9.994 0 0 1 12 22C6.477 22 2 17.523 2 12S6.477 2 12 2c2.51 0 4.802.924 6.558 2.45l-7.635 7.636L7.707 8.87a1 1 0 0 0-1.414 1.414l3.923 3.923a1 1 0 0 0 1.414 0l8.3-8.3A9.956 9.956 0 0 1 22 12a9.994 9.994 0 0 1-.466 3.026A2.49 2.49 0 0 0 20 14.5h-.5V14a2.5 2.5 0 0 0-5 0v.5H14a2.5 2.5 0 0 0 0 5h.5v.5c0 .578.196 1.11.526 1.534Z"
                                        clip-rule="evenodd" />
                                </svg>
                                        Asistencia
                                        </a>
                                        
                                        <button wire:click="edit({{ $conferencia->id }})"
                                            class="mb-1 w-28 h-10 px-3 py-2 text-sm font-medium text-white inline-flex items-center bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 rounded-lg text-center dark:bg-yellow-500 dark:hover:bg-yellow-600 dark:focus:ring-yellow-800">
                                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                            </svg>


                                            Editar
                                        </button>
                                        <button wire:click="confirmDelete({{ $conferencia->id }})"
                                        class="px-3 w-28 h-10 py-2 text-sm font-medium text-white inline-flex items-center bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800">
                                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                            </svg>
                                            Borrar
                                        </button>
                                    </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="px-6 py-3">
                    {{ $conferencias->links() }}
                </div>
            </div>
        </div>
        
    </div>

    <!-- Modal for Detailed View -->
    @if ($showDetails)
        <div class="fixed z-50 inset-0 flex items-center justify-center overflow-y-auto ease-out duration-400">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Detalles del Evento</h3>
                    <div>
                        <table class=" text-sm  text-left rtl:text-left text-gray-500 dark:text-gray-400">
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Evento:</strong> </td><td class="px-6 py-2"> {{ $selectedConferencia->evento->nombreevento }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Nombre:</strong></td><td class="px-6 py-2">  {{ $selectedConferencia->nombre }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Descripción:</strong></td><td class="px-6 py-2">   {{ $selectedConferencia->descripcion }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Fecha:</strong></td><td class="px-6 py-2">  {{ $selectedConferencia->fecha }}</p></td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Hora Inicio:</strong> </td><td class="px-6 py-2"> {{ $selectedConferencia->horaInicio }}</p></td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Hora Fin:</strong></td><td class="px-6 py-2">  {{ $selectedConferencia->horaFin }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Lugar:</strong></td><td class="px-6 py-2">  {{ $selectedConferencia->lugar }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Link Reunión:</strong></td><td class="px-6 py-2">  <a href="{{ $selectedConferencia->linkreunion }}" target="_blank">{{ $selectedConferencia->linkreunion }}</a></td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Conferencista:</strong></td><td class="px-6 py-2">  @if ($selectedConferencia->conferencista)
                            @if ($selectedConferencia->conferencista->persona)
                                {{ $selectedConferencia->conferencista->persona->nombre }}
                                {{ $selectedConferencia->conferencista->persona->apellido ?? '' }}
                            @else
                                N/A
                            @endif
                        @else
                            N/A
                        @endif</td>
                            </tr>
                        </table>
                    </div>
                    <div class="mt-4">
                        <button wire:click="closeDetails()"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
      <!-- Modal for Detailed View -->
    @if ($showDetails)
        <div class="fixed z-50 inset-0 flex items-center justify-center overflow-y-auto ease-out duration-400">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Detalles del Evento</h3>
                    <div>
                        <table class=" text-sm  text-left rtl:text-left text-gray-500 dark:text-gray-400">
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Evento:</strong> </td><td class="px-6 py-2"> {{ $selectedConferencia->evento->nombreevento }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Nombre:</strong></td><td class="px-6 py-2">  {{ $selectedConferencia->nombre }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Descripción:</strong></td><td class="px-6 py-2">   {{ $selectedConferencia->descripcion }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Fecha:</strong></td><td class="px-6 py-2">  {{ $selectedConferencia->fecha }}</p></td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Hora Inicio:</strong> </td><td class="px-6 py-2"> {{ $selectedConferencia->horaInicio }}</p></td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Hora Fin:</strong></td><td class="px-6 py-2">  {{ $selectedConferencia->horaFin }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Lugar:</strong></td><td class="px-6 py-2">  {{ $selectedConferencia->lugar }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Link Reunión:</strong></td><td class="px-6 py-2">  <a href="{{ $selectedConferencia->linkreunion }}" target="_blank">{{ $selectedConferencia->linkreunion }}</a></td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Conferencista:</strong></td><td class="px-6 py-2">  @if ($selectedConferencia->conferencista)
                            @if ($selectedConferencia->conferencista->persona)
                                {{ $selectedConferencia->conferencista->persona->nombre }}
                                {{ $selectedConferencia->conferencista->persona->apellido ?? '' }}
                            @else
                                N/A
                            @endif
                        @else
                            N/A
                        @endif</td>
                            </tr>
                        </table>
                    </div>
                    <div class="mt-4">
                        <button wire:click="closeDetails()"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed z-50 inset-0 flex items-center justify-center overflow-y-auto ease-out duration-400">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Error</h3>
                    <p>{{ session('error') }}</p>
                    <div class="mt-4 flex justify-end">
                        <button wire:click="$set('confirmingDelete', false)" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($confirmingDelete)
        <div class="fixed z-50 inset-0 flex items-center justify-center overflow-y-auto ease-out duration-400">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Confirmación de Eliminación</h3>
                    <p>¿Estás seguro de que deseas eliminar la conferencia "<strong>{{ $nombreAEliminar }}</strong>"? Esta acción no se puede deshacer.</p>
                    <div class="mt-4 flex justify-end">
                        <button wire:click="$set('confirmingDelete', false)" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                            Cancelar
                        </button>
                        <button wire:click="delete" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>