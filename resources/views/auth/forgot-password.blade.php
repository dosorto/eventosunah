@extends('layouts.base')

@section('content')
    <x-public-auth-header />

    <div class="orbit-auth-stage px-4 py-8 sm:px-6">
        <div class="orbit-auth-ambient orbit-auth-ambient--a left-[-7rem] top-[8rem] h-72 w-72 sm:h-[24rem] sm:w-[24rem]"></div>
        <div class="orbit-auth-ambient orbit-auth-ambient--b right-[-6rem] top-[22%] h-80 w-80 sm:h-[28rem] sm:w-[28rem]"></div>
        <div class="orbit-auth-ambient orbit-auth-ambient--c bottom-[-8rem] left-[30%] h-72 w-72 sm:h-[22rem] sm:w-[22rem]"></div>

        <div class="relative z-10 mx-auto flex min-h-[calc(100vh-9rem)] max-w-3xl items-center justify-center">
            <section class="orbit-panel w-full max-w-xl p-6 sm:p-8">
                <div class="space-y-3 text-center">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-500">Recuperación de acceso</p>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">Restablece tu contraseña</h1>
                    <p class="text-sm leading-7 text-slate-500 dark:text-slate-400">
                        Escribe tu correo electrónico y te enviaremos un enlace para que puedas definir una nueva contraseña.
                    </p>
                </div>

                @session('status')
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-200">
                        {{ $value }}
                    </div>
                @endsession

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-200">
                        Verifica el correo electrónico ingresado e inténtalo nuevamente.
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Correo electrónico <span class="text-red-500">*</span></label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="orbit-input" placeholder="tu-correo@dominio.com">
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('login') }}" class="orbit-button-secondary">
                            Volver al login
                        </a>

                        <button type="submit" class="orbit-button-primary">
                            Recibir enlace
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
