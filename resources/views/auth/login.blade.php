@extends('layouts.base')

@section('content')
    <x-public-auth-header />

    <div class="orbit-auth-stage px-4 py-8 sm:px-6">
        <div class="orbit-auth-ambient orbit-auth-ambient--a left-[-6rem] top-[-4rem] h-72 w-72 sm:h-96 sm:w-96"></div>
        <div class="orbit-auth-ambient orbit-auth-ambient--b right-[-5rem] top-[12%] h-80 w-80 sm:h-[26rem] sm:w-[26rem]"></div>
        <div class="orbit-auth-ambient orbit-auth-ambient--c bottom-[-7rem] left-[18%] h-72 w-72 sm:h-[24rem] sm:w-[24rem]"></div>

        <div class="relative z-10 flex min-h-[calc(100vh-9rem)] items-center justify-center">
            <section class="orbit-panel w-full max-w-md p-6 sm:p-8">
                <div class="space-y-2 text-center">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-500">Acceso seguro</p>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">Bienvenido de nuevo</h1>
                    <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Ingresa con tu correo y contraseña para continuar en el sistema.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-200">
                        Las credenciales ingresadas son incorrectas. Verifica la información e inténtalo de nuevo.
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Correo electrónico <span class="text-red-500">*</span></label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="orbit-input" placeholder="tu-correo@dominio.com">
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Contraseña <span class="text-red-500">*</span></label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-emerald-600 transition hover:text-emerald-500 dark:text-emerald-300">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        <div class="relative">
                            <input id="password" name="password" x-bind:type="showPassword ? 'text' : 'password'" required autocomplete="current-password" class="orbit-input pr-20" placeholder="Ingresa tu contraseña">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-4 inline-flex items-center text-sm font-medium text-slate-500 transition hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                                <span x-text="showPassword ? 'Ocultar' : 'Mostrar'"></span>
                            </button>
                        </div>
                    </div>

                    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-white/8 dark:bg-white/[0.03] dark:text-slate-300">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-400">
                        Recuérdame en este equipo
                    </label>

                    <button type="submit" class="orbit-button-primary w-full">
                        Ingresar
                    </button>
                </form>

                <div class="mt-6 rounded-[1.5rem] border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-600 dark:border-white/8 dark:bg-white/[0.03] dark:text-slate-300">
                    ¿Aún no tienes cuenta?
                    <a href="{{ route('register') }}" class="font-semibold text-emerald-600 transition hover:text-emerald-500 dark:text-emerald-300">
                        Regístrate aquí
                    </a>
                </div>
            </section>
        </div>
    </div>
@endsection
