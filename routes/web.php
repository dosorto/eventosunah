<?php
use App\Http\Controllers\EventoVistaController;
use App\Livewire\DescargarDiploma;
use App\Livewire\Tipoperfil\Tipoperfiles;
use App\Livewire\Moneda\Monedas;
use App\Livewire\Facturacion\ConfiguracionFacturacion;
use App\Livewire\TipoConferencia\TiposConferencias;
use App\Livewire\VistaDiplomas;
use App\Models\Evento;
use App\Models\Persona;
use Illuminate\Support\Facades\Route;
use App\Livewire\Nacionalidad\Nacionalidades;
use App\Livewire\Modalidad\Modalidades;
use App\Livewire\Localidad\Localidades;
use App\Livewire\Persona\Personas;
use App\Livewire\ValidarDiploma;
use App\Livewire\EventoVista\EventosVistas;
use App\Livewire\Rol\Roles;
use App\Livewire\Diploma\Diplomas;
use App\Livewire\Conferencia\Conferencias;
use App\Livewire\Conferencia\SpeakerOnboarding;
use App\Livewire\VistaConferencia\VistaConferencias;
use App\Livewire\Conferencia\CrearConferencia;
use App\Livewire\Conferencista\Conferencistas;
use App\Livewire\Evento\ConfigurarEvento;
use App\Livewire\Evento\Eventos;
use App\Livewire\Evento\GestionarEventoPublicado;
use App\Livewire\ConferenciaInscrita\ConferenciasInscritas;
use App\Livewire\Asistencia\PortalAsistencia;
use App\Livewire\Asistencia\EventoAsistencia;
use App\Livewire\Asistencia\ScannerAsistenciaConferencia;
use App\Livewire\Asistencia\AutoRegistroAsistencia;
use App\Http\Controllers\Login\RegistrarUsarioController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\EventoDocumentoController;
use App\Livewire\Usuario\Usuarios;
use Illuminate\Http\Request;
use App\Livewire\ReporteEvento\ReporteEventos;
use App\Livewire\HistorialConferencia\HistorialConferencias;
use App\Livewire\ApiIntegration\ApiIntegrations;
use App\Livewire\MetodoPago\MetodosPago;
use App\Livewire\Pago\GestionPagosEvento;
use App\Livewire\Pago\PortalPagos;
use App\Livewire\Participante\DetalleInscripcionEvento;
use App\Livewire\Participante\MiHistorial;
use App\Livewire\Participante\EventosDisponibles;
use App\Livewire\Participante\MisCertificados;
use App\Livewire\Participante\MisPagos;
use App\Livewire\vista_Diploma;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\ParticipantBadgeController;
use App\Http\Controllers\Pago\PagoComprobanteController;
use App\Http\Controllers\Pago\PagoDocumentoController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\SpeakerAccessController;
use App\Http\Controllers\StaffAccessController;
use App\Livewire\Evento\StaffOnboarding;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::middleware('permission:catalog.nationalities.manage')->group(function () {
        Route::get('/nacionalidad', Nacionalidades::class)->name('nacionalidad');
    });

    Route::middleware('permission:catalog.modalities.manage')->group(function () {
        Route::get('/modalidad', Modalidades::class)->name('modalidad');
    });

    Route::middleware('permission:catalog.profile-types.manage')->group(function () {
        Route::get('/tipoperfil', Tipoperfiles::class)->name('tipoperfil');
    });

    Route::middleware('permission:catalog.conference-types.manage')->group(function () {
        Route::get('/tipos-conferencias', TiposConferencias::class)->name('tipos-conferencias');
    });

    Route::middleware('permission:catalog.currencies.manage')->group(function () {
        Route::get('/monedas', Monedas::class)->name('monedas');
    });

    Route::middleware('permission:catalog.locations.manage')->group(function () {
        Route::get('/localidad', Localidades::class)->name('localidad');
    });

    Route::get('/asistencia', PortalAsistencia::class)->name('asistencia');
    Route::get('/asistencia/evento/{evento}', EventoAsistencia::class)->name('asistencia.evento');
    Route::get('/asistencia/conferencia/{conferencia}', ScannerAsistenciaConferencia::class)->name('asistencia.conferencia');
    Route::get('/asistencia/auto/{code}', AutoRegistroAsistencia::class)->name('asistencia.auto');

    Route::middleware('permission:security.roles.manage')->group(function () {
        Route::get('/rol', Roles::class)->name('rol');
    });

    Route::middleware('permission:conferences.manage')->group(function () {
        Route::get('/conferencia/{evento?}', Conferencias::class)->name('conferencia');
    });

    Route::middleware('permission:speakers.manage')->group(function () {
        Route::get('/conferencista', Conferencistas::class)->name('conferencista');
    });

    Route::middleware('permission:events.manage')->group(function () {
        Route::get('/eventos', Eventos::class)->name('eventos');
        Route::get('/eventos/{evento}/configuracion', ConfigurarEvento::class)->name('eventos.configurar');
        Route::get('/eventos/documentos/{proceso}/descargar', [EventoDocumentoController::class, 'download'])->name('eventos.documentos.download');
        Route::get('/integraciones-api', ApiIntegrations::class)->name('integraciones-api');
        Route::get('/metodos-pago', MetodosPago::class)->name('metodos-pago');
        Route::get('/configuracion-facturacion', ConfiguracionFacturacion::class)->name('configuracion-facturacion');
        Route::get('/pagos', PortalPagos::class)->name('pagos.index');
        Route::get('/pagos/evento/{evento}', GestionPagosEvento::class)->name('pagos.evento');
        Route::get('/pagos/evento/{evento}/registro/{registro}/comprobante', [PagoComprobanteController::class, 'show'])->name('pagos.comprobante.preview');
        Route::get('/pagos/evento/{evento}/registro/{registro}/recibo', [PagoDocumentoController::class, 'receipt'])->name('pagos.documentos.recibo');
        Route::get('/pagos/evento/{evento}/registro/{registro}/factura', [PagoDocumentoController::class, 'invoice'])->name('pagos.documentos.factura');
        Route::get('/pagos/evento/{evento}/registro/{registro}/recibo-devolucion', [PagoDocumentoController::class, 'refundReceipt'])->name('pagos.documentos.recibo-devolucion');
    });

    Route::get('/eventos/{evento}/gestion', GestionarEventoPublicado::class)->name('eventos.gestion');

    Route::middleware('permission:people.manage')->group(function () {
        Route::get('/persona', Personas::class)->name('persona');
    });

    Route::middleware('permission:security.users.manage')->group(function () {
        Route::get('/usuario', Usuarios::class)->name('usuario');
    });

    Route::get('/eventoVista', EventosDisponibles::class)->name('eventoVista');

    Route::get('/mi-historial', MiHistorial::class)->name('mi-historial');
    Route::get('/mi-historial/{registro}', DetalleInscripcionEvento::class)->name('mi-historial.detalle');
    Route::get('/mi-historial/{registro}/gafete', [ParticipantBadgeController::class, 'download'])->name('mi-historial.gafete');
    Route::get('/mis-certificados', MisCertificados::class)->name('mis-certificados');
    Route::get('/mis-pagos', MisPagos::class)->name('mis-pagos');
    Route::get('/evento/{evento}/conferencias', [EventoController::class, 'show'])->name('vistaconferencia');

    Route::middleware('permission:diplomas.manage')->group(function () {
        Route::get('/diploma', Diplomas::class)->name('diploma');
    });

    Route::middleware('permission:participant.registrations.manage')->group(function () {
        Route::get('/conferencias-inscritas', ConferenciasInscritas::class)->name('conferencias-inscritas');
    });

    Route::middleware('permission:participant.history.view')->group(function () {
        Route::get('/historial-conferencias', HistorialConferencias::class)->name('historial-conferencias');
    });

    Route::get('/vistaDiploma/asistencia/{asistencia?}', VistaDiplomas::class)->name('vistaDiploma');

    Route::middleware('permission:reports.events.view')->group(function () {
        Route::get('/evento/{evento}/reporteEvento', ReporteEventos::class)->name('reporteEvento');
    });
});

Route::middleware('guest')->post('/register/profile-lookup', [RegistrarUsarioController::class, 'lookupProfile'])->name('register.lookup-profile');
Route::redirect('/registrar', '/register')->name('register.legacy');
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/evento/{evento}', [EventoController::class, 'show'])->name('evento');
Route::get('/evento/{evento}/acceso-inscripcion', [EventRegistrationController::class, 'promptLogin'])->name('evento.login-inscripcion');
Route::middleware('auth')->post('/evento/{evento}/inscripcion-directa', [EventRegistrationController::class, 'quickRegister'])->name('evento.quick-register');
Route::middleware('auth')->get('/evento/{evento}/inscripcion', [EventRegistrationController::class, 'show'])->name('evento.inscripcion');
Route::get('/conferencista/acceso/{token}', SpeakerAccessController::class)->name('speaker.access');
Route::get('/conferencista/onboarding/{token}', SpeakerAccessController::class)->name('speaker.onboarding');
Route::get('/conferencista/wizard/{token}', SpeakerOnboarding::class)->name('speaker.wizard');
Route::get('/staff/acceso/{token}', StaffAccessController::class)->name('staff.access');
Route::get('/staff/wizard/{token}', StaffOnboarding::class)->name('staff.wizard');
// VALIDAR DIPLOMA
Route::get('/validarDiploma/{uuid}', ValidarDiploma::class)->name('validarDiploma');
