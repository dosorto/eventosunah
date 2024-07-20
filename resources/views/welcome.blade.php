@extends('layouts.base')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/welcome.css')}}">
@endsection

@section('content')
    <section>
        <header>
            <nav class="navbar">
                <a class="logo" href="#">EVENTOS <span>UNAH</span></a>
                <ul class="menu-links">
                    <span id="close-menu-btn" class="material-symbols-outlined">close</span>
                    <li><a href="/login">Acceder</a></li>
                    <li><a href="/register">Registrarse</a></li>
                </ul>
                <span id="hamburger-btn" class="material-symbols-outlined">menu</span>
            </nav>
        </header>

        <section class="hero-section">
            <div class="content">
                <h2>Gestión De Eventos De UNAH-Choluteca</h2>
                <p>
                    La gestión de eventos es una parte fundamental de la administración de una organización.
                </p>
                <button><a href="/login" style="text-decoration: none; color: #000; font-weight: 500;">Acceder
                        ahora</a></button>
            </div>
        </section>


    </section>
@endsection


@section('scripts')
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
