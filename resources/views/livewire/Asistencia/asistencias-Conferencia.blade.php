<div x-data="{ showModal: false, message: '' }" @show-modal.window="showModal = true; message = $event.detail" class="flex flex-col min-h-screen">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-7">
        Asistencias
    </h2>

    <div class="dark:bg-gray-900">
        <div class="">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 dark:bg-gray-800">
                
                <!-- Modal -->
                <div x-show="showModal" x-cloak @keydown.escape.window="showModal = false"
                     class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg shadow-lg p-8 dark:bg-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Notificación</h2>
                        <p class="mt-4 text-sm text-gray-700 dark:text-gray-300" x-text="message"></p>
                        <div class="mt-6 flex justify-end">
                            <button @click="showModal = false"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-x-auto sm:rounded-lg">
                    <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input wire:model="search" type="text" id="table-search-asistencias"
                                class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:text-white"
                                placeholder="Buscar...">
                        </div>

                        <div class="space-x-2">
                            <button wire:click="marcarTodos"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Marcar Todos</button>
                        </div>
                    </div>

                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">No.</th>
                                <th scope="col" class="px-6 py-3">Persona</th>
                                <th scope="col" class="px-6 py-3">Conferencia</th>
                                <th scope="col" class="px-6 py-3">Asistencia</th>
                                <th scope="col" class="px-6 py-3 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suscripciones as $suscripcion)
                                @php
                                    $asistencia = $suscripcion->asistencias->first();
                                    $rowClass = $asistencia ? ($asistencia->Asistencia ? 'bg-green-100' : 'bg-red-100') : 'bg-gray-100';
                                @endphp
                                <tr class="{{ $rowClass }} border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $suscripcion->id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $suscripcion->persona->nombre }} {{ $suscripcion->persona->apellido }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $suscripcion->conferencia->nombre }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($asistencia)
                                            {{ $asistencia->Asistencia ? 'Presente' : 'Ausente' }}
                                        @else
                                            No registrada
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex space-x-2 justify-center">
                                            <button wire:click="marcarAsistencia({{ $suscripcion->id }})"
                                                class="px-3 py-1 w-28 h-10 bg-green-600 text-white rounded-lg hover:bg-green-700">Asistencia</button>
                                            <button wire:click="marcarAusencia({{ $suscripcion->id }})"
                                                class="px-3 py-1 w-28 h-10 bg-red-600 text-white rounded-lg hover:bg-red-700">No Asistió</button>
                                            @if ($asistencia && $asistencia->Asistencia)
                                                <a href="{{ route('vistaDiploma', ['asistencia' => $asistencia->id]) }}"
                                                    class="px-3 py-3 w-28 h-10 text-center bg-blue-600 text-white rounded-lg hover:bg-blue-700">Ver Diploma</a>
                                            @endif
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
                    {{ $suscripciones->links() }}
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
