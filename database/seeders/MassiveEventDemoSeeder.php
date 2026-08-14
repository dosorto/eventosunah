<?php

namespace Database\Seeders;

use App\Models\Conferencia;
use App\Models\ConferenciaRegistroAsistencia;
use App\Models\Conferencista;
use App\Models\Evento;
use App\Models\EventoInvitacion;
use App\Models\EventoPrecio;
use App\Models\EventoRegistro;
use App\Models\EventoStaff;
use App\Models\Localidad;
use App\Models\MetodoPago;
use App\Models\Modalidad;
use App\Models\Moneda;
use App\Models\Nacionalidad;
use App\Models\Persona;
use App\Models\Tipoperfil;
use App\Models\TipoConferencia;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class MassiveEventDemoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('es_ES');
        $now = now();

        $roles = Role::query()->pluck('id', 'name');
        $profileTypes = Tipoperfil::query()->orderBy('id')->get()->keyBy('tipoperfil');
        $eventTypes = TipoConferencia::query()->orderBy('id')->get()->values();
        $modalidades = Modalidad::query()->orderBy('id')->get()->values();
        $nationalityIds = Nacionalidad::query()->pluck('id')->values()->all();
        $localidades = Localidad::query()->orderBy('id')->get()->values();
        $currencyHnl = Moneda::query()->where('codigo', 'HNL')->first();

        $admin = User::query()->where('email', 'admin@gmail.com')->firstOrFail();
        $this->ensureAdminPersona($admin->id, $profileTypes->get('Empleado')?->id ?? $profileTypes->first()->id, $nationalityIds[0] ?? 1);
        $paymentMethods = $this->seedPaymentMethods();

        [$demoUsers, $demoPeople] = $this->seedUsersAndPersonas(
            count: 1000,
            roleIds: $roles,
            profileTypes: $profileTypes,
            nationalityIds: $nationalityIds,
            now: $now,
        );

        $speakerPool = $this->seedSpeakers($demoPeople, $demoUsers, $roles['conferencista'] ?? null, $now);
        $events = $this->seedEvents($eventTypes, $modalidades, $localidades, $currencyHnl?->id, $now);
        $priceMap = $this->seedEventPrices($events, $profileTypes, $currencyHnl?->id ?? Moneda::query()->value('id') ?? 1, $now);
        $conferenceMap = $this->seedConferences($events, $speakerPool, $eventTypes, $now);
        $registrationMap = $this->seedRegistrations($events, $demoPeople, $paymentMethods, $priceMap, $now);
        $this->seedAttendance($conferenceMap, $registrationMap, $now);
        $this->seedGuests($events, $now);
        $this->seedStaff($events, $demoPeople, $demoUsers, $roles['staff-evento'] ?? null, $now);
    }

    private function ensureAdminPersona(int $userId, int $profileId, int $nationalityId): void
    {
        Persona::query()->updateOrCreate(
            ['IdUsuario' => $userId],
            [
                'dni' => '0801199000001',
                'nombre' => 'Root',
                'apellido' => 'Administrador',
                'correo' => 'admin@gmail.com',
                'correoInstitucional' => 'admin@unah.edu.hn',
                'fechaNacimiento' => '1990-01-01',
                'sexo' => 'M',
                'direccion' => 'Ciudad Universitaria, Tegucigalpa',
                'telefono' => '99990001',
                'numeroCuenta' => null,
                'numeroEmpleado' => 'ADM-0001',
                'IdNacionalidad' => $nationalityId,
                'IdTipoPerfil' => $profileId,
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );
    }

    private function seedPaymentMethods(): Collection
    {
        return collect([
            [
                'nombre' => 'Pago presencial',
                'codigo' => 'PRESENCIAL',
                'descripcion' => 'Pago administrativo registrado manualmente.',
                'instrucciones' => 'Presenta tu comprobante en la mesa de registro.',
                'orden' => 1,
            ],
            [
                'nombre' => 'Transferencia bancaria',
                'codigo' => 'TRANSFERENCIA',
                'descripcion' => 'Transferencia o depósito bancario validado por el organizador.',
                'instrucciones' => 'Envía el comprobante al correo del evento.',
                'orden' => 2,
            ],
            [
                'nombre' => 'Tarjeta demo',
                'codigo' => 'TARJETA_DEMO',
                'descripcion' => 'Método de prueba para demos del flujo de cobro.',
                'instrucciones' => 'Método de prueba sin integración real.',
                'orden' => 3,
            ],
        ])->map(function (array $payment) {
            return MetodoPago::query()->updateOrCreate(
                ['codigo' => $payment['codigo']],
                [
                    ...$payment,
                    'requiere_api' => false,
                    'http_method' => 'POST',
                    'auth_type' => 'none',
                    'is_active' => true,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );
        });
    }

    private function seedUsersAndPersonas(
        int $count,
        Collection $roleIds,
        Collection $profileTypes,
        array $nationalityIds,
        Carbon $now
    ): array {
        $password = Hash::make('12345678');
        $userRows = [];
        $personRows = [];

        for ($i = 1; $i <= $count; $i++) {
            $profileName = match (true) {
                $i % 10 <= 3 => 'Estudiante',
                $i % 10 <= 7 => 'Empleado',
                default => 'Externo',
            };

            $profile = $profileTypes->get($profileName) ?? $profileTypes->first();
            $firstName = fake('es_ES')->firstName();
            $firstLastName = fake('es_ES')->lastName();
            $secondLastName = fake('es_ES')->lastName();
            $lastName = $firstLastName . ' ' . $secondLastName;
            $email = sprintf('demo.user.%04d@eventosunah.test', $i);
            $dni = (string) (1700000000000 + $i);
            $numeroCuenta = $profileName === 'Estudiante' ? sprintf('2026%07d', $i) : null;
            $numeroEmpleado = $profileName === 'Empleado' ? sprintf('EMP%05d', $i) : null;

            $userRows[] = [
                'name' => trim($firstName . ' ' . $lastName),
                'email' => $email,
                'email_verified_at' => $now,
                'password' => $password,
                'remember_token' => Str::random(10),
                'current_team_id' => null,
                'profile_photo_path' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $personRows[] = [
                'email' => $email,
                'dni' => $dni,
                'primer_nombre' => $firstName,
                'segundo_nombre' => null,
                'primer_apellido' => $firstLastName,
                'segundo_apellido' => $secondLastName,
                'correo' => $email,
                'correoInstitucional' => $profileName !== 'Externo' ? sprintf('usuario%04d@unah.edu.hn', $i) : null,
                'fechaNacimiento' => fake('es_ES')->dateTimeBetween('-45 years', '-18 years')->format('Y-m-d'),
                'sexo' => $i % 2 === 0 ? 'F' : 'M',
                'direccion' => fake('es_ES')->address(),
                'telefono' => sprintf('9%07d', 1000000 + $i),
                'numeroCuenta' => $numeroCuenta,
                'numeroEmpleado' => $numeroEmpleado,
                'IdNacionalidad' => $nationalityIds[array_rand($nationalityIds)],
                'IdTipoPerfil' => $profile->id,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($userRows, 200) as $chunk) {
            DB::table('users')->insert($chunk);
        }

        $users = User::query()
            ->where('email', 'like', 'demo.user.%@eventosunah.test')
            ->get()
            ->keyBy('email');

        $personaInserts = [];
        foreach ($personRows as $row) {
            $user = $users->get($row['email']);

            $personaInserts[] = [
                'IdUsuario' => $user?->id,
                'dni' => $row['dni'],
                'primer_nombre' => $row['primer_nombre'],
                'segundo_nombre' => $row['segundo_nombre'],
                'primer_apellido' => $row['primer_apellido'],
                'segundo_apellido' => $row['segundo_apellido'],
                'correo' => $row['correo'],
                'correoInstitucional' => $row['correoInstitucional'],
                'fechaNacimiento' => $row['fechaNacimiento'],
                'sexo' => $row['sexo'],
                'direccion' => $row['direccion'],
                'telefono' => $row['telefono'],
                'numeroCuenta' => $row['numeroCuenta'],
                'numeroEmpleado' => $row['numeroEmpleado'],
                'IdNacionalidad' => $row['IdNacionalidad'],
                'IdTipoPerfil' => $row['IdTipoPerfil'],
                'created_by' => $row['created_by'],
                'updated_by' => $row['updated_by'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
            ];
        }

        foreach (array_chunk($personaInserts, 200) as $chunk) {
            DB::table('personas')->insert($chunk);
        }

        $participantRoleId = $roleIds['participante'] ?? null;
        if ($participantRoleId) {
            $rolePivotRows = $users->values()->map(fn (User $user) => [
                'role_id' => $participantRoleId,
                'model_type' => User::class,
                'model_id' => $user->id,
            ])->all();

            foreach (array_chunk($rolePivotRows, 500) as $chunk) {
                DB::table('model_has_roles')->insertOrIgnore($chunk);
            }
        }

        $people = Persona::query()
            ->with('tipoPerfil')
            ->where('correo', 'like', 'demo.user.%@eventosunah.test')
            ->get()
            ->values();

        return [$users->values(), $people];
    }

    private function seedSpeakers(Collection $people, Collection $users, ?int $roleId, Carbon $now): Collection
    {
        $speakerPeople = $people->shuffle()->take(140)->values();
        $rows = [];
        $roleRows = [];

        foreach ($speakerPeople as $index => $person) {
            $rows[] = [
                'IdPersona' => $person->id,
                'foto' => 'Logo/Eventis_Logo.png',
                'titulo' => ['MSc.', 'Dr.', 'Lic.', 'Ing.'][$index % 4],
                'nivel_academico' => ['Licenciatura', 'Maestria', 'Doctor', 'PosDoctorado'][$index % 4],
                'descripcion' => 'Especialista invitado para contenidos académicos, talleres prácticos y sesiones de networking del evento.',
                'firma' => null,
                'sello' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($roleId && $person->IdUsuario) {
                $roleRows[] = [
                    'role_id' => $roleId,
                    'model_type' => User::class,
                    'model_id' => $person->IdUsuario,
                ];
            }
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('conferencistas')->insert($chunk);
        }

        if ($roleRows !== []) {
            foreach (array_chunk($roleRows, 500) as $chunk) {
                DB::table('model_has_roles')->insertOrIgnore($chunk);
            }
        }

        return Conferencista::query()
            ->with('persona')
            ->whereIn('IdPersona', $speakerPeople->pluck('id'))
            ->get()
            ->values();
    }

    private function seedEvents(Collection $eventTypes, Collection $modalidades, Collection $localidades, ?int $currencyId, Carbon $now): Collection
    {
        $events = collect();

        for ($i = 1; $i <= 20; $i++) {
            $type = $eventTypes[($i - 1) % max(1, $eventTypes->count())];
            $modalidad = $modalidades[($i - 1) % max(1, $modalidades->count())];
            $localidad = $localidades[($i - 1) % max(1, $localidades->count())];

            $startDate = match (true) {
                $i <= 4 => Carbon::today(),
                $i <= 16 => Carbon::today()->addDays(($i - 4) * 3),
                default => Carbon::today()->subDays(($i - 16) * 2),
            };

            $dayCount = ($i % 3) + 1;
            $isPaid = $i % 2 === 0;
            $withConferenceCertificates = $i % 3 !== 0;

            $event = Evento::query()->create([
                'logo' => 'Logo/Eventis_Logo.png',
                'banner' => 'Logo/imagesfondo/bg.svg',
                'nombreevento' => $type->tipo . ' UNAH ' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'descripcion' => 'Evento de demostración para pruebas funcionales del sistema de eventos, inscripciones, certificados, invitados y asistencia con múltiples perfiles de usuario.',
                'organizador' => 'Universidad Nacional Autónoma de Honduras',
                'tipo_conferencia_id' => $type->id,
                'fechainicio' => $startDate->toDateString(),
                'fechafinal' => $startDate->copy()->addDays($dayCount - 1)->toDateString(),
                'horainicio' => '08:00:00',
                'horafin' => '18:00:00',
                'idmodalidad' => $modalidad->id,
                'idlocalidad' => $localidad->id,
                'localidad_nombre' => $localidad->localidad,
                'tipo_acceso' => $isPaid ? 'pagada' : 'gratuita',
                'genera_diploma_participacion' => true,
                'IdDiploma' => null,
                'graphic_designs' => $this->baseGraphicDesigns($withConferenceCertificates, $currencyId),
                'estado' => 'publicado',
                'published_at' => $now,
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            $events->push($event);
        }

        return $events;
    }

    private function baseGraphicDesigns(bool $withConferenceCertificates, ?int $currencyId): array
    {
        $general = [
            'file' => 'Logo/Eventis_Logo.png',
            'page_size' => 'Carta',
            'orientation' => 'Horizontal',
            'font_size' => 24,
            'qr_size' => 18,
            'name_x' => 50,
            'name_y' => 55,
            'qr_x' => 83,
            'qr_y' => 84,
        ];

        return array_filter([
            'participacion_general' => $general,
            'diploma_conferencia' => $withConferenceCertificates ? [
                'file' => 'Logo/Eventis_Logo.png',
                'page_size' => 'Carta',
                'orientation' => 'Horizontal',
                'font_size' => 22,
                'qr_size' => 18,
                'name_x' => 50,
                'name_y' => 55,
                'qr_x' => 83,
                'qr_y' => 84,
            ] : null,
        ]);
    }

    private function seedEventPrices(Collection $events, Collection $profiles, int $currencyId, Carbon $now): array
    {
        $priceMap = [];

        foreach ($events as $event) {
            if ($event->tipo_acceso !== 'pagada') {
                continue;
            }

            foreach ($profiles as $profile) {
                $baseAmount = match ($profile->tipoperfil) {
                    'Estudiante' => 350,
                    'Empleado' => 550,
                    default => 800,
                } + (($event->id % 5) * 25);

                $priceMap[$event->id][$profile->id]['base'] = [
                    'amount' => $baseAmount,
                    'currency_id' => $currencyId,
                ];

                EventoPrecio::query()->create([
                    'evento_id' => $event->id,
                    'IdTipoPerfil' => $profile->id,
                    'moneda_id' => $currencyId,
                    'categoria_key' => Str::slug($profile->tipoperfil),
                    'categoria_nombre' => $profile->tipoperfil,
                    'es_precio_evento_dia' => true,
                    'precio' => $baseAmount,
                    'fecha_inicio' => null,
                    'fecha_fin' => null,
                    'orden' => 10,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);

                if ($event->fechainicio && Carbon::parse($event->fechainicio)->greaterThan(Carbon::today()->addDays(4))) {
                    $promoAmount = max(150, $baseAmount - 100);
                    $promoStart = Carbon::today();
                    $promoEnd = Carbon::parse($event->fechainicio)->copy()->subDays(3);

                    EventoPrecio::query()->create([
                        'evento_id' => $event->id,
                        'IdTipoPerfil' => $profile->id,
                        'moneda_id' => $currencyId,
                        'categoria_key' => Str::slug($profile->tipoperfil) . '-promo',
                        'categoria_nombre' => $profile->tipoperfil . ' promocional',
                        'es_precio_evento_dia' => false,
                        'precio' => $promoAmount,
                        'fecha_inicio' => $promoStart->toDateString(),
                        'fecha_fin' => $promoEnd->toDateString(),
                        'orden' => 5,
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]);

                    $priceMap[$event->id][$profile->id]['promo'] = [
                        'amount' => $promoAmount,
                        'starts_at' => $promoStart->toDateString(),
                        'ends_at' => $promoEnd->toDateString(),
                        'currency_id' => $currencyId,
                    ];
                }
            }
        }

        return $priceMap;
    }

    private function seedConferences(Collection $events, Collection $speakerPool, Collection $eventTypes, Carbon $now): array
    {
        $conferenceMap = [];
        $timeSlots = [
            ['09:00:00', '10:30:00'],
            ['11:00:00', '12:30:00'],
            ['14:00:00', '15:30:00'],
            ['16:00:00', '17:30:00'],
        ];

        foreach ($events as $event) {
            $days = Carbon::parse($event->fechainicio)->diffInDays(Carbon::parse($event->fechafinal)) + 1;
            $speakerIndexBase = ($event->id - 1) * 4;

            for ($day = 0; $day < $days; $day++) {
                $conferenceDate = Carbon::parse($event->fechainicio)->addDays($day);
                $slotsForDay = $day === 0 ? 2 : (($day % 2) === 0 ? 1 : 2);

                for ($slot = 0; $slot < $slotsForDay; $slot++) {
                    $speaker = $speakerPool[($speakerIndexBase + $day + $slot) % max(1, $speakerPool->count())];
                    [$hourStart, $hourEnd] = $timeSlots[($day + $slot) % count($timeSlots)];
                    $type = $eventTypes[($event->id + $slot) % max(1, $eventTypes->count())];
                    $speakerName = trim(($speaker->persona?->nombre ?? 'Ponente') . ' ' . ($speaker->persona?->apellido ?? 'Invitado'));

                    $conference = Conferencia::query()->create([
                        'IdEvento' => $event->id,
                        'tipo_conferencia_id' => $type->id,
                        'foto' => 'Logo/Eventis_Logo.png',
                        'nombre' => $type->tipo . ' ' . ($slot + 1) . ' del día ' . ($day + 1),
                        'descripcion' => 'Sesión demostrativa creada para poblar la agenda del evento y validar el flujo de asistencia, certificados y detalle público.',
                        'fecha' => $conferenceDate->toDateString(),
                        'horaInicio' => $hourStart,
                        'horaFin' => $hourEnd,
                        'lugar' => str_contains(mb_strtolower((string) $event->modalidad?->modalidad), 'virtual')
                            ? 'Sala virtual principal'
                            : ($event->localidad_nombre ?: 'Auditorio principal'),
                        'linkreunion' => str_contains(mb_strtolower((string) $event->modalidad?->modalidad), 'presencial')
                            ? null
                            : 'https://meet.example.com/evento-' . $event->id . '-conf-' . $day . '-' . $slot,
                        'conferencista_nombre_invitado' => $speakerName,
                        'speaker_persona_id' => $speaker->IdPersona,
                        'idConferencista' => $speaker->id,
                        'speaker_access_token' => (string) Str::uuid(),
                        'speaker_profile_completed_at' => $now,
                        'conference_content_completed_at' => $now,
                        'speaker_onboarding_step' => 4,
                        'speaker_onboarding_submitted_at' => $now,
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]);

                    $conferenceMap[$event->id][] = $conference;
                }
            }
        }

        return $conferenceMap;
    }

    private function seedRegistrations(
        Collection $events,
        Collection $people,
        Collection $paymentMethods,
        array $priceMap,
        Carbon $now
    ): array {
        $registrationMap = [];
        $peopleByProfile = $people->groupBy('IdTipoPerfil');
        $defaultPaymentMethodId = $paymentMethods->first()?->id;

        foreach ($events as $event) {
            $selectedPeople = $people->shuffle()->take(400)->values();
            $rows = [];

            foreach ($selectedPeople as $person) {
                $profileId = $person->IdTipoPerfil;
                $isPaid = $event->tipo_acceso === 'pagada';
                $pricePreview = $this->resolvePriceForProfile($priceMap[$event->id][$profileId] ?? null, Carbon::today());

                $rows[] = [
                    'evento_id' => $event->id,
                    'persona_id' => $person->id,
                    'tipoperfil_id' => $profileId,
                    'metodo_pago_id' => $isPaid ? $defaultPaymentMethodId : null,
                    'precio_aplicado' => $isPaid ? $pricePreview['amount'] : 0,
                    'detalle_precio' => $isPaid ? $pricePreview['label'] : 'Evento gratuito',
                    'estado' => 'registrado',
                    'estado_pago' => $isPaid ? 'pagado' : 'no_aplica',
                    'referencia_pago' => $isPaid ? strtoupper(Str::random(12)) : null,
                    'payload_pago' => $isPaid ? json_encode(['seeded' => true, 'metodo' => 'demo']) : null,
                    'pagado_en' => $isPaid ? $now : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            foreach (array_chunk($rows, 200) as $chunk) {
                DB::table('evento_registros')->insert($chunk);
            }

            $registrationMap[$event->id] = EventoRegistro::query()
                ->where('evento_id', $event->id)
                ->get()
                ->values();
        }

        return $registrationMap;
    }

    private function resolvePriceForProfile(?array $config, Carbon $today): array
    {
        if (! $config) {
            return ['amount' => 0, 'label' => 'Tarifa general'];
        }

        if (isset($config['promo'])) {
            $promoStarts = Carbon::parse($config['promo']['starts_at']);
            $promoEnds = Carbon::parse($config['promo']['ends_at']);

            if ($today->betweenIncluded($promoStarts, $promoEnds)) {
                return ['amount' => $config['promo']['amount'], 'label' => 'Precio promocional vigente'];
            }
        }

        return ['amount' => $config['base']['amount'], 'label' => 'Precio del día del evento'];
    }

    private function seedAttendance(array $conferenceMap, array $registrationMap, Carbon $now): void
    {
        $rows = [];

        foreach ($conferenceMap as $eventId => $conferences) {
            $registrations = collect($registrationMap[$eventId] ?? []);

            foreach ($conferences as $conference) {
                $attendanceRows = $registrations->shuffle()->take(random_int(60, 140))->map(function (EventoRegistro $registration) use ($conference, $now) {
                    return [
                        'conferencia_id' => $conference->id,
                        'evento_registro_id' => $registration->id,
                        'checked_in_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                })->all();

                $rows = [...$rows, ...$attendanceRows];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('conferencia_registro_asistencias')->insertOrIgnore($chunk);
        }
    }

    private function seedGuests(Collection $events, Carbon $now): void
    {
        foreach ($events as $event) {
            $rows = [];

            for ($i = 1; $i <= 15; $i++) {
                $sent = $i % 3 !== 0;
                $channel = $sent ? ($i % 2 === 0 ? 'correo' : 'whatsapp') : null;
                $sentAt = $sent ? $now->copy()->subHours($i) : null;

                $rows[] = [
                    'evento_id' => $event->id,
                    'codigo' => strtoupper(Str::random(10)),
                    'nombre_invitado' => fake('es_ES')->name(),
                    'correo_invitado' => sprintf('invitado.%d.%d@example.test', $event->id, $i),
                    'telefono_invitado' => sprintf('8%07d', ($event->id * 100) + $i),
                    'cupos' => ($i % 3) + 1,
                    'cupos_utilizados' => 0,
                    'activa' => true,
                    'enviada_at' => $sentAt,
                    'correo_enviado_at' => $channel === 'correo' ? $sentAt : null,
                    'whatsapp_enviado_at' => $channel === 'whatsapp' ? $sentAt : null,
                    'ultimo_canal_envio' => $channel,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            foreach (array_chunk($rows, 100) as $chunk) {
                DB::table('evento_invitaciones')->insert($chunk);
            }
        }
    }

    private function seedStaff(Collection $events, Collection $people, Collection $users, ?int $roleId, Carbon $now): void
    {
        $roleRows = [];

        foreach ($events as $event) {
            $staffPeople = $people->shuffle()->take(6)->values();
            $rows = [];

            foreach ($staffPeople as $index => $person) {
                $completed = $index < 4;

                $rows[] = [
                    'evento_id' => $event->id,
                    'persona_id' => $completed ? $person->id : null,
                    'nombre' => trim($person->nombre . ' ' . $person->apellido),
                    'correo' => $person->correo,
                    'telefono' => $person->telefono,
                    'staff_access_token' => (string) Str::uuid(),
                    'invitado_at' => $now->copy()->subDays(2),
                    'perfil_completado_at' => $completed ? $now->copy()->subDay() : null,
                    'activo' => true,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if ($completed && $roleId && $person->IdUsuario) {
                    $roleRows[] = [
                        'role_id' => $roleId,
                        'model_type' => User::class,
                        'model_id' => $person->IdUsuario,
                    ];
                }
            }

            foreach (array_chunk($rows, 100) as $chunk) {
                DB::table('evento_staff')->insert($chunk);
            }
        }

        if ($roleRows !== []) {
            foreach (array_chunk($roleRows, 500) as $chunk) {
                DB::table('model_has_roles')->insertOrIgnore($chunk);
            }
        }
    }
}
