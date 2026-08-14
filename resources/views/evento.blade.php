@extends('layouts.base')

@section('content')
    <div class="orbit-shell">
        <header x-data="{ publicUserMenuOpen: false, mobileNavOpen: false }" class="sticky top-0 z-30 border-b border-slate-200/70 bg-white/85 backdrop-blur-xl dark:border-white/6 dark:bg-[#0b0b12]/85">
            <div class="mx-auto flex h-20 w-full max-w-[1680px] items-center justify-between gap-4 px-4 sm:px-6 lg:px-10 2xl:px-12">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-[#8c6dff] via-[#7b5cff] to-[#5230cc] shadow-[0_16px_40px_rgba(123,92,255,0.38)]">
                        <img src="{{ asset('Logo/Eventis_Logo.png') }}" alt="Logo" class="h-7 w-7 rounded-xl object-cover" />
                    </div>
                    <div>
                        <p class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">EventIS</p>
                        <p class="text-[11px] uppercase tracking-[0.28em] text-slate-500 dark:text-slate-500">Detalle del evento</p>
                    </div>
                </a>

                <div class="hidden items-center gap-3 md:flex">
                    <a href="{{ route('welcome') }}" class="orbit-button-secondary">
                        Volver al catálogo
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
                        <a href="{{ route('login') }}" class="orbit-button-primary">
                            Ingresar
                        </a>
                    @endauth
                </div>

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
                    <a href="{{ route('welcome') }}" class="orbit-button-secondary">
                        Volver al catálogo
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="orbit-button-primary">
                            Mi dashboard
                        </a>
                        <a href="{{ route('profile.show') }}" class="orbit-button-secondary">
                            Mi perfil
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="orbit-button-primary">
                            Ingresar
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <main class="w-full py-0">
            @php
                $speakerCount = $conferencias
                    ->map(fn ($conference) => $conference->speaker_persona_id ?: $conference->idConferencista ?: $conference->conferencista_nombre_invitado)
                    ->filter()
                    ->unique()
                    ->count();
                $dayCount = max(count($agendaDays), 1);
                $lowestPrice = collect($allPriceDisplay ?? [])->map(fn ($price) => $price['active_amount'] ?? $price['base_amount'])->filter(fn ($amount) => $amount !== null)->min();
                $formatPriceDisplay = static function (?array $price, mixed $amount = null): string {
                    if (! $price) {
                        return '0.00';
                    }

                    $value = $amount ?? ($price['active_amount'] ?? $price['base_amount'] ?? 0);
                    $symbol = $price['currency_symbol'] ?? '';
                    $code = $price['currency_code'] ?? '';

                    return trim(($symbol ? $symbol . ' ' : '') . number_format((float) $value, 2) . ($code ? ' ' . $code : ''));
                };
            @endphp

            <section class="space-y-8">
                <article class="overflow-hidden rounded-none border-0 bg-transparent shadow-none">
                    <div class="relative aspect-[16/10] sm:aspect-[16/8] lg:aspect-[16/7] bg-slate-100 dark:bg-slate-950">
                        @if ($evento->banner_url)
                            <img src="{{ $evento->banner_url }}" alt="{{ $evento->nombreevento }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center bg-[radial-gradient(circle_at_top,_rgba(123,92,255,0.35),_transparent_32%),linear-gradient(135deg,_#111827_0%,_#1e1b4b_48%,_#312e81_100%)] px-8 text-center text-white">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.34em] text-violet-200">{{ $evento->tipoEvento?->tipo ?? 'Evento' }}</p>
                                    <p class="mt-4 text-3xl font-black sm:text-4xl lg:text-6xl">{{ $evento->nombreevento }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(9,12,28,0.18)_0%,rgba(9,12,28,0.52)_55%,rgba(9,12,28,0.92)_100%)]"></div>

                        <div class="absolute inset-x-0 bottom-0 p-5 sm:p-8 lg:p-10">
                            @if ($evento->logo_url)
                                <div class="mb-4 inline-flex rounded-[1.25rem] border border-white/20 bg-white/12 p-2 shadow-lg backdrop-blur">
                                    <img src="{{ $evento->logo_url }}" alt="Logo del evento" class="h-14 w-14 rounded-[0.9rem] object-cover sm:h-16 sm:w-16">
                                </div>
                            @endif

                            <span class="inline-flex rounded-full bg-violet-500/85 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-violet-500/30">
                                Evento destacado
                            </span>

                            <h1 class="mt-4 max-w-4xl text-3xl font-black tracking-[-0.05em] text-white sm:text-4xl lg:text-6xl">{{ $evento->nombreevento }}</h1>

                            <div class="mt-5 flex flex-wrap gap-5 text-sm text-slate-200">
                                <span class="inline-flex items-center gap-2">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M6 2a1 1 0 0 1 1 1v1h6V3a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v2H2V6a2 2 0 0 1 2-2h1V3a1 1 0 0 1 1-1Z"/><path fill-rule="evenodd" d="M2 10h16v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4Zm3 2a1 1 0 0 0 0 2h3a1 1 0 1 0 0-2H5Z" clip-rule="evenodd"/></svg>
                                    {{ $evento->fechainicio?->format('d/m/Y') }} - {{ $evento->fechafinal?->format('d/m/Y') }}
                                </span>
                                <span class="inline-flex items-center gap-2">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.69 18.933a1 1 0 0 0 .62 0c3.68-1.06 6.69-4.814 6.69-8.933A7 7 0 1 0 3 10c0 4.119 3.01 7.873 6.69 8.933ZM10 11.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" clip-rule="evenodd"/></svg>
                                    {{ $evento->localidad_display }}
                                </span>
                            </div>
                        </div>
                    </div>
                </article>

                <section class="mx-auto grid w-full max-w-[1680px] gap-6 px-4 sm:px-6 lg:grid-cols-[minmax(0,1fr)_360px] lg:px-10 2xl:max-w-[1760px] 2xl:grid-cols-[minmax(0,1fr)_400px] 2xl:px-12">
                    <article class="orbit-panel p-6 lg:p-7">
                        <h2 class="text-2xl font-black tracking-[-0.03em] text-slate-950 dark:text-white">Sobre el Evento</h2>
                        <p class="mt-4 text-base leading-8 text-slate-600 dark:text-slate-300">{{ $evento->descripcion }}</p>
                        <p class="mt-4 text-sm leading-7 text-slate-500 dark:text-slate-400">
                            Organizado por {{ $evento->organizador }}, con modalidad {{ strtolower($evento->modalidad?->modalidad ?? 'por definir') }} y agenda distribuida en {{ $dayCount }} {{ \Illuminate\Support\Str::plural('día', $dayCount) }}.
                        </p>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-[1.5rem] bg-violet-50 p-4 text-center dark:bg-violet-500/10">
                                <p class="text-xl font-black text-slate-950 dark:text-white">{{ $conferencias->count() }}</p>
                                <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Actividades</p>
                            </div>
                            <div class="rounded-[1.5rem] bg-slate-50 p-4 text-center dark:bg-slate-800/70">
                                <p class="text-xl font-black text-slate-950 dark:text-white">{{ $speakerCount }}</p>
                                <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Ponentes</p>
                            </div>
                            <div class="rounded-[1.5rem] bg-slate-50 p-4 text-center dark:bg-slate-800/70">
                                <p class="text-xl font-black text-slate-950 dark:text-white">{{ $dayCount }}</p>
                                <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Días</p>
                            </div>
                            <div class="rounded-[1.5rem] bg-slate-50 p-4 text-center dark:bg-slate-800/70">
                                <p class="text-xl font-black text-slate-950 dark:text-white">{{ $evento->genera_diploma_participacion ? 'Sí' : 'No' }}</p>
                                <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Diploma</p>
                            </div>
                        </div>
                    </article>

                    <aside
                        x-data="{
                            registrationModalOpen: {{ session('error') || $errors->any() ? 'true' : 'false' }},
                            successModalOpen: {{ session('status') && ! session('error') && ! $errors->any() ? 'true' : 'false' }},
                            selectedPaymentMethod: '{{ (string) ($metodosPago->first()?->id ?? '') }}',
                            proofFileName: '',
                            proofDragOver: false,
                            isSubmitting: false,
                            paymentMethods: @js($metodosPago->map(fn ($metodo) => [
                                'id' => (string) $metodo->id,
                                'type' => $metodo->tipo,
                                'name' => $metodo->nombre,
                                'description' => $metodo->descripcion,
                                'instructions' => $metodo->instrucciones,
                                'bank' => $metodo->banco_nombre,
                                'account' => $metodo->numero_cuenta,
                                'holder' => $metodo->titular_cuenta,
                                'international' => $metodo->detalle_transferencia_internacional,
                            ])->values()->all()),
                            selectedMethod() {
                                return this.paymentMethods.find((method) => method.id === this.selectedPaymentMethod) || null;
                            },
                            setProofFile(files) {
                                const file = files && files.length ? files[0] : null;
                                this.proofFileName = file ? file.name : '';
                            },
                            resetPaymentProof() {
                                this.proofFileName = '';
                                this.proofDragOver = false;
                                if (this.$refs.paymentProofInput) {
                                    this.$refs.paymentProofInput.value = '';
                                }
                            },
                            closeSuccessModal() {
                                this.successModalOpen = false;
                            }
                        }"
                        class="orbit-panel p-6"
                    >
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Precio de entrada</p>
                        @if ($evento->tipo_acceso === 'pagada' && $featuredPrice)
                            <div class="mt-3">
                                @if ($viewerProfileId)
                                    <p class="text-4xl font-black tracking-[-0.05em] text-slate-950 dark:text-white">
                                        {{ $formatPriceDisplay($featuredPrice, $featuredPrice['active_amount'] ?? $featuredPrice['base_amount'] ?? 0) }}
                                        <span class="text-lg font-medium text-slate-500 dark:text-slate-400">/persona</span>
                                    </p>
                                    <p class="mt-2 text-sm font-medium text-slate-600 dark:text-slate-300">
                                        Tarifa asignada para <span class="font-semibold text-slate-900 dark:text-white">{{ $featuredPrice['profile_name'] }}</span>.
                                    </p>
                                    @if ($featuredPrice['active_amount'] !== null)
                                        <p class="mt-2 text-sm font-medium text-emerald-600 dark:text-emerald-300">{{ $featuredPrice['offer_label'] }}</p>
                                        @if ($featuredPrice['offer_dates'])
                                            <p class="mt-1 text-xs text-emerald-600/80 dark:text-emerald-300/80">{{ $featuredPrice['offer_dates'] }}</p>
                                        @endif
                                        <p class="mt-1 text-sm text-slate-400 line-through dark:text-slate-500">
                                            {{ $formatPriceDisplay($featuredPrice, $featuredPrice['base_amount'] ?? 0) }}
                                        </p>
                                    @endif
                                @else
                                    <p class="text-sm font-medium uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Tarifas disponibles desde</p>
                                    <p class="mt-1 text-4xl font-black tracking-[-0.05em] text-slate-950 dark:text-white">
                                        {{ $formatPriceDisplay($featuredPrice, $lowestPrice ?? ($featuredPrice['active_amount'] ?? $featuredPrice['base_amount'] ?? 0)) }}
                                        <span class="text-lg font-medium text-slate-500 dark:text-slate-400">/persona</span>
                                    </p>
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                        Inicia sesión para ver tu tarifa exacta según tu perfil.
                                    </p>
                                @endif
                            </div>
                        @else
                            <div class="mt-3">
                                <p class="text-4xl font-black tracking-[-0.05em] text-emerald-600 dark:text-emerald-300">Gratis</p>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Acceso sin costo para participantes habilitados.</p>
                            </div>
                        @endif

                        @if ($evento->tipo_acceso === 'pagada')
                            <div class="mt-5 rounded-[1.35rem] border border-slate-200 bg-slate-50/90 p-4 dark:border-slate-800 dark:bg-slate-900/70">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                        {{ $viewerProfileId ? 'Tu tarifa aplicada' : 'Tarifas por perfil' }}
                                    </p>
                                    @if ($viewerProfileId)
                                        <span class="rounded-full bg-violet-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-violet-700 dark:bg-violet-500/15 dark:text-violet-300">
                                            Perfil detectado
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-3 space-y-3">
                                    @forelse ($priceDisplay as $price)
                                        <div class="rounded-[1rem] border border-slate-200 bg-white px-3.5 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $price['profile_name'] }}</p>
                                                    @if ($price['active_amount'] !== null)
                                                        <p class="mt-1 text-xs font-medium text-emerald-600 dark:text-emerald-300">{{ $price['offer_label'] }}</p>
                                                        @if ($price['offer_dates'])
                                                            <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">{{ $price['offer_dates'] }}</p>
                                                        @endif
                                                    @else
                                                        <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">Precio vigente del perfil</p>
                                                    @endif
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-base font-black text-slate-950 dark:text-white">
                                                        {{ $formatPriceDisplay($price, $price['active_amount'] ?? $price['base_amount'] ?? 0) }}
                                                    </p>
                                                    @if ($price['active_amount'] !== null)
                                                        <p class="mt-1 text-[11px] text-slate-400 line-through dark:text-slate-500">
                                                            {{ $formatPriceDisplay($price, $price['base_amount'] ?? 0) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="rounded-[1rem] border border-dashed border-slate-300 px-3.5 py-4 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                            No hay tarifas disponibles para mostrar.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        @auth
                            @if ($existingRegistration)
                                <div class="mt-6 rounded-[1.75rem] border border-emerald-200 bg-[linear-gradient(135deg,rgba(236,253,245,0.96),rgba(255,255,255,0.96))] px-5 py-4 text-left shadow-[0_20px_50px_rgba(16,185,129,0.12)] dark:border-emerald-900/40 dark:bg-[linear-gradient(135deg,rgba(6,78,59,0.32),rgba(15,23,42,0.92))]">
                                    <div class="flex items-start gap-3">
                                        <span class="mt-0.5 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-200">
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7" />
                                            </svg>
                                        </span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-200">
                                                Ya te encuentras inscrito en este evento
                                            </p>
                                            <p class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                                Estado de inscripción:
                                                <span class="font-semibold text-slate-900 dark:text-white">{{ str_replace('_', ' ', $existingRegistration->estado_pago ?? $existingRegistration->estado) }}</span>.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <button type="button" @click="registrationModalOpen = true" class="orbit-button-primary mt-6 w-full justify-center">
                                    {{ $evento->tipo_acceso === 'pagada' ? 'Comprar entrada' : 'Registrarme ahora' }}
                                </button>
                            @endif
                        @else
                            <a href="{{ route('evento.login-inscripcion', $evento) }}" class="orbit-button-primary mt-6 w-full justify-center">
                                {{ $evento->tipo_acceso === 'pagada' ? 'Comprar entrada' : 'Registrarme ahora' }}
                            </a>
                        @endauth

                        <div class="mt-6 space-y-4 border-t border-slate-200 pt-5 dark:border-slate-800">
                            <div class="flex gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-violet-100 text-violet-600 dark:bg-violet-500/15 dark:text-violet-300">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M5 2a1 1 0 0 0 0 2h1v1a3 3 0 1 0 6 0V4h1a1 1 0 1 0 0-2H5Z"/><path d="M4 8a1 1 0 0 0-1 1v1a7 7 0 1 0 14 0V9a1 1 0 1 0-2 0v1a5 5 0 1 1-10 0V9a1 1 0 0 0-1-1Z"/></svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Reserva inmediata</p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Completa tu inscripción y asegura tu lugar hoy.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-violet-100 text-violet-600 dark:bg-violet-500/15 dark:text-violet-300">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a1 1 0 0 1 1 1v1.07A7.001 7.001 0 0 1 17 11h-2a5 5 0 1 0-10 0H3a7.001 7.001 0 0 1 6-6.93V3a1 1 0 0 1 1-1Z"/><path d="M10 8a1 1 0 0 1 1 1v3.382l1.447.894a1 1 0 0 1-1.054 1.7l-1.84-1.137A1 1 0 0 1 9 13V9a1 1 0 0 1 1-1Z"/></svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Agenda intensiva</p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $conferencias->count() }} actividades en {{ $dayCount }} días de evento.</p>
                                </div>
                            </div>
                        </div>

                        @auth
                            <template x-teleport="body">
                                <div
                                    x-cloak
                                    x-show="successModalOpen"
                                    x-transition.opacity
                                    x-init="if (successModalOpen) { setTimeout(() => closeSuccessModal(), 3000) }"
                                    class="fixed inset-0 z-[130] flex items-center justify-center bg-slate-950/65 px-4 backdrop-blur-sm"
                                >
                                    <div class="absolute inset-0" @click="closeSuccessModal()"></div>

                                    <div
                                        x-show="successModalOpen"
                                        x-transition.scale.origin.center
                                        class="relative z-10 w-full max-w-md overflow-hidden rounded-[2rem] border border-emerald-200 bg-[linear-gradient(135deg,rgba(236,253,245,0.98),rgba(255,255,255,0.98))] p-6 text-center shadow-[0_35px_100px_-50px_rgba(16,185,129,0.65)] dark:border-emerald-900/40 dark:bg-[linear-gradient(135deg,rgba(6,78,59,0.92),rgba(15,23,42,0.95))]"
                                    >
                                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 animate-[pulse_1.8s_ease-in-out_infinite] dark:bg-emerald-500/15 dark:text-emerald-200">
                                            <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="m5 13 4 4L19 7" />
                                            </svg>
                                        </div>
                                        <p class="mt-5 text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-200">Inscripción realizada</p>
                                        <h3 class="mt-3 text-2xl font-black tracking-[-0.03em] text-slate-950 dark:text-white">Proceso completado</h3>
                                        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">
                                            {{ session('status') }}
                                        </p>
                                    </div>
                                </div>
                            </template>

                            <template x-teleport="body">
                                <div
                                    x-cloak
                                    x-show="registrationModalOpen"
                                    x-transition.opacity
                                    class="fixed inset-0 z-[120] flex items-stretch justify-center bg-slate-950/75 backdrop-blur-sm lg:items-center lg:px-6 lg:py-8"
                                >
                                    <div @click="registrationModalOpen = false" class="absolute inset-0"></div>

                                    <div
                                        x-show="registrationModalOpen"
                                        x-transition.scale
                                        class="relative z-10 flex h-full w-full flex-col overflow-hidden bg-white dark:bg-slate-950 lg:h-auto lg:max-h-[90vh] lg:max-w-5xl lg:rounded-[2rem] lg:border lg:border-slate-200 lg:shadow-[0_35px_100px_-50px_rgba(15,23,42,0.65)] lg:dark:border-slate-800"
                                    >
                                    <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 sm:px-8 dark:border-slate-800">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-violet-600 dark:text-violet-300">
                                                {{ $evento->tipo_acceso === 'pagada' ? 'Confirmación y cobro' : 'Confirmación de inscripción' }}
                                            </p>
                                            <h3 class="mt-2 text-2xl font-black tracking-[-0.03em] text-slate-950 dark:text-white">
                                                {{ $evento->tipo_acceso === 'pagada' ? 'Selecciona el método de pago' : 'Confirma tu inscripción al evento' }}
                                            </h3>
                                            <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                                                @if ($evento->tipo_acceso === 'pagada')
                                                    Vas a registrar tu inscripción y dejar el pago pendiente con el método seleccionado para el evento <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $evento->nombreevento }}</span>.
                                                @else
                                                    Al confirmar, tu inscripción quedará registrada de inmediato en el evento <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $evento->nombreevento }}</span>.
                                                @endif
                                            </p>
                                        </div>

                                        <button type="button" @click="registrationModalOpen = false" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 transition hover:border-slate-300 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/></svg>
                                        </button>
                                    </div>

                                    <div class="flex-1 overflow-y-auto">
                                        <div class="mx-auto flex h-full w-full max-w-[1360px] flex-col px-6 py-6 sm:px-8 lg:py-8">
                                            <div class="mx-auto w-full max-w-3xl space-y-6">
                                                @if (session('error') || $errors->any())
                                                    <div
                                                        x-cloak
                                                        x-transition.scale.origin.top
                                                        class="overflow-hidden rounded-[2rem] border border-rose-200 bg-[linear-gradient(135deg,rgba(255,241,242,0.98),rgba(255,255,255,0.98))] shadow-[0_20px_60px_rgba(15,23,42,0.12)] dark:border-rose-900/40 dark:bg-[linear-gradient(135deg,rgba(76,5,25,0.92),rgba(15,23,42,0.92))]"
                                                    >
                                                        <div class="flex items-start gap-4 px-5 py-5 sm:px-6">
                                                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 animate-[pulse_2.2s_ease-in-out_infinite] dark:bg-rose-500/15 dark:text-rose-200">
                                                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                                                                </svg>
                                                            </div>

                                                            <div class="min-w-0 flex-1">
                                                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rose-500 dark:text-rose-200/90">Error de inscripción</p>

                                                                @if (session('error'))
                                                                    <p class="mt-2 text-base font-semibold text-slate-950 dark:text-white">{{ session('error') }}</p>
                                                                @endif

                                                                @if ($errors->any())
                                                                    <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                                                        @foreach ($errors->all() as $error)
                                                                            <li class="flex items-start gap-2">
                                                                                <span class="mt-1 inline-block h-2 w-2 rounded-full bg-rose-500"></span>
                                                                                <span>{{ $error }}</span>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="rounded-[2rem] border border-slate-200 bg-slate-50/80 p-5 dark:border-slate-800 dark:bg-slate-900/60">
                                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                                                        <div>
                                                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Participante</p>
                                                            <p class="mt-2 text-base font-semibold text-slate-950 dark:text-white">{{ auth()->user()->name }}</p>
                                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ auth()->user()->persona?->tipoPerfil?->tipoperfil ?? 'Perfil no definido' }}</p>
                                                        </div>
                                                        <div class="sm:text-right">
                                                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Monto a registrar</p>
                                                            <p class="mt-2 text-3xl font-black tracking-[-0.04em] text-slate-950 dark:text-white">
                                                                @if ($evento->tipo_acceso === 'pagada' && $featuredPrice)
                                                                    {{ $formatPriceDisplay($featuredPrice, $featuredPrice['active_amount'] ?? $featuredPrice['base_amount'] ?? 0) }}
                                                                @else
                                                                    Gratis
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if ($evento->tipo_acceso === 'pagada')
                                                    @if ($metodosPago->isNotEmpty())
                                                        <form method="POST" action="{{ route('evento.quick-register', $evento) }}" enctype="multipart/form-data" class="space-y-6" @submit="isSubmitting = true">
                                                            @csrf
                                                            <input type="hidden" name="metodo_pago_id" :value="selectedPaymentMethod">

                                                            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900/60">
                                                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Método de pago</p>
                                                                <div class="mt-4 grid gap-3">
                                                                    @foreach ($metodosPago as $metodo)
                                                                        <label class="flex cursor-pointer items-start gap-3 rounded-[1.5rem] border border-slate-200 bg-slate-50/80 p-4 transition hover:border-violet-300 hover:bg-violet-50/60 dark:border-slate-800 dark:bg-slate-950/60 dark:hover:border-violet-500/50 dark:hover:bg-violet-500/5">
                                                                            <input
                                                                                type="radio"
                                                                                name="selectedPaymentMethod"
                                                                                x-model="selectedPaymentMethod"
                                                                                value="{{ $metodo->id }}"
                                                                                class="mt-1 h-4 w-4 border-slate-300 text-violet-600 focus:ring-violet-400"
                                                                            >
                                                                            <div class="min-w-0">
                                                                                <div class="flex flex-wrap items-center gap-2">
                                                                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $metodo->nombre }}</p>
                                                                                    <span class="rounded-full bg-violet-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-violet-700 dark:bg-violet-500/15 dark:text-violet-300">
                                                                                        {{ ucfirst($metodo->tipo) }}
                                                                                    </span>
                                                                                </div>
                                                                                <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">
                                                                                    {{ $metodo->descripcion ?: 'Método de pago disponible para este evento.' }}
                                                                                </p>
                                                                            </div>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            </div>

                                                            <div x-show="selectedMethod()" x-cloak class="rounded-[2rem] border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900/60">
                                                                <template x-if="selectedMethod() && selectedMethod().type === 'efectivo'">
                                                                    <div class="space-y-4">
                                                                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Pago en efectivo</p>
                                                                        <div class="rounded-[1.5rem] border border-amber-200 bg-amber-50 px-4 py-4 text-sm leading-7 text-amber-800 dark:border-amber-900/50 dark:bg-amber-950/20 dark:text-amber-200">
                                                                            Tu inscripción quedará reservada y el pago se realizará presencialmente durante el evento. El equipo de pagos validará el cobro y luego habilitará tu gafete.
                                                                        </div>
                                                                        <p x-show="selectedMethod()?.instructions" class="text-sm text-slate-500 dark:text-slate-400" x-text="selectedMethod()?.instructions"></p>
                                                                    </div>
                                                                </template>

                                                                <template x-if="selectedMethod() && selectedMethod().type === 'transferencia'">
                                                                    <div class="space-y-5">
                                                                        <div>
                                                                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Transferencia bancaria</p>
                                                                            <p class="mt-2 text-sm leading-7 text-slate-500 dark:text-slate-400">
                                                                                Transfiere el valor correspondiente y sube aquí mismo la imagen o PDF del comprobante para dejar tu pago en revisión.
                                                                            </p>
                                                                        </div>

                                                                        <div class="grid gap-4 md:grid-cols-2">
                                                                            <div class="rounded-[1.4rem] border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                                                                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Banco</p>
                                                                                <p class="mt-2 font-semibold text-slate-950 dark:text-white" x-text="selectedMethod()?.bank || 'Pendiente de configurar'"></p>
                                                                            </div>
                                                                            <div class="rounded-[1.4rem] border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60">
                                                                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Número de cuenta</p>
                                                                                <p class="mt-2 font-semibold text-slate-950 dark:text-white" x-text="selectedMethod()?.account || 'Pendiente de configurar'"></p>
                                                                            </div>
                                                                            <div class="rounded-[1.4rem] border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/60 md:col-span-2">
                                                                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Titular</p>
                                                                                <p class="mt-2 font-semibold text-slate-950 dark:text-white" x-text="selectedMethod()?.holder || 'Pendiente de configurar'"></p>
                                                                            </div>
                                                                            <div class="rounded-[1.4rem] border border-slate-200 bg-slate-50 px-4 py-3 text-sm leading-7 text-slate-500 dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-400 md:col-span-2" x-show="selectedMethod()?.international">
                                                                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Transferencias internacionales</p>
                                                                                <p class="mt-2 whitespace-pre-line" x-text="selectedMethod()?.international"></p>
                                                                            </div>
                                                                        </div>

                                                                        <div>
                                                                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Comprobante de pago <span class="text-red-500">*</span></label>
                                                                            <input
                                                                                x-ref="paymentProofInput"
                                                                                name="payment_proof"
                                                                                x-bind:required="selectedMethod()?.type === 'transferencia'"
                                                                                type="file"
                                                                                accept=".jpg,.jpeg,.png,.pdf"
                                                                                class="hidden"
                                                                                @change="setProofFile($event.target.files)"
                                                                            >

                                                                            <div
                                                                                class="rounded-[1.5rem] border-2 border-dashed px-5 py-8 text-center transition"
                                                                                :class="proofDragOver
                                                                                    ? 'border-violet-400 bg-violet-50/80 dark:border-violet-400 dark:bg-violet-500/10'
                                                                                    : 'border-slate-300 bg-slate-50/70 dark:border-slate-700 dark:bg-slate-950/50'"
                                                                                @dragover.prevent="proofDragOver = true"
                                                                                @dragleave.prevent="proofDragOver = false"
                                                                                @drop.prevent="
                                                                                    proofDragOver = false;
                                                                                    if ($event.dataTransfer.files.length) {
                                                                                        $refs.paymentProofInput.files = $event.dataTransfer.files;
                                                                                        setProofFile($event.dataTransfer.files);
                                                                                    }
                                                                                "
                                                                            >
                                                                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white text-violet-600 shadow-sm dark:bg-slate-900 dark:text-violet-300">
                                                                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16V4m0 0-4 4m4-4 4 4M5 16.5v1A2.5 2.5 0 0 0 7.5 20h9a2.5 2.5 0 0 0 2.5-2.5v-1" />
                                                                                    </svg>
                                                                                </div>

                                                                                <p class="mt-4 text-base font-semibold text-slate-900 dark:text-white">Arrastra y suelta el comprobante aquí</p>
                                                                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">O haz clic para buscar una imagen JPG, PNG o un PDF.</p>

                                                                                <div class="mt-5 flex flex-wrap items-center justify-center gap-3">
                                                                                    <button
                                                                                        type="button"
                                                                                        @click="$refs.paymentProofInput.click()"
                                                                                        class="inline-flex items-center justify-center rounded-full bg-violet-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-500"
                                                                                    >
                                                                                        Seleccionar archivo
                                                                                    </button>

                                                                                    <button
                                                                                        x-show="proofFileName"
                                                                                        x-cloak
                                                                                        type="button"
                                                                                        @click="resetPaymentProof()"
                                                                                        class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                                                                                    >
                                                                                        Quitar archivo
                                                                                    </button>
                                                                                </div>
                                                                            </div>

                                                                            <div class="mt-3 rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-300">
                                                                                <span class="font-medium text-slate-900 dark:text-white">Archivo seleccionado:</span>
                                                                                <span x-text="proofFileName || 'Ningún archivo cargado todavía.'"></span>
                                                                            </div>

                                                                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Formatos permitidos: JPG, PNG o PDF. El pago quedará pendiente de validación.</p>
                                                                        </div>

                                                                        <p x-show="selectedMethod()?.instructions" class="text-sm text-slate-500 dark:text-slate-400" x-text="selectedMethod()?.instructions"></p>
                                                                    </div>
                                                                </template>
                                                            </div>

                                                            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end dark:border-slate-800">
                                                                <button type="button" @click="registrationModalOpen = false" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                                                                    Cancelar
                                                                </button>
                                                                <button type="submit" class="orbit-button-primary w-full justify-center sm:w-auto disabled:cursor-not-allowed disabled:opacity-70" x-bind:disabled="isSubmitting">
                                                                    <span x-show="!isSubmitting">Confirmar inscripción</span>
                                                                    <span x-show="isSubmitting" x-cloak class="inline-flex items-center gap-2">
                                                                        <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                                                            <path class="opacity-90" fill="currentColor" d="M12 2a10 10 0 0 1 10 10h-3a7 7 0 0 0-7-7V2Z"></path>
                                                                        </svg>
                                                                        Procesando...
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    @else
                                                        <div class="rounded-[1.5rem] border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900/50 dark:bg-amber-950/20 dark:text-amber-200">
                                                            Este evento es pagado, pero aún no tiene métodos de pago disponibles. Contacta al administrador antes de continuar.
                                                        </div>
                                                    @endif
                                                @else
                                                    <form method="POST" action="{{ route('evento.quick-register', $evento) }}" class="space-y-6" @submit="isSubmitting = true">
                                                        @csrf
                                                        <div class="rounded-[2rem] border border-emerald-200 bg-emerald-50 p-6 text-sm leading-7 text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-950/20 dark:text-emerald-200">
                                                            Este evento es gratuito. Al confirmar, tu inscripción quedará registrada de inmediato y no tendrás que completar un pago.
                                                        </div>
                                                        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end dark:border-slate-800">
                                                            <button type="button" @click="registrationModalOpen = false" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                                                                Cancelar
                                                            </button>
                                                            <button type="submit" class="orbit-button-primary w-full justify-center sm:w-auto disabled:cursor-not-allowed disabled:opacity-70" x-bind:disabled="isSubmitting">
                                                                <span x-show="!isSubmitting">Confirmar inscripción</span>
                                                                <span x-show="isSubmitting" x-cloak class="inline-flex items-center gap-2">
                                                                    <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none">
                                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                                                        <path class="opacity-90" fill="currentColor" d="M12 2a10 10 0 0 1 10 10h-3a7 7 0 0 0-7-7V2Z"></path>
                                                                    </svg>
                                                                    Procesando...
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </template>
                        @endauth
                    </aside>
                </section>

                <section class="mx-auto w-full max-w-[1680px] space-y-5 px-4 sm:px-6 lg:px-10 2xl:max-w-[1760px] 2xl:px-12">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h2 class="text-2xl font-black tracking-[-0.03em] text-slate-950 dark:text-white">Listado de Conferencias</h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Explora la agenda completa organizada por día.</p>
                        </div>
                    </div>

                    @if (count($agendaDays) > 0)
                        <div class="space-y-8">
                            @foreach ($agendaDays as $day)
                                <section>
                                    <div class="mb-4 flex items-center justify-between gap-3">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-violet-600 dark:text-violet-300">{{ $day['label'] }}</p>
                                            <h3 class="mt-1 text-xl font-black tracking-[-0.03em] text-slate-950 dark:text-white">{{ $day['display'] }}</h3>
                                        </div>
                                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                            {{ $day['conferences']->count() }} {{ \Illuminate\Support\Str::plural('actividad', $day['conferences']->count()) }}
                                        </span>
                                    </div>

                                    @if ($day['conferences']->isNotEmpty())
                                        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                                            @foreach ($day['conferences'] as $conferencia)
                                                @php
                                                    $speakerPersona = $conferencia->speakerPersona ?? $conferencia->conferencista?->persona;
                                                    $speakerProfile = $conferencia->conferencista;
                                                    $speakerName = $conferencia->conferencista_nombre_invitado
                                                        ?: trim(($speakerPersona?->nombre ?? '') . ' ' . ($speakerPersona?->apellido ?? ''))
                                                        ?: 'Conferencista pendiente';
                                                    $speakerNationality = $speakerPersona?->nacionalidad?->nombreNacionalidad;
                                                    $speakerAcademicLevel = $speakerProfile?->nivel_academico;
                                                    $speakerPhoto = $speakerProfile?->foto
                                                        ? (\Illuminate\Support\Str::startsWith($speakerProfile->foto, ['http://', 'https://']) ? $speakerProfile->foto : asset(str_replace('public/', 'storage/', $speakerProfile->foto)))
                                                        : null;
                                                    $conferencePhoto = $conferencia->foto_url;
                                                @endphp

                                                <article class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-[0_20px_50px_rgba(15,23,42,0.06)] dark:border-slate-800 dark:bg-slate-900/85">
                                                    <div class="relative">
                                                        @if ($conferencePhoto)
                                                            <img src="{{ $conferencePhoto }}" alt="{{ $conferencia->nombre }}" class="aspect-[16/9] w-full object-cover">
                                                        @else
                                                            <div class="flex aspect-[16/9] items-center justify-center bg-[linear-gradient(135deg,_#111827_0%,_#1e1b4b_48%,_#312e81_100%)] px-6 text-center text-white">
                                                                <p class="text-lg font-black leading-tight">{{ $conferencia->nombre }}</p>
                                                            </div>
                                                        @endif

                                                        <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-3 bg-gradient-to-t from-slate-950/80 via-slate-950/30 to-transparent p-4">
                                                            <div class="flex items-center gap-3">
                                                                @if ($speakerPhoto)
                                                                    <img src="{{ $speakerPhoto }}" alt="{{ $speakerName }}" class="h-11 w-11 rounded-full border-2 border-white object-cover">
                                                                @else
                                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($speakerName) }}&amp;color=fff&amp;background=7b5cff" alt="{{ $speakerName }}" class="h-11 w-11 rounded-full border-2 border-white object-cover">
                                                                @endif
                                                                <div class="min-w-0">
                                                                    <p class="truncate text-sm font-semibold text-white">{{ $speakerName }}</p>
                                                                    <p class="truncate text-xs text-slate-200">{{ $speakerAcademicLevel ?: ($speakerNationality ?: 'Conferencista') }}</p>
                                                                </div>
                                                            </div>
                                                            @if ($conferencia->tipoConferencia?->tipo)
                                                                <span class="rounded-full bg-white/90 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-700">
                                                                    {{ $conferencia->tipoConferencia->tipo }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="p-5">
                                                        <h4 class="text-xl font-black tracking-[-0.03em] text-slate-950 dark:text-white">{{ $conferencia->nombre }}</h4>
                                                        <div class="mt-3 flex flex-wrap gap-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                                                            <span>{{ substr((string) $conferencia->horaInicio, 0, 5) }} - {{ substr((string) $conferencia->horaFin, 0, 5) }}</span>
                                                            <span>{{ $conferencia->lugar ?: 'Por definir' }}</span>
                                                        </div>
                                                        <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                                            {{ \Illuminate\Support\Str::words($conferencia->descripcion ?: 'La descripción de esta conferencia será publicada próximamente.', 24, '...') }}
                                                        </p>

                                                        <div class="mt-4 rounded-[1.25rem] bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:bg-slate-800/70 dark:text-slate-300">
                                                            <p><span class="font-semibold text-slate-900 dark:text-white">Nacionalidad:</span> {{ $speakerNationality ?: 'No especificada' }}</p>
                                                            @if ($conferencia->linkreunion)
                                                                <a href="{{ $conferencia->linkreunion }}" target="_blank" class="mt-3 inline-flex text-sm font-semibold text-violet-600 hover:text-violet-500 dark:text-violet-300">
                                                                    Abrir enlace virtual
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-white px-5 py-10 text-center dark:border-slate-700 dark:bg-slate-900/70">
                                            <p class="text-base font-semibold text-slate-900 dark:text-slate-100">No hay actividades configuradas para este día.</p>
                                        </div>
                                    @endif
                                </section>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-white px-6 py-14 text-center dark:border-slate-700 dark:bg-slate-900/70">
                            <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">La agenda del evento aún no está disponible.</p>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">El evento ya es visible, pero las actividades todavía no han sido publicadas.</p>
                        </div>
                    @endif
                </section>
            </section>
        </main>
    </div>
@endsection
