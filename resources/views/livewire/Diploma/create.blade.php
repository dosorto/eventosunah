<div>
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
                        <div
                            class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Nuevo Diploma
                            </h3>
                            <button wire:click="closeModal" type="button"
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
                            <label for="codigo"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">codigo:</label>
                            <input type="text" wire:model="codigo"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="codigo" placeholder="codigo">
                            @error('codigo')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="URL"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">URL:</label>
                            <input type="url" wire:model="URL"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="URL" placeholder="URL">
                            @error('URL')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="Fecha"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Fecha:</label>
                            <input type="date" wire:model="Fecha"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="Fecha" placeholder="Fecha">
                            @error('Fecha')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="evento"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Evento:</label>
                            <input wire:model.live="inputSearchEvento"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                type="text" placeholder="Buscar evento...">
                            @if (!empty($inputSearchEvento) && !empty($searchEventos))
                                <ul
                                    class="bg-white border border-gray-300 mt-2 rounded-md max-h-48 overflow-auto shadow-lg z-10">
                                    @foreach ($searchEventos as $evento)
                                        <li wire:click="selectEvento({{ $evento->id }})"
                                            class="p-2 cursor-pointer hover:bg-gray-200">
                                            {{ $evento->nombreevento }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @error('IdEvento')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="mb-4">
                            <label for="conferencia"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Conferencia:</label>
                            <input wire:model.live="inputSearchConferencia"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                type="text" placeholder="Buscar evento...">
                            @if (!empty($inputSearchConferencia) && !empty($searchConferencias))
                                <ul
                                    class="bg-white border border-gray-300 mt-2 rounded-md max-h-48 overflow-auto shadow-lg z-10">
                                    @foreach ($searchConferencias as $conferencia)
                                        <li wire:click="selectConferencia({{ $conferencia->id }})"
                                            class="p-2 cursor-pointer hover:bg-gray-200">
                                            {{ $conferencia->nombre }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @error('IdConferencia')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="Firma"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Firma:</label>
                            <input wire:model.live="inputSearchFirma"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                type="text" placeholder="Buscar evento...">
                            @if (!empty($inputSearchFirma) && !empty($searchFirmas))
                                <ul
                                    class="bg-white border border-gray-300 mt-2 rounded-md max-h-48 overflow-auto shadow-lg z-10">
                                    @foreach ($searchFirmas as $Firma)
                                        <li wire:click="selectFirma({{ $Firma->id }})"
                                            class="p-2 cursor-pointer hover:bg-gray-200">
                                            {{ $Firma->Nombre }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @error('IdFirma')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>








                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-900">
                        <button type="submit" wire:click.prevent="store()"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-yellow-500 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:bg-yellow-500 dark:hover:bg-yellow-600 dark:focus:ring-yellow-800 sm:w-auto sm:text-sm">
                            Guardar
                        </button>
                        <button type="button" wire:click="closeModal"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-white dark:ring-gray-600 dark:hover:bg-gray-700 dark:focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
