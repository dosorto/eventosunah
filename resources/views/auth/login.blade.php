@extends('layouts.login-layout')

@section('app-content')

    <header>
        <nav class="navbar">
            <a class="logo" href="#">EVENTOS <span>UNAH</span></a>
            <ul class="menu-links">
                <span id="close-menu-btn" class="material-symbols-outlined">close</span>
                <li><a href="/">Home</a></li>
                <li><a href="/register">Registrarse</a></li>
            </ul>
            <span id="hamburger-btn" class="material-symbols-outlined">menu</span>
        </nav>
    </header>
    <div class="container max ">
        <div class="forms">
            <div class="form login active dark:bg-gray-900">
                <span class="title">Iniciar sesión</span>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-field">
                        <input type="email" id="email" name="email" placeholder="Correo electrónico" required
                            autofocus>
                        <i class="uil uil-envelope icon"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" id="password" name="password" class="password" placeholder="Contraseña"
                            required>
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw"></i>
                    </div>

                    <div class="checkbox-text">
                        <div class="checkbox-content">
                            <input type="checkbox" id="remember_me" name="remember">
                            <label for="remember_me" class="text">Recuérdame</label>
                        </div>

                        <a href="{{ route('password.request') }}" class="text">¿No recuerdas la contraseña?</a>
                    </div>

                    <div class="input-field button">
                        <input type="submit" value="Iniciar">
                    </div>
                </form>

                <div class="login-signup">
                    <span class="text">¿No tienes cuenta?
                        <a href="/register" class="text signup-link">Registrarse</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
    
@endsection