<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="">
                        <div class="mb-4">
                            <label for="exampleFormControlInput1"
                                class="block text-gray-700 text-sm font-bold mb-2">Nombre del Evento:</label>
                            <input type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="exampleFormControlInput1" placeholder="Nombre Evento" wire:model="nombreevento">
                            @error('nombreevento') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-4">
                            <label for="exampleFormControlTextarea1"
                                class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                            <textarea
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="exampleFormControlTextarea1" placeholder="Descripción" wire:model="descripcion"></textarea>
                            @error('descripcion') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-4">
                            <label for="exampleFormControlInput2"
                                class="block text-gray-700 text-sm font-bold mb-2">Organizador:</label>
                            <input type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="exampleFormControlInput2" placeholder="Organizador" wire:model="organizador">
                            @error('organizador') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="exampleFormControlInput3"
                                    class="block text-gray-700 text-sm font-bold mb-2">Fecha de Inicio:</label>
                                <input type="date"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="exampleFormControlInput3" wire:model="fechainicio">
                                @error('fechainicio') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="exampleFormControlInput4"
                                    class="block text-gray-700 text-sm font-bold mb-2">Fecha Final:</label>
                                <input type="date"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="exampleFormControlInput4" wire:model="fechafinal">
                                @error('fechafinal') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="exampleFormControlInput5"
                                    class="block text-gray-700 text-sm font-bold mb-2">Hora de Inicio:</label>
                                <input type="time"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="exampleFormControlInput5" wire:model="horainicio">
                                @error('horainicio') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="exampleFormControlInput6"
                                    class="block text-gray-700 text-sm font-bold mb-2">Hora de Fin:</label>
                                <input type="time"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="exampleFormControlInput6" wire:model="horafin">
                                @error('horafin') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="modalidadSelect"
                                    class="block text-gray-700 text-sm font-bold mb-2">Modalidad:</label>
                                <select id="modalidadSelect"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    wire:model="idmodalidad">
                                    <option value="">Seleccione una Modalidad</option>
                                    @foreach($modalidades as $modalidad)
                                        <option value="{{ $modalidad->id }}">{{ $modalidad->modalidad }}</option>
                                    @endforeach
                                </select>
                                @error('idmodalidad') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="localidadSelect"
                                    class="block text-gray-700 text-sm font-bold mb-2">Localidad:</label>
                                <select id="localidadSelect"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    wire:model="idlocalidad">
                                    <option value="">Seleccione una Localidad</option>
                                    @foreach($localidades as $localidad)
                                        <option value="{{ $localidad->id }}">{{ $localidad->localidad }}</option>
                                    @endforeach
                                </select>
                                @error('idlocalidad') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="conferenciaSelect"
                                class="block text-gray-700 text-sm font-bold mb-2">Conferencia:</label>
                            <select id="conferenciaSelect"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                wire:model="idconferencia">
                                <option value="">Seleccione una Conferencia</option>
                                @foreach($conferencias as $conferencia)
                                    <option value="{{ $conferencia->id }}">{{ $conferencia->nombre }}</option>
                                @endforeach
                            </select>
                            @error('idconferencia') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="store()" type="button"
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