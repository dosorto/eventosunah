<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EventoRegistro;
use App\Models\Persona;
use App\Models\Suscripcion;
use App\Models\User;
use App\Models\Evento;
use App\Models\Conferencia;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $persona = $user?->persona;
        $isParticipantDashboard = $user?->hasRole('participante') ?? false;
        $now = Carbon::now();
        $cantidadEventos = Evento::count();
        $eventosFinalizados = Evento::where('fechafinal', '<', Carbon::now())->count();
        $eventosActivos = Evento::where('fechainicio', '<=', $now)->where('fechafinal', '>=', $now)->count();
        $cantidadConferencias = Conferencia::count();
        $cantidadInscripciones = Suscripcion::count();

        $conferenciass = Conferencia::withCount(['suscripciones as unique_subscriptions' => function ($query) {
            $query->select(DB::raw('count(distinct IdPersona)'));
        }])
        ->having('unique_subscriptions', '>', 0)
        ->orderByDesc('unique_subscriptions')
        ->take(5)
        ->get();

        $conferencias = Conferencia::with(['conferencista.persona', 'evento'])
            ->orderBy('fecha')
            ->take(6)
            ->get();

        $eventosPresenciales = Evento::join('modalidads', 'eventos.idmodalidad', '=', 'modalidads.id')
            ->where('modalidads.modalidad', 'Presencial')
            ->count();

        $eventosVirtuales = Evento::join('modalidads', 'eventos.idmodalidad', '=', 'modalidads.id')
            ->where('modalidads.modalidad', 'Virtual')
            ->count();

        $totalUsuarios = User::count();
        $ocupacionEventos = $cantidadEventos > 0
            ? round(($eventosActivos / $cantidadEventos) * 100)
            : 0;

        $quickActions = collect([
            [
                'label' => 'Eventos',
                'description' => 'Administra jornadas, congresos y ferias.',
                'route' => route('eventos'),
                'permission' => 'events.manage',
                'icon' => 'calendar',
            ],
            [
                'label' => 'Conferencias',
                'description' => 'Gestiona la agenda de charlas y ponencias.',
                'route' => route('conferencia'),
                'permission' => 'conferences.manage',
                'icon' => 'presentation',
            ],
            [
                'label' => 'Conferencistas',
                'description' => 'Mantiene actualizada la ficha de expositores.',
                'route' => route('conferencista'),
                'permission' => 'speakers.manage',
                'icon' => 'mic',
            ],
            [
                'label' => 'Asistencias',
                'description' => 'Controla el marcaje por conferencia.',
                'route' => route('asistencia'),
                'permission' => 'attendances.manage',
                'icon' => 'check',
            ],
            [
                'label' => 'Diplomas',
                'description' => 'Plantillas, emision y validacion.',
                'route' => route('diploma'),
                'permission' => 'diplomas.manage',
                'icon' => 'certificate',
            ],
            [
                'label' => 'Personas',
                'description' => 'Consulta y administra participantes.',
                'route' => route('persona'),
                'permission' => 'people.manage',
                'icon' => 'users',
            ],
            [
                'label' => 'Usuarios',
                'description' => 'Accesos, cuentas y seguridad.',
                'route' => route('usuario'),
                'permission' => 'security.users.manage',
                'icon' => 'shield',
            ],
            [
                'label' => 'Roles',
                'description' => 'Define permisos por perfil operativo.',
                'route' => route('rol'),
                'permission' => 'security.roles.manage',
                'icon' => 'lock',
            ],
            [
                'label' => 'Eventos',
                'description' => 'Explora el catálogo interno y revisa los eventos disponibles.',
                'route' => route('eventoVista'),
                'permission' => 'participant.portal.access',
                'icon' => 'rocket',
            ],
            [
                'label' => 'Eventos',
                'description' => 'Explora el catálogo interno para inscribirte a nuevos eventos.',
                'route' => route('eventoVista'),
                'permission' => 'participant.registrations.manage',
                'icon' => 'calendar',
            ],
            [
                'label' => 'Mis inscripciones',
                'description' => 'Consulta los eventos en los que ya te registraste.',
                'route' => route('mi-historial'),
                'permission' => 'participant.history.view',
                'icon' => 'spark',
            ],
        ])->filter(fn (array $action) => $user->can($action['permission']))->values();

        if ($user) {
            $quickActions->push([
                'label' => 'Perfil',
                'description' => 'Edita tus datos personales y tu cuenta.',
                'route' => route('profile.show'),
                'permission' => null,
                'icon' => 'badge',
            ]);
        }

        $quickActions = $quickActions->values();

        $participantEventRegistrations = $persona
            ? EventoRegistro::query()
                ->with('evento')
                ->where('persona_id', $persona->id)
                ->get()
            : collect();

        $participantUpcomingRegistrations = $participantEventRegistrations->filter(function ($registration) use ($now) {
            return optional($registration->evento?->fechafinal)->greaterThanOrEqualTo($now->copy()->startOfDay());
        });

        $participantPaidRegistrations = $participantEventRegistrations->filter(function ($registration) {
            return $registration->evento?->tipo_acceso === 'pagada';
        });

        if ($user->hasRole('participante')) {
            $userStats = [
                [
                    'label' => 'Mi perfil',
                    'value' => $persona?->tipoPerfil?->tipoperfil
                        ?: ($user->getRoleNames()->first() ? Str::headline($user->getRoleNames()->first()) : 'Participante'),
                    'meta' => 'Tipo de perfil registrado en el sistema',
                    'icon' => 'badge',
                ],
                [
                    'label' => 'Eventos inscritos',
                    'value' => $participantEventRegistrations->count(),
                    'meta' => 'Registros acumulados en eventos publicados',
                    'icon' => 'calendar',
                ],
                [
                    'label' => 'Proximos eventos',
                    'value' => $participantUpcomingRegistrations->count(),
                    'meta' => 'Eventos vigentes o pendientes de realizarse',
                    'icon' => 'rocket',
                ],
            ];

            $cantidadEventos = Evento::published()->count();
            $eventosFinalizados = $participantEventRegistrations->filter(function ($registration) use ($now) {
                return optional($registration->evento?->fechafinal)->lt($now->copy()->startOfDay());
            })->count();
            $eventosActivos = $participantUpcomingRegistrations->count();
            $cantidadConferencias = $participantEventRegistrations
                ->pluck('evento')
                ->filter()
                ->unique('id')
                ->sum(fn ($evento) => $evento->conferencias()->count());
            $cantidadInscripciones = $participantEventRegistrations->count();
            $totalUsuarios = $participantPaidRegistrations->count();
            $eventosPresenciales = $participantEventRegistrations->filter(fn ($registration) => strtolower((string) $registration->evento?->modalidad?->modalidad) === 'presencial')->count();
            $eventosVirtuales = $participantEventRegistrations->filter(fn ($registration) => strtolower((string) $registration->evento?->modalidad?->modalidad) === 'virtual')->count();
            $ocupacionEventos = $cantidadInscripciones > 0
                ? min(100, round(($eventosActivos / max($cantidadInscripciones, 1)) * 100))
                : 0;
            $conferenciass = collect();
            $conferencias = $participantEventRegistrations
                ->pluck('evento')
                ->filter()
                ->unique('id')
                ->take(6)
                ->flatMap(fn ($evento) => $evento->conferencias()->with(['conferencista.persona', 'evento'])->get())
                ->sortBy('fecha')
                ->take(6)
                ->values();
        } else {
            $userStats = [
                [
                    'label' => 'Rol principal',
                    'value' => $user->getRoleNames()->first()
                        ? Str::headline($user->getRoleNames()->first())
                        : 'Sin rol',
                    'meta' => 'Perfil con el que estas operando',
                    'icon' => 'badge',
                ],
                [
                    'label' => 'Permisos activos',
                    'value' => $user->getAllPermissions()->count(),
                    'meta' => 'Capacidades habilitadas para tu cuenta',
                    'icon' => 'spark',
                ],
                [
                    'label' => 'Accesos rapidos',
                    'value' => $quickActions->count(),
                    'meta' => 'Modulos disponibles desde tu rol',
                    'icon' => 'grid',
                ],
            ];
        }

        return view('dashboard', [
            'isParticipantDashboard' => $isParticipantDashboard,
            'cantidadEventos' => $cantidadEventos,
            'eventosFinalizados' => $eventosFinalizados,
            'eventosActivos' => $eventosActivos,
            'eventosPresenciales' => $eventosPresenciales,
            'eventosVirtuales' => $eventosVirtuales,
            'totalUsuarios' => $totalUsuarios,
            'cantidadConferencias' => $cantidadConferencias,
            'cantidadInscripciones' => $cantidadInscripciones,
            'ocupacionEventos' => $ocupacionEventos,
            'conferencias' => $conferencias,
            'now' => $now,
            'conferenciass' => $conferenciass,
            'quickActions' => $quickActions,
            'userStats' => $userStats,
            'participantRegistrations' => $participantEventRegistrations,
        ]);
    }
}
