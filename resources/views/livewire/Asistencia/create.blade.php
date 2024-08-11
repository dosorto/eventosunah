<div>
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 sm:mx-auto">
                <form wire:submit.prevent="store">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between pb-4 mb-4 border-b dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Nueva Asistencia</h3>
                            <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Cerrar</span>
                            </button>
                        </div>

                        <div class="mb-4">
                            <label for="suscripcion" class="block text-sm font-medium text-gray-900 dark:text-white">Suscripción:</label>
                            <input wire:model.live="inputSearchSuscripcion" type="text" id="suscripcion"
                                class="block w-full p-2.5 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                placeholder="Buscar suscripción...">
                            @error('IdSuscripcion') <span class="text-red-500">{{ $message }}</span> @enderror

                            @if(!empty($searchSuscripciones))
                                <ul class="mt-2 bg-white border border-gray-300 rounded-lg shadow-lg dark:bg-gray-800">
                                    @foreach($searchSuscripciones as $suscripcion)
                                        <li wire:click="selectSuscripcion({{ $suscripcion->id }})"
                                            class="p-2 hover:bg-gray-200 cursor-pointer dark:hover:bg-gray-600 dark:text-white">
                                            {{ $suscripcion->persona->nombre }} {{ $suscripcion->persona->apellido }} - {{ $suscripcion->conferencia->nombre }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label for="asistencia" class="block text-sm font-medium text-gray-900 dark:text-white">Asistencia:</label>
                            <select id="asistencia" wire:model="Asistencia"
                                class="block w-full p-2.5 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                                <option value="">Seleccione</option>
                                <option value="1">Presente</option>
                                <option value="0">Ausente</option>
                            </select>
                            @error('Asistencia') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end space-x-2">
                            <button type="button" wire:click="closeModal"
                                class="text-gray-500 bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm font-medium hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:border-gray-600">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg text-sm shadow-sm ring-1 ring-yellow-500 focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-600">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
