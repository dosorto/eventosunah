<div>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-7">
        Tipos de conferencias
    </h2>

    <div class="dark:bg-gray-900">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 dark:bg-gray-800">
            @if (session()->has('message'))
                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                    <p class="text-sm">{{ session('message') }}</p>
                </div>
            @endif

            @if ($isOpen)
                @include('livewire.TipoConferencia.create')
            @endif

            <div class="relative overflow-x-auto sm:rounded-lg dark:bg-gray-800">
                <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
                    <div>
                        <button wire:click="create()"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded my-3">Nuevo</button>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input wire:model.live="search" type="text"
                            class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Buscar...">
                    </div>
                </div>

                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">No.</th>
                            <th scope="col" class="px-6 py-3">Tipo</th>
                            <th scope="col" class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tiposConferencias as $tipoConferencia)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $tipoConferencia->id }}
                                </th>
                                <td class="px-6 py-4">{{ $tipoConferencia->tipo }}</td>
                                <td class="px-6 py-4">
                                    <button wire:click="edit({{ $tipoConferencia->id }})"
                                        class="mb-1 px-3 py-2 text-sm font-medium text-white inline-flex items-center bg-yellow-500 hover:bg-yellow-600 rounded-lg text-center">
                                        Editar
                                    </button>
                                    <button wire:click="confirmDelete({{ $tipoConferencia->id }})"
                                        class="px-3 py-2 text-sm font-medium text-white inline-flex items-center bg-red-500 hover:bg-red-600 rounded-lg text-center">
                                        Borrar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <br>
                {{ $tiposConferencias->links() }}
                <br>
            </div>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="fixed z-50 inset-0 flex items-center justify-center overflow-y-auto ease-out duration-400">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Error</h3>
                    <p>{{ session('error') }}</p>
                    <div class="mt-4 flex justify-end">
                        <button wire:click="$refresh" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
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

            <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Confirmación de eliminación</h3>
                    <p>¿Estás seguro de que deseas eliminar el tipo "<strong>{{ $nombreAEliminar }}</strong>"? Esta acción no se puede deshacer.</p>
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
