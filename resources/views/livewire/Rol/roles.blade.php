<div>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-7">
        Manejo de roles y permisos
    </h2>

    <div class="dark:bg-gray-900">
        <div>
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
                    @include('livewire.Rol.create')
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
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input wire:model.live="search" type="text" id="table-search-users" class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:text-white" placeholder="Buscar...">
                        </div>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    No.
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Roles
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Accesos
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $rol)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $rol->id }}</th>
                                <td class="px-6 py-4  text-gray-600 dark:text-gray-400">{{ $rol->name }}</td>
                                <td class="px-6 py-4">
                                    <div class="divide-y divide-gray-300 dark:divide-gray-600">
                                        @foreach ($rol->permissions as $permission)
                                            <div class="py-1 text-gray-600 dark:text-gray-400 ">{{ $permission->name }}</div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                <button wire:click="edit({{ $rol->id }})"
                                            class="mb-1 px-3 py-2 text-sm font-medium text-white inline-flex items-center bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 rounded-lg text-center dark:bg-yellow-500 dark:hover:bg-yellow-600 dark:focus:ring-yellow-800">
                                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                            </svg>


                                            Editar
                                        </button>
                                        <button wire:click="confirmDelete({{ $rol->id }})"
                                            class="px-3 py-2 text-sm font-medium text-white inline-flex items-center bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800">
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
                    {{ $roles->links() }}
                    <br>
                </div>
            </div>
        </div>
    </div>
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
                                <p>¿Estás seguro de que deseas eliminar el Rol : "<strong>{{ $nombreAEliminar }}</strong>"? Esta acción no se puede deshacer.</p>
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
