<div>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-7">
        Localidades
    </h2>

    <div class="dark:bg-gray-900">
        <div class="">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 dark:bg-gray-800">
                @if (session()->has('message'))
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ session('message') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($isOpen)
                    @include('livewire.localidad.create')
                @endif

                <div class="relative overflow-x-auto sm:rounded-lg dark:bg-gray-800">
                    <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
                        <div>
                            <button wire:click="create()" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded my-3">Nuevo</button>
                        </div>
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input wire:model.live="search" type="text" id="table-search-users" class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:text-white" placeholder="Buscar...">
                        </div>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">No.</th>
                                <th scope="col" class="px-6 py-3">Localidad</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($localidades as $localidad)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $localidad->id }}
                                    </th>
                                    <td class="px-6 py-4">{{ $localidad->localidad }}</td>
                                    <td class="px-6 py-4">
                                        <button wire:click="edit({{ $localidad->id }})" class="mb-1 px-3 py-2 text-sm font-medium text-white inline-flex items-center bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 rounded-lg text-center dark:bg-yellow-500 dark:hover:bg-yellow-600 dark:focus:ring-yellow-800">
                                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                            </svg>
                                            Editar
                                        </button>
                                        <button wire:click="confirmDelete({{ $localidad->id }})"
                                                class="px-3 py-2 text-sm font-medium text-white inline-flex items-center bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800">
                                                <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M4 7h16M9 7V4h6v3m1 0h4l-1 14H7L6 7Zm4-2h4a1 1 0 0 1 1 1v1H9V6a1 1 0 0 1 1-1Z" />
                                                </svg>
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">{{ $localidades->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    @if ($confirmingDelete)
        <div class="fixed z-50 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center z-50 sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                @if ($eventosAsociados)
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        {{ __('Esta localidad está asociada a uno o más eventos, no se puede eliminar ') }} "<strong>{{ $nombreAEliminar }}</strong>"
                                    </h3>
                                @else
                                    <h3 class="text-lg font-semibold mb-4">{{ __('Confirmación de Eliminación') }}</h3>
                                    <p>{{ __('¿Estás seguro de que deseas eliminar la localidad') }} "<strong>{{ $nombreAEliminar }}</strong>"? {{ __('Esta acción no se puede deshacer.') }}</p>
                                    <div class="mt-4 flex justify-end">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        @if (!$eventosAsociados)
                            <button wire:click="delete" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                                Eliminar
                            </button>

                        @endif
                        <button wire:click="$set('confirmingDelete', false)" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                            Cancelar
                        </button>

                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
