<div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Modal backdrop -->
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form wire:submit.prevent="store">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-900">
                    <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Crear Persona
                        </h3>
                        <button wire:click="closeModal()" type="button"
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

                    <!-- Formulario de Crear Persona -->
                    <div class="details personal">
                        <span class="title text-lg font-medium text-gray-700 dark:text-gray-300">Detalles Personales</span>
                        <div class="fields space-y-4">
                            <div class="input-field">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo Perfil</label>
                                <select class="focus:ring-yellow-500 focus:border-yellow-500 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    id="IdTipoPerfil" name="IdTipoPerfil" wire:model.live="IdTipoPerfil" required>
                                    <option value="" disabled selected>Seleccione tipo perfil</option>
                                    @foreach($tipoperfiles as $tipoPerfil)
                                        <option value="{{ $tipoPerfil->id }}">{{ $tipoPerfil->tipoperfil }}</option>
                                    @endforeach
                                </select>
                                @error('IdTipoPerfil') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Usuario</label>
                                <select class="focus:ring-yellow-500 focus:border-yellow-500 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    id="IdUsuario" name="IdUsuario" wire:model="IdUsuario" required>
                                    <option value="" disabled selected>Seleccione su usuario</option>
                                    @foreach($user as $users)
                                        <option value="{{ $users->id }}">{{ $users->email }}</option>
                                    @endforeach
                                </select>
                                @error('IdUsuario') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">DNI</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    type="text" placeholder="Ingrese su DNI" id="dni" name="dni" wire:model="dni" required>
                                @error('dni') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    type="text" placeholder="Ingrese su nombre" id="nombre" name="nombre" wire:model="nombre" required>
                                @error('nombre') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Apellido</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    type="text" placeholder="Ingrese su apellido" id="apellido" name="apellido" wire:model="apellido" required>
                                @error('apellido') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    type="email" placeholder="Ingrese su correo" id="correo" name="correo" wire:model="correo" required>
                                @error('correo') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            @if($IdTipoPerfil == 1 ) 
                            <div class="mb-4">
                                <label for="correoInstitucional" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Correo Institucional:</label>
                                <input type="email" wire:model="correoInstitucional"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    id="correoInstitucional" placeholder="Correo Institucional">
                                @error('correoInstitucional') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="numeroCuenta" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Número de Cuenta:</label>
                                <input type="text" wire:model="numeroCuenta"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    id="numeroCuenta" placeholder="Número de Cuenta">
                                @error('numeroCuenta') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif
                        @if($IdTipoPerfil == 3) 
                            <div class="mb-4">
                                <label for="correoInstitucional" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Correo Institucional:</label>
                                <input type="email" wire:model="correoInstitucional"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    id="correoInstitucional" placeholder="Correo Institucional">
                                @error('correoInstitucional') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="numeroCuenta" class="block text-gray-700 text-sm font-bold mb-2 dark:text-white">Código de docente:</label>
                                <input type="text" wire:model="numeroCuenta"
                                    class="shadow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                    id="numeroCuenta" placeholder="Número de Cuenta">
                                @error('numeroCuenta') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif

                            <div class="input-field">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Nacimiento</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    type="date" id="fechaNacimiento" name="fechaNacimiento" wire:model="fechaNacimiento" required>
                                @error('fechaNacimiento') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sexo</label>
                                <select class="focus:ring-yellow-500 focus:border-yellow-500 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    id="sexo" name="sexo" wire:model="sexo" required>
                                    <option value="" disabled selected>Seleccione su sexo</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="O">Otro</option>
                                </select>
                                @error('sexo') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dirección</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    type="text" placeholder="Ingrese su dirección" id="direccion" name="direccion" wire:model="direccion" required>
                                @error('direccion') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    type="text" placeholder="Ingrese su número de teléfono" id="telefono" name="telefono" wire:model="telefono" required>
                                @error('telefono') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nacionalidad</label>
                                <select class="focus:ring-yellow-500 focus:border-yellow-500 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    id="IdNacionalidad" name="IdNacionalidad" wire:model="IdNacionalidad" required>
                                    <option value="" disabled selected>Seleccione nacionalidad</option>
                                    @foreach($nacionalidades as $nacionalidad)
                                        <option value="{{ $nacionalidad->id }}">{{ $nacionalidad->nombreNacionalidad }}</option>
                                    @endforeach
                                </select>
                                @error('IdNacionalidad') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end gap-4 px-4 py-3 sm:px-6">
                        <button type="button" wire:click="closeModal()" class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 px-4 py-2 text-base font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:ring-gray-600 dark:hover:bg-gray-600">Cancelar</button>
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-yellow-500 px-4 py-2 text-base font-medium text-white shadow-sm ring-1 ring-yellow-600 hover:bg-yellow-600 dark:bg-yellow-600 dark:ring-yellow-600 dark:hover:bg-yellow-700">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
