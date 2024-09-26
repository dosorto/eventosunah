<div>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-7">
        Historial de Eventos
    </h2>

    <div class="dark:bg-gray-900">
        <div class="">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 dark:bg-gray-800">
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

                <div class="relative overflow-x-auto sm:rounded-lg dark:bg-gray-800">
                    <div
                        class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">

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
                            <input wire:model.debounce.300ms="search" type="text" id="table-search-conferencias"
                                class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:text-white"
                                placeholder="Buscar...">
                        </div>
                        <div class="mr-32"></div>
                        <a href="{{ route('historial-conferencias') }}"
                            class="mb-1 px-3 py-2 text-sm ml-96 font-medium text-white inline-flex items-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                            Historial de conferencias
                            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                            </svg>
                        </a>
                    </div>

                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Evento
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Organizador
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Modalidad
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Fecha
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Costo
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Acción
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($eventos as $item)
                                <tr
                                    class="hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $item['evento']->nombreevento }}</td>
                                    <td class="px-6 py-4">{{ $item['evento']->organizador }}</td>
                                    <td class="px-6 py-4">{{ $item['evento']->modalidad->modalidad }}</td>
                                    <td class="px-6 py-4">{{ $item['evento']->fechainicio }}</td>
                                    <td class="px-6 py-4">{{ $item['evento']->estado }}</td>
                                    <td class="px-6 py-4">
                                        <button wire:click="descargarDiploma({{ $item['inscripcion_id'] }})"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                            Diploma
                                            <svg class="w-6 h-6 text-white ms-2 dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2m-8 1V4m0 12-4-4m4 4 4-4" />
                                            </svg>
                                        </button>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center">No has asistido a ningun evento.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="fixed z-50 inset-0 flex items-center justify-center overflow-y-auto ease-out duration-400">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Error</h3>
                    <p>{{ session('error') }}</p>
                    <div class="mt-4 flex justify-end">
                        <button wire:click="$set('confirmingDelete', false)"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded-lg">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>