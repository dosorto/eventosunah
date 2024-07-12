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
            max-width: 900px;
            width: 80%;
            border-radius: 6px;
            padding: 30px;
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
            margin: 25px 0;
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
    <div class="container inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
        role="dialog" aria-modal="true" aria-labelledby="modal-headline">
        <h1>Registro Personas</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form first">
                <div class="details personal">
                    <span class="title">Detalles Personales</span>
                    <div class="fields">

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

                        <div class="input-field">
                            <label>Nombre</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500" type="text"
                                placeholder="Ingrese su nombre" id="nombre" name="nombre" wire:model="Nomnre" required>
                            @error('Nombre') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field">
                            <label>Apellido</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500" type="text"
                                placeholder="Ingrese su apellido" id="apellido" name="apellido" wire:model="Apellido"
                                required>
                            @error('Apellido') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field">
                            <label>Correo Electrónico</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500" type="email"
                                placeholder="Ingrese su correo" id="correo" name="correo" wire:model="Correo" required>
                            @error('Correo') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field estudiante" style="display: none;">
                            <label>Correo Institucional</label>
                            <input type="email" placeholder="Ingrese su correo institucional" id="correo_institucional"
                                name="CorreoInstitucional">
                        </div>

                        <div class="input-field estudiante" style="display: none;">
                            <label>Cuenta de Estudiante</label>
                            <input type="text" placeholder="Ingrese su cuenta de estudiante" id="cuenta_estudiante"
                                name="CuentaEstudiante">
                        </div>

                        <div class="input-field docente" style="display: none;">
                            <label>Correo Institucional</label>
                            <input type="email" placeholder="Ingrese su correo institucional"
                                id="correo_institucional_docente" name="correo_institucional_docente">
                        </div>

                        <div class="input-field docente" style="display: none;">
                            <label>Número de Registro</label>
                            <input type="text" placeholder="Ingrese su número de registro" id="numero_registro"
                                name="numero_registro">
                        </div>

                        <div class="input-field">
                            <label>Fecha de Nacimiento</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500" type="date"
                                id="fecha_nacimiento" name="fecha_nacimiento" wire:model="FechaNacimiento" required>
                            @error('FechaNacimiento') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field">
                            <label>Sexo</label>
                            <select class="focus:ring-yellow-500 focus:border-yellow-500" id="sexo" name="sexo"
                                wire:model="IdSexo" required>
                                <option value="" disabled selected>Seleccione su sexo</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                            @error('Sexo') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field">
                            <label>Teléfono</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500" type="tel"
                                placeholder="Ingrese su teléfono" id="telefono" name="telefono" wire:model="Telefono"
                                required>
                            @error('Telefono') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field">
                            <label>Nacionalidad</label>
                            <select class="focus:ring-yellow-500 focus:border-yellow-500" id="id_Nacionalidad"
                                name="id_Nacionalidad" wire:model="IdNacionalidad" required>
                                <option value="" disabled selected>Seleccione su nacionalidad</option>
                                <option value="">Hondureña</option>
                                <option value="">Guatemalteca</option>
                                @error('IdNacionalidad') <span class="text-red-500">{{ $message }}</span> @enderror
                            </select>
                        </div>

                        <div class="input-field">
                            <label>Dirección</label>
                            <input class=" focus:ring-yellow-500 focus:border-yellow-500" id="direccion"
                                name="direccion" placeholder="Escribe tu dirección..." wire:model="direccion"
                                required></input>
                            @error('Direccion') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <button wire:click.prevent="store()" class="submit">
                    <span class="btnText">Registrar</span>
                </button>
            </div>
        </form>
    </div>



    <script>
        const tipoPerfil = document.getElementById('IdTipoPerfil');
        const estudianteFields = document.querySelectorAll('.estudiante');
        const docenteFields = document.querySelectorAll('.docente');

        tipoPerfil.addEventListener('change', () => {
            if (tipoPerfil.value === 'Estudiante') {
                estudianteFields.forEach(field => field.style.display = 'block');
                docenteFields.forEach(field => field.style.display = 'none');
            } else if (tipoPerfil.value === 'Docente') {
                docenteFields.forEach(field => field.style.display = 'block');
                estudianteFields.forEach(field => field.style.display = 'none');
            } else {
                estudianteFields.forEach(field => field.style.display = 'none');
                docenteFields.forEach(field => field.style.display = 'none');
            }
        });
    </script>
</body>

</html>