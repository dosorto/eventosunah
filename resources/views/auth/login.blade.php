@extends('layouts.login-layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/loginStyles.css') }}">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
@endsection
<body class="dark:bg-gray-900">
@section('app-content')
<x-nav />
<br>
<div class="container max">
    <br>
    {{-- Alerta de error de Flowbite --}}
    @if ($errors->any())
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-red-800 dark:text-red-300" role="alert">
            <span class="font-medium">Error:</span> Las credenciales ingresadas son incorrectas. Por favor, inténtalo de
            nuevo.
        </div>
    @endif
    <div class="forms">

        <div class="form login active bg-white rounded-md dark:bg-gray-800">

            <span class="title text-black dark:text-white">Iniciar sesión</span>



            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-field">
                    <input type="email" id="email" name="email" placeholder="Correo electrónico" required
                        class="dark:bg-gray-800 dark:text-white border-b-2 border-black dark:border-yellow-500 placeholder-gray-500 dark:placeholder-gray-300">
                    <i class="uil uil-envelope text-black dark:text-white icon"></i>
                </div>
                <div class="input-field">
                    <input type="password" id="password" name="password" placeholder="Contraseña" required
                        class="password dark:bg-gray-800 dark:text-white border-b-2 border-black dark:border-yellow-500 placeholder-gray-500 dark:placeholder-gray-300">
                    <i class="uil uil-lock icon text-black dark:text-white"></i>
                    <i class="uil uil-eye-slash text-black dark:text-white showHidePw"></i>
                </div>

                <div class="checkbox-text">
                    <div class="checkbox-content">
                        <input type="checkbox" id="remember_me" name="remember">
                        <label for="remember_me" class="text text-black dark:text-white">Recuérdame</label>
                    </div>

                    <a href="{{ route('password.request') }}" class="text text-blue-800 dark:text-white">¿No
                        recuerdas la contraseña?</a>
                </div>

                <div class="input-field button">
                    <input type="submit" value="Iniciar"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white dark:bg-yellow-600 dark:hover:bg-yellow-700">
                </div>
            </form>

            <div class="login-signup">
                <span class="text text-black dark:text-white">¿No tienes cuenta?
                    <a href="/register" class="text text-blue-800 dark:text-white signup-link">Registrarse</a>
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

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
