<div x-data="{ showModal: false, message: '' }" @show-modal.window="showModal = true; message = $event.detail" class="flex flex-col min-h-screen">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-7">
        Inscripciones
    </h2>

    <div class="dark:bg-gray-900">
        <div class="">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 dark:bg-gray-800">

                <!-- Modal -->
                <div x-show="showModal" x-cloak @keydown.escape.window="showModal = false" class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg shadow-lg p-8 dark:bg-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Notificación</h2>
                        <p class="mt-4 text-sm text-gray-700 dark:text-gray-300" x-text="message"></p>
                        <div class="mt-6 flex justify-end">
                            <button @click="showModal = false" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Cerrar</button>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-x-auto sm:rounded-lg">
                    <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center pb-4">
                        <label for="table-search" class="sr-only">Buscar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input wire:model="search" type="text" id="table-search-inscripciones" class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:text-white" placeholder="Buscar...">
                        </div>

                        <div class="mr-32"></div>
                        <button wire:click="marcarTodos('Aceptado')" class="mb-1 px-3 py-2 text-sm ml-96 font-medium text-white inline-flex items-center bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-300 rounded-lg text-center dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800">
                            Marcar Todos
                        </button>
                        
                    </div>

                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">No.</th>
                                <th scope="col" class="px-6 py-3">Persona</th>
                                <th scope="col" class="px-6 py-3">Evento</th>
                                <th scope="col" class="px-6 py-3">Estado</th>
                                <th scope="col" class="px-6 py-3 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inscripciones as $inscripcion)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-200">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-gray-900">
                                        {{ $inscripcion->id }}
                                    </td>
                                    <td class="px-6 py-4 dark:text-gray-900">
                                        {{ $inscripcion->persona->nombre }} {{ $inscripcion->persona->apellido }}
                                    </td>
                                    <td class="px-6 py-4 dark:text-gray-900">
                                        {{ $inscripcion->evento->nombreevento }}
                                    </td>
                                    <td class="px-6 py-4 dark:text-gray-900">
                                        {{ $inscripcion->Status }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex space-x-2 justify-center">
                                            <button wire:click="marcarComprobado({{ $inscripcion->id }})" class="px-3 py-1 w-28 h-10 bg-green-500 text-white rounded-lg hover:bg-green-600">Aceptar</button>
                                            <button wire:click="rechazarComprobacion({{ $inscripcion->id }})" class="px-3 py-1 w-28 h-10 bg-red-600 text-white rounded-lg hover:bg-red-700">Rechazar</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">No hay inscripciones registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    {{ $inscripciones->links() }}
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
