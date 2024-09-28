<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registro</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
    <!-- Agrega tus estilos CSS personalizados aquí -->
    <style>
        /* ===== Google Font Import - Poformsins ===== */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /*  background: linear-gradient(to bottom, #1254a1 23%, #facc15 95%);*/

        }


        .containerRegister {
            position: relative;
            max-width: 910px;
            height: 570px;
            width: 90%;
            border-radius: 6px;
            padding: 40px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .containerRegister h1 {
            position: relative;
            font-size: 18px;
            font-weight: 500;
            
        }

        .containerRegister form {
            position: relative;
            margin-top: 16px;
            min-height: 440px;
            overflow: hidden;
        }

        .containerRegister form .formRegister {
            position: absolute;
            transition: 0.2s ease;
        }

        .containerRegister form .formRegister.second {
            opacity: 0;
            pointer-events: none;
            transform: translateX(100%);
        }

        .containerRegister form.secActive .formRegister.second {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(0);
        }

        .containerRegister form.secActive .formRegister.first {
            opacity: 0;
            pointer-events: none;
            transform: translateX(-100%);
        }

        .containerRegister form .title {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 500;
            margin: 6px 0;
            color: #facc15;
        }

        .containerRegister form .fields {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .containerRegister form .fields .inputRegister {
            display: flex;
            width: calc(100% / 3 - 15px);
            flex-direction: column;
            margin: 4px 0;
        }


        .containerRegister form .fields .inputRegister .direccion {
            display: flex;
            width: 840px;
            flex-direction: column;
            margin: 4px 0;
        }

        .containerRegister .inputRegister label {
            font-size: 12px;
            font-weight: 500;
        }

        .containerRegister .inputRegister input,
        select,
        textarea {
            outline: none;
            font-size: 14px;
            font-weight: 400;
            border-radius: 5px;
            border: 1px solid #aaa;
            padding: 0 15px;
            height: 42px;
            margin: 8px 0;
        }

        .containerRegister .inputRegister input :focus,
        .containerRegister .inputRegister select:focus .inputRegister textarea:focus {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.13);
        }

       

        .containerRegister form button,
        .backBtn {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 45px;
            max-width: 266px;
            width: 100%;
            border: none;
            outline: none;
            
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

        .containerRegister form .btnText {
            font-weight: 500;
            font-size: 14px;
        }

        .containerRegister form button:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(202 138 4 / var(--tw-bg-opacity))
                /* #ca8a04 */
            ;
        }

        .containerRegister form button i,
        form .backBtn i {
            margin: 0 6px;
        }

        .containerRegister form .backBtn i {
            transformRegister: rotate(180deg);
        }

        .containerRegister form .buttons {
            display: flex;
            align-items: center;
        }

        .containerRegister form .buttons button,
        .backBtn {
            margin-right: 14px;
        }

        @media (max-width: 750px) {
            .containerRegister form {
                overflow-y: scroll;
            }

            .containerRegister form::-webkit-scrollbar {
                display: none;
            }

            .containerRegister form .fields .inputRegister {
                width: calc(100% / 2 - 15px);
            }
        }

        @media (max-width: 550px) {
            .containerRegister form .fields .inputRegister {
                width: 100%;
            }
        }

        .max {
            max-width: 430px;
        }

        .container {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            width: 900px;
            margin: 0 20px;
        }

        .container .forms {
            display: flex;
            align-items: center;
            height: 540px;
            width: 200%;
            transition: height 0.2s ease;
        }


        .container .form {
            width: 50%;
            height: 540px;
            padding: 30px;
            transition: margin-left 0.10s ease;
        }

        .container.active .login {
            margin-left: -50%;
            opacity: 0;
            transition: margin-left 0.10s ease, opacity 0.12s ease;
        }

        .container .signup {

            opacity: 0;
            transition: opacity 0.08s ease;
        }

        .container.active .signup {
            opacity: 1;
            transition: opacity 0.2s ease;
        }

        .container.active .forms {
            height: 600px;
        }

        .container .form .title {
            position: relative;
            font-size: 27px;
            font-weight: 600;
        }

        .container .form .title::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 30px;
            background-color: #1254a1;
            border-radius: 25px;
        }

        .container .form .input-field {
            position: relative;
            height: 50px;
            width: 100%;
            margin-top: 30px;
        }

        .container .input-field input {
            position: absolute;
            height: 100%;
            width: 100%;
            padding: 0 35px;
            border: none;
            outline: none;
            font-size: 16px;
            border-bottom: 2px solid #000;
            border-top: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .container .input-field input:is(:focus, :valid) {
            border-bottom-color: #facc15;
        }

        .container .input-field i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 23px;
            transition: all 0.2s ease;
        }

        .container .input-field input:is(:focus, :valid)~i {
            color: #facc15;
        }

        .input-field i.icon {
            left: 0;
        }

        .container .input-field i.showHidePw {
            right: 0;
            cursor: pointer;
            padding: 10px;
        }

        .container .form .checkbox-text {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .container .checkbox-text .checkbox-content {
            display: flex;
            align-items: center;
        }

        .container .checkbox-content input {
            margin-right: 10px;
            accent-color: #facc15;
        }

        .container .form .text {
            color: #333;
            font-size: 14px;
        }

        .container .form a.text {
            color: #1254a1;
            text-decoration: none;
        }

        .container .form a:hover {
            text-decoration: underline;
        }

        .container .form .button {
            margin-top: 35px;
        }

        .container .form .button input {
            border: none;
            font-size: 17px;
            font-weight: 500;
            letter-spacing: 1px;
            border-radius: 6px;
            --tw-bg-opacity: 1;
            background-color: rgb(250 204 21 / var(--tw-bg-opacity))
                /* #facc15 */
            ;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .container .button input:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(202 138 4 / var(--tw-bg-opacity))
                /* #ca8a04 */
            ;
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
            color: #facc15;
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
            color: #facc15;
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
@extends('layouts.login-layout')
@section('styles')
 {{-- <link rel="stylesheet" href="{{ asset('css/fondo.css') }}"> --}} 
<link rel="stylesheet" href="{{ asset('css/loginStyles.css') }}">
@endsection
<body class="bg-gray-100 dark:bg-gray-900 text-black dark:text-white">
    <!-- Registro Usuario -->
    
    <div class="form signup containerRegister bg-white dark:bg-gray-800">
        <span class="title">Registro De Usuario</span>


        <form method="POST" action="{{ route('nueva-persona') }}">
            @csrf

            <!-- Campo oculto para el ID del user -->
            <input type="hidden" name="User" id="User" value="{{ $user }}">
            <input type="hidden" name="UserC" id="UserC" value="{{ $password }}">

            <div class="formRegister first">
                <div class="details personal">
                    <h1 class="text-black dark:text-white">Detalles Personales</h1>
                    <div class="fields">
                        <div class="inputRegister">
                            <label class="text-black dark:text-white">DNI</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-300" type="text" placeholder="Ingrese su DNI" id="dni" name="dni" wire:model="dni" required>
                            @error('dni') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="inputRegister">
                            <label class="text-black dark:text-white">Nombre</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-300" type="text" placeholder="Ingrese su nombre" id="nombre" name="nombre" value="{{ $user->name }}" required>
                            @error('nombre') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="inputRegister">
                            <label class="text-black dark:text-white">Apellido</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-300" type="text" placeholder="Ingrese su apellido" id="apellido" name="apellido" wire:model="apellido" required>
                            @error('apellido') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="inputRegister">
                            <label class="text-black dark:text-white">Correo Electrónico</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-300" type="email" placeholder="Ingrese su correo" id="correo" name="correo" value="{{ $user->email }}" required>
                            @error('correo') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="inputRegister">
                            <label class="text-black dark:text-white">Fecha de Nacimiento</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-300" type="date" id="fecha_nacimiento" name="fechaNacimiento" wire:model="fechaNacimiento" required>
                            @error('fechaNacimiento') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="inputRegister">
                            <label class="text-black dark:text-white">Sexo</label>
                            <select class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-300" id="sexo" name="sexo" wire:model="sexo" required>
                                <option class="text-black dark:text-white" value="" disabled selected>Seleccione su sexo</option>
                                <option class="text-black dark:text-white" value="M">Masculino</option>
                                <option class="text-black dark:text-white" value="F">Femenino</option>
                            </select>
                            @error('sexo') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="inputRegister">
                            <label class="text-black dark:text-white">Teléfono</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-300" type="tel" placeholder="Ingrese su teléfono" id="telefono" name="telefono" wire:model="telefono" required>
                            @error('telefono') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="inputRegister">
                            <label class="text-black dark:text-white">Nacionalidad</label>
                            <select class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-300" id="IdNacionalidad" name="IdNacionalidad" wire:model="IdNacionalidad" required>
                                <option value="" disabled selected>Seleccione su nacionalidad</option>
                                @if(isset($nacionalidades) && count($nacionalidades) > 0)
                                    @foreach($nacionalidades as $nacionalidad)
                                        <option class="text-black dark:text-white" value="{{ $nacionalidad->id }}">{{ $nacionalidad->nombreNacionalidad }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No hay nacionalidades disponibles</option>
                                @endif
                            </select>
                            @error('IdNacionalidad') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="inputRegister">
                            <label class="text-black dark:text-white">Dirección</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-300" id="direccion" name="direccion" placeholder="Escribe tu dirección..." required></input>
                            @error('direccion') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="inputRegister">
                            <label class="text-black dark:text-white">Tipo Perfil</label>
                            <select class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-300" id="IdTipoPerfil" name="IdTipoPerfil" wire:model="TipoPerfil" required>
                                <option class="text-black dark:text-white" value="" disabled selected>Seleccione su tipo de perfil</option>
                                @if(isset($tipoperfiles) && count($tipoperfiles) > 0)
                                    @foreach($tipoperfiles as $tipoperfil)
                                        <option class="text-black dark:text-white" value="{{ $tipoperfil->id }}">{{ $tipoperfil->tipoperfil }}</option>
                                    @endforeach
                                @else
                                    <option class="text-black dark:text-white" value="" disabled>No hay tipos de perfil disponibles</option>
                                @endif
                            </select>
                            @error('IdTipoPerfil') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="inputRegister estudiante" style="display: none;">
                            <label id="correoLabel" class="text-black dark:text-white">Correo Institucional</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-300" type="email" placeholder="Ingrese su correo institucional" id="correo_institucional" name="correo_institucional">
                            @error('CorreoInstitucional') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="inputRegister estudiante" style="display: none;">
                            <label id="numeroLabel" class="text-black dark:text-white">Número de cuenta</label>
                            <input class="focus:ring-yellow-500 focus:border-yellow-500 bg-gray-100 dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-300" type="text" placeholder="Ingrese su cuenta de estudiante" id="cuenta_estudiante" name="cuenta_estudiante">
                            @error('Cuenta') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit bg-yellow-500 hover:bg-yellow-600 text-black dark:bg-yellow-600 dark:hover:bg-yellow-700">
                    <span class="btnText">Registrar</span>
                </button>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tipoPerfil = document.getElementById('IdTipoPerfil');
            const estudianteFields = document.querySelectorAll('.estudiante');
            const label1 = document.getElementById('correoLabel');
            const input1 = document.getElementById('correo_institucional');
            const label2 = document.getElementById('numeroLabel');
            const input2 = document.getElementById('cuenta_estudiante');

            tipoPerfil.addEventListener('change', () => {
                if (tipoPerfil.value === '1') {
                    estudianteFields.forEach(field => field.style.display = 'flex');
                    label1.textContent = 'Correo Institucional';
                    input1.placeholder = 'Ingrese correo institucional';
                    label2.textContent = 'Número de Cuenta';
                    input2.placeholder = 'Número de cuenta';
                } else if (tipoPerfil.value === '3') {
                    estudianteFields.forEach(field => field.style.display = 'flex');
                    label1.textContent = 'Correo Institucional';
                    input1.placeholder = 'Ingrese correo Institucional';
                    label2.textContent = 'Identificación de docente';
                    input2.placeholder = 'Numero de empleado';
                } else {
                    estudianteFields.forEach(field => field.style.display = 'none');
                }
            });
        });
    </script>
</body>

</html>