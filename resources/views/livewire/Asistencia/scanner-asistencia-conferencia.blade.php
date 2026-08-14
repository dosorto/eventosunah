<div
    x-data="{
        supported: 'BarcodeDetector' in window && !! navigator.mediaDevices?.getUserMedia,
        stream: null,
        detector: null,
        running: false,
        modalOpen: false,
        init() {
            window.addEventListener('attendance-scan-resolved', () => this.forceCloseModal());
            window.addEventListener('attendance-confirmed', () => this.forceCloseModal());
        },
        openModal() {
            this.modalOpen = true;
            this.$nextTick(() => this.start());
        },
        closeModal() {
            this.stop();
            this.modalOpen = false;
            $wire.clearPendingParticipant();
        },
        forceCloseModal() {
            this.stop();
            this.modalOpen = false;
        },
        async start() {
            if (! this.supported || this.running) {
                return;
            }

            try {
                this.running = true;
                await this.$nextTick();

                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: { ideal: 'environment' }
                    },
                    audio: false,
                });

                if (! this.$refs.video) {
                    this.running = false;
                    return;
                }

                this.$refs.video.srcObject = this.stream;
                await this.$refs.video.play().catch(() => {});
                this.detector = new BarcodeDetector({ formats: ['qr_code'] });
                this.scanLoop();
            } catch (error) {
                this.stop();
                this.running = false;
            }
        },
        stop() {
            this.running = false;

            if (this.stream) {
                this.stream.getTracks().forEach((track) => track.stop());
                this.stream = null;
            }

            if (this.$refs.video) {
                this.$refs.video.srcObject = null;
            }
        },
        async scanLoop() {
            if (! this.running || ! this.detector || ! this.$refs.video) {
                return;
            }

            try {
                const codes = await this.detector.detect(this.$refs.video);

                if (codes.length > 0 && codes[0].rawValue) {
                    const scannedCode = codes[0].rawValue;
                    this.forceCloseModal();

                    try {
                        await $wire.processQrCode(scannedCode);
                    } finally {
                        this.forceCloseModal();
                    }
                    return;
                }
            } catch (error) {
            }

            setTimeout(() => this.scanLoop(), 450);
        },
    }"
    x-on:attendance-scan-resolved.window="stop(); modalOpen = false"
    x-on:attendance-confirmed.window="stop(); modalOpen = false"
    x-init="init()"
    class="space-y-6"
>
    <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Lectura QR</p>
            <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ $conferencia->nombre }}</h2>
            <div class="mt-3 flex flex-wrap gap-3 text-sm text-slate-500 dark:text-slate-400">
                <span>{{ $conferencia->evento->nombreevento }}</span>
                <span>{{ $conferencia->fecha ? \Illuminate\Support\Carbon::parse($conferencia->fecha)->format('d/m/Y') : 'Sin fecha' }}</span>
                <span>{{ $conferencia->horaInicio ? \Illuminate\Support\Carbon::parse($conferencia->horaInicio)->format('h:i a') : '--' }} - {{ $conferencia->horaFin ? \Illuminate\Support\Carbon::parse($conferencia->horaFin)->format('h:i a') : '--' }}</span>
                <span>{{ $conferencia->lugar ?: 'Lugar por definir' }}</span>
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">
            <div class="relative min-w-[18rem]">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 1 0 3.471 9.768l3.63 3.631a.75.75 0 1 0 1.06-1.06l-3.63-3.631A5.5 5.5 0 0 0 8.5 3Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar asistencias registradas..."
                    class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                >
            </div>

            <a
                href="{{ route('asistencia.evento', ['evento' => $conferencia->evento->id]) }}"
                class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
            >
                Volver a conferencias
            </a>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.1fr)_360px]">
        <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_25px_70px_rgba(15,23,42,0.08)] dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Escáner de gafetes</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Lee el QR del participante para registrar su asistencia en esta conferencia.</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button
                        type="button"
                        x-show="supported"
                        @click="openModal()"
                        class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500"
                    >
                        Escanear QR
                    </button>
                </div>
            </div>

            <div class="mt-4 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-950/50 dark:text-slate-400">
                <p x-show="supported">La cámara se abre dentro de una modal al pulsar el botón de escaneo. Luego podrás confirmar la asistencia antes de guardarla.</p>
                <p x-show="! supported">Este navegador no soporta lectura directa de QR. Usa el campo manual de abajo para escribir o pegar el código del gafete.</p>
            </div>

            <div class="mt-5 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                <h4 class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-500">Ingreso manual</h4>
                <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                    <input
                        type="text"
                        wire:model.defer="manualCode"
                        placeholder="Ej. REG-20-145"
                        class="min-w-0 flex-1 rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-violet-400 dark:focus:ring-violet-500/10"
                    >
                    <button
                        type="button"
                        wire:click="submitManualCode"
                        class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-500"
                    >
                        Escanear código
                    </button>
                </div>
            </div>
        </section>

        <aside class="space-y-5">
            <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_25px_70px_rgba(15,23,42,0.08)] dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Estado del escaneo</h3>
                <div class="mt-4 rounded-2xl border px-4 py-4 text-sm {{ $feedbackType === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200' : ($feedbackType === 'warning' ? 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200' : ($feedbackType === 'error' ? 'border-red-200 bg-red-50 text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-200' : 'border-slate-200 bg-slate-50 text-slate-600 dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-300')) }}">
                    {{ $feedbackMessage !== '' ? $feedbackMessage : 'Aún no se ha registrado ninguna lectura en esta sesión.' }}
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Asistencias registradas</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $attendanceCount }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Tipo de acceso</p>
                        <p class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">Gafete de participante</p>
                    </div>
                </div>
            </section>

            <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_25px_70px_rgba(15,23,42,0.08)] dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Último registro</h3>

                @if ($lastAttendance !== [])
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                            <dt class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Participante</dt>
                            <dd class="mt-2 font-semibold text-slate-900 dark:text-slate-100">{{ $lastAttendance['nombre'] }}</dd>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                                <dt class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Identidad</dt>
                                <dd class="mt-2 text-slate-900 dark:text-slate-100">{{ $lastAttendance['dni'] }}</dd>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                                <dt class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Teléfono</dt>
                                <dd class="mt-2 text-slate-900 dark:text-slate-100">{{ $lastAttendance['telefono'] }}</dd>
                            </div>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                                <dt class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Perfil</dt>
                                <dd class="mt-2 text-slate-900 dark:text-slate-100">{{ $lastAttendance['perfil'] }}</dd>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                                <dt class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Hora</dt>
                                <dd class="mt-2 text-slate-900 dark:text-slate-100">{{ $lastAttendance['hora'] }}</dd>
                            </div>
                        </div>
                    </dl>
                @else
                    <div class="mt-4 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-950/40 dark:text-slate-400">
                        Todavía no hay registros en esta sesión.
                    </div>
                @endif
            </section>
        </aside>
    </div>

    @if ($pendingParticipant !== [])
        <section class="rounded-[2rem] border border-emerald-200 bg-emerald-50/80 p-5 shadow-[0_25px_70px_rgba(16,185,129,0.12)] dark:border-emerald-500/30 dark:bg-emerald-500/10">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-700 dark:text-emerald-300">Participante detectado</p>
                    <h3 class="mt-3 text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $pendingParticipant['nombre'] ?? 'Participante' }}</h3>
                    <dl class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="rounded-2xl border border-white/70 bg-white/90 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/70">
                            <dt class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Perfil</dt>
                            <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $pendingParticipant['perfil'] ?? 'Sin perfil' }}</dd>
                        </div>
                        <div class="rounded-2xl border border-white/70 bg-white/90 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/70">
                            <dt class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Identidad</dt>
                            <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $pendingParticipant['dni'] ?? 'Sin identidad' }}</dd>
                        </div>
                        <div class="rounded-2xl border border-white/70 bg-white/90 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/70">
                            <dt class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Teléfono</dt>
                            <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $pendingParticipant['telefono'] ?? 'Sin teléfono' }}</dd>
                        </div>
                        <div class="rounded-2xl border border-white/70 bg-white/90 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/70">
                            <dt class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Correo</dt>
                            <dd class="mt-2 truncate text-sm font-medium text-slate-900 dark:text-slate-100">{{ $pendingParticipant['correo'] ?? 'Sin correo' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="flex shrink-0 flex-col gap-3 sm:flex-row lg:flex-col">
                    <button
                        type="button"
                        wire:click="confirmAttendance"
                        class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500"
                    >
                        Registrar asistencia
                    </button>
                    <button
                        type="button"
                        @click="$wire.clearPendingParticipant(); openModal()"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                    >
                        Escanear otro QR
                    </button>
                </div>
            </div>
        </section>
    @endif

    <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0_25px_70px_rgba(15,23,42,0.08)] dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Participantes con asistencia registrada</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Se muestra el historial reciente de personas que ya fueron validadas en esta conferencia.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-800 dark:bg-slate-950/60">
                <span class="text-slate-500 dark:text-slate-400">Total:</span>
                <span class="ml-2 font-semibold text-slate-900 dark:text-slate-100">{{ $attendanceCount }}</span>
            </div>
        </div>

        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Participante</th>
                        <th class="px-5 py-4">Perfil</th>
                        <th class="px-5 py-4">Identidad</th>
                        <th class="px-5 py-4">Teléfono</th>
                        <th class="px-5 py-4">Registrado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse ($attendances as $attendance)
                        @php
                            $person = $attendance->eventoRegistro?->persona;
                            $fullName = trim(($person?->nombre ?? '') . ' ' . ($person?->apellido ?? '')) ?: 'Participante';
                        @endphp
                        <tr class="text-slate-700 dark:text-slate-200">
                            <td class="px-5 py-4 font-medium text-slate-900 dark:text-slate-100">{{ $fullName }}</td>
                            <td class="px-5 py-4">{{ $attendance->eventoRegistro?->tipoPerfil?->tipoperfil ?? 'Sin perfil' }}</td>
                            <td class="px-5 py-4">{{ $person?->dni ?: 'Sin identidad' }}</td>
                            <td class="px-5 py-4">{{ $person?->telefono ?: 'Sin teléfono' }}</td>
                            <td class="px-5 py-4">{{ $attendance->checked_in_at?->format('d/m/Y h:i a') ?: '--' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                                Aún no se han registrado asistencias en esta conferencia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $attendances->links() }}
        </div>
    </section>

    <div
        x-cloak
        x-show="modalOpen"
        class="fixed inset-0 z-[90] flex items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm"
    >
        <div
            @click.outside="closeModal()"
            class="flex max-h-[92vh] w-full max-w-3xl flex-col overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0_40px_120px_rgba(15,23,42,0.45)] dark:border-slate-800 dark:bg-slate-900"
        >
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Escaneo QR</p>
                    <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-slate-100">Registrar asistencia</h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Escanea el gafete y confirma manualmente antes de guardar la asistencia.</p>
                </div>
                <button
                    type="button"
                    @click="closeModal()"
                    class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto px-6 py-6">
                @if ($pendingParticipant !== [])
                    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_280px]">
                        <div class="rounded-[1.75rem] border border-emerald-200 bg-emerald-50 p-6 dark:border-emerald-500/30 dark:bg-emerald-500/10">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-700 dark:text-emerald-300">Participante detectado</p>
                            <h4 class="mt-3 text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $pendingParticipant['nombre'] ?? 'Participante' }}</h4>
                            <dl class="mt-5 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl border border-white/70 bg-white/90 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/70">
                                    <dt class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Perfil</dt>
                                    <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $pendingParticipant['perfil'] ?? 'Sin perfil' }}</dd>
                                </div>
                                <div class="rounded-2xl border border-white/70 bg-white/90 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/70">
                                    <dt class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Identidad</dt>
                                    <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $pendingParticipant['dni'] ?? 'Sin identidad' }}</dd>
                                </div>
                                <div class="rounded-2xl border border-white/70 bg-white/90 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/70">
                                    <dt class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Teléfono</dt>
                                    <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $pendingParticipant['telefono'] ?? 'Sin teléfono' }}</dd>
                                </div>
                                <div class="rounded-2xl border border-white/70 bg-white/90 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/70">
                                    <dt class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-500">Correo</dt>
                                    <dd class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $pendingParticipant['correo'] ?? 'Sin correo' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                            <h5 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Confirmar registro</h5>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Si los datos son correctos, registra la asistencia en esta conferencia.</p>
                            <div class="mt-5 space-y-3">
                                <button
                                    type="button"
                                    wire:click="confirmAttendance"
                                    class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500"
                                >
                                    Registrar asistencia
                                </button>
                                <button
                                    type="button"
                                    @click="$wire.clearPendingParticipant(); start()"
                                    class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                                >
                                    Escanear otro QR
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="space-y-5">
                        <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-slate-950 dark:border-slate-800">
                            <template x-if="running">
                                <video x-ref="video" autoplay playsinline muted class="aspect-[16/10] w-full object-cover"></video>
                            </template>

                            <div
                                x-show="! running"
                                class="flex aspect-[16/10] w-full flex-col items-center justify-center gap-4 px-6 text-center"
                            >
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/10 text-white">
                                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7V6a2 2 0 0 1 2-2h1m10 0h1a2 2 0 0 1 2 2v1M4 17v1a2 2 0 0 0 2 2h1m10 0h1a2 2 0 0 0 2-2v-1M8 12h8" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 9h.01M15 9h.01M9 15h.01M15 15h.01" />
                                    </svg>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-base font-semibold text-white">Cámara lista para escanear</p>
                                    <p class="text-sm text-slate-300">Apunta al QR del gafete del participante. Al detectar el código, se mostrarán sus datos para confirmación.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button
                                type="button"
                                x-show="supported && ! running"
                                @click="start()"
                                class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500"
                            >
                                Activar cámara
                            </button>
                            <button
                                type="button"
                                x-show="running"
                                @click="stop()"
                                class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                            >
                                Detener cámara
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
