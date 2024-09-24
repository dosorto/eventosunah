<div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-gray-900"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form wire:submit.prevent="store">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-900">
                    <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $evento_id ? 'Editar Evento' : 'Crear Evento' }}
                        </h3>
                        <button wire:click="closeModal()" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="">
                        <div class="mb-4">
                            <label for="nombreevento"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Nombre del Evento:</label>
                            <input type="text"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="nombreevento" placeholder="Nombre Evento" wire:model="nombreevento">
                            @error('nombreevento') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-4">
                            <label for="logo" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Foto:</label>
                            <input type="file" wire:model="logo"
                                class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                            @if ($logo)
                                <img src="{{ $logo->temporaryUrl() }}" class="mt-2 w-20 h-20 object-cover rounded-full">
                            @endif
                        </div>
                        <div class="mb-4">
                            <label for="descripcion"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Descripción:</label>
                            <textarea
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="descripcion" placeholder="Descripción" wire:model="descripcion"></textarea>
                            @error('descripcion') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-4">
                            <label for="organizador"
                                class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Organizador:</label>
                            <input type="text"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="organizador" placeholder="Organizador" wire:model="organizador">
                            @error('organizador') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="mb-4">
                                <label for="fechainicio"
                                    class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Fecha Inicio:</label>
                                <input type="date"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    id="fechainicio" wire:model="fechainicio">
                                @error('fechainicio') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="fechafinal"
                                    class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Fecha Fin:</label>
                                <input type="date"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    id="fechafinal" wire:model="fechafinal">
                                @error('fechafinal') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="mb-4">
                                <label for="horainicio"
                                    class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Hora Inicio:</label>
                                <input type="time"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    id="horainicio" wire:model="horainicio">
                                @error('horainicio') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="horafin"
                                    class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Hora Fin:</label>
                                <input type="time"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    id="horafin" wire:model="horafin">
                                @error('horafin') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado:</label>
                        <select class="focus:ring-yellow-500 focus:border-yellow-500 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                            id="estado" name="estado" wire:model.live="estado" required>
                            <option value="" >Seleccione estado</option>
                            <option value="Gratis">Gratis</option>
                            <option value="Pagado">Pagado</option>
                        </select>
                        @error('estado') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    @if($estado == 'Pagado') 
                        <div class="mb-4">
                            <label for="precio" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Precio:</label>
                            <input type="number" wire:model="precio"
                                class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                id="precio" placeholder="Precio" step="0.01" min="0">
                            @error('precio') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    @endif

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="mb-4">
                                <label for="modalidadSelect"
                                    class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Modalidad:</label>
                                <select id="modalidadSelect"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
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
                                    class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Localidad:</label>
                                <select id="localidadSelect"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    wire:model="idlocalidad">
                                    <option value="">Seleccione una Localidad</option>
                                    @foreach($localidades as $localidad)
                                        <option value="{{ $localidad->id }}">{{ $localidad->localidad }}</option>
                                    @endforeach
                                </select>
                                @error('idlocalidad') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="diplomasSelect"
                                    class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Plantilla del diploma:</label>
                                <select id="diplomasSelect"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    wire:model="IdDiploma">
                                    <option value="">Plantilla del diploma</option>
                                    @foreach($diplomas as $diploma)
                                        <option value="{{ $diploma->id }}">Plantilla Diploma: {{ $diploma->Nombre }}</option>
                                    @endforeach
                                </select>
                                @error('IdDiploma') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Opción para agregar una nueva cuenta -->
                        <div class="mt-4">
                            <label for="createNewCuenta" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">
                                Crear Nueva Cuenta
                            </label>
                            <input type="checkbox" id="createNewCuenta" wire:model.live="createNewCuenta">
                        </div>

                        @if($createNewCuenta)
                            <!-- Formulario para nueva cuenta -->
                            <div class="mt-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Detalles de la Nueva Cuenta</h4>
                                <div class="mb-4">
                                    <label for="cuentaNombre"
                                        class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Nombre del Titular:</label>
                                    <input type="text" id="cuentaNombre" wire:model="cuentaNombre"
                                        class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                                    @error('cuentaNombre') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="mb-4">
                                    <label for="cuentaNumeroDeCuenta"
                                        class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Número de Cuenta:</label>
                                    <input type="text" id="cuentaNumeroDeCuenta" wire:model="cuentaNumeroDeCuenta"
                                        class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                                    @error('cuentaNumeroDeCuenta') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="mb-4">
                                    <label for="cuentaBanco"
                                        class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Banco:</label>
                                    <select id="cuentaBanco" wire:model="cuentaBanco"
                                        class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                                        <option value="" disabled selected>Seleccione un banco</option>
                                        <option value="Banco Atlántida">Banco Atlántida</option>
                                        <option value="Banco de Occidente">Banco de Occidente</option>
                                        <option value="Banco Ficohsa">Banco Ficohsa</option>
                                        <option value="Banco Popular">Banco Popular</option>
                                        <option value="Banco del Pais">Banco del Pais</option>
                                        <option value="Bac Honduras">Bac Honduras</option>
                                        <option value="Banco Nacional de Desarrollo Agrícola">Banco Nacional de Desarrollo Agrícola</option>
                                     
                                    </select>
                                    @error('cuentaBanco') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="mb-4">
                                    <label for="cuentaTipoCuenta"
                                        class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Tipo de Cuenta:</label>
                                    <select id="cuentaTipoCuenta" wire:model="cuentaTipoCuenta"
                                        class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                                        <option value="" disabled selected>Seleccione un tipo de cuenta</option>
                                        <option value="Ahorro">Ahorro</option>
                                        <option value="Corriente">Corriente</option>
                                        <option value="Cheques">Cheques</option>
                                    </select>
                                    @error('cuentaTipoCuenta') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>

                                <div class="mb-4">
                                    <label for="cuentaSaldoActual"
                                        class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Saldo Actual:</label>
                                    <input type="number" id="cuentaSaldoActual" wire:model="cuentaSaldoActual"
                                        class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                                    @error('cuentaSaldoActual') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700">
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
