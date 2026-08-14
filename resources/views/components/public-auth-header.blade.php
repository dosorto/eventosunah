<header x-data="{ publicUserMenuOpen: false, mobileNavOpen: false }" class="sticky top-0 z-30 border-b border-slate-200/70 bg-white/85 backdrop-blur-xl dark:border-white/6 dark:bg-[#0b0b12]/85">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('welcome') }}" class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-[#8c6dff] via-[#7b5cff] to-[#5230cc] shadow-[0_16px_40px_rgba(123,92,255,0.38)]">
                <img src="{{ asset('Logo/Eventis_Logo.png') }}" alt="Logo" class="h-7 w-7 rounded-xl object-cover" />
            </div>
            <div>
                <p class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">EventIS</p>
                <p class="text-[11px] uppercase tracking-[0.28em] text-slate-500 dark:text-slate-500">Acceso al sistema</p>
            </div>
        </a>

        <nav class="hidden items-center gap-3 md:flex">
            <a href="{{ route('welcome') }}" class="orbit-button-secondary">
                Eventos
            </a>

            @auth
                <div class="relative" @click.outside="publicUserMenuOpen = false">
                    <button
                        type="button"
                        class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-2 py-1.5 text-left shadow-sm transition hover:bg-slate-50 dark:border-white/8 dark:bg-white/[0.03] dark:hover:bg-white/[0.06]"
                        @click="publicUserMenuOpen = ! publicUserMenuOpen"
                    >
                        <img
                            class="h-10 w-10 rounded-2xl object-cover"
                            src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&amp;color=fff&amp;background=7b5cff"
                            alt="{{ auth()->user()->name }}"
                        >
                        <div class="max-w-[12rem]">
                            <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-slate-500 dark:text-slate-400">Sesión activa</p>
                        </div>
                    </button>

                    <div
                        x-cloak
                        x-show="publicUserMenuOpen"
                        x-transition.origin.top.right
                        class="absolute right-0 mt-3 w-64 rounded-3xl border border-slate-200 bg-white p-2 shadow-[0_30px_80px_rgba(15,23,42,0.15)] dark:border-white/8 dark:bg-[#12131d] dark:shadow-[0_30px_80px_rgba(0,0,0,0.45)]"
                    >
                        <a href="{{ route('dashboard') }}" class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                            Mi dashboard
                        </a>
                        <a href="{{ route('profile.show') }}" class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                            Mi perfil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center rounded-2xl px-4 py-3 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-300 dark:hover:bg-red-500/10">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            @else
                @if (! request()->routeIs('login'))
                    <a href="{{ route('login') }}" class="orbit-button-secondary">
                        Iniciar sesión
                    </a>
                @endif

                @if (! request()->routeIs('register'))
                    <a href="{{ route('register') }}" class="orbit-button-primary">
                        Crear cuenta
                    </a>
                @endif
            @endauth
        </nav>

        <div class="flex items-center gap-2 md:hidden">
            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/8 dark:bg-white/[0.03]">
                    <img
                        class="h-9 w-9 rounded-2xl object-cover"
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&amp;color=fff&amp;background=7b5cff"
                        alt="{{ auth()->user()->name }}"
                    >
                </a>
            @endif

            <button
                type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm dark:border-white/8 dark:bg-white/[0.03] dark:text-slate-200"
                @click="mobileNavOpen = ! mobileNavOpen"
            >
                <span class="sr-only">Abrir navegación</span>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>
        </div>
    </div>

    <div x-cloak x-show="mobileNavOpen" x-transition class="border-t border-slate-200/70 px-4 py-4 md:hidden dark:border-slate-800/70">
        <div class="flex flex-col gap-2">
            <a href="{{ route('welcome') }}" class="orbit-button-secondary">
                Eventos
            </a>

            @auth
                <a href="{{ route('dashboard') }}" class="orbit-button-primary">
                    Mi dashboard
                </a>
                <a href="{{ route('profile.show') }}" class="orbit-button-secondary">
                    Mi perfil
                </a>
            @else
                @if (! request()->routeIs('login'))
                    <a href="{{ route('login') }}" class="orbit-button-secondary">
                        Iniciar sesión
                    </a>
                @endif

                @if (! request()->routeIs('register'))
                    <a href="{{ route('register') }}" class="orbit-button-primary">
                        Crear cuenta
                    </a>
                @endif
            @endauth
        </div>
    </div>
</header>
