<div>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-7">
        Eventos
    </h2>

    <div class=" dark:bg-gray-900">
        <div class="">
            <div
                class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 dark:bg-gray-800  dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
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




                @if($isOpen)
                    @include('livewire.Evento.create')
                @endif




                <div class="relative overflow-x-auto  sm:rounded-lg  dark:bg-gray-800">
                    <div
                        class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4 ">
                        <div>
                            <button wire:click="create()"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded my-3">Nuevo</button>

                        </div>
                        <label for="table-search" class="sr-only  dark:text-white">Buscar...</label>
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
                                class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700  dark:text-white"
                                placeholder="Buscar...">
                        </div>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700  dark:text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3">No.</th>
                                <th scope="col" class="px-6 py-3">Nombre Evento</th>
                                <th scope="col" class="px-6 py-3">Descripción</th>
                                <th scope="col" class="px-6 py-3">Organizador</th>
                                <th scope="col" class="px-6 py-3">Modalidad</th>
                                <th scope="col" class="px-6 py-3">Localidad</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nombreeventos as $nombreevento)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ $nombreevento->id }}</td>
                                    <td class="px-6 py-4">{{ $nombreevento->nombreevento }}</td>
                                    <td class="px-6 py-4">{{ $nombreevento->descripcion }}</td>
                                    <td class="px-6 py-4">{{ $nombreevento->organizador }}</td>
                                    <td class="px-6 py-4">{{ $nombreevento->modalidad->modalidad }}</td>
                                    <td class="px-6 py-4">{{ $nombreevento->localidad->localidad }}</td>
                                    <td class="px-6 py-4">
                                        <button wire:click="viewDetails({{ $nombreevento->id }})"
                                            class="mb-1 w-full px-3 py-2 text-sm font-medium text-white inline-flex items-center bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
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
                                        <a href="{{ route('conferencia', ['evento' => $nombreevento->id]) }}"
                                            class="mb-1 w-full px-3 py-2 text-sm font-medium text-white inline-flex items-center bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-300 rounded-lg text-center dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800">
                                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>


                                            Conferencia


                                        </a>

                                        <button wire:click="edit({{ $nombreevento->id }})"
                                            class="mb-1 w-full px-3 py-2 text-sm font-medium text-white inline-flex items-center bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 rounded-lg text-center dark:bg-yellow-500 dark:hover:bg-yellow-600 dark:focus:ring-yellow-800">
                                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                            </svg>


                                            Editar
                                        </button>
                                        <button wire:click="delete({{ $nombreevento->id }})"
                                            class="px-3 w-full py-2 text-sm font-medium text-white inline-flex items-center bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800">
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
                    <br>
                    {{ $nombreeventos->links()}}
                    <br>
                </div>
            </div>

        </div>

    </div>

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
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Evento:</strong> </td><td class="px-6 py-2"> {{ $selectedEvento->nombreevento }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Descripción:</strong></td><td class="px-6 py-2">  {{ $selectedEvento->descripcion }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Organizador:</strong></td><td class="px-6 py-2">  {{ $selectedEvento->organizador }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Fecha Inicio:</strong></td><td class="px-6 py-2">  {{ $selectedEvento->fechainicio }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Fecha Final:</strong> </td><td class="px-6 py-2"> {{ $selectedEvento->fechafinal }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Hora Inicio:</strong></td><td class="px-6 py-2">  {{ $selectedEvento->horainicio }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Hora Final:</strong></td><td class="px-6 py-2">  {{ $selectedEvento->horafin }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Modalidad:</strong></td><td class="px-6 py-2">  {{ $selectedEvento->modalidad->modalidad }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:bg-gray-800 dark:border-gray-700">
                                <td scope="row" class="flex items-center pl-2 py-4 text-gray-900 font-bold dark:text-white"><strong>Localidad:</strong></td><td class="px-6 py-2">  {{ $selectedEvento->localidad->localidad }}</td>
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

</div>