<div>
    @if($isOpen)
    <div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <form wire:submit.prevent="store">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-900">
                        <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Nueva Asistencia
                            </h3>
                            <button wire:click="closeModal" type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-toggle="defaultModal">
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
                            <label for="asistencia"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Asistencia:</label>
                            <select wire:model="Asistencia" id="asistencia"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                                <option value="">Selecciona una opción</option>
                                <option value="1">Presente</option>
                                <option value="0">Ausente</option>
                            </select>
                            @error('Asistencia') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="persona" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Persona:</label>
                            <input wire:model.live="inputSearchPersona"
                            class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                            type="text" placeholder="Buscar persona...">
                            @if(!empty($inputSearchPersona) && !empty($searchPersonas))
                            <ul
                                class="bg-white border border-gray-300 mt-2 rounded-md max-h-48 overflow-auto shadow-lg z-10">
                                @foreach($searchPersonas as $persona)
                                <li wire:click="selectPersona({{ $persona->id }})"
                                    class="p-2 cursor-pointer hover:bg-gray-200">
                                    {{ $persona->nombre }} {{ $persona->apellido }}
                                </li>
                                @endforeach
                            </ul>
                            @endif
                            @error('IdPersona') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="evento" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Conferencia:</label>
                            <input wire:model.live="inputSearchEvento"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                type="text" placeholder="Buscar conferencia...">
                            @if(!empty($inputSearchConferencia) && !empty($searchConferencia))
                            <ul
                                class="bg-white border border-gray-300 mt-2 rounded-md max-h-48 overflow-auto shadow-lg z-10">
                                @foreach($searchConferencia as $conferencia)
                                <li wire:click="selectConferencia({{ $conferencia->id }})"
                                    class="p-2 cursor-pointer hover:bg-gray-200">
                                    {{ $conferencia->nombre }}
                                </li>
                                @endforeach
                            </ul>
                            @endif
                            @error('IdConferencia') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-800">
                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <button type="submit"
                                class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-yellow-500 text-base leading-6 font-medium text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Guardar
                            </button>
                        </span>
                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                            <button wire:click="closeModal" type="button"
                                class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Cancelar
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>