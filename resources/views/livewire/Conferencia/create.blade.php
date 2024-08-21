<div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form wire:submit.prevent="store">
                <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-6 dark:bg-gray-900">
                    <div
                        class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Conferencia
                        </h3>
                        <button wire:click="closeModal()" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Cerrar modal</span>
                        </button>
                    </div>

                    <div class="mb-4">
                        <label for="evento"
                            class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Evento:</label>
                        <input wire:model.live="inputSearchEvento"
                            class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                            type="text" placeholder="Buscar evento..." readonly>
                        @error('IdEvento') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="foto"
                            class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Imagen:</label>
                        <input type="file" wire:model="foto"
                            class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                        @if ($foto)
                            <img src="{{ $foto->temporaryUrl() }}" class="mt-2 w-20 h-20 object-cover rounded-full">
                        @endif
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="mb-4">
                            <label for="nombre"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Nombre de la
                                Conferencia:</label>
                            <input type="text"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="nombre" placeholder="Nombre de la Conferencia" wire:model="nombre">
                            @error('nombre') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="descripcion"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Descripción:</label>
                            <textarea
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="descripcion" placeholder="Descripción" wire:model="descripcion"></textarea>
                            @error('descripcion') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="mb-4">
                            <label for="fecha"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Fecha:</label>
                            <input type="date"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="fecha" wire:model="fecha">
                            @error('fecha') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="lugar"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Lugar:</label>
                            <textarea
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="lugar" placeholder="Lugar" wire:model="lugar"></textarea>
                            @error('lugar') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="mb-4">
                            <label for="horaInicio" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Hora Inicio:</label>
                            <input type="time"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="horaInicio" wire:model="horaInicio">
                            @error('horaInicio') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="horaFin" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Hora Fin:</label>
                            <input type="time"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="horaFin" wire:model="horaFin">
                            @error('horaFin') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>


                    <div class="mb-4 col-span-2">
                        <label for="linkreunion" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Link
                            Reunion:</label>
                        <input type="url"
                            class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                            id="linkreunion" placeholder="Link Reunion" wire:model="linkreunion">
                        @error('linkreunion') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4 col-span-2">
                        <label for="conferencistaSearch"
                            class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Conferencista:</label>
                        <input type="text" wire:model.live="inputSearchConferencista"
                            class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                            id="conferencistaSearch" placeholder="Buscar conferencista...">
                        @if (count($searchConferencistas) > 0)
                            <ul
                                class="mt-2 bg-white border border-gray-300 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                                @foreach($searchConferencistas as $conferencista)
                                    <li wire:click="selectConferencista({{ $conferencista->id }})"
                                        class="cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        {{ $conferencista->persona->nombre }} {{ $conferencista->persona->apellido }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @error('idConferencista') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-800">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button type="submit"
                            class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-yellow-500 text-base leading-6 font-medium text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Guardar
                        </button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                        <button wire:click="closeModal()" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Cancelar
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
    
</div>