<div
    x-data="{
        sidebarOpen: false,
        desktopCollapsed: document.documentElement.dataset.sidebarState === 'collapsed',
        userMenuOpen: false,
        eventsMenuOpen: {{ request()->routeIs('eventos*') || request()->routeIs('asistencia*') || request()->routeIs('pagos*') ? 'true' : 'false' }},
        maintenanceMenuOpen: {{ request()->routeIs('nacionalidad') || request()->routeIs('modalidad') || request()->routeIs('localidad') || request()->routeIs('tipoperfil') || request()->routeIs('tipos-conferencias') || request()->routeIs('monedas') || request()->routeIs('integraciones-api') || request()->routeIs('metodos-pago') || request()->routeIs('configuracion-facturacion') || request()->routeIs('usuario') || request()->routeIs('rol') || request()->routeIs('persona') ? 'true' : 'false' }},
        scrollStorageKey: 'admin-sidebar-scroll',
        openStateStorageKey: 'admin-sidebar-open-state',
        syncSidebarState() {
            const state = this.desktopCollapsed ? 'collapsed' : 'expanded';
            localStorage.setItem('admin-sidebar', state);
            document.documentElement.dataset.sidebarState = state;
        },
        saveSidebarScroll() {
            if (! this.$refs.sidebarScroll) {
                return;
            }

            localStorage.setItem(this.scrollStorageKey, String(this.$refs.sidebarScroll.scrollTop));
        },
        restoreSidebarScroll() {
            if (! this.$refs.sidebarScroll) {
                return;
            }

            const saved = localStorage.getItem(this.scrollStorageKey);

            if (saved !== null) {
                this.$refs.sidebarScroll.scrollTop = Number(saved);
                return;
            }

            const activeItem = this.$refs.sidebarScroll.querySelector('[data-sidebar-active=true]');

            if (activeItem) {
                activeItem.scrollIntoView({ block: 'center' });
            }
        },
        persistOpenStates() {
            localStorage.setItem(this.openStateStorageKey, JSON.stringify({
                eventsMenuOpen: this.eventsMenuOpen,
                maintenanceMenuOpen: this.maintenanceMenuOpen,
            }));
        },
        restoreOpenStates() {
            const saved = localStorage.getItem(this.openStateStorageKey);

            if (! saved) {
                return;
            }

            try {
                const parsed = JSON.parse(saved);
                this.eventsMenuOpen = parsed.eventsMenuOpen ?? this.eventsMenuOpen;
                this.maintenanceMenuOpen = parsed.maintenanceMenuOpen ?? this.maintenanceMenuOpen;
            } catch (error) {
                localStorage.removeItem(this.openStateStorageKey);
            }
        },
        toggleEventsMenu() {
            this.eventsMenuOpen = ! this.eventsMenuOpen;
            this.persistOpenStates();
        },
        toggleMaintenanceMenu() {
            this.maintenanceMenuOpen = ! this.maintenanceMenuOpen;
            this.persistOpenStates();
        },
        rememberAndFollow(url) {
            this.saveSidebarScroll();
            window.location.href = url;
        },
    }"
    x-init="syncSidebarState(); restoreOpenStates(); $nextTick(() => restoreSidebarScroll())"
    class="contents"
>
    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity
        class="fixed inset-0 z-40 bg-slate-950/45 backdrop-blur-sm sm:hidden"
        @click="sidebarOpen = false"
    ></div>

    <nav class="fixed inset-x-0 top-0 z-50 border-b border-slate-200/70 bg-white/85 backdrop-blur-xl dark:border-white/6 dark:bg-[#0b0b12]/85">
        <div class="mx-auto flex h-20 items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-white/8 dark:bg-white/[0.03] dark:text-slate-200 dark:hover:bg-white/[0.06]"
                    @click="window.innerWidth < 640 ? sidebarOpen = ! sidebarOpen : (desktopCollapsed = ! desktopCollapsed, syncSidebarState())"
                >
                    <span class="sr-only">Alternar menu</span>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>

                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-[#8c6dff] via-[#7b5cff] to-[#5230cc] shadow-[0_16px_40px_rgba(123,92,255,0.38)]">
                        <img src="{{ asset('Logo/Eventis_Logo.png') }}" alt="Logo" class="h-7 w-7 rounded-xl object-cover" />
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">EventIS</p>
                        <p class="text-[11px] uppercase tracking-[0.28em] text-slate-500 dark:text-slate-500">Admin Dashboard</p>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
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

                <div class="relative" @click.outside="userMenuOpen = false">
                    <button
                        type="button"
                        class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-2 py-1.5 text-left shadow-sm transition hover:bg-slate-50 dark:border-white/8 dark:bg-white/[0.03] dark:hover:bg-white/[0.06]"
                        @click="userMenuOpen = ! userMenuOpen"
                    >
                        <img
                            class="h-10 w-10 rounded-2xl"
                            src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&amp;color=fff&amp;background=7b5cff"
                            alt="{{ Auth::user()->name }}"
                        >
                        <div class="hidden sm:block">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-500">{{ Auth::user()->email }}</p>
                        </div>
                    </button>

                    <div
                        x-cloak
                        x-show="userMenuOpen"
                        x-transition.origin.top.right
                        class="absolute right-0 mt-3 w-64 rounded-3xl border border-slate-200 bg-white p-2 shadow-[0_30px_80px_rgba(15,23,42,0.15)] dark:border-white/8 dark:bg-[#12131d] dark:shadow-[0_30px_80px_rgba(0,0,0,0.45)]"
                    >
                        <x-dropdown-link href="{{ route('profile.show') }}" class="rounded-2xl text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-white/[0.04]">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-dropdown-link
                                href="{{ route('logout') }}"
                                class="rounded-2xl text-red-600 hover:bg-red-50 dark:text-red-300 dark:hover:bg-red-500/10"
                                @click.prevent="$root.submit();"
                            >
                                {{ __('Cerrar Sesion') }}
                            </x-dropdown-link>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside
        class="admin-sidebar fixed bottom-0 left-0 top-20 z-40 flex w-[17.5rem] flex-col border-r border-slate-200/70 bg-[#f7f9ff]/95 px-4 pb-4 pt-4 shadow-[20px_0_80px_rgba(15,23,42,0.08)] backdrop-blur-xl transition-all duration-200 dark:border-white/6 dark:bg-[#0d0e16]/95 dark:shadow-[20px_0_80px_rgba(0,0,0,0.35)]"
        :class="[
            sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            desktopCollapsed ? 'sm:w-24' : 'sm:w-[17.5rem]',
            'sm:translate-x-0'
        ]"
    >
        <div class="flex items-center justify-end sm:hidden">
            <button
                type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-white/8 dark:bg-white/[0.03] dark:text-slate-200 dark:hover:bg-white/[0.06]"
                @click="sidebarOpen = false"
            >
                <span class="sr-only">Cerrar menu</span>
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 0 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto pt-4" x-ref="sidebarScroll" @scroll.debounce.120ms="saveSidebarScroll()">
            <div class="mt-2">
                <p class="px-3 text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-500 dark:text-slate-600" :class="desktopCollapsed ? 'sm:hidden' : ''">Inicio</p>
                <nav class="mt-3 space-y-2">
                    @can('dashboard.view')
                        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" data-sidebar-active="{{ request()->routeIs('dashboard') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('dashboard') }}')" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3' : ''">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 13.2c0-.73.32-1.42.88-1.9l6.5-5.58a2.5 2.5 0 0 1 3.24 0l6.5 5.58c.56.48.88 1.17.88 1.9V19a2 2 0 0 1-2 2h-4.5v-4.5a1.5 1.5 0 0 0-1.5-1.5h-2a1.5 1.5 0 0 0-1.5 1.5V21H5a2 2 0 0 1-2-2v-5.8Z" />
                            </svg>
                            <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Dashboard</span>
                        </x-nav-link>
                    @endcan
                </nav>
            </div>

            <div class="mt-6">
                <p class="px-3 text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-500 dark:text-slate-600" :class="desktopCollapsed ? 'sm:hidden' : ''">Mi cuenta</p>
                <nav class="mt-3 space-y-2">
                    <x-nav-link href="{{ route('eventoVista') }}" :active="request()->routeIs('eventoVista')" data-sidebar-active="{{ request()->routeIs('eventoVista') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('eventoVista') }}')" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3' : ''">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 6a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1h1a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6Zm4 3a1 1 0 0 0 0 2h8a1 1 0 1 0 0-2H8Zm0 4a1 1 0 1 0 0 2h5a1 1 0 1 0 0-2H8Z" />
                        </svg>
                        <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Eventos</span>
                    </x-nav-link>

                    <x-nav-link href="{{ route('mi-historial') }}" :active="request()->routeIs('mi-historial')" data-sidebar-active="{{ request()->routeIs('mi-historial') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('mi-historial') }}')" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3' : ''">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2a10 10 0 1 0 10 10A10.01 10.01 0 0 0 12 2Zm1 5a1 1 0 0 0-2 0v5a1 1 0 0 0 .29.71l3 3a1 1 0 1 0 1.42-1.42L13 11.59V7Z" />
                        </svg>
                        <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Mis inscripciones</span>
                    </x-nav-link>

                    <x-nav-link href="{{ route('mis-certificados') }}" :active="request()->routeIs('mis-certificados')" data-sidebar-active="{{ request()->routeIs('mis-certificados') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('mis-certificados') }}')" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3' : ''">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2 3 7v6c0 5.2 3.84 9.74 9 11 5.16-1.26 9-5.8 9-11V7l-9-5Zm0 5.5 1.78 3.6 3.97.58-2.88 2.8.68 3.96L12 16.56l-3.55 1.88.68-3.96-2.88-2.8 3.97-.58L12 7.5Z" />
                        </svg>
                        <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Mis Certificados</span>
                    </x-nav-link>

                    <x-nav-link href="{{ route('mis-pagos') }}" :active="request()->routeIs('mis-pagos')" data-sidebar-active="{{ request()->routeIs('mis-pagos') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('mis-pagos') }}')" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3' : ''">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 6.75A2.75 2.75 0 0 1 5.75 4h12.5A2.75 2.75 0 0 1 21 6.75v10.5A2.75 2.75 0 0 1 18.25 20H5.75A2.75 2.75 0 0 1 3 17.25V6.75Zm2.75-1.25A1.25 1.25 0 0 0 4.5 6.75V8h15V6.75a1.25 1.25 0 0 0-1.25-1.25H5.75ZM19.5 11h-15v6.25c0 .69.56 1.25 1.25 1.25h12.5c.69 0 1.25-.56 1.25-1.25V11Zm-4.75 3a1 1 0 1 1 0 2h-2.5a1 1 0 1 1 0-2h2.5Z" />
                        </svg>
                        <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Mis Pagos</span>
                    </x-nav-link>
                </nav>
            </div>

            @if(auth()->user()->can('events.manage') || auth()->user()->can('attendances.manage') || auth()->user()->can('staff.attendance.scan'))
                <div class="mt-6">
                    <div class="mt-3">
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-2xl px-3 py-3 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-white/[0.04] dark:hover:text-white"
                            @click="toggleEventsMenu()"
                            :class="desktopCollapsed ? 'sm:justify-center' : ''"
                        >
                            <div class="flex items-center">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v3H2V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Z" />
                                    <path d="M2 12h20v7a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-7Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Gestión de Eventos</span>
                            </div>
                            <svg class="h-4 w-4 transition" :class="[eventsMenuOpen ? 'rotate-180' : '', desktopCollapsed ? 'sm:hidden' : '']" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 0 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-cloak x-show="eventsMenuOpen" x-transition class="mt-2 ml-5 space-y-1 border-l border-slate-200 pl-3 dark:border-white/10" :class="desktopCollapsed ? 'sm:ml-0 sm:border-l-0 sm:pl-0' : ''">
                            @if(auth()->user()->can('events.manage'))
                                <x-nav-link href="{{ route('eventos') }}" :active="request()->routeIs('eventos*')" data-sidebar-active="{{ request()->routeIs('eventos*') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('eventos') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v3H2V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Z" />
                                        <path d="M2 12h20v7a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-7Z" />
                                    </svg>
                                    <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Mis Eventos</span>
                                </x-nav-link>
                            @endif

                            @if(auth()->user()->can('events.manage'))
                                <x-nav-link href="{{ route('pagos.index') }}" :active="request()->routeIs('pagos*')" data-sidebar-active="{{ request()->routeIs('pagos*') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('pagos.index') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3 6.75A2.75 2.75 0 0 1 5.75 4h12.5A2.75 2.75 0 0 1 21 6.75v10.5A2.75 2.75 0 0 1 18.25 20H5.75A2.75 2.75 0 0 1 3 17.25V6.75Zm2.75-1.25A1.25 1.25 0 0 0 4.5 6.75V8h15V6.75a1.25 1.25 0 0 0-1.25-1.25H5.75ZM19.5 11h-15v6.25c0 .69.56 1.25 1.25 1.25h12.5c.69 0 1.25-.56 1.25-1.25V11Zm-4.75 3a1 1 0 1 1 0 2h-2.5a1 1 0 1 1 0-2h2.5Z" />
                                    </svg>
                                    <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Pagos</span>
                                </x-nav-link>
                            @endif

                            @if(auth()->user()->can('events.manage') || auth()->user()->can('attendances.manage') || auth()->user()->can('staff.attendance.scan'))
                                <x-nav-link href="{{ route('asistencia') }}" :active="request()->routeIs('asistencia*')" data-sidebar-active="{{ request()->routeIs('asistencia*') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('asistencia') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2a3 3 0 0 1 3 3v1h2.5A2.5 2.5 0 0 1 20 8.5v9A2.5 2.5 0 0 1 17.5 20h-11A2.5 2.5 0 0 1 4 17.5v-9A2.5 2.5 0 0 1 6.5 6H9V5a3 3 0 0 1 3-3Zm-1 4h2V5a1 1 0 1 0-2 0v1Zm1 4a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm0 1.5a.75.75 0 0 1 .75.75v1.44l.97.56a.75.75 0 0 1-.74 1.3l-1.35-.78a.75.75 0 0 1-.38-.65v-1.87a.75.75 0 0 1 .75-.75Z" />
                                    </svg>
                                    <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Asistencia</span>
                                </x-nav-link>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if(auth()->user()->hasRole('super-admin'))
                <div class="mt-6">
                    <p class="px-3 text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-500 dark:text-slate-600" :class="desktopCollapsed ? 'sm:hidden' : ''">Configuración</p>
                    <div class="mt-3">
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-2xl px-3 py-3 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-white/[0.04] dark:hover:text-white"
                            @click="toggleMaintenanceMenu()"
                            :class="desktopCollapsed ? 'sm:justify-center' : ''"
                        >
                            <div class="flex items-center">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19.14 12.94a7.43 7.43 0 0 0 .05-.94 7.43 7.43 0 0 0-.05-.94l2.03-1.58a.5.5 0 0 0 .12-.65l-1.92-3.32a.5.5 0 0 0-.61-.22l-2.39.96a7.28 7.28 0 0 0-1.63-.94l-.36-2.54A.5.5 0 0 0 13.9 2h-3.8a.5.5 0 0 0-.49.42l-.36 2.54a7.28 7.28 0 0 0-1.63.94l-2.39-.96a.5.5 0 0 0-.61.22L2.7 8.48a.5.5 0 0 0 .12.65l2.03 1.58a7.43 7.43 0 0 0-.05.94c0 .32.02.63.05.94l-2.03 1.58a.5.5 0 0 0-.12.65l1.92 3.32c.13.22.39.31.61.22l2.39-.96c.5.39 1.05.71 1.63.94l.36 2.54c.04.24.25.42.49.42h3.8c.24 0 .45-.18.49-.42l.36-2.54c.58-.23 1.13-.55 1.63-.94l2.39.96c.22.09.48 0 .61-.22l1.92-3.32a.5.5 0 0 0-.12-.65l-2.03-1.58ZM12 15.5A3.5 3.5 0 1 1 12 8a3.5 3.5 0 0 1 0 7.5Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Configuración</span>
                            </div>
                            <svg class="h-4 w-4 transition" :class="[maintenanceMenuOpen ? 'rotate-180' : '', desktopCollapsed ? 'sm:hidden' : '']" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 0 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-cloak x-show="maintenanceMenuOpen" x-transition class="mt-2 ml-5 space-y-1 border-l border-slate-200 pl-3 dark:border-white/10" :class="desktopCollapsed ? 'sm:ml-0 sm:border-l-0 sm:pl-0' : ''">
                            <x-nav-link href="{{ route('usuario') }}" :active="request()->routeIs('usuario')" data-sidebar-active="{{ request()->routeIs('usuario') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('usuario') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16 10a4 4 0 1 0-8 0 4 4 0 0 0 8 0ZM4 20a8 8 0 0 1 16 0Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Usuarios</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('rol') }}" :active="request()->routeIs('rol')" data-sidebar-active="{{ request()->routeIs('rol') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('rol') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 1 3 5v6c0 5 3.84 9.74 9 11 5.16-1.26 9-6 9-11V5l-9-4Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Roles</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('persona') }}" :active="request()->routeIs('persona')" data-sidebar-active="{{ request()->routeIs('persona') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('persona') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm-7 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Personas</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('nacionalidad') }}" :active="request()->routeIs('nacionalidad')" data-sidebar-active="{{ request()->routeIs('nacionalidad') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('nacionalidad') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2a10 10 0 1 0 10 10A10.01 10.01 0 0 0 12 2Zm6.93 9h-3.2a15.73 15.73 0 0 0-.8-4.02A8.03 8.03 0 0 1 18.93 11ZM12 4.07c.89 1.08 1.65 3.09 1.93 5.93h-3.86C10.35 7.16 11.11 5.15 12 4.07ZM4.07 13h3.2c.14 1.41.42 2.77.8 4.02A8.03 8.03 0 0 1 4.07 13Zm3.2-2h-3.2a8.03 8.03 0 0 1 4-4.02A15.73 15.73 0 0 0 7.27 11Zm4.73 8.93c-.89-1.08-1.65-3.09-1.93-5.93h3.86c-.28 2.84-1.04 4.85-1.93 5.93Zm2.93-2.91c.38-1.25.66-2.61.8-4.02h3.2a8.03 8.03 0 0 1-4 4.02Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Nacionalidades</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('modalidad') }}" :active="request()->routeIs('modalidad')" data-sidebar-active="{{ request()->routeIs('modalidad') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('modalidad') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M4 4h16v6H4V4Zm0 10h7v6H4v-6Zm9 0h7v6h-7v-6Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Modalidades</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('localidad') }}" :active="request()->routeIs('localidad')" data-sidebar-active="{{ request()->routeIs('localidad') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('localidad') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Localidades</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('tipoperfil') }}" :active="request()->routeIs('tipoperfil')" data-sidebar-active="{{ request()->routeIs('tipoperfil') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('tipoperfil') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M5 4h14a2 2 0 0 1 2 2v2H3V6a2 2 0 0 1 2-2Zm-2 6h18v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-8Zm4 2v2h4v-2H7Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Tipos de perfil</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('tipos-conferencias') }}" :active="request()->routeIs('tipos-conferencias')" data-sidebar-active="{{ request()->routeIs('tipos-conferencias') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('tipos-conferencias') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M4 5a2 2 0 0 1 2-2h8.5a2 2 0 0 1 1.41.59l3.5 3.5A2 2 0 0 1 20 8.5V19a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Zm4 6a1 1 0 0 0 0 2h8a1 1 0 1 0 0-2H8Zm0 4a1 1 0 1 0 0 2h5a1 1 0 1 0 0-2H8Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Tipos de conferencia</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('monedas') }}" :active="request()->routeIs('monedas')" data-sidebar-active="{{ request()->routeIs('monedas') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('monedas') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 3c4.42 0 8 1.79 8 4s-3.58 4-8 4-8-1.79-8-4 3.58-4 8-4Zm-8 9.5c1.4 1.65 4.46 2.75 8 2.75s6.6-1.1 8-2.75V17c0 2.21-3.58 4-8 4s-8-1.79-8-4v-4.5Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Monedas</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('integraciones-api') }}" :active="request()->routeIs('integraciones-api')" data-sidebar-active="{{ request()->routeIs('integraciones-api') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('integraciones-api') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M7 7a3 3 0 0 1 3-3h4a3 3 0 0 1 0 6h-1v4h1a3 3 0 0 1 0 6h-4a3 3 0 1 1 0-6h1v-4h-1a3 3 0 0 1-3-3Zm3-1a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-4Zm0 10a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-4Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Integraciones API</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('metodos-pago') }}" :active="request()->routeIs('metodos-pago')" data-sidebar-active="{{ request()->routeIs('metodos-pago') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('metodos-pago') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 6.75A2.75 2.75 0 0 1 5.75 4h12.5A2.75 2.75 0 0 1 21 6.75v10.5A2.75 2.75 0 0 1 18.25 20H5.75A2.75 2.75 0 0 1 3 17.25V6.75Zm2.75-1.25A1.25 1.25 0 0 0 4.5 6.75V8h15V6.75a1.25 1.25 0 0 0-1.25-1.25H5.75ZM19.5 11h-15v6.25c0 .69.56 1.25 1.25 1.25h12.5c.69 0 1.25-.56 1.25-1.25V11Zm-4.75 3a1 1 0 1 1 0 2h-2.5a1 1 0 1 1 0-2h2.5Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Métodos de pago</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('configuracion-facturacion') }}" :active="request()->routeIs('configuracion-facturacion')" data-sidebar-active="{{ request()->routeIs('configuracion-facturacion') ? 'true' : 'false' }}" @click.prevent="rememberAndFollow('{{ route('configuracion-facturacion') }}')" class="ps-4" ::class="desktopCollapsed ? 'sm:justify-center sm:px-3 sm:ps-3' : ''">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M4 5.75A2.75 2.75 0 0 1 6.75 3h8.88c.73 0 1.43.29 1.94.8l2.63 2.63c.51.51.8 1.21.8 1.94v9.88A2.75 2.75 0 0 1 18.25 21H6.75A2.75 2.75 0 0 1 4 18.25V5.75Zm4 5.25a1 1 0 0 0 0 2h8a1 1 0 1 0 0-2H8Zm0 4a1 1 0 1 0 0 2h5a1 1 0 1 0 0-2H8Z" />
                                </svg>
                                <span class="ms-3" :class="desktopCollapsed ? 'sm:hidden' : ''">Facturación</span>
                            </x-nav-link>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-4 rounded-[26px] border border-slate-200/80 bg-white/90 p-3 shadow-sm dark:border-white/6 dark:bg-white/[0.03]">
            <div class="flex items-center gap-3" :class="desktopCollapsed ? 'sm:justify-center' : ''">
                <img
                    class="h-11 w-11 rounded-2xl"
                    src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&amp;color=fff&amp;background=1b9aaa"
                    alt="{{ Auth::user()->name }}"
                >
                <div class="min-w-0" :class="desktopCollapsed ? 'sm:hidden' : ''">
                    <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs text-slate-500 dark:text-slate-500">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </aside>
</div>
