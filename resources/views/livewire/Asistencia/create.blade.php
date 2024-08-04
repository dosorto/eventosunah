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
                                <span class="sr-only">Cerrar</span>
                            </button>
                        </div>
                        <div class="mb-4">
                            <label for="suscripcion"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Suscripción:</label>
                            <input wire:model.live="inputSearchSuscripcion"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                type="text" placeholder="Buscar suscripción...">
                            @error('IdSuscripcion') <span class="text-red-500">{{ $message }}</span> @enderror
                            <div class="mt-2">
                                @if(!empty($searchSuscripciones))
                                    <ul class="bg-white shadow rounded-lg">
                                        @foreach($searchSuscripciones as $suscripcion)
                                            <li wire:click="selectSuscripcion({{ $suscripcion->id }})"
                                                class="p-2 hover:bg-gray-200 cursor-pointer">
                                                {{ $suscripcion->persona->nombre }} {{ $suscripcion->persona->apellido }} - {{ $suscripcion->conferencia->nombre}}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <div class="col-span-2 sm:col-span-2">
                            <label for="asistencia"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Asistencia</label>
                            <select id="asistencia" wire:model="Asistencia"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                                <option value="">Seleccione</option>
                                <option value="1">Presente</option>
                                <option value="0">Ausente</option>
                            </select>
                            @error('Asistencia') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                    </div>
                    <div class="flex items-center justify-end p-6 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="button" wire:click="closeModal"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 rounded-lg border border-gray-300 font-medium text-sm px-5 py-2.5 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:border-gray-600 dark:focus:ring-gray-700">Cancelar</button>
                        <button type="submit"
                            class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-yellow-500 px-4 py-2 text-base font-medium text-white shadow-sm ring-1 ring-yellow-500 hover:ring-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-yellow-600">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif