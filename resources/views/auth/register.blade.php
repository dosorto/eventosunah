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

            /* background: linear-gradient(to bottom, #1254a1 23%, #facc15 95%);*/

        }


        .containersRegister {
            position: relative;
            max-width: 910px;
            height: 570px;
            width: 90%;
            border-radius: 6px;
            padding: 40px;
            background-color: #ffffff;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .containersRegister h1 {
            position: relative;
            font-size: 18px;
            font-weight: 500;
            color: #333;
        }

        .containersRegister form {
            position: relative;
            margin-top: 16px;
            min-height: 440px;
            background-color: #ffffff;
            overflow: hidden;
        }

        .containersRegister form .formRegister {
            position: absolute;
            background-color: #ffffff;
            transition: 0.2s ease;
        }

        .containersRegister form .formRegister.second {
            opacity: 0;
            pointer-events: none;
            transform: translateX(100%);
        }

        .containersRegister form.secActive .formRegister.second {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(0);
        }

        .containersRegister form.secActive .formRegister.first {
            opacity: 0;
            pointer-events: none;
            transform: translateX(-100%);
        }

        .containersRegister form .title {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 500;
            margin: 6px 0;
            color: #facc15;
        }

        .containersRegister form .fields {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .containersRegister form .fields .inputRegister {
            display: flex;
            width: calc(100% / 3 - 15px);
            flex-direction: column;
            margin: 4px 0;
        }


        .containersRegister form .fields .inputRegister .direccion {
            display: flex;
            width: 840px;
            flex-direction: column;
            margin: 4px 0;
        }

        .containersRegister .inputRegister label {
            font-size: 12px;
            font-weight: 500;
            color: #2e2e2e;
        }

        .containersRegister .inputRegister input,
        select,
        textarea {
            outline: none;
            font-size: 14px;
            font-weight: 400;
            color: #333;
            border-radius: 5px;
            border: 1px solid;
            padding: 0 15px;
            height: 42px;
            margin: 8px 0;
        }

        .containersRegister .inputRegister input :focus,
        .containersRegister .inputRegister select:focus .inputRegister textarea:focus {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.13);
        }

        .containersRegister form button,
        .backBtn {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 45px;
            max-width: 266px;
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

        .containersRegister form .btnText {
            font-weight: 500;
            font-size: 14px;
        }

        .containersRegister form button:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(202 138 4 / var(--tw-bg-opacity))
                /* #ca8a04 */
            ;
        }

        .containersRegister form button i,
        form .backBtn i {
            margin: 0 6px;
        }

        .containersRegister form .backBtn i {
            transform: rotate(180deg);
        }

        .containersRegister form .buttons {
            display: flex;
            align-items: center;
        }

        .containersRegister form .buttons button,
        .backBtn {
            margin-right: 14px;
            margin-left: 10px;
        }

        @media (max-width: 750px) {
            .containersRegister form {
                overflow-y: scroll;
            }

            .containersRegister form::-webkit-scrollbar {
                display: none;
            }

            .containersRegister form .fields .inputRegister {
                width: calc(100% / 2 - 15px);
            }
        }

        @media (max-width: 550px) {
            .containersRegister form .fields .inputRegister {
                width: 100%;
            }
        }

        .max {
            max-width: 430px;
        }

        .containers {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            width: 900px;
            margin: 0 20px;
        }

        .containers .forms {
            display: flex;
            align-items: center;
            height: 540px;
            width: 200%;
            transition: height 0.2s ease;
        }


        .containers .form {
            width: 50%;
            height: 540px;
            padding: 30px;
            background-color: #fff;
            transition: margin-left 0.10s ease;
        }

        .containers.active .login {
            margin-left: -50%;
            opacity: 0;
            transition: margin-left 0.10s ease, opacity 0.12s ease;
        }

        .containers .signup {

            opacity: 0;
            transition: opacity 0.08s ease;
        }

        .containers.active .signup {
            opacity: 1;
            transition: opacity 0.2s ease;
        }

        .containers.active .forms {
            height: 600px;
        }

        .containers .form .title {
            position: relative;
            font-size: 27px;
            font-weight: 600;
        }

        .containers .form .title::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 30px;
            background-color: #1254a1;
            border-radius: 25px;
        }

        .containers .form .input-field {
            position: relative;
            height: 50px;
            width: 100%;
            margin-top: 30px;
        }

        .containers .input-field input {
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

        .containers .input-field input:is(:focus, :valid) {
            border-bottom-color: #facc15;
        }

        .containers .input-field i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 23px;
            transition: all 0.2s ease;
        }

        .containers .input-field input:is(:focus, :valid)~i {
            color: #facc15;
        }

        .input-field i.icon {
            left: 0;
        }

        .containers .input-field i.showHidePw {
            right: 0;
            cursor: pointer;
            padding: 10px;
        }

        .containers .form .checkbox-text {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .containers .checkbox-text .checkbox-content {
            display: flex;
            align-items: center;
        }

        .containers .checkbox-content input {
            margin-right: 10px;
            accent-color: #facc15;
        }

        .containers .form .text {
            color: #333;
            font-size: 14px;
        }

        .containers .form a.text {
            color: #1254a1;
            text-decoration: none;
        }

        .containers .form a:hover {
            text-decoration: underline;
        }

        .containers .form .button {
            margin-top: 35px;
        }

        .containers .form .button input {
            border: none;
            color: #000;
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

        .containers .button input:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(202 138 4 / var(--tw-bg-opacity))
                /* #ca8a04 */
            ;
        }
    </style>
    @section('styles')
   {{-- <link rel="stylesheet" href="{{ asset('css/fondo.css') }}"> --}} 
    <link rel="stylesheet" href="{{ asset('css/loginStyles.css') }}">
    @endsection
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    @extends('layouts.login-layout')

    <x-nav />

    <div class="containers max mt-20 bg-white dark:bg-gray-800">
        <div class="forms bg-white dark:bg-gray-800">
            <div class="form login active bg-white dark:bg-gray-800">
                <span class="title text-black dark:text-white">Registro De Usuario</span>

                <form method="POST" action="{{ route('registerpost') }}">
                    @csrf
                    <div class="input-field">
                        <input type="text" placeholder="Nombre completo" id="name" name="name" required
                            class="placeholder-gray-500 dark:placeholder-gray-300 dark:bg-gray-800 dark:text-white border-b-2 border-black dark:border-yellow-500">
                        <i class="uil uil-user text-black dark:text-white"></i>
                    </div>
                    <div class="input-field">
                        <input type="email" placeholder="Correo electrónico" id="email" name="email" required
                            class="placeholder-gray-500 dark:placeholder-gray-300 dark:bg-gray-800 dark:text-white border-b-2 border-black dark:border-yellow-500">
                        <i class="uil uil-envelope icon text-black dark:text-white"></i>
                    </div>
                    <div class="input-field">
                        <input type="password"
                            class="placeholder-gray-500 dark:placeholder-gray-300 password dark:bg-gray-800 dark:text-white border-b-2 border-black dark:border-yellow-500"
                            placeholder="Crear contraseña" id="password" name="password" required>
                        <i class="uil uil-lock icon text-black dark:text-white"></i>
                    </div>
                    <div class="input-field">
                        <input type="password"
                            class="password placeholder-gray-500 dark:placeholder-gray-300 dark:bg-gray-800 dark:text-white border-b-2 border-black dark:border-yellow-500"
                            placeholder="Confirmar contraseña" id="password_confirmation" name="password_confirmation"
                            autocomplete="new-password" required>
                        <i class="uil uil-lock text-black dark:text-white icon"></i>
                        <i class="uil uil-eye-slash text-black dark:text-white showHidePw"></i>
                    </div>
                    
                    <div class="input-field button text">
                        <input type="submit" style="text-align: center;" value="Siguiente"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white dark:bg-yellow-600 dark:hover:bg-yellow-700">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tipoPerfil = document.getElementById('IdTipoPerfil');
        const estudianteFields = document.querySelectorAll('.estudiante');
        const label1 = document.getElementById('correoLabel');
        const input1 = document.getElementById('correo_institucional');
        const label2 = document.getElementById('numeroLabel');
        const input2 = document.getElementById('cuenta_estudiante');

        tipoPerfil.addEventListener('change', () => {
            if (tipoPerfil.value === 'Estudiante') {
                estudianteFields.forEach(field => field.style.display = 'flex');
                label1.textContent = 'Correo Institucional';
                input1.placeholder = 'Ingrese correo institucional';
                label2.textContent = 'Número de Cuenta';
                input2.placeholder = 'Número de cuenta';
            } else if (tipoPerfil.value === 'Docente') {
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

<script>
    const pwShowHide = document.querySelectorAll(".showHidePw");
    const pwFields = document.querySelectorAll(".password");

    pwShowHide.forEach(eyeIcon => {
        eyeIcon.addEventListener("click", () => {
            pwFields.forEach(pwField => {
                if (pwField.type === "password") {
                    pwField.type = "text";
                    eyeIcon.classList.replace("uil-eye-slash", "uil-eye");
                } else {
                    pwField.type = "password";
                    eyeIcon.classList.replace("uil-eye", "uil-eye-slash");
                }
            });
        });
    });

    const signUp = document.querySelector(".signup-link");
    const login = document.querySelector(".login-link");
    const containers = document.querySelector(".containers");

    signUp.addEventListener("click", () => {
        containers.classList.add("active");
        containers.classList.remove("max");
    });

    login.addEventListener("click", () => {
        containers.classList.remove("active");
        containers.classList.add("max");

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