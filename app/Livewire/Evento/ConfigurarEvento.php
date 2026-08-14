<?php

namespace App\Livewire\Evento;

use App\Models\Conferencia;
use App\Models\Conferencista;
use App\Models\Evento;
use App\Models\EventoInvitacion;
use App\Models\EventoPrecio;
use App\Models\Localidad;
use App\Models\Modalidad;
use App\Models\Moneda;
use App\Models\Nacionalidad;
use App\Models\Persona;
use App\Models\TipoConferencia;
use App\Models\Tipoperfil;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ConfigurarEvento extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    private const AVAILABLE_PAGE_SIZES = [
        'Carta',
        'Oficio',
        'A4',
        'A3',
        'Legal',
        'Personalizado',
    ];

    private const AVAILABLE_ORIENTATIONS = [
        'Horizontal',
        'Vertical',
    ];

    private const DESIGN_TYPES = [
        'participacion_general' => 'Diplomas de participacion general',
        'diploma_conferencia' => 'Diplomas por conferencia',
        'gafete_participante' => 'Gafetes de participantes',
        'gafete_staff' => 'Gafetes del staff',
        'invitacion' => 'Diseño de invitaciones',
        'diploma_conferencista' => 'Diplomas a conferencistas',
    ];

    private const REQUIRED_DESIGN_KEY = 'participacion_general';

    private const PAGE_SIZE_RATIOS = [
        'Carta' => [8.5, 11],
        'Oficio' => [8.5, 13],
        'A4' => [210, 297],
        'A3' => [297, 420],
        'Legal' => [8.5, 14],
        'Personalizado' => [1, 1.414],
    ];

    public Evento $evento;
    public $logo;
    public $banner;
    public $graphicDesignFile;
    public string $nombreevento = '';
    public string $descripcion = '';
    public string $organizador = '';
    public string $tipo_conferencia_id = '';
    public string $idmodalidad = '';
    public string $localidad_nombre = '';
    public string $tipo_acceso = 'gratuita';
    public bool $genera_diploma_participacion = true;
    public string $fechainicio = '';
    public string $fechafinal = '';
    public string $horainicio = '';
    public string $horafin = '';
    public string $activeSection = 'logo';
    public string $activeDesignKey = 'participacion_general';
    public string $activeMediaType = 'logo';
    public string $design_page_size = '';
    public string $design_orientation = '';
    public int|string $design_name_x = 50;
    public int|string $design_name_y = 70;
    public int|string $design_qr_x = 84;
    public int|string $design_qr_y = 84;
    public int|string $design_font_size = 6;
    public int|string $design_qr_size = 18;
    public bool $showDesignModal = false;
    public bool $showMediaModal = false;
    public bool $showConferenceModal = false;
    public bool $showDeleteConferenceModal = false;
    public bool $showSpeakerLinkModal = false;
    public bool $showPublishModal = false;
    public ?int $conference_id = null;
    public ?int $pendingDeleteConferenceId = null;
    public string $pendingDeleteConferenceName = '';
    public ?int $speakerLinkConferenceId = null;
    public string $speakerLinkConferenceName = '';
    public string $speakerLinkUrl = '';
    public string $speakerWhatsappUrl = '';
    public bool $speakerLinkRequiresLogin = false;
    public string $conference_nombre = '';
    public string $conference_tipo_conferencia_id = '';
    public string $conference_descripcion = '';
    public string $conference_fecha = '';
    public string $conference_hora_inicio = '';
    public string $conference_hora_fin = '';
    public string $conference_lugar = '';
    public string $conference_linkreunion = '';
    public string $conference_conferencista_nombre = '';
    public string $conference_conferencista_id = '';
    public string $selectedConferenceDay = '';
    public array $profilePriceConfigurations = [];
    public string $invitacion_nombre = '';
    public string $invitacion_correo = '';
    public int $invitation_slots = 1;
    public bool $editingPrincipal = false;
    public ?string $currentLogoPath = null;
    public ?string $currentBannerPath = null;
    public ?string $currentDesignPath = null;
    public $modalidades = [];
    public $conferencistas = [];
    public $tiposConferencias = [];
    public $tiposPerfilCatalog = [];
    public $monedasCatalog = [];

    protected $validationAttributes = [
        'nombreevento' => 'nombre del evento',
        'descripcion' => 'descripcion',
        'organizador' => 'organizador',
        'tipo_conferencia_id' => 'tipo de evento',
        'idmodalidad' => 'modalidad',
        'localidad_nombre' => 'localidad',
        'tipo_acceso' => 'tipo de acceso',
        'genera_diploma_participacion' => 'genera diploma de participacion',
        'logo' => 'logo del evento',
        'banner' => 'banner promocional del evento',
        'graphicDesignFile' => 'archivo grafico',
        'design_page_size' => 'tamano de impresion',
        'design_orientation' => 'orientacion de impresion',
        'design_name_x' => 'posición horizontal del nombre',
        'design_name_y' => 'posición vertical del nombre',
        'design_qr_x' => 'posición horizontal del QR',
        'design_qr_y' => 'posición vertical del QR',
        'design_font_size' => 'tamano de letra',
        'design_qr_size' => 'tamano del QR',
        'conference_nombre' => 'nombre de la conferencia',
        'conference_tipo_conferencia_id' => 'tipo de conferencia',
        'conference_descripcion' => 'descripcion de la conferencia',
        'conference_fecha' => 'fecha de la conferencia',
        'conference_hora_inicio' => 'hora de inicio',
        'conference_hora_fin' => 'hora de finalizacion',
        'conference_lugar' => 'lugar',
        'conference_linkreunion' => 'link de reunion',
        'conference_conferencista_nombre' => 'conferencista',
        'conference_conferencista_id' => 'conferencista existente',
        'profilePriceConfigurations' => 'configuración de precios',
        'invitacion_nombre' => 'nombre del invitado',
        'invitacion_correo' => 'correo del invitado',
        'invitation_slots' => 'cupos de la invitacion',
        'fechainicio' => 'fecha de inicio',
        'fechafinal' => 'fecha de finalizacion',
        'horainicio' => 'hora de inicio',
        'horafin' => 'hora de finalizacion',
    ];

    public function mount(Evento $evento): void
    {
        $this->authorize('events.manage');
        $this->evento = $evento;
        $this->modalidades = $this->loadModalidades();
        $this->conferencistas = $this->loadConferencistas();
        $this->tiposConferencias = $this->loadTiposConferencias();
        $this->tiposPerfilCatalog = $this->loadTiposPerfilCatalog();
        $this->monedasCatalog = $this->loadMonedasCatalog();
        $this->fillFromEvento();
    }

    public function render()
    {
        $this->evento->load(['modalidad', 'tipoEvento', 'localidad', 'conferencias.conferencista.persona', 'conferencias.speakerPersona.user', 'conferencias.tipoConferencia', 'invitaciones', 'precios.tipoPerfil', 'precios.moneda']);
        $sectionStatus = $this->sectionStatus();
        $eventDays = $this->eventDays();
        $scheduleByDay = $this->scheduleByDay();

        return view('livewire.Evento.configurar-evento', [
            'evento' => $this->evento,
            'sectionStatus' => $sectionStatus,
            'allSectionsReady' => collect($sectionStatus)->every(fn (array $section) => $section['ready']),
            'allConfigSectionsReady' => $this->configurationSectionsReady(),
            'pageSizes' => self::AVAILABLE_PAGE_SIZES,
            'orientations' => self::AVAILABLE_ORIENTATIONS,
            'designTypes' => self::DESIGN_TYPES,
            'designs' => $this->evento->graphic_designs ?? [],
            'requiredDesignKey' => self::REQUIRED_DESIGN_KEY,
            'eventDays' => $eventDays,
            'scheduleByDay' => $scheduleByDay,
            'tiposConferencias' => $this->tiposConferencias,
            'tiposPerfilCatalog' => $this->tiposPerfilCatalog,
            'monedasCatalog' => $this->monedasCatalog,
        ])->layout('components.layouts.app');
    }

    public function editPrincipal(): void
    {
        $this->authorize('events.manage');
        $this->editingPrincipal = true;
        $this->resetValidation();
    }

    public function closePrincipalModal(): void
    {
        $this->authorize('events.manage');
        $this->editingPrincipal = false;
        $this->fillFromEvento();
        $this->resetValidation();
    }

    public function setActiveSection(string $section): void
    {
        $this->authorize('events.manage');

        if (! in_array($section, ['logo', 'designs', 'conferencias', 'prices', 'publication'], true)) {
            return;
        }

        if ($section === 'designs' && ! $this->genera_diploma_participacion) {
            return;
        }

        if ($section === 'prices' && $this->tipo_acceso !== 'pagada') {
            return;
        }

        if (! $this->canAccessSection($section)) {
            session()->flash('error', 'Completa el paso actual antes de avanzar al siguiente.');

            return;
        }

        $this->activeSection = $section;
        $this->resetValidation();
    }

    public function nextSectionStep(): void
    {
        $this->authorize('events.manage');

        if ($this->activeSection === 'prices') {
            $this->storePrices(false);
            $this->activeSection = 'publication';
            $this->resetValidation();

            return;
        }

        if (! $this->currentSectionReady()) {
            session()->flash('error', 'Debes completar este paso antes de continuar.');

            return;
        }

        $keys = $this->orderedSectionKeys();
        $currentIndex = array_search($this->activeSection, $keys, true);

        if ($currentIndex === false) {
            return;
        }

        $nextKey = $keys[$currentIndex + 1] ?? null;

        if ($nextKey) {
            $this->activeSection = $nextKey;
            $this->resetValidation();
        }
    }

    public function previousSectionStep(): void
    {
        $this->authorize('events.manage');

        $keys = $this->orderedSectionKeys();
        $currentIndex = array_search($this->activeSection, $keys, true);

        if ($currentIndex === false || $currentIndex === 0) {
            return;
        }

        $this->activeSection = $keys[$currentIndex - 1];
        $this->resetValidation();
    }

    public function selectConferenceDay(string $date): void
    {
        $this->authorize('events.manage');
        $this->selectedConferenceDay = $date;
    }

    public function setActiveDesign(string $designKey): void
    {
        $this->authorize('events.manage');

        if (! array_key_exists($designKey, self::DESIGN_TYPES)) {
            return;
        }

        $this->activeDesignKey = $designKey;
        $this->fillDesignEditor();
        $this->resetValidation();
    }

    public function openDesignModal(string $designKey): void
    {
        $this->setActiveDesign($designKey);
        $this->showDesignModal = true;
    }

    public function closeDesignModal(): void
    {
        $this->authorize('events.manage');
        $this->showDesignModal = false;
        $this->fillDesignEditor();
        $this->resetValidation();
    }

    public function openMediaModal(string $mediaType): void
    {
        $this->authorize('events.manage');

        if (! in_array($mediaType, ['logo', 'banner'], true)) {
            return;
        }

        $this->activeMediaType = $mediaType;
        $this->showMediaModal = true;
        $this->resetValidation();
    }

    public function closeMediaModal(): void
    {
        $this->authorize('events.manage');
        $this->showMediaModal = false;
        $this->logo = null;
        $this->banner = null;
        $this->resetValidation();
    }

    public function openConferenceModal(?int $conferenceId = null): void
    {
        $this->authorize('events.manage');
        $this->resetConferenceForm();

        if ($conferenceId) {
            $conference = $this->evento->conferencias()->findOrFail($conferenceId);
            $this->conference_id = $conference->id;
            $this->conference_nombre = $conference->nombre;
            $this->conference_tipo_conferencia_id = (string) ($conference->tipo_conferencia_id ?? '');
            $this->conference_descripcion = $conference->descripcion;
            $this->conference_fecha = $conference->fecha;
            $this->conference_hora_inicio = substr((string) $conference->horaInicio, 0, 5);
            $this->conference_hora_fin = substr((string) $conference->horaFin, 0, 5);
            $this->conference_lugar = $conference->lugar;
            $this->conference_linkreunion = $conference->linkreunion ?? '';
            $this->conference_conferencista_id = (string) ($conference->speaker_persona_id ?? $conference->conferencista?->persona?->id ?? '');
            $this->conference_conferencista_nombre = $conference->conferencista_nombre_invitado
                ?: trim(($conference->speakerPersona?->nombre ?? '') . ' ' . ($conference->speakerPersona?->apellido ?? ''))
                ?: trim(($conference->conferencista?->persona?->nombre ?? '') . ' ' . ($conference->conferencista?->persona?->apellido ?? ''));
        } else {
            $this->conference_fecha = $this->selectedConferenceDay ?: ($this->eventDays()[0]['date'] ?? '');
        }

        $this->showConferenceModal = true;
        $this->resetValidation();
    }

    public function closeConferenceModal(): void
    {
        $this->authorize('events.manage');
        $this->showConferenceModal = false;
        $this->resetConferenceForm();
        $this->resetValidation();
    }

    public function openSpeakerLinkModal(int $conferenceId): void
    {
        $this->authorize('events.manage');

        $conference = $this->evento->conferencias()
            ->with(['conferencista.persona', 'speakerPersona.user'])
            ->findOrFail($conferenceId);

        if (! $conference->speaker_access_token) {
            $conference->update([
                'speaker_access_token' => Str::uuid()->toString(),
            ]);
            $conference->refresh();
        }

        $this->speakerLinkConferenceId = $conference->id;
        $this->speakerLinkConferenceName = $conference->nombre;
        $this->speakerLinkUrl = $conference->onboarding_url;
        $this->speakerLinkRequiresLogin = (bool) ($conference->speakerPersona?->IdUsuario ?: $conference->conferencista?->persona?->IdUsuario);
        $this->speakerWhatsappUrl = $this->buildSpeakerWhatsappUrl($conference);
        $this->showSpeakerLinkModal = true;
    }

    public function closeSpeakerLinkModal(): void
    {
        $this->authorize('events.manage');
        $this->showSpeakerLinkModal = false;
        $this->speakerLinkConferenceId = null;
        $this->speakerLinkConferenceName = '';
        $this->speakerLinkUrl = '';
        $this->speakerWhatsappUrl = '';
        $this->speakerLinkRequiresLogin = false;
    }

    public function regenerateSpeakerLink(): void
    {
        $this->authorize('events.manage');

        if (! $this->speakerLinkConferenceId) {
            return;
        }

        $conference = $this->evento->conferencias()
            ->with(['conferencista.persona', 'speakerPersona.user'])
            ->findOrFail($this->speakerLinkConferenceId);

        $conference->update([
            'speaker_access_token' => Str::uuid()->toString(),
            'speaker_profile_completed_at' => null,
            'conference_content_completed_at' => null,
            'speaker_onboarding_step' => 1,
            'speaker_onboarding_submitted_at' => null,
        ]);

        $conference->refresh();
        $this->speakerLinkUrl = $conference->onboarding_url;
        $this->speakerLinkRequiresLogin = (bool) ($conference->speakerPersona?->IdUsuario ?: $conference->conferencista?->persona?->IdUsuario);
        $this->speakerWhatsappUrl = $this->buildSpeakerWhatsappUrl($conference);

        session()->flash('message', 'Enlace del conferencista regenerado correctamente.');
    }

    public function updatedConferenceConferencistaNombre(string $value): void
    {
        if ($this->conference_conferencista_id === '') {
            return;
        }

        $selected = $this->conferencistas->firstWhere('id', (int) $this->conference_conferencista_id);

        if (! $selected) {
            $this->conference_conferencista_id = '';

            return;
        }

        $selectedName = trim(($selected->nombre ?? '') . ' ' . ($selected->apellido ?? ''));

        if (trim($value) !== $selectedName) {
            $this->conference_conferencista_id = '';
        }
    }

    public function selectExistingConferenceSpeaker(int $speakerId): void
    {
        $this->authorize('events.manage');

        $speaker = $this->conferencistas->firstWhere('id', $speakerId);

        if (! $speaker) {
            return;
        }

        $this->conference_conferencista_id = (string) $speakerId;
        $this->conference_conferencista_nombre = trim(($speaker->nombre ?? '') . ' ' . ($speaker->apellido ?? ''));
    }

    public function clearExistingConferenceSpeaker(): void
    {
        $this->authorize('events.manage');
        $this->conference_conferencista_id = '';
    }

    public function addProfilePriceRange(int $tipoPerfilId): void
    {
        $this->authorize('events.manage');

        $defaultDate = $this->fechainicio ?: now()->format('Y-m-d');

        if (! isset($this->profilePriceConfigurations[$tipoPerfilId])) {
            return;
        }

        $this->profilePriceConfigurations[$tipoPerfilId]['ranges'][] = [
            'label' => '',
            'start_date' => $defaultDate,
            'end_date' => $defaultDate,
            'currency_id' => $this->profilePriceConfigurations[$tipoPerfilId]['currency_id'] ?? $this->defaultCurrencyId(),
            'amount' => '',
        ];
    }

    public function removeProfilePriceRange(int $tipoPerfilId, int $index): void
    {
        $this->authorize('events.manage');

        if (! isset($this->profilePriceConfigurations[$tipoPerfilId]['ranges'][$index])) {
            return;
        }

        unset($this->profilePriceConfigurations[$tipoPerfilId]['ranges'][$index]);
        $this->profilePriceConfigurations[$tipoPerfilId]['ranges'] = array_values($this->profilePriceConfigurations[$tipoPerfilId]['ranges']);
    }

    public function savePrincipal(): void
    {
        $this->authorize('events.manage');

        $validated = $this->validate($this->principalRules());
        $localidad = Localidad::query()->firstOrCreate([
            'localidad' => trim($validated['localidad_nombre']),
        ]);

        $oldStartDate = $this->evento->fechainicio?->format('Y-m-d');
        $oldEndDate = ($this->evento->fechafinal ?? $this->evento->fechainicio)?->format('Y-m-d');
        $newStartDate = $validated['fechainicio'] ?: null;
        $newEndDate = $validated['fechafinal'] ?: ($newStartDate ?: null);

        DB::transaction(function () use ($validated, $localidad, $oldStartDate, $oldEndDate, $newStartDate, $newEndDate): void {
            $this->evento->update([
                'nombreevento' => trim($validated['nombreevento']),
                'descripcion' => trim($validated['descripcion']),
                'organizador' => trim($validated['organizador']),
                'tipo_conferencia_id' => $validated['tipo_conferencia_id'],
                'idmodalidad' => $validated['idmodalidad'],
                'idlocalidad' => $localidad->id,
                'localidad_nombre' => $localidad->localidad,
                'tipo_acceso' => $validated['tipo_acceso'],
                'genera_diploma_participacion' => $validated['genera_diploma_participacion'],
                'fechainicio' => $newStartDate,
                'fechafinal' => $newEndDate,
                'horainicio' => $validated['horainicio'] ?: null,
                'horafin' => $validated['horafin'] ?: null,
            ]);

            $this->realignConferenceDatesToEventWindow($oldStartDate, $oldEndDate, $newStartDate, $newEndDate);
        });

        $this->evento->refresh();
        $this->fillFromEvento();
        $this->editingPrincipal = false;
        $this->syncActiveSectionWithProgress();

        session()->flash('message', 'Informacion principal actualizada correctamente.');
    }

    private function realignConferenceDatesToEventWindow(?string $oldStartDate, ?string $oldEndDate, ?string $newStartDate, ?string $newEndDate): void
    {
        if (! $oldStartDate || ! $newStartDate) {
            return;
        }

        $oldStart = Carbon::parse($oldStartDate)->startOfDay();
        $oldEnd = Carbon::parse($oldEndDate ?: $oldStartDate)->startOfDay();
        $newStart = Carbon::parse($newStartDate)->startOfDay();
        $newEnd = Carbon::parse($newEndDate ?: $newStartDate)->startOfDay();

        if ($oldStart->equalTo($newStart) && $oldEnd->equalTo($newEnd)) {
            return;
        }

        $newDaySpan = max(0, $newStart->diffInDays($newEnd));

        $this->evento->conferencias()->get()->each(function (Conferencia $conference) use ($oldStart, $oldEnd, $newStart, $newDaySpan): void {
            $conferenceDate = Carbon::parse($conference->fecha)->startOfDay();

            if ($conferenceDate->lt($oldStart)) {
                $dayIndex = 0;
            } elseif ($conferenceDate->gt($oldEnd)) {
                $dayIndex = $oldStart->diffInDays($oldEnd);
            } else {
                $dayIndex = $oldStart->diffInDays($conferenceDate);
            }

            $targetOffset = min($dayIndex, $newDaySpan);
            $conference->update([
                'fecha' => $newStart->copy()->addDays($targetOffset)->format('Y-m-d'),
            ]);
        });
    }

    public function saveLogo(): void
    {
        $this->authorize('events.manage');

        $validated = $this->validate($this->logoRules());
        $payload = [];

        if (! empty($validated['logo'])) {
            $payload['logo'] = str_replace('public/', 'storage/', $validated['logo']->store('public/eventos'));
        }

        if (! empty($validated['banner'])) {
            $payload['banner'] = str_replace('public/', 'storage/', $validated['banner']->store('public/eventos'));
        }

        if ($payload !== []) {
            $this->evento->update($payload);
        }

        $this->evento->refresh();
        $this->fillFromEvento();
        $this->showMediaModal = false;

        session()->flash('message', 'Identidad visual del evento actualizada correctamente.');
    }

    public function saveGraphicDesign(): void
    {
        $this->authorize('events.manage');

        $validated = $this->validate($this->graphicDesignRules());
        $designs = $this->evento->graphic_designs ?? [];
        $currentDesign = $designs[$this->activeDesignKey] ?? [];
        $designPath = $currentDesign['file'] ?? null;

        if ($this->graphicDesignFile) {
            $designPath = str_replace('public/', 'storage/', $this->graphicDesignFile->store('public/evento-disenos'));
        }

        $designs[$this->activeDesignKey] = [
            'label' => self::DESIGN_TYPES[$this->activeDesignKey],
            'file' => $designPath,
            'page_size' => $validated['design_page_size'],
            'orientation' => $validated['design_orientation'],
            'name_x' => round((float) $validated['design_name_x'], 2),
            'name_y' => round((float) $validated['design_name_y'], 2),
            'qr_x' => round((float) $validated['design_qr_x'], 2),
            'qr_y' => round((float) $validated['design_qr_y'], 2),
            'font_size' => round((float) $validated['design_font_size'], 2),
            'qr_size' => (int) $validated['design_qr_size'],
        ];

        $this->evento->update([
            'graphic_designs' => $designs,
        ]);

        $this->evento->refresh();
        $this->fillFromEvento();
        $this->showDesignModal = false;

        session()->flash('message', 'Diseño grafico guardado correctamente.');
    }

    public function saveConference(): void
    {
        $this->authorize('events.manage');

        $validated = $this->validate($this->conferenceRules());
        $isEditing = $this->conference_id !== null;
        $currentConference = $this->conference_id
            ? $this->evento->conferencias()->find($this->conference_id)
            : null;

        $speakerName = trim($validated['conference_conferencista_nombre']);
        $speakerPersonaId = $validated['conference_conferencista_id'] ?: null;
        $speakerPersona = $speakerPersonaId ? Persona::query()->with(['conferencistas', 'user'])->find($speakerPersonaId) : null;
        $speakerRecord = $speakerPersona?->conferencistas?->first();
        $speakerConferencistaId = $speakerRecord?->id;

        if ($speakerPersona) {
            $speakerName = trim(($speakerPersona->nombre ?? '') . ' ' . ($speakerPersona->apellido ?? ''));
        }

        $speakerChanged = ! $currentConference
            || (int) ($currentConference->speaker_persona_id ?? 0) !== (int) ($speakerPersonaId ?? 0)
            || (int) ($currentConference->idConferencista ?? 0) !== (int) ($speakerConferencistaId ?? 0)
            || trim((string) $currentConference->conferencista_nombre_invitado) !== $speakerName;

        Conferencia::query()->updateOrCreate(
            ['id' => $this->conference_id],
            [
                'IdEvento' => $this->evento->id,
                'tipo_conferencia_id' => $validated['conference_tipo_conferencia_id'],
                'foto' => $currentConference?->foto,
                'nombre' => trim($validated['conference_nombre']),
                'descripcion' => trim($validated['conference_descripcion']),
                'fecha' => $validated['conference_fecha'],
                'horaInicio' => $validated['conference_hora_inicio'],
                'horaFin' => $validated['conference_hora_fin'],
                'lugar' => trim($validated['conference_lugar']),
                'linkreunion' => $this->requiresConferenceLink() || $this->allowsConferenceLink()
                    ? ($validated['conference_linkreunion'] ? trim($validated['conference_linkreunion']) : null)
                    : null,
                'conferencista_nombre_invitado' => $speakerName,
                'speaker_persona_id' => $speakerPersonaId,
                'speaker_access_token' => $currentConference?->speaker_access_token ?: Str::uuid()->toString(),
                'speaker_profile_completed_at' => $speakerChanged ? null : $currentConference?->speaker_profile_completed_at,
                'conference_content_completed_at' => $speakerChanged ? null : $currentConference?->conference_content_completed_at,
                'speaker_onboarding_step' => $speakerChanged ? 1 : ($currentConference?->speaker_onboarding_step ?? 1),
                'speaker_onboarding_submitted_at' => $speakerChanged ? null : $currentConference?->speaker_onboarding_submitted_at,
                'idConferencista' => $speakerConferencistaId,
            ]
        );

        $this->evento->refresh();
        $this->selectedConferenceDay = $validated['conference_fecha'];
        $this->showConferenceModal = false;
        $this->resetConferenceForm();
        $this->fillFromEvento();
        $this->activeSection = 'conferencias';

        session()->flash('message', $isEditing ? 'Conferencia actualizada correctamente.' : 'Conferencia creada correctamente.');
    }

    public function deleteConference(int $conferenceId): void
    {
        $this->authorize('events.manage');

        $conference = $this->evento->conferencias()->findOrFail($conferenceId);

        if ($conference->suscripciones()->exists()) {
            session()->flash('error', 'No se puede eliminar la conferencia porque tiene inscripciones asociadas.');

            return;
        }

        $conference->delete();
        $this->evento->refresh();
        session()->flash('message', 'Conferencia eliminada correctamente.');
    }

    public function confirmDeleteConference(int $conferenceId): void
    {
        $this->authorize('events.manage');

        $conference = $this->evento->conferencias()->findOrFail($conferenceId);
        $this->pendingDeleteConferenceId = $conference->id;
        $this->pendingDeleteConferenceName = $conference->nombre;
        $this->showDeleteConferenceModal = true;
    }

    public function closeDeleteConferenceModal(): void
    {
        $this->authorize('events.manage');
        $this->showDeleteConferenceModal = false;
        $this->pendingDeleteConferenceId = null;
        $this->pendingDeleteConferenceName = '';
    }

    public function deleteConferenceConfirmed(): void
    {
        $this->authorize('events.manage');

        if (! $this->pendingDeleteConferenceId) {
            return;
        }

        $conferenceId = $this->pendingDeleteConferenceId;
        $this->closeDeleteConferenceModal();
        $this->deleteConference($conferenceId);
    }

    public function saveInvitation(): void
    {
        $this->authorize('events.manage');

        $validated = $this->validate($this->invitationRules());

        EventoInvitacion::query()->create([
            'evento_id' => $this->evento->id,
            'codigo' => $this->generateInvitationCode(),
            'nombre_invitado' => trim($validated['invitacion_nombre']),
            'correo_invitado' => $validated['invitacion_correo'] ? trim($validated['invitacion_correo']) : null,
            'cupos' => $validated['invitation_slots'],
            'cupos_utilizados' => 0,
            'activa' => true,
        ]);

        $this->evento->refresh();
        $this->resetInvitationForm();
        $this->fillFromEvento();
        session()->flash('message', 'Invitacion creada correctamente.');
    }

    public function savePrices(): void
    {
        $this->authorize('events.manage');
        $this->storePrices();
    }

    private function storePrices(bool $flashMessage = true): void
    {
        $this->authorize('events.manage');

        if ($this->tipo_acceso !== 'pagada') {
            session()->flash('error', 'La configuración de precios solo aplica para eventos pagados.');

            return;
        }

        $validated = $this->validate($this->priceRules());
        $tiposPerfil = collect($validated['profilePriceConfigurations']);
        $records = [];
        $order = 1;

        foreach ($tiposPerfil as $tipoPerfilId => $configuration) {
            $eventDayAmount = $this->normalizeMoney($configuration['event_day_amount']);
            $eventDayCurrencyId = (int) ($configuration['currency_id'] ?? $this->defaultCurrencyId());

            $records[] = [
                'evento_id' => $this->evento->id,
                'IdTipoPerfil' => (int) $tipoPerfilId,
                'moneda_id' => $eventDayCurrencyId,
                'categoria_key' => 'tipoperfil',
                'categoria_nombre' => $this->tiposPerfilCatalog->firstWhere('id', (int) $tipoPerfilId)?->tipoperfil,
                'es_precio_evento_dia' => true,
                'precio' => $eventDayAmount,
                'orden' => $order++,
            ];

            $ranges = collect($configuration['ranges'] ?? [])
                ->map(function (array $range) {
                    $range['label'] = trim($range['label']);
                    $range['amount'] = $this->normalizeMoney($range['amount']);
                    $range['currency_id'] = (int) ($range['currency_id'] ?? $this->defaultCurrencyId());

                    return $range;
                })
                ->sortBy('start_date')
                ->values()
                ->all();

            $this->validateProfilePriceRanges($ranges, (int) $tipoPerfilId);

            foreach ($ranges as $range) {
                $records[] = [
                    'evento_id' => $this->evento->id,
                    'IdTipoPerfil' => (int) $tipoPerfilId,
                    'moneda_id' => $range['currency_id'],
                    'categoria_key' => 'tipoperfil',
                    'categoria_nombre' => $range['label'],
                    'es_precio_evento_dia' => false,
                    'precio' => $range['amount'],
                    'fecha_inicio' => $range['start_date'],
                    'fecha_fin' => $range['end_date'],
                    'orden' => $order++,
                ];
            }
        }

        DB::transaction(function () use ($records) {
            $this->evento->precios()->delete();

            foreach ($records as $record) {
                EventoPrecio::query()->create($record);
            }
        });

        $this->evento->refresh();
        $this->fillFromEvento();

        if ($flashMessage) {
            session()->flash('message', 'Precios del evento guardados correctamente.');
        }
    }

    public function deleteInvitation(int $invitationId): void
    {
        $this->authorize('events.manage');

        $invitation = $this->evento->invitaciones()->findOrFail($invitationId);
        $invitation->delete();
        $this->evento->refresh();

        session()->flash('message', 'Invitacion eliminada correctamente.');
    }

    public function publish(): void
    {
        $this->authorize('events.manage');

        if (! $this->configurationSectionsReady()) {
            $firstIncompleteSection = collect($this->sectionStatus())
                ->reject(fn (array $section) => $section['key'] === 'publication')
                ->first(fn (array $section) => ! $section['ready']);

            if ($firstIncompleteSection) {
                $this->activeSection = $firstIncompleteSection['key'];
            }

            session()->flash('error', 'Completa todas las configuraciones antes de publicar el evento.');

            return;
        }

        $this->showPublishModal = true;
    }

    public function closePublishModal(): void
    {
        $this->authorize('events.manage');
        $this->showPublishModal = false;
    }

    public function confirmPublish(): void
    {
        $this->authorize('events.manage');

        if (! $this->configurationSectionsReady()) {
            $firstIncompleteSection = collect($this->sectionStatus())
                ->reject(fn (array $section) => $section['key'] === 'publication')
                ->first(fn (array $section) => ! $section['ready']);

            if ($firstIncompleteSection) {
                $this->activeSection = $firstIncompleteSection['key'];
            }

            $this->showPublishModal = false;
            session()->flash('error', 'Completa todas las configuraciones antes de publicar el evento.');

            return;
        }

        $this->evento->update([
            'estado' => 'publicado',
            'published_at' => now(),
        ]);

        $this->showPublishModal = false;
        $this->evento->refresh();
        $this->fillFromEvento();

        session()->flash('message', 'Evento publicado correctamente. Ya se muestra en la pagina inicial.');
    }

    public function designConfigured(string $designKey): bool
    {
        $design = ($this->evento->graphic_designs ?? [])[$designKey] ?? null;

        return (bool) (
            $design
            && ! empty($design['file'])
            && ! empty($design['page_size'])
            && ! empty($design['orientation'])
            && ($this->designHasStoredCoordinates($design, 'name') || ! empty($design['name_position']))
            && ($this->designHasStoredCoordinates($design, 'qr') || ! empty($design['qr_position']))
            && ! empty($design['font_size'])
            && ! empty($design['qr_size'])
        );
    }

    public function designIsRequired(string $designKey): bool
    {
        return $designKey === self::REQUIRED_DESIGN_KEY;
    }

    public function designPreviewStyle(?string $pageSize, ?string $orientation): string
    {
        $ratio = self::PAGE_SIZE_RATIOS[$pageSize ?: ''] ?? self::PAGE_SIZE_RATIOS['Carta'];
        [$width, $height] = $ratio;

        if (($orientation ?: 'Vertical') === 'Horizontal') {
            [$width, $height] = [$height, $width];
        }

        return sprintf('aspect-ratio: %s / %s; container-type: inline-size;', $width, $height);
    }

    public function designElementStyle(float|int|string|null $x, float|int|string|null $y, int|string|null $size, string $type = 'name'): string
    {
        if ($x === null || $y === null) {
            return 'display:none;';
        }

        $left = max(0, min(100, (float) $x));
        $top = max(0, min(100, (float) $y));

        if ($type === 'qr') {
            $qrSize = max(12, min(28, (int) $size));

            return sprintf(
                'left: %1$.4f%%; top: %2$.4f%%; width: %3$d%%; aspect-ratio: 1 / 1; transform: translate(-50%%, -50%%);',
                $left,
                $top,
                $qrSize
            );
        }

        $fontSize = (float) $size;
        if ($fontSize > 12) {
            $fontSize = round($fontSize / 4, 1);
        }
        $fontSize = max(3, min(12, $fontSize));

        return sprintf(
            'left: %1$.4f%%; top: %2$.4f%%; max-width: calc(100%% - 1.5rem); transform: translate(-50%%, -50%%); font-size: clamp(0.75rem, %3$.2fcqw, 4rem); line-height: 1.15; text-align: center; white-space: nowrap;',
            $left,
            $top,
            $fontSize
        );
    }

    public function designStoredCoordinate(?array $design, string $element, string $axis): float
    {
        $coordinates = $this->resolveStoredDesignCoordinates($design ?? [], $element);

        return (float) ($coordinates[$axis] ?? 0);
    }

    public function conferenceCalendarBounds(array $schedule = []): array
    {
        $defaultStartHour = 7;
        $defaultEndHour = 12;

        if (empty($schedule)) {
            return [
                'start_hour' => $defaultStartHour,
                'end_hour' => $defaultEndHour,
                'start_minutes' => $defaultStartHour * 60,
                'end_minutes' => $defaultEndHour * 60,
                'start_label' => Carbon::createFromTime($defaultStartHour, 0)->translatedFormat('g:i A'),
                'end_label' => Carbon::createFromTime($defaultEndHour, 0)->translatedFormat('g:i A'),
            ];
        }

        $earliestStartMinutes = collect($schedule)
            ->map(fn (array $conference) => $this->timeToMinutes($conference['hora_inicio']))
            ->min() ?? ($defaultStartHour * 60);

        $latestEndMinutes = collect($schedule)
            ->map(fn (array $conference) => $this->timeToMinutes($conference['hora_fin']))
            ->max() ?? ($defaultEndHour * 60);

        $startHour = max(0, (int) floor(($earliestStartMinutes - 60) / 60));
        $endHour = max($startHour + 1, (int) ceil(($latestEndMinutes + 120) / 60));

        return [
            'start_hour' => $startHour,
            'end_hour' => $endHour,
            'start_minutes' => $startHour * 60,
            'end_minutes' => $endHour * 60,
            'start_label' => Carbon::createFromTime($startHour, 0)->translatedFormat('g:i A'),
            'end_label' => Carbon::createFromTime($endHour, 0)->translatedFormat('g:i A'),
        ];
    }

    public function conferenceCalendarHours(array $schedule = []): array
    {
        $bounds = $this->conferenceCalendarBounds($schedule);
        $hours = [];

        for ($hour = $bounds['start_hour']; $hour < $bounds['end_hour']; $hour++) {
            $hours[] = [
                'hour' => $hour,
                'label' => Carbon::createFromTime($hour, 0)->translatedFormat('g:i A'),
            ];
        }

        return $hours;
    }

    public function conferenceCalendarStyle(array $conference, array $schedule = []): string
    {
        $bounds = $this->conferenceCalendarBounds($schedule);
        $startMinutes = $this->timeToMinutes($conference['hora_inicio']);
        $endMinutes = $this->timeToMinutes($conference['hora_fin']);
        $calendarStart = $bounds['start_minutes'];
        $calendarEnd = $bounds['end_minutes'];

        $clampedStart = max($calendarStart, min($startMinutes, $calendarEnd));
        $clampedEnd = max($clampedStart + 30, min($endMinutes, $calendarEnd));
        $totalMinutes = $calendarEnd - $calendarStart;

        $top = (($clampedStart - $calendarStart) / $totalMinutes) * 100;
        $minimumBlockMinutes = 45;
        $height = max((($clampedEnd - $clampedStart) / $totalMinutes) * 100, ($minimumBlockMinutes / $totalMinutes) * 100);
        $column = max(0, (int) ($conference['overlap_column'] ?? 0));
        $columns = max(1, (int) ($conference['overlap_columns'] ?? 1));
        $gap = 0.8;
        $availableWidth = 100 - ($gap * ($columns - 1));
        $width = $availableWidth / $columns;
        $left = ($width + $gap) * $column;

        return sprintf('top: %.4f%%; height: %.4f%%; left: %.4f%%; width: %.4f%%;', $top, $height, $left, $width);
    }

    public function truncateConferenceTitle(string $title, int $limit = 30): string
    {
        return Str::limit($title, $limit, '...');
    }

    public function conferenceCalendarTrackStyle(array $schedule = []): string
    {
        return 'width: 100%; min-width: 0;';
    }

    public function requiresConferenceLink(): bool
    {
        return $this->evento->modalidad?->modalidad === 'Virtual';
    }

    public function allowsConferenceLink(): bool
    {
        return in_array($this->evento->modalidad?->modalidad, ['Virtual', 'Hibrida (Presencial/Virtual)'], true);
    }

    public function matchingConferenceSpeakers()
    {
        $term = trim($this->conference_conferencista_nombre);

        if ($term === '') {
            return collect();
        }

        return $this->conferencistas
            ->filter(function ($speaker) use ($term) {
                $fullName = trim(($speaker->nombre ?? '') . ' ' . ($speaker->apellido ?? ''));

                return str_contains(mb_strtolower($fullName), mb_strtolower($term));
            })
            ->filter(fn ($speaker) => trim(($speaker->nombre ?? '') . ' ' . ($speaker->apellido ?? '')) !== $term)
            ->take(5)
            ->values();
    }

    private function principalRules(): array
    {
        return [
            'nombreevento' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'organizador' => 'required|string|max:255',
            'tipo_conferencia_id' => 'required|exists:tipos_conferencias,id',
            'idmodalidad' => 'required|exists:modalidads,id',
            'localidad_nombre' => 'required|string|max:255',
            'tipo_acceso' => 'required|string|in:gratuita,pagada',
            'genera_diploma_participacion' => 'required|boolean',
            'fechainicio' => 'nullable|date',
            'fechafinal' => 'nullable|date|after_or_equal:fechainicio',
            'horainicio' => 'nullable|date_format:H:i',
            'horafin' => 'nullable|date_format:H:i|after:horainicio',
        ];
    }

    private function logoRules(): array
    {
        $rules = [];

        if ($this->activeMediaType === 'logo') {
            $rules['logo'] = 'required|file|mimes:png|max:2048';
        }

        if ($this->activeMediaType === 'banner') {
            $rules['banner'] = 'required|file|mimes:jpg,jpeg,png,webp|max:8192';
        }

        if ($rules === []) {
            $rules['banner'] = $this->currentBannerPath
                ? 'nullable|file|mimes:jpg,jpeg,png,webp|max:8192'
                : 'required|file|mimes:jpg,jpeg,png,webp|max:8192';
            $rules['logo'] = 'nullable|file|mimes:png|max:2048';
        }

        return $rules;
    }

    public function mediaTypeTitle(): string
    {
        return $this->activeMediaType === 'banner' ? 'Banner promocional' : 'Logo del evento';
    }

    public function mediaTypeDescription(): string
    {
        return $this->activeMediaType === 'banner'
            ? 'Primero revisa la proporción sugerida. Luego carga el banner y verifica la vista previa antes de guardar.'
            : 'El logo es opcional. Sube un PNG con fondo transparente y revisa cómo se verá dentro del área sugerida.';
    }

    public function mediaIsConfigured(string $mediaType): bool
    {
        return match ($mediaType) {
            'logo' => filled($this->evento->logo),
            'banner' => filled($this->evento->banner),
            default => false,
        };
    }

    public function mediaPreviewUrl(string $mediaType): ?string
    {
        return match ($mediaType) {
            'logo' => $this->evento->logo_url,
            'banner' => $this->evento->banner_url,
            default => null,
        };
    }

    public function mediaFileLabel(string $mediaType): string
    {
        return $mediaType === 'banner' ? 'Seleccionar banner' : 'Seleccionar PNG';
    }

    public function mediaAccept(string $mediaType): string
    {
        return $mediaType === 'banner' ? 'image/jpeg,image/png,image/webp' : 'image/png';
    }

    public function mediaPendingLabel(string $mediaType): string
    {
        return $mediaType === 'banner' ? 'Banner pendiente' : 'Logo opcional';
    }

    public function mediaHelperText(string $mediaType): string
    {
        return $mediaType === 'banner'
            ? 'Obligatorio. Recomendado en 1920 x 1080 px o proporción equivalente 16:9.'
            : 'Opcional. Recomendado en PNG con fondo transparente.';
    }

    public function mediaStatusText(string $mediaType): string
    {
        return $this->mediaIsConfigured($mediaType) ? 'Configurado' : 'Pendiente';
    }

    public function mediaStatusClasses(string $mediaType): string
    {
        if ($this->mediaIsConfigured($mediaType)) {
            return 'border-emerald-200 bg-emerald-50/40 dark:border-emerald-900/50 dark:bg-emerald-950/10';
        }

        return $mediaType === 'banner'
            ? 'border-amber-200 bg-amber-50/40 dark:border-amber-900/50 dark:bg-amber-950/10'
            : 'border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-950/60';
    }

    public function mediaStatusTextClasses(string $mediaType): string
    {
        if ($this->mediaIsConfigured($mediaType)) {
            return 'text-emerald-600 dark:text-emerald-300';
        }

        return $mediaType === 'banner'
            ? 'text-amber-600 dark:text-amber-300'
            : 'text-slate-500 dark:text-slate-400';
    }

    private function graphicDesignRules(): array
    {
        return [
            'graphicDesignFile' => $this->currentDesignPath
                ? 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:8192'
                : 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:8192',
            'design_page_size' => 'required|string|in:' . implode(',', self::AVAILABLE_PAGE_SIZES),
            'design_orientation' => 'required|string|in:' . implode(',', self::AVAILABLE_ORIENTATIONS),
            'design_name_x' => 'required|numeric|min:0|max:100',
            'design_name_y' => 'required|numeric|min:0|max:100',
            'design_qr_x' => 'required|numeric|min:0|max:100',
            'design_qr_y' => 'required|numeric|min:0|max:100',
            'design_font_size' => 'required|numeric|min:3|max:12',
            'design_qr_size' => 'required|integer|min:12|max:28',
        ];
    }

    private function invitationRules(): array
    {
        return [
            'invitacion_nombre' => 'required|string|max:255',
            'invitacion_correo' => 'nullable|email|max:255',
            'invitation_slots' => 'required|integer|min:1|max:20',
        ];
    }

    private function priceRules(): array
    {
        return [
            'profilePriceConfigurations' => 'required|array|min:1',
            'profilePriceConfigurations.*.currency_id' => 'required|exists:monedas,id',
            'profilePriceConfigurations.*.event_day_amount' => 'required|numeric|min:0.01',
            'profilePriceConfigurations.*.ranges' => 'nullable|array',
            'profilePriceConfigurations.*.ranges.*.label' => 'required|string|max:150',
            'profilePriceConfigurations.*.ranges.*.start_date' => 'required|date',
            'profilePriceConfigurations.*.ranges.*.end_date' => 'required|date',
            'profilePriceConfigurations.*.ranges.*.currency_id' => 'required|exists:monedas,id',
            'profilePriceConfigurations.*.ranges.*.amount' => 'required|numeric|min:0.01',
        ];
    }

    private function conferenceRules(): array
    {
        $startDate = $this->evento->fechainicio?->format('Y-m-d');
        $endDate = $this->evento->fechafinal?->format('Y-m-d') ?? $startDate;

        return [
            'conference_nombre' => 'required|string|max:255',
            'conference_tipo_conferencia_id' => 'required|exists:tipos_conferencias,id',
            'conference_descripcion' => 'required|string|max:500',
            'conference_fecha' => 'required|date'
                . ($startDate ? '|after_or_equal:' . $startDate : '')
                . ($endDate ? '|before_or_equal:' . $endDate : ''),
            'conference_hora_inicio' => 'required|date_format:H:i',
            'conference_hora_fin' => 'required|date_format:H:i|after:conference_hora_inicio',
            'conference_lugar' => 'required|string|max:255',
            'conference_linkreunion' => ($this->requiresConferenceLink() ? 'required' : 'nullable') . '|url|max:255',
            'conference_conferencista_nombre' => 'required|string|max:255',
            'conference_conferencista_id' => 'nullable|exists:personas,id',
        ];
    }

    private function fillFromEvento(): void
    {
        $this->nombreevento = $this->evento->nombreevento;
        $this->descripcion = $this->evento->descripcion;
        $this->organizador = $this->evento->organizador;
        $this->tipo_conferencia_id = (string) ($this->evento->tipo_conferencia_id ?? '');
        $this->idmodalidad = (string) $this->evento->idmodalidad;
        $this->localidad_nombre = $this->evento->localidad_display;
        $this->tipo_acceso = $this->evento->tipo_acceso ?: 'gratuita';
        $this->genera_diploma_participacion = (bool) $this->evento->genera_diploma_participacion;
        $this->fechainicio = $this->evento->fechainicio?->format('Y-m-d') ?? '';
        $this->fechafinal = $this->evento->fechafinal?->format('Y-m-d') ?? '';
        $this->horainicio = $this->evento->horainicio ?? '';
        $this->horafin = $this->evento->horafin ?? '';
        $this->currentLogoPath = $this->evento->logo;
        $this->currentBannerPath = $this->evento->banner;
        $this->logo = null;
        $this->banner = null;
        if (! $this->genera_diploma_participacion && $this->activeSection === 'designs') {
            $this->activeSection = 'conferencias';
        }
        if ($this->tipo_acceso !== 'pagada' && $this->activeSection === 'prices') {
            $this->activeSection = 'conferencias';
        }
        $this->syncActiveSectionWithProgress();
        $this->syncSelectedConferenceDay();
        $this->fillDesignEditor();
        $this->fillPricingEditor();
        $this->resetInvitationForm();
        $this->resetValidation();
    }

    private function fillDesignEditor(): void
    {
        $currentDesign = ($this->evento->graphic_designs ?? [])[$this->activeDesignKey] ?? [];
        $this->currentDesignPath = $currentDesign['file'] ?? null;
        $this->design_page_size = $currentDesign['page_size'] ?? '';
        $this->design_orientation = $currentDesign['orientation'] ?? '';
        $nameCoordinates = $this->resolveStoredDesignCoordinates($currentDesign, 'name');
        $qrCoordinates = $this->resolveStoredDesignCoordinates($currentDesign, 'qr');
        $this->design_name_x = $nameCoordinates['x'];
        $this->design_name_y = $nameCoordinates['y'];
        $this->design_qr_x = $qrCoordinates['x'];
        $this->design_qr_y = $qrCoordinates['y'];
        $storedFontSize = (float) ($currentDesign['font_size'] ?? 6);
        if ($storedFontSize > 12) {
            $storedFontSize = round($storedFontSize / 4, 1);
        }
        $this->design_font_size = max(3, min(12, $storedFontSize));
        $this->design_qr_size = (int) ($currentDesign['qr_size'] ?? 18);
        $this->graphicDesignFile = null;
    }

    private function sectionStatus(): array
    {
        $sections = [
            [
                'key' => 'logo',
                'title' => 'Identidad visual',
                'description' => 'Banner promocional obligatorio y logo opcional del evento.',
                'ready' => filled($this->evento->banner),
            ],
            [
                'key' => 'conferencias',
                'title' => 'Conferencias y talleres',
                'description' => 'Agenda disponible para el registro del publico.',
                'ready' => $this->evento->conferencias->count() > 0,
            ],
        ];

        if ($this->genera_diploma_participacion) {
            array_splice($sections, 1, 0, [[
                'key' => 'designs',
                'title' => 'Diseños graficos de evento',
                'description' => 'Diplomas, gafetes, invitaciones y piezas oficiales.',
                'ready' => $this->allDesignsConfigured(),
            ]]);
        }

        if ($this->tipo_acceso === 'pagada') {
            $sections[] = [
                'key' => 'prices',
                'title' => 'Precios',
                'description' => 'Tarifas por tipo de perfil y rangos opcionales por fecha.',
                'ready' => $this->pricingConfigured(),
            ];
        }

        $sections[] = [
            'key' => 'publication',
            'title' => 'Publicación',
            'description' => 'Resumen final y publicación del evento.',
            'ready' => $this->evento->estado === 'publicado',
        ];

        return $sections;
    }

    private function allDesignsConfigured(): bool
    {
        return $this->designConfigured(self::REQUIRED_DESIGN_KEY);
    }

    private function designHasStoredCoordinates(array $design, string $element): bool
    {
        return array_key_exists($element . '_x', $design) && array_key_exists($element . '_y', $design);
    }

    private function resolveStoredDesignCoordinates(array $design, string $element): array
    {
        $default = $element === 'qr'
            ? ['x' => 84, 'y' => 84]
            : ['x' => 50, 'y' => 70];

        if ($this->designHasStoredCoordinates($design, $element)) {
            return [
                'x' => max(0, min(100, (float) $design[$element . '_x'])),
                'y' => max(0, min(100, (float) $design[$element . '_y'])),
            ];
        }

        $legacyPosition = $design[$element . '_position'] ?? null;

        if (! $legacyPosition || ! preg_match('/row-(\d+)-col-(\d+)/', $legacyPosition, $matches)) {
            return $default;
        }

        $row = max(1, min(7, (int) $matches[1]));
        $column = max(1, min(3, (int) $matches[2]));
        $cellWidth = 100 / 3;
        $cellHeight = 100 / 7;

        return [
            'x' => round((($column - 1) * $cellWidth) + ($cellWidth / 2), 2),
            'y' => round((($row - 1) * $cellHeight) + ($cellHeight / 2), 2),
        ];
    }

    private function pricingConfigured(): bool
    {
        if ($this->tipo_acceso !== 'pagada') {
            return true;
        }

        $prices = $this->evento->precios;
        $tiposPerfilIds = $this->tiposPerfilCatalog->pluck('id')->all();

        foreach ($tiposPerfilIds as $tipoPerfilId) {
            $hasEventDayPrice = $prices
                ->where('IdTipoPerfil', $tipoPerfilId)
                ->contains(fn ($price) => (bool) $price->es_precio_evento_dia);

            $hasCurrency = $prices
                ->where('IdTipoPerfil', $tipoPerfilId)
                ->contains(fn ($price) => (bool) $price->es_precio_evento_dia && filled($price->moneda_id));

            if (! $hasEventDayPrice || ! $hasCurrency) {
                return false;
            }
        }

        return ! empty($tiposPerfilIds);
    }

    private function orderedSectionKeys(): array
    {
        return collect($this->sectionStatus())
            ->pluck('key')
            ->values()
            ->all();
    }

    private function currentSectionReady(): bool
    {
        $section = collect($this->sectionStatus())
            ->firstWhere('key', $this->activeSection);

        return (bool) ($section['ready'] ?? false);
    }

    private function canAccessSection(string $section): bool
    {
        $sections = collect($this->sectionStatus())->values();
        $targetIndex = $sections->search(fn (array $item) => $item['key'] === $section);

        if ($targetIndex === false) {
            return false;
        }

        $firstIncompleteIndex = $sections->search(fn (array $item) => ! $item['ready']);

        if ($firstIncompleteIndex === false) {
            return true;
        }

        return $targetIndex <= $firstIncompleteIndex;
    }

    private function configurationSectionsReady(): bool
    {
        return collect($this->sectionStatus())
            ->reject(fn (array $section) => $section['key'] === 'publication')
            ->every(fn (array $section) => $section['ready']);
    }

    private function syncActiveSectionWithProgress(): void
    {
        $keys = $this->orderedSectionKeys();

        if (! in_array($this->activeSection, $keys, true)) {
            $this->activeSection = $keys[0] ?? 'logo';

            return;
        }

        if (! $this->canAccessSection($this->activeSection)) {
            $firstIncomplete = collect($this->sectionStatus())->first(fn (array $section) => ! $section['ready']);
            $this->activeSection = $firstIncomplete['key'] ?? ($keys[0] ?? 'logo');
        }
    }

    private function resetInvitationForm(): void
    {
        $this->invitacion_nombre = '';
        $this->invitacion_correo = '';
        $this->invitation_slots = 1;
    }

    private function fillPricingEditor(): void
    {
        $this->profilePriceConfigurations = [];

        foreach ($this->tiposPerfilCatalog as $tipoPerfil) {
            $prices = $this->evento->precios
                ->where('IdTipoPerfil', $tipoPerfil->id)
                ->sortBy('orden')
                ->values();

            $eventDayPrice = $prices->firstWhere('es_precio_evento_dia', true);
            $ranges = $prices
                ->where('es_precio_evento_dia', false)
                ->values()
                ->map(fn ($price) => [
                    'label' => $price->categoria_nombre ?? '',
                    'start_date' => $price->fecha_inicio?->format('Y-m-d') ?? ($this->fechainicio ?: ''),
                    'end_date' => $price->fecha_fin?->format('Y-m-d') ?? ($this->fechainicio ?: ''),
                    'currency_id' => (string) ($price->moneda_id ?? $this->defaultCurrencyId()),
                    'amount' => number_format((float) $price->precio, 2, '.', ''),
                ])
                ->all();

            $this->profilePriceConfigurations[$tipoPerfil->id] = [
                'tipoperfil' => $tipoPerfil->tipoperfil,
                'currency_id' => (string) ($eventDayPrice?->moneda_id ?? $this->defaultCurrencyId()),
                'event_day_amount' => $eventDayPrice ? number_format((float) $eventDayPrice->precio, 2, '.', '') : '',
                'ranges' => $ranges,
            ];
        }
    }

    private function validateProfilePriceRanges(array $ranges, int $tipoPerfilId): void
    {
        $sortedRanges = collect($ranges)->sortBy('start_date')->values();
        $profileName = $this->tiposPerfilCatalog->firstWhere('id', $tipoPerfilId)?->tipoperfil ?? 'el tipo de perfil';

        for ($index = 0; $index < $sortedRanges->count() - 1; $index++) {
            $current = $sortedRanges[$index];
            $next = $sortedRanges[$index + 1];

            if ($current['end_date'] < $current['start_date']) {
                $this->addError('profilePriceConfigurations', 'Cada rango de ' . $profileName . ' debe tener una fecha final igual o posterior a la inicial.');

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'profilePriceConfigurations' => 'Cada rango de ' . $profileName . ' debe tener una fecha final igual o posterior a la inicial.',
                ]);
            }

            if ($current['end_date'] >= $next['start_date']) {
                $this->addError('profilePriceConfigurations', 'Los rangos de fechas para ' . $profileName . ' no deben traslaparse.');

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'profilePriceConfigurations' => 'Los rangos de fechas para ' . $profileName . ' no deben traslaparse.',
                ]);
            }
        }

        if ($sortedRanges->isNotEmpty()) {
            $last = $sortedRanges->last();

            if ($last['end_date'] < $last['start_date']) {
                $this->addError('profilePriceConfigurations', 'Cada rango de ' . $profileName . ' debe tener una fecha final igual o posterior a la inicial.');

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'profilePriceConfigurations' => 'Cada rango de ' . $profileName . ' debe tener una fecha final igual o posterior a la inicial.',
                ]);
            }
        }
    }

    private function normalizeMoney(string|int|float|null $value): float
    {
        return round((float) $value, 2);
    }

    private function resetConferenceForm(): void
    {
        $this->conference_id = null;
        $this->conference_nombre = '';
        $this->conference_tipo_conferencia_id = '';
        $this->conference_descripcion = '';
        $this->conference_fecha = $this->selectedConferenceDay ?: ($this->eventDays()[0]['date'] ?? '');
        $this->conference_hora_inicio = '';
        $this->conference_hora_fin = '';
        $this->conference_lugar = '';
        $this->conference_linkreunion = '';
        $this->conference_conferencista_nombre = '';
        $this->conference_conferencista_id = '';
    }

    private function syncSelectedConferenceDay(): void
    {
        $days = $this->eventDays();

        if (! $days) {
            $this->selectedConferenceDay = '';

            return;
        }

        $validDates = collect($days)->pluck('date')->all();

        if (! in_array($this->selectedConferenceDay, $validDates, true)) {
            $this->selectedConferenceDay = $validDates[0];
        }
    }

    private function eventDays(): array
    {
        $start = $this->evento->fechainicio;
        $end = $this->evento->fechafinal ?? $start;

        if (! $start) {
            return [];
        }

        $days = [];
        $cursor = Carbon::parse($start);
        $finish = Carbon::parse($end);
        $index = 1;

        while ($cursor->lte($finish)) {
            $date = $cursor->format('Y-m-d');

            $days[] = [
                'index' => $index,
                'date' => $date,
                'label' => 'Dia ' . $index,
                'display' => $cursor->format('d/m/Y'),
                'count' => $this->evento->conferencias->where('fecha', $date)->count(),
            ];

            $cursor->addDay();
            $index++;
        }

        return $days;
    }

    private function scheduleByDay(): array
    {
        return $this->evento->conferencias
            ->sortBy(fn ($conference) => sprintf('%s %s', $conference->fecha, $conference->horaInicio))
            ->groupBy('fecha')
            ->map(function ($items) {
                $schedule = $items->map(function ($conference) {
                    return [
                        'id' => $conference->id,
                        'nombre' => $conference->nombre,
                        'descripcion' => $conference->descripcion,
                        'fecha' => $conference->fecha,
                        'hora_inicio' => substr((string) $conference->horaInicio, 0, 5),
                        'hora_fin' => substr((string) $conference->horaFin, 0, 5),
                        'duration_minutes' => max(
                            30,
                            $this->timeToMinutes(substr((string) $conference->horaFin, 0, 5))
                            - $this->timeToMinutes(substr((string) $conference->horaInicio, 0, 5))
                        ),
                        'lugar' => $conference->lugar,
                        'linkreunion' => $conference->linkreunion,
                        'foto_url' => $conference->foto_url,
                        'speaker_id' => $conference->idConferencista,
                        'speaker_persona_id' => $conference->speaker_persona_id,
                        'conferencista' => $conference->conferencista_nombre_invitado
                            ?: trim(($conference->speakerPersona?->nombre ?? '') . ' ' . ($conference->speakerPersona?->apellido ?? ''))
                            ?: trim(($conference->conferencista?->persona?->nombre ?? '') . ' ' . ($conference->conferencista?->persona?->apellido ?? ''))
                            ?: 'Sin asignar',
                        'onboarding_url' => $conference->onboarding_url,
                        'is_pending' => $conference->speaker_pending,
                    ];
                })->values()->all();

                return $this->withConferenceOverlapLayout($schedule);
            })->toArray();
    }

    private function withConferenceOverlapLayout(array $schedule): array
    {
        $indexed = collect($schedule)
            ->values()
            ->map(function (array $conference, int $index) {
                $conference['schedule_index'] = $index;
                $conference['start_minutes'] = $this->timeToMinutes($conference['hora_inicio']);
                $conference['end_minutes'] = $this->timeToMinutes($conference['hora_fin']);

                return $conference;
            })
            ->all();

        $active = [];

        foreach ($indexed as $index => &$conference) {
            $active = array_values(array_filter($active, fn (array $activeConference) => $activeConference['end_minutes'] > $conference['start_minutes']));

            $usedColumns = array_map(fn (array $activeConference) => (int) $activeConference['overlap_column'], $active);
            $column = 0;

            while (in_array($column, $usedColumns, true)) {
                $column++;
            }

            $conference['overlap_column'] = $column;
            $active[] = [
                'schedule_index' => $index,
                'end_minutes' => $conference['end_minutes'],
                'overlap_column' => $column,
            ];

            $simultaneous = array_merge(
                [[$index, $conference['start_minutes'], $conference['end_minutes']]],
                array_map(
                    fn (array $activeConference) => [$activeConference['schedule_index'], $indexed[$activeConference['schedule_index']]['start_minutes'], $activeConference['end_minutes']],
                    array_filter($active, fn (array $activeConference) => $activeConference['schedule_index'] !== $index)
                )
            );

            $maxColumns = count($simultaneous);

            foreach ($simultaneous as [$simultaneousIndex]) {
                $indexed[$simultaneousIndex]['overlap_columns'] = max(
                    $indexed[$simultaneousIndex]['overlap_columns'] ?? 1,
                    $maxColumns
                );
            }
        }
        unset($conference);

        return array_map(function (array $conference) {
            unset($conference['schedule_index'], $conference['start_minutes'], $conference['end_minutes']);

            $conference['overlap_column'] = $conference['overlap_column'] ?? 0;
            $conference['overlap_columns'] = $conference['overlap_columns'] ?? 1;

            return $conference;
        }, $indexed);
    }

    private function generateInvitationCode(): string
    {
        do {
            $code = strtoupper(Str::random(10));
        } while (EventoInvitacion::query()->where('codigo', $code)->exists());

        return $code;
    }

    private function timeToMinutes(string $time): int
    {
        [$hours, $minutes] = array_pad(explode(':', $time), 2, 0);

        return ((int) $hours * 60) + (int) $minutes;
    }

    private function loadModalidades()
    {
        $canonical = [
            'Presencial',
            'Virtual',
            'Hibrida (Presencial/Virtual)',
        ];

        foreach ($canonical as $modalidad) {
            Modalidad::query()->firstOrCreate(['modalidad' => $modalidad]);
        }

        return Modalidad::query()
            ->whereIn('modalidad', $canonical)
            ->orderByRaw("CASE modalidad WHEN 'Presencial' THEN 1 WHEN 'Virtual' THEN 2 WHEN 'Hibrida (Presencial/Virtual)' THEN 3 ELSE 4 END")
            ->get();
    }

    private function loadConferencistas()
    {
        return Persona::query()
            ->with(['user', 'conferencistas'])
            ->where(function ($query) {
                $query->whereNotNull('IdUsuario')
                    ->orWhereHas('conferencistas');
            })
            ->get()
            ->sortBy(fn ($persona) => trim(($persona->nombre ?? '') . ' ' . ($persona->apellido ?? '')))
            ->values();
    }

    private function loadTiposConferencias()
    {
        return TipoConferencia::query()
            ->orderBy('tipo')
            ->get();
    }

    private function loadTiposPerfilCatalog()
    {
        return Tipoperfil::query()
            ->orderBy('tipoperfil')
            ->get();
    }

    private function loadMonedasCatalog()
    {
        return Moneda::query()
            ->orderBy('nombre')
            ->get();
    }

    private function defaultCurrencyId(): string
    {
        return (string) ($this->monedasCatalog->first()?->id ?? '');
    }

    private function buildSpeakerWhatsappUrl(Conferencia $conference): string
    {
        $speakerName = $conference->conferencista_nombre_invitado
            ?: trim(($conference->speakerPersona?->nombre ?? '') . ' ' . ($conference->speakerPersona?->apellido ?? ''))
            ?: trim(($conference->conferencista?->persona?->nombre ?? '') . ' ' . ($conference->conferencista?->persona?->apellido ?? ''))
            ?: 'Conferencista';

        $loginMessage = ($conference->speakerPersona?->IdUsuario ?: $conference->conferencista?->persona?->IdUsuario)
            ? ' Debes iniciar sesión con tu cuenta para completar el wizard.'
            : '';

        $message = "Hola {$speakerName}, te compartimos tu enlace para actualizar tu perfil y la información de tu conferencia \"{$conference->nombre}\" del evento \"{$this->evento->nombreevento}\": {$conference->onboarding_url}.{$loginMessage}";

        return 'https://wa.me/?text=' . urlencode($message);
    }
}
