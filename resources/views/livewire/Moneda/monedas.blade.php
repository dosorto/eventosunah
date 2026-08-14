<div>
    <h2 class="mb-7 text-xl font-semibold leading-tight text-gray-800 dark:text-white">
        Monedas
    </h2>

    <div class="dark:bg-gray-900">
        <div class="bg-white px-4 py-4 shadow-xl dark:bg-gray-800 sm:rounded-lg">
            @if (session()->has('message'))
                <div class="my-3 rounded-b border-t-4 border-teal-500 bg-teal-100 px-4 py-3 text-teal-900 shadow-md" role="alert">
                    <p class="text-sm">{{ session('message') }}</p>
                </div>
            @endif

            @if($isOpen)
                @include('livewire.Moneda.create')
            @endif

            <div class="relative overflow-x-auto dark:bg-gray-800 sm:rounded-lg">
                <div class="flex flex-column flex-wrap items-center justify-between space-y-4 pb-4 sm:flex-row sm:space-y-0">
                    <div>
                        <button wire:click="create()" class="my-3 rounded bg-yellow-500 px-4 py-2 font-bold text-white hover:bg-yellow-600">Nuevo</button>
                    </div>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input wire:model.live="search" type="text" class="block w-80 rounded-lg border border-gray-300 bg-gray-50 ps-10 pt-2 text-sm text-gray-900 focus:border-yellow-500 focus:ring-yellow-500 dark:bg-gray-700 dark:text-white" placeholder="Buscar...">
                    </div>
                </div>

                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">No.</th>
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">Código</th>
                            <th class="px-6 py-3">Símbolo</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monedas as $moneda)
                            <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                                <th class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $moneda->id }}</th>
                                <td class="px-6 py-4">{{ $moneda->nombre }}</td>
                                <td class="px-6 py-4">{{ $moneda->codigo }}</td>
                                <td class="px-6 py-4">{{ $moneda->simbolo ?: '—' }}</td>
                                <td class="px-6 py-4">
                                    <button wire:click="edit({{ $moneda->id }})" class="mb-1 inline-flex items-center rounded-lg bg-yellow-500 px-3 py-2 text-sm font-medium text-white hover:bg-yellow-600">
                                        Editar
                                    </button>
                                    <button wire:click="confirmDelete({{ $moneda->id }})" class="inline-flex items-center rounded-lg bg-red-500 px-3 py-2 text-sm font-medium text-white hover:bg-red-600">
                                        Borrar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <br>
                {{ $monedas->links() }}
                <br>
            </div>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto ease-out duration-400">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="w-full max-w-lg rounded-lg bg-white p-6 text-left shadow-xl">
                <h3 class="mb-4 text-lg font-semibold">Error</h3>
                <p>{{ session('error') }}</p>
                <div class="mt-4 flex justify-end">
                    <button wire:click="$set('confirmingDelete', false)" class="rounded bg-gray-500 px-4 py-2 font-bold text-white hover:bg-gray-600">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    @elseif ($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto ease-out duration-400">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="w-full max-w-lg rounded-lg bg-white p-6 text-left shadow-xl">
                <h3 class="mb-4 text-lg font-semibold">Confirmación de eliminación</h3>
                <p>¿Estás seguro de que deseas eliminar la moneda "<strong>{{ $nombreAEliminar }}</strong>"?</p>
                <div class="mt-4 flex justify-end">
                    <button wire:click="$set('confirmingDelete', false)" class="mr-2 rounded bg-gray-500 px-4 py-2 font-bold text-white hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button wire:click="delete" class="rounded bg-red-500 px-4 py-2 font-bold text-white hover:bg-red-600">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
