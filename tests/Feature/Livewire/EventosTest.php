<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Evento\Eventos;
use App\Models\Diploma;
use App\Models\Evento;
use App\Models\Localidad;
use App\Models\Modalidad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EventosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::findOrCreate('events.manage', 'web');
        Role::findOrCreate('admin-eventos', 'web')->givePermissionTo('events.manage');
    }

    public function test_eventos_route_requires_permission(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('eventos'));

        $response->assertForbidden();
    }

    public function test_authorized_user_can_create_an_event(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $user->assignRole('admin-eventos');

        $modalidad = Modalidad::factory()->create();
        $localidad = Localidad::factory()->create();
        $diploma = Diploma::factory()->create();

        Livewire::actingAs($user)
            ->test(Eventos::class)
            ->call('create')
            ->set('nombreevento', 'Congreso de Tecnologia')
            ->set('descripcion', 'Evento academico de innovacion y desarrollo.')
            ->set('organizador', 'Facultad de Ingenieria')
            ->set('fechainicio', '2026-08-10')
            ->set('fechafinal', '2026-08-12')
            ->set('horainicio', '08:00')
            ->set('horafin', '17:00')
            ->set('idmodalidad', $modalidad->id)
            ->set('idlocalidad', $localidad->id)
            ->set('IdDiploma', $diploma->id)
            ->set('logo', UploadedFile::fake()->image('evento.png'))
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('eventos', [
            'nombreevento' => 'Congreso de Tecnologia',
            'organizador' => 'Facultad de Ingenieria',
            'idmodalidad' => $modalidad->id,
            'idlocalidad' => $localidad->id,
            'IdDiploma' => $diploma->id,
        ]);

        $evento = Evento::query()->first();

        $this->assertNotNull($evento);
        $this->assertStringStartsWith('storage/eventos/', $evento->logo);
    }

    public function test_eventos_route_loads_for_authorized_user(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin-eventos');

        $response = $this->actingAs($user)->get(route('eventos'));

        $response->assertOk();
        $response->assertSee('Modulo de eventos');
    }

    public function test_event_end_date_must_not_be_before_start_date(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin-eventos');

        $modalidad = Modalidad::factory()->create();
        $localidad = Localidad::factory()->create();
        $diploma = Diploma::factory()->create();

        Livewire::actingAs($user)
            ->test(Eventos::class)
            ->call('create')
            ->set('nombreevento', 'Feria Cientifica')
            ->set('descripcion', 'Prueba de validacion.')
            ->set('organizador', 'UNAH')
            ->set('fechainicio', '2026-08-12')
            ->set('fechafinal', '2026-08-10')
            ->set('horainicio', '08:00')
            ->set('horafin', '17:00')
            ->set('idmodalidad', $modalidad->id)
            ->set('idlocalidad', $localidad->id)
            ->set('IdDiploma', $diploma->id)
            ->call('store')
            ->assertHasErrors(['fechafinal' => 'after_or_equal']);
    }
}
