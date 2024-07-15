<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Registro Persona</title>
  
</head>

<body>
    <header>
        <nav class="navbar">
            <a class="logo" href="#">EVENTOS <span>UNAH</span></a>
            <ul class="menu-links">
                <span id="close-menu-btn" class="material-symbols-outlined">close</span>
                <li><a href="/">Home</a></li>
            </ul>
            <span id="hamburger-btn" class="material-symbols-outlined">menu</span>
        </nav>
    </header>

    <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
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
                        <span class="title">Detalles Personales</span>
                        <div class="fields">
                            <div class="input-field">
                                <label>Tipo Perfil</label>
                                <select class="focus:ring-yellow-500 focus:border-yellow-500" id="IdTipoPerfil"
                                    name="IdTipoPerfil" wire:model="IdTipoPerfil" required>
                                    <option value="" disabled selected>Seleccione tipo perfil</option>
                                    @foreach($tipoperfiles as $tipoPerfil)
                                        <option value="{{ $tipoPerfil->id }}">{{ $tipoPerfil->tipoperfil }}</option>
                                    @endforeach
                                </select>
                                @error('IdTipoPerfil') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label>Usuario</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500" type="text"
                                    placeholder="Ingrese su Usuario" id="IdUsuario" name="dni" wire:model="IdUsuario" required>
                                @error('IdUsuario') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label>DNI</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500" type="text"
                                    placeholder="Ingrese su DNI" id="dni" name="dni" wire:model="dni" required>
                                @error('dni') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label>Nombre</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500" type="text"
                                    placeholder="Ingrese su nombre" id="nombre" name="nombre" wire:model="nombre"
                                    required>
                                @error('nombre') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label>Apellido</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500" type="text"
                                    placeholder="Ingrese su apellido" id="apellido" name="apellido"
                                    wire:model="apellido" required>
                                @error('apellido') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label>Correo Electrónico</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500" type="email"
                                    placeholder="Ingrese su correo" id="correo" name="correo" wire:model="correo"
                                    required>
                                @error('correo') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field estudiante">
                                <label>Correo Institucional</label>
                                <input type="email" placeholder="Ingrese su correo institucional"
                                    id="correoInstitucional" name="correoInstitucional"
                                    wire:model="correoInstitucional">
                                @error('correoInstitucional') <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field estudiante">
                                <label>Número de cuenta</label>
                                <input type="text" placeholder="Ingrese su cuenta de estudiante" id="numeroCuenta"
                                    name="numeroCuenta" wire:model="numeroCuenta">
                                @error('numeroCuenta') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label>Fecha de Nacimiento</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500" type="date"
                                    id="fechaNacimiento" name="fechaNacimiento" wire:model="fechaNacimiento"
                                    required>
                                @error('fechaNacimiento') <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field">
                                <label>Sexo</label>
                                <select class="focus:ring-yellow-500 focus:border-yellow-500" id="sexo" name="sexo"
                                    wire:model="sexo" required>
                                    <option value="" disabled selected>Seleccione su sexo</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                                @error('sexo') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label>Teléfono</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500" type="tel"
                                    placeholder="Ingrese su teléfono" id="telefono" name="telefono"
                                    wire:model="telefono" required>
                                @error('telefono') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field">
                                <label>Nacionalidad</label>
                                <select class="focus:ring-yellow-500 focus:border-yellow-500" id="IdNacionalidad"
                                    name="IdNacionalidad" wire:model="IdNacionalidad" required>
                                    <option value="" disabled selected>Seleccione su nacionalidad</option>
                                    @foreach($nacionalidades as $nacionalidad)
                                        <option value="{{ $nacionalidad->id }}">{{ $nacionalidad->nombreNacionalidad }}</option>
                                    @endforeach
                                </select>
                                @error('IdNacionalidad') <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field full-width">
                                <label>Dirección</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500" id="direccion"
                                    name="direccion" placeholder="Escribe tu dirección..." wire:model="direccion"
                                    required>
                                @error('direccion') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-800">
                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <button type="submit"
                                class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-yellow-500 text-base leading-6 font-medium text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Guardar
                            </button>
                        </span>
                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                            <button type="button" wire:click="closeModal()"
                                class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Cancelar
                            </button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



</body>

</html>
