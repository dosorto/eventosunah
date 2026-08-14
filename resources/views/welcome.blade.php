@extends('layouts.base')

@section('content')
    @php
        $gridClasses = match ($displayMode) {
            'list' => 'space-y-6',
            default => 'grid gap-6 md:grid-cols-2 xl:grid-cols-3',
        };
    @endphp

    <div class="orbit-shell">
        <header x-data="{ publicUserMenuOpen: false, mobileNavOpen: false }" class="sticky top-0 z-30 border-b border-slate-200/70 bg-white/85 backdrop-blur-xl dark:border-white/6 dark:bg-[#0b0b12]/85">
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-[#8c6dff] via-[#7b5cff] to-[#5230cc] shadow-[0_16px_40px_rgba(123,92,255,0.38)]">
                        <img src="{{ asset('Logo/Eventis_Logo.png') }}" alt="Logo" class="h-7 w-7 rounded-xl object-cover" />
                    </div>
                    <div>
                        <p class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">EventIS</p>
                        <p class="text-[11px] uppercase tracking-[0.28em] text-slate-500 dark:text-slate-500">Eventos publicados</p>
                    </div>
                </a>

                <nav class="hidden items-center gap-3 md:flex">
                    <a href="#eventos" class="orbit-button-secondary">
                        Eventos
                    </a>

                    <button
                        type="button"
                        data-theme-toggle
                        class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-white/8 dark:bg-white/[0.03] dark:text-slate-200 dark:hover:bg-white/[0.06]"
                    >
                        <span class="sr-only">Cambiar tema</span>
                        <svg class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12Zm0-16a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V3a1 1 0 0 1 1-1Zm0 18a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1Zm10-9a1 1 0 1 1 0 2h-1a1 1 0 1 1 0-2h1ZM4 11a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2h1Z" />
                        </svg>
                        <svg class="hidden h-5 w-5 dark:block" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 2a8 8 0 1 0 12 10.58A9 9 0 1 1 11.42 2 7.96 7.96 0 0 0 10 2Z" />
                        </svg>
                    </button>

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
                        <a href="{{ route('login') }}" class="orbit-button-secondary">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}" class="orbit-button-primary">
                            Crear cuenta
                        </a>
                    @endauth
                </nav>

                <div class="flex items-center gap-2 md:hidden">
                    <button
                        type="button"
                        data-theme-toggle
                        class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-white/8 dark:bg-white/[0.03] dark:text-slate-200 dark:hover:bg-white/[0.06]"
                    >
                        <span class="sr-only">Cambiar tema</span>
                        <svg class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12Zm0-16a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V3a1 1 0 0 1 1-1Zm0 18a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1Zm10-9a1 1 0 1 1 0 2h-1a1 1 0 1 1 0-2h1ZM4 11a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2h1Z" />
                        </svg>
                        <svg class="hidden h-5 w-5 dark:block" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 2a8 8 0 1 0 12 10.58A9 9 0 1 1 11.42 2 7.96 7.96 0 0 0 10 2Z" />
                        </svg>
                    </button>

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
                    <a href="#eventos" class="orbit-button-secondary">
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
                        <a href="{{ route('login') }}" class="orbit-button-secondary">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}" class="orbit-button-primary">
                            Crear cuenta
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <main class="pb-20">
            <section class="mx-auto max-w-7xl px-4 pt-10 sm:px-6 lg:px-8 lg:pt-14">
                <div class="orbit-panel overflow-hidden p-4 sm:p-5 lg:p-6">
                    <div class="grid gap-5">
                        <form method="GET" action="{{ route('welcome') }}" class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
                            <div class="relative">
                                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 1 0 3.471 9.768l3.63 3.631a.75.75 0 1 0 1.06-1.06l-3.63-3.631A5.5 5.5 0 0 0 8.5 3Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                                </svg>
                                <input
                                    type="text"
                                    name="q"
                                    value="{{ $search }}"
                                    class="orbit-input h-14 rounded-[22px] pl-12 pr-4 text-[15px]"
                                    placeholder="Buscar por nombre, ubicación, organizador o tema..."
                                >
                            </div>

                            <div class="flex lg:flex">
                                <button type="submit" class="orbit-button-primary h-14 px-6">
                                    Buscar
                                </button>
                            </div>

                            <input type="hidden" name="tipo" value="{{ $selectedType }}">
                            @if ($displayMode !== 'card')
                                <input type="hidden" name="vista" value="{{ $displayMode }}">
                            @endif
                        </form>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8">
                <div class="orbit-panel p-5 sm:p-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500 dark:text-slate-400">Filtrar por tipo</p>
                            <h2 class="mt-2 text-2xl font-black tracking-[-0.03em] text-slate-950 dark:text-white">Explora por categoría</h2>
                        </div>

                        <a href="#eventos" class="inline-flex items-center gap-2 text-sm font-semibold text-[#7b5cff] transition hover:text-[#6242ef] dark:text-[#c7bcff]">
                            Ver catálogo
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l5 5a1 1 0 0 1 0 1.414l-5 5a1 1 0 0 1-1.414-1.414L13.586 10H4a1 1 0 1 1 0-2h9.586l-3.293-3.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a
                            href="{{ route('welcome', array_filter(['q' => $search !== '' ? $search : null, 'vista' => $displayMode !== 'card' ? $displayMode : null])) }}"
                            class="{{ $selectedType === 'todos' ? 'orbit-button-primary' : 'orbit-button-secondary' }} px-5 py-3 text-sm"
                        >
                            Todos
                        </a>

                        @foreach ($availableTypes as $eventType)
                            <a
                                href="{{ route('welcome', array_filter(['q' => $search !== '' ? $search : null, 'tipo' => $eventType, 'vista' => $displayMode !== 'card' ? $displayMode : null])) }}"
                                class="{{ $selectedType === $eventType ? 'orbit-button-primary' : 'orbit-button-secondary' }} px-5 py-3 text-sm"
                            >
                                {{ $eventType }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="eventos" class="mx-auto max-w-7xl px-4 pt-12 sm:px-6 lg:px-8">
                <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500 dark:text-slate-400">Catálogo público</p>
                        <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-slate-950 dark:text-white">
                            {{ $selectedType !== 'todos' ? $selectedType : 'Todos los eventos' }}
                        </h2>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-500 shadow-sm dark:border-white/8 dark:bg-white/[0.03] dark:text-slate-400">
                        Mostrando <span class="font-semibold text-slate-900 dark:text-white">{{ $Eventos->count() }}</span> de <span class="font-semibold text-slate-900 dark:text-white">{{ $Eventos->total() }}</span> eventos
                    </div>
                </div>

                <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Ordenados desde los más próximos hasta los posteriores.
                    </p>

                    <div class="flex flex-wrap gap-2">
                        @foreach ([
                            'card' => 'Card',
                            'list' => 'Lista',
                        ] as $mode => $label)
                            <a
                                href="{{ route('welcome', array_filter(['q' => $search !== '' ? $search : null, 'tipo' => $selectedType !== 'todos' ? $selectedType : null, 'vista' => $mode !== 'card' ? $mode : null])) }}"
                                class="{{ $displayMode === $mode ? 'orbit-button-primary' : 'orbit-button-secondary' }} px-4 py-2.5 text-sm"
                            >
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                @if ($Eventos->isEmpty())
                    <div class="orbit-panel px-6 py-16 text-center">
                        <p class="text-xl font-semibold text-slate-900 dark:text-slate-100">No encontramos eventos con esos filtros.</p>
                        <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Prueba con otra palabra clave o cambia la categoría seleccionada.</p>
                    </div>
                @else
                    <div class="{{ $gridClasses }}">
                        @foreach ($Eventos as $evento)
                            @php
                                $today = now()->startOfDay();
                                $start = optional($evento->fechainicio)?->startOfDay();
                                $end = optional($evento->fechafinal)?->startOfDay() ?? $start;
                                $statusLabel = 'Próximamente';
                                $statusClasses = 'bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300';

                                if ($start && $end && $start->lte($today) && $end->gte($today)) {
                                    $statusLabel = 'Abierto';
                                    $statusClasses = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300';
                                } elseif ($end && $end->lt($today)) {
                                    $statusLabel = 'Finalizado';
                                    $statusClasses = 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300';
                                }
                            @endphp

                            <article
                                role="link"
                                tabindex="0"
                                aria-label="Ver detalle de {{ $evento->nombreevento }}"
                                onclick="window.location.href='{{ route('evento', ['evento' => $evento->id]) }}'"
                                onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location.href='{{ route('evento', ['evento' => $evento->id]) }}'; }"
                                class="orbit-panel group cursor-pointer overflow-hidden transition focus:outline-none focus:ring-4 focus:ring-[#7b5cff]/20 hover:-translate-y-1 hover:border-[#7b5cff]/30 hover:shadow-[0_44px_120px_-50px_rgba(123,92,255,0.45)] {{ $displayMode === 'list' ? 'lg:flex' : '' }}"
                            >
                                <div class="relative">
                                    @if ($evento->banner_url)
                                        <img src="{{ $evento->banner_url }}" alt="{{ $evento->nombreevento }}" class="h-56 w-full object-cover {{ $displayMode === 'list' ? 'lg:h-full lg:w-80 lg:min-w-80' : '' }}">
                                    @elseif ($evento->logo_url)
                                        <img src="{{ $evento->logo_url }}" alt="{{ $evento->nombreevento }}" class="h-56 w-full object-cover {{ $displayMode === 'list' ? 'lg:h-full lg:w-80 lg:min-w-80' : '' }}">
                                    @else
                                        <div class="flex h-56 items-center justify-center bg-[linear-gradient(135deg,_#082f49_0%,_#0f172a_48%,_#ca8a04_100%)] px-6 text-center text-white {{ $displayMode === 'list' ? 'lg:h-full lg:w-80 lg:min-w-80' : '' }}">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-200">{{ $evento->tipoEvento?->tipo ?? 'Evento' }}</p>
                                                <p class="mt-3 text-2xl font-black">{{ $evento->nombreevento }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="absolute inset-x-0 top-0 flex items-start justify-between p-4">
                                        <span class="rounded-full {{ $statusClasses }} px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em]">
                                            {{ $statusLabel }}
                                        </span>

                                        <span class="rounded-full bg-white/90 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-700 shadow-sm dark:bg-slate-950/90 dark:text-slate-200">
                                            {{ $evento->tipoEvento?->tipo ?? 'Evento' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex flex-1 flex-col p-6">
                                    <div class="flex flex-wrap gap-2">
                                        @php
                                            $isPaidEvent = $evento->tipo_acceso === 'pagada';
                                        @endphp
                                        <span @class([
                                            'inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-[11px] font-black uppercase tracking-[0.2em] text-white shadow-lg',
                                            'border-rose-200 bg-gradient-to-r from-rose-500 via-orange-500 to-amber-500 shadow-rose-500/20' => $isPaidEvent,
                                            'border-emerald-200 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 shadow-emerald-500/20' => ! $isPaidEvent,
                                        ])>
                                            <span class="h-2 w-2 rounded-full bg-white/90 shadow-sm"></span>
                                            {{ $isPaidEvent ? 'Evento pagado' : 'Acceso gratuito' }}
                                        </span>
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">
                                            {{ $evento->conferencias_count }} {{ \Illuminate\Support\Str::plural('actividad', $evento->conferencias_count) }}
                                        </span>
                                    </div>

                                    <div class="mt-4 flex items-start gap-3">
                                        <div class="flex-1">
                                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#7b5cff] dark:text-[#c7bcff]">
                                                {{ $evento->tipoEvento?->tipo ?? 'Evento' }}
                                            </p>
                                            <h3 class="mt-2 text-2xl font-black tracking-[-0.03em] text-slate-950 transition group-hover:text-[#7b5cff] dark:text-white dark:group-hover:text-[#c7bcff]">
                                                {{ $evento->nombreevento }}
                                            </h3>
                                        </div>

                                        <div x-data="{ open: false }" class="relative shrink-0">
                                            <button
                                                type="button"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:text-[#7b5cff] dark:border-white/8 dark:bg-white/[0.03] dark:text-slate-300"
                                                @mouseenter="open = true"
                                                @mouseleave="open = false"
                                                @click.stop="open = ! open"
                                            >
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 10A8 8 0 1 1 2 10a8 8 0 0 1 16 0Zm-7-3a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm2 8a1 1 0 1 1-2 0v-4a1 1 0 0 1 2 0v4Z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <div
                                                x-cloak
                                                x-show="open"
                                                x-transition
                                                @mouseenter="open = true"
                                                @mouseleave="open = false"
                                                class="absolute right-0 top-10 z-20 w-72 rounded-2xl border border-slate-200 bg-white p-3 text-xs leading-6 text-slate-600 shadow-[0_20px_60px_rgba(15,23,42,0.16)] dark:border-white/8 dark:bg-[#12131d] dark:text-slate-300"
                                            >
                                                {{ $evento->descripcion }}
                                            </div>
                                        </div>
                                    </div>

                                    <p class="mt-3 min-h-[3.5rem] text-sm leading-7 text-slate-600 dark:text-slate-300">
                                        {{ \Illuminate\Support\Str::words($evento->descripcion, 10, '...') }}
                                    </p>

                                    <div class="mt-auto">
                                        <div class="mt-5 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                            <p><span class="font-semibold text-slate-950 dark:text-white">Organiza:</span> {{ $evento->organizador }}</p>
                                            <p><span class="font-semibold text-slate-950 dark:text-white">Lugar:</span> {{ $evento->localidad_display }}</p>
                                            <p><span class="font-semibold text-slate-950 dark:text-white">Fechas:</span> {{ optional($evento->fechainicio)->format('d/m/Y') ?? 'Pendiente' }} @if($evento->fechafinal) - {{ $evento->fechafinal->format('d/m/Y') }} @endif</p>
                                        </div>

                                        <div class="mt-6 flex items-center justify-between gap-3">
                                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                                Publicado {{ $evento->published_at?->translatedFormat('d M Y') ?? optional($evento->created_at)->translatedFormat('d M Y') }}
                                            </p>

                                            <a href="{{ route('evento', ['evento' => $evento->id]) }}" class="orbit-button-primary">
                                                Ver evento
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $Eventos->links() }}
                    </div>
                @endif
            </section>
        </main>
    </div>
@endsection
