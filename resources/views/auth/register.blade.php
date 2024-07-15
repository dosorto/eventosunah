<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Registro Persona</title>
    <style>
        /* ===== Google Font Import - Poppins ===== */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to bottom, #1254a1 23%, #facc15 95%);
        }

        .focus\:ring-yellow-500:focus {
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb(234 179 8 / var(--tw-ring-opacity))
                /* #eab308 */
            ;
        }

        .focus\:border-yellow-500:focus {
            --tw-border-opacity: 1;
            border-color: rgb(234 179 8 / var(--tw-border-opacity))
                /* #eab308 */
            ;
        }

        .container {
            position: relative;
            max-width: 910px;
            width: 90%;
            border-radius: 6px;
            padding: 40px;
            margin: 0 15px;
            background-color: #fff;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            position: relative;
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .container h1::before {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px;
            height: 3px;
            width: 27px;
            border-radius: 8px;
            background-color: #4070f4;
        }

        .container form {
            position: relative;
            margin-top: 16px;
            min-height: 440px;
            background-color: #fff;
            overflow: hidden;
        }

        .container form .form {
            position: absolute;
            background-color: #fff;
            transition: 0.3s ease;
        }

        .container form .form.second {
            opacity: 0;
            pointer-events: none;
            transform: translateX(100%);
        }

        form.secActive .form.second {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(0);
        }

        form.secActive .form.first {
            opacity: 0;
            pointer-events: none;
            transform: translateX(-100%);
        }

        .container form .title {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 500;
            margin: 6px 0;
            color: #333;
        }

        .container form .fields {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        form .fields .input-field {
            display: flex;
            width: calc(100% / 3 - 15px);
            flex-direction: column;
            margin: 4px 0;
        }


        form .fields .input-field .direccion {
            display: flex;
            width: 840px;
            flex-direction: column;
            margin: 4px 0;
        }

        .input-field label {
            font-size: 12px;
            font-weight: 500;
            color: #2e2e2e;
        }

        .input-field input,
        select,
        textarea {
            outline: none;
            font-size: 14px;
            font-weight: 400;
            color: #333;
            border-radius: 5px;
            border: 1px solid #aaa;
            padding: 0 15px;
            height: 42px;
            margin: 8px 0;
        }

        .input-field input :focus,
        .input-field select:focus .input-field textarea:focus {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.13);
        }

        .input-field select,
        .input-field input .input-field textarea[type="date"] {
            color: #707070;
        }

        .input-field input[type="date"]:valid {
            color: #333;
        }

        .container form button,
        .backBtn {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 45px;
            max-width: 200px;
            width: 100%;
            border: none;
            outline: none;
            color: #000;
            font-weight: 500;
            border-radius: 5px;
            margin: 18px 0;
            --tw-bg-opacity: 1;
            background-color: rgb(250 204 21 / var(--tw-bg-opacity))
                /* #facc15 */
            ;
            transition: all 0.3s linear;
            cursor: pointer;
        }

        .container form .btnText {
            font-weight: 500;
            font-size: 14px;
        }

        form button:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(202 138 4 / var(--tw-bg-opacity))
                /* #ca8a04 */
            ;
        }

        form button i,
        form .backBtn i {
            margin: 0 6px;
        }

        form .backBtn i {
            transform: rotate(180deg);
        }

        form .buttons {
            display: flex;
            align-items: center;
        }

        form .buttons button,
        .backBtn {
            margin-right: 14px;
        }

        @media (max-width: 750px) {
            .container form {
                overflow-y: scroll;
            }

            .container form::-webkit-scrollbar {
                display: none;
            }

            form .fields .input-field {
                width: calc(100% / 2 - 15px);
            }
        }

        @media (max-width: 550px) {
            form .fields .input-field {
                width: 100%;
            }
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            z-index: 1;
        }

        header .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }

        .navbar .logo {
            color: #fff;
            font-weight: 600;
            font-size: 2.1rem;
            text-decoration: none;
        }

        .navbar .logo span {
            --tw-bg-opacity: 1;
            color: rgb(250 204 21 / var(--tw-bg-opacity))
                /* #facc15 */
            ;
        }

        .navbar .menu-links {
            display: flex;
            list-style: none;
            gap: 35px;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .navbar a:hover {
            --tw-bg-opacity: 1;
            color: rgb(250 204 21 / var(--tw-bg-opacity))
                /* #facc15 */
            ;
        }

        #close-menu-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            cursor: pointer;
            display: none;
        }

        #hamburger-btn {
            color: #fff;
            cursor: pointer;
            display: none;
        }

        @media (max-width: 768px) {
            header {
                padding: 10px;
            }

            header.show-mobile-menu::before {
                content: "";
                position: fixed;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                backdrop-filter: blur(5px);
            }

            .navbar .logo {
                font-size: 1.7rem;
            }


            #hamburger-btn,
            #close-menu-btn {
                display: block;
            }

            .navbar .menu-links {
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                height: 100vh;
                background: #fff;
                flex-direction: column;
                padding: 70px 40px 0;
                transition: left 0.2s ease;
            }

            header.show-mobile-menu .navbar .menu-links {
                left: 0;
            }

            .navbar a {
                color: #000;
            }
        }
    </style>
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
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
    <div class="container">
        <h1>Registro Personas</h1>

        <form method="POST" action="{{ route('register') }}">
        <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Modal backdrop -->
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

<<<<<<< HEAD
        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form wire:submit.prevent="store">
            @csrf <!-- CSRF Token -->
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
=======
                        <div class="input-field">
                            <label>Tipo Perfil</label>
                            <select class="focus:ring-yellow-500 focus:border-yellow-500" id="IdTipoPerfil"
                                name="IdTipoPerfil" wire:model="TipoPerfil" required>
                                <option value="" disabled selected>Seleccione tipo perfil</option>
                                <option value="Estudiante">Estudiante</option>
                                <option value="Docente">Docente</option>
                                <option value="Externo">Externo</option>
                            </select>
                            @error('IdTipoPerfil') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field">
                            <label>DNI</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500" type="text"
                                placeholder="Ingrese su DNI" id="dni" name="dni" wire:model="DNI" required>
                            @error('DNI') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
>>>>>>> origin/acxel

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
                                <select class="focus:ring-yellow-500 focus:border-yellow-500" id="IdUsuario"
                                    name="IdUsuario" wire:model="IdUsuario" required>
                                    <option value="" disabled selected>Seleccione su usuario</option>
                                    @foreach($user as $users)
                                        <option value="{{ $users->id }}">{{ $users->email }}</option>
                                    @endforeach
                                </select>
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

<<<<<<< HEAD
                            <div class="input-field">
                                <label>Correo Electrónico</label>
                                <input class="focus:ring-yellow-500 focus:border-yellow-500" type="email"
                                    placeholder="Ingrese su correo" id="correo" name="correo" wire:model="correo"
                                    required>
                                @error('correo') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
=======

>>>>>>> origin/acxel

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
            </form>
        </div>
    </div>
</div>
</body>

</html>


    <script>
        const tipoPerfil = document.getElementById('IdTipoPerfil');
        const estudianteFields = document.querySelectorAll('.estudiante');

        tipoPerfil.addEventListener('change', () => {
            if (tipoPerfil.value === 'Estudiante') {
                estudianteFields.forEach(field => field.style.display = 'flex');
            } else if (tipoPerfil.value === 'Docente') {
                estudianteFields.forEach(field => field.style.display = 'flex');
            } else {
                estudianteFields.forEach(field => field.style.display = 'none');
            }
        });
    </script>
    <script>
        const header = document.querySelector("header");
        const hamburgerBtn = document.querySelector("#hamburger-btn");
        const closeMenuBtn = document.querySelector("#close-menu-btn");

        // Toggle mobile menu on hamburger button click
        hamburgerBtn.addEventListener("click", () => header.classList.toggle("show-mobile-menu"));

        // Close mobile menu on close button click
        closeMenuBtn.addEventListener("click", () => hamburgerBtn.click());
    </script>
</body>

</html>