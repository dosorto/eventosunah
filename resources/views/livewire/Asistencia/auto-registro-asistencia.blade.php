<div class="min-h-[calc(100vh-6rem)] bg-slate-50 px-4 py-10 dark:bg-slate-950">
    <div class="mx-auto max-w-3xl rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0_25px_80px_rgba(15,23,42,0.12)] dark:border-slate-800 dark:bg-slate-900 sm:p-8">
        <div class="flex flex-col items-center text-center">
            <div class="flex h-20 w-20 items-center justify-center rounded-full {{ $status === 'success' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300' : 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-300' }}">
                @if($status === 'success')
                    <svg class="h-10 w-10" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 0 1 .006 1.414l-7.25 7.313a1 1 0 0 1-1.42.006L3.29 9.273a1 1 0 1 1 1.414-1.414l4.04 4.04 6.54-6.604a1 1 0 0 1 1.42-.006Z" clip-rule="evenodd" />
                    </svg>
                @else
                    <svg class="h-10 w-10" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.485 2.495a1.75 1.75 0 0 1 3.03 0l6.28 10.87A1.75 1.75 0 0 1 16.28 16H3.72a1.75 1.75 0 0 1-1.515-2.635l6.28-10.87ZM10 6a.75.75 0 0 0-.75.75v3.5a.75.75 0 0 0 1.5 0v-3.5A.75.75 0 0 0 10 6Zm0 7a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z" clip-rule="evenodd" />
                    </svg>
                @endif
            </div>

            <p class="mt-6 text-xs font-semibold uppercase tracking-[0.26em] {{ $status === 'success' ? 'text-emerald-600 dark:text-emerald-300' : 'text-red-600 dark:text-red-300' }}">
                {{ $status === 'success' ? 'Asistencia registrada' : 'No se pudo registrar' }}
            </p>
            <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-950 dark:text-white">{{ $message }}</h1>
        </div>

        @if($conference && $event)
            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950/50">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Evento</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $event->nombreevento }}</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950/50">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Conferencia</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $conference->nombre }}</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950/50">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Fecha y hora</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">
                        {{ $conference->fecha ? \Illuminate\Support\Carbon::parse($conference->fecha)->format('d/m/Y') : 'Sin fecha' }}
                        ·
                        {{ $conference->horaInicio ? \Illuminate\Support\Carbon::parse($conference->horaInicio)->format('h:i a') : '--:--' }}
                    </p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950/50">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Lugar</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $conference->lugar ?: $event->localidad_nombre ?: 'Por definir' }}</p>
                </div>
            </div>
        @endif

        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('eventoVista') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                Ver eventos
            </a>
            <a href="{{ route('mi-historial') }}" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                Mis inscripciones
            </a>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                Ir al dashboard
            </a>
        </div>
    </div>
</div>
