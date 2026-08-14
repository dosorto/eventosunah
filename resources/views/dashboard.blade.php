<x-layouts.app>
    @php
        $iconMap = [
            'badge' => '<path d="M12 2 4 5v6c0 5 3.41 9.41 8 10.79C16.59 20.41 20 16 20 11V5l-8-3Zm0 5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5Z" />',
            'spark' => '<path d="M11 2h2l1.3 4.2L19 7.5l-3 2.8.8 4.2L12 12.8 7.2 14.5 8 10.3 5 7.5l4.7-1.3L11 2Z" /><path d="M5 16h2l.8 2.5L10 19l-2 1.5L8.5 23 6 21.8 3.5 23 4 20.5 2 19l2.2-.5L5 16Z" />',
            'grid' => '<path d="M4 4h7v7H4V4Zm9 0h7v7h-7V4ZM4 13h7v7H4v-7Zm9 0h7v7h-7v-7Z" />',
            'calendar' => '<path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v3H2V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Z" /><path d="M2 12h20v7a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-7Z" />',
            'presentation' => '<path d="M4 3h16a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-6l2 4h-2l-2-4h-2l-2 4H6l2-4H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm2 4v5h12V7H6Z" />',
            'mic' => '<path d="M12 15a4 4 0 0 0 4-4V7a4 4 0 1 0-8 0v4a4 4 0 0 0 4 4Z" /><path d="M5 11a1 1 0 0 1 2 0 5 5 0 0 0 10 0 1 1 0 1 1 2 0 7 7 0 0 1-6 6.93V21h3a1 1 0 1 1 0 2H8a1 1 0 1 1 0-2h3v-3.07A7 7 0 0 1 5 11Z" />',
            'check' => '<path d="M9 11.5 11 13.5 15.5 9 17 10.5 11 16.5 7.5 13 9 11.5Z" /><path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" />',
            'certificate' => '<path d="M7 4h10a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2h-3l-2 2-2-2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z" />',
            'users' => '<path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm-7 8a7 7 0 0 1 14 0Z" />',
            'shield' => '<path d="M12 2 4 5v6c0 5 3.41 9.41 8 10.79C16.59 20.41 20 16 20 11V5l-8-3Z" />',
            'lock' => '<path d="M7 10V7a5 5 0 0 1 10 0v3h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h1Zm2 0h6V7a3 3 0 0 0-6 0v3Z" />',
            'rocket' => '<path d="M14 3c3.5 0 7 3.5 7 7 0 2.5-1 4.5-3 6l-4.5-1.5L12 19l-1.5-1.5L6 16c-2-1.5-3-3.5-3-6 0-3.5 3.5-7 7-7 1.3 0 2.8.3 4 .9Z" />',
        ];
    @endphp

    <section class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
            <div class="orbit-panel overflow-hidden p-6 lg:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-500">Hola, {{ \Illuminate\Support\Str::of(Auth::user()->name)->before(' ') }}</p>
                        <h1 class="mt-3 max-w-2xl text-4xl font-semibold tracking-tight text-slate-900 dark:text-white lg:text-5xl">
                            {{ $isParticipantDashboard ? 'Tu portal personal de eventos' : 'Tu centro de control administrativo' }}
                        </h1>
                        <p class="mt-4 max-w-2xl text-[15px] leading-7 text-slate-600 dark:text-slate-400">
                            {{ $isParticipantDashboard
                                ? 'Desde aqui puedes revisar tus eventos inscritos, acceder al portal publico, consultar tu historial y administrar tu perfil dentro del sistema.'
                                : 'Este inicio se adapta a tu rol: muestra tus capacidades activas, accesos disponibles y el estado general del sistema para que llegues mas rapido a cada tarea.' }}
                        </p>
                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            @if ($quickActions->isNotEmpty())
                                <a href="{{ $quickActions->first()['route'] }}" class="orbit-button-primary">
                                    Abrir {{ $quickActions->first()['label'] }}
                                </a>
                            @endif
                            <a href="{{ route('profile.show') }}" class="orbit-button-secondary">Ver perfil</a>
                        </div>
                    </div>

                    <div class="orbit-panel-soft max-w-sm p-5">
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Hoy</p>
                        <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $now->translatedFormat('l') }}</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $now->translatedFormat('d \\d\\e F \\d\\e Y') }}</p>
                        <div class="mt-6 h-2 rounded-full bg-slate-200 dark:bg-white/6">
                            <div class="h-2 rounded-full bg-gradient-to-r from-[#7b5cff] via-[#8d74ff] to-[#24c8db]" style="width: {{ $ocupacionEventos }}%"></div>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-sm text-slate-600 dark:text-slate-400">
                            <span>{{ $isParticipantDashboard ? 'Progreso de actividad' : 'Eventos activos' }}</span>
                            <span class="font-semibold text-emerald-600 dark:text-emerald-300">{{ $ocupacionEventos }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ($userStats as $stat)
                    <article class="orbit-metric">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.22em] text-slate-500">{{ $stat['label'] }}</p>
                                <p class="mt-4 text-2xl font-semibold text-slate-900 dark:text-white">{{ $stat['value'] }}</p>
                                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $stat['meta'] }}</p>
                            </div>
                            <div class="rounded-2xl bg-[#7b5cff]/12 p-3 text-[#7b5cff] dark:bg-[#7b5cff]/15 dark:text-[#b8a8ff]">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">{!! $iconMap[$stat['icon']] !!}</svg>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        @if ($quickActions->isNotEmpty())
            <section class="orbit-panel p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Accesos</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">Accesos rapidos segun tu rol</h2>
                    </div>
                    <span class="rounded-full border border-[#7b5cff]/20 bg-[#7b5cff]/10 px-3 py-1 text-xs font-medium text-[#7b5cff] dark:text-[#c7bcff]">
                        {{ $quickActions->count() }} disponibles
                    </span>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($quickActions as $action)
                        <a href="{{ $action['route'] }}" class="orbit-panel-soft group flex items-start gap-4 p-5 transition hover:-translate-y-0.5 hover:border-[#7b5cff]/30">
                            <div class="rounded-2xl bg-[#7b5cff]/12 p-3 text-[#7b5cff] dark:bg-[#7b5cff]/15 dark:text-[#b8a8ff]">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">{!! $iconMap[$action['icon']] !!}</svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-base font-semibold text-slate-900 transition group-hover:text-[#6b4ff5] dark:text-white dark:group-hover:text-[#c7bcff]">{{ $action['label'] }}</p>
                                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $action['description'] }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <div class="grid gap-6 xl:grid-cols-[1fr_1fr]">
            <div class="grid gap-4 sm:grid-cols-2">
                <article class="orbit-metric">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-500">{{ $isParticipantDashboard ? 'Eventos disponibles' : 'Eventos' }}</p>
                            <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">{{ $cantidadEventos }}</p>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                                {{ $isParticipantDashboard ? $eventosActivos . ' vigentes y ' . $eventosFinalizados . ' ya finalizados en tu historial' : $eventosActivos . ' activos y ' . $eventosFinalizados . ' finalizados' }}
                            </p>
                        </div>
                        <div class="rounded-2xl bg-[#7b5cff]/12 p-3 text-[#7b5cff] dark:bg-[#7b5cff]/15 dark:text-[#b8a8ff]">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">{!! $iconMap['calendar'] !!}</svg>
                        </div>
                    </div>
                </article>

                <article class="orbit-metric">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-500">{{ $isParticipantDashboard ? 'Conferencias asociadas' : 'Conferencias' }}</p>
                            <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">{{ $cantidadConferencias }}</p>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ $isParticipantDashboard ? 'Agenda vinculada a tus eventos inscritos' : 'Agenda vinculada a los eventos registrados' }}</p>
                        </div>
                        <div class="rounded-2xl bg-emerald-500/12 p-3 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-300">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">{!! $iconMap['presentation'] !!}</svg>
                        </div>
                    </div>
                </article>

                <article class="orbit-metric">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-500">{{ $isParticipantDashboard ? 'Mis inscripciones' : 'Inscripciones' }}</p>
                            <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">{{ $cantidadInscripciones }}</p>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ $isParticipantDashboard ? 'Registros acumulados en eventos del sistema' : 'Participaciones acumuladas en conferencias' }}</p>
                        </div>
                        <div class="rounded-2xl bg-cyan-500/12 p-3 text-cyan-600 dark:bg-cyan-500/15 dark:text-cyan-300">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">{!! $iconMap['users'] !!}</svg>
                        </div>
                    </div>
                </article>

                <article class="orbit-metric">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.22em] text-slate-500">{{ $isParticipantDashboard ? 'Eventos pagados' : 'Usuarios' }}</p>
                            <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">{{ $totalUsuarios }}</p>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                                {{ $isParticipantDashboard ? $eventosPresenciales . ' presenciales y ' . $eventosVirtuales . ' virtuales en tu actividad' : $eventosPresenciales . ' presenciales y ' . $eventosVirtuales . ' virtuales' }}
                            </p>
                        </div>
                        <div class="rounded-2xl bg-amber-500/12 p-3 text-amber-600 dark:bg-amber-500/15 dark:text-amber-300">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">{!! $iconMap['shield'] !!}</svg>
                        </div>
                    </div>
                </article>
            </div>

            <section class="orbit-panel p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Actividad</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ $isParticipantDashboard ? 'Mis registros recientes' : 'Mayor demanda' }}</h2>
                    </div>
                    @if (! $isParticipantDashboard)
                        <span class="rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-600 dark:text-emerald-300">
                            Top 5
                        </span>
                    @endif
                </div>

                <div class="mt-6 space-y-3">
                    @if ($isParticipantDashboard)
                        @forelse ($participantRegistrations->take(5) as $registro)
                            <article class="orbit-panel-soft flex items-center gap-4 p-4">
                                <div class="h-14 w-14 overflow-hidden rounded-2xl border border-slate-200 bg-white dark:border-white/6 dark:bg-white/[0.03]">
                                    @if($registro->evento?->logo_url)
                                        <img src="{{ $registro->evento->logo_url }}" alt="{{ $registro->evento?->nombreevento }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-xs uppercase tracking-[0.16em] text-slate-500">Event</div>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-medium text-slate-900 dark:text-white">{{ $registro->evento?->nombreevento ?? 'Evento' }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ optional($registro->evento?->fechainicio)->translatedFormat('d M Y') ?: 'Sin fecha' }}</p>
                                </div>

                                <div class="rounded-2xl bg-slate-100 px-3 py-2 text-right dark:bg-white/[0.04]">
                                    <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Estado</p>
                                    <p class="mt-1 text-sm font-semibold text-emerald-600 dark:text-emerald-300">{{ \Illuminate\Support\Str::headline($registro->estado ?? 'registrado') }}</p>
                                </div>
                            </article>
                        @empty
                            <div class="orbit-panel-soft p-8 text-center text-sm text-slate-500">
                                Aun no tienes eventos registrados.
                            </div>
                        @endforelse
                    @else
                    @forelse ($conferenciass as $suscripcion)
                        <article class="orbit-panel-soft flex items-center gap-4 p-4">
                            <div class="h-14 w-14 overflow-hidden rounded-2xl border border-slate-200 bg-white dark:border-white/6 dark:bg-white/[0.03]">
                                @if($suscripcion->foto)
                                    <img
                                        src="{{ \Illuminate\Support\Str::startsWith($suscripcion->foto, ['http://', 'https://']) ? $suscripcion->foto : asset(str_replace('public', 'storage', $suscripcion->foto)) }}"
                                        alt="{{ $suscripcion->nombre }}"
                                        class="h-full w-full object-cover"
                                    >
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-xs uppercase tracking-[0.16em] text-slate-500">Talk</div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="truncate font-medium text-slate-900 dark:text-white">{{ $suscripcion->nombre }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ \Carbon\Carbon::parse($suscripcion->fecha)->translatedFormat('d M Y') }}</p>
                            </div>

                            <div class="rounded-2xl bg-slate-100 px-3 py-2 text-right dark:bg-white/[0.04]">
                                <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Inscritos</p>
                                <p class="mt-1 text-lg font-semibold text-emerald-600 dark:text-emerald-300">{{ $suscripcion->unique_subscriptions }}</p>
                            </div>
                        </article>
                    @empty
                        <div class="orbit-panel-soft p-8 text-center text-sm text-slate-500">
                            No hay inscripciones registradas todavia.
                        </div>
                    @endforelse
                    @endif
                </div>
            </section>
        </div>

        <section class="orbit-panel p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Agenda</p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ $isParticipantDashboard ? 'Conferencias de mis eventos' : 'Proximas conferencias' }}</h2>
                </div>
                <a href="{{ $isParticipantDashboard ? route('eventoVista') : route('conferencia') }}" class="orbit-button-secondary">Ver todas</a>
            </div>

            <div class="mt-6 overflow-hidden rounded-[24px] border border-slate-200 dark:border-white/6">
                <table class="orbit-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Conferencia</th>
                            <th>Evento</th>
                            <th>Conferencista</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($conferencias as $index => $conferencia)
                            <tr>
                                <td class="text-slate-500">{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($conferencia->fecha)->translatedFormat('d M Y') }}</td>
                                <td>
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $conferencia->nombre }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $conferencia->horaInicio }} - {{ $conferencia->horaFin }}</p>
                                </td>
                                <td>{{ $conferencia->evento?->nombreevento ?? 'Sin evento' }}</td>
                                <td>
                                    {{ $conferencia->conferencista?->persona?->nombre ?? 'Sin asignar' }}
                                    {{ $conferencia->conferencista?->persona?->apellido ?? '' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-500">No hay conferencias registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </section>
</x-layouts.app>
