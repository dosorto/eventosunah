<?php
use App\Http\Controllers\EventoVistaController;
use App\Http\Controllers\ValidarDiplomaController;
use App\Livewire\Conferencista\Perfilconferencista;
use App\Livewire\DescargarDiploma;
use App\Livewire\Gafete\Gafetes;
use App\Livewire\HistorialEvento\HistorialEventos;
use App\Livewire\PaginaInicial;
use App\Livewire\TemasCongreso;
use App\Livewire\Tipoperfil\Tipoperfiles;
use App\Livewire\VistaDiplomas;
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
use App\Livewire\VistaConferencia\VistaConferencias;
use App\Livewire\Conferencista\Conferencistas;
use App\Livewire\Evento\Eventos;
use App\Livewire\ConferenciaInscrita\ConferenciasInscritas;
use App\Livewire\Asistencia\Asistencias;
use App\Http\Controllers\Login\RegistrarUsarioController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\TemasCongresoController;
use App\Livewire\Usuario\Usuarios;
use Illuminate\Http\Request;
use App\Livewire\ReporteEvento\ReporteEventos;
use App\Livewire\Asistencia\AsistenciasConferencias;
use App\Livewire\HistorialConferencia\HistorialConferencias;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\reporteQrController;
use App\Livewire\ReciboPago\ReciboPagos;
use App\Livewire\ReciboPago\ComprobacionPago;
use App\Livewire\VistaConferencia\VistasConferencias;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('congreso/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('congreso/nacionalidad', Nacionalidades::class)->name('nacionalidad');
    Route::get('congreso/modalidad', Modalidades::class)->name('modalidad');
    Route::get('congreso/tipoperfil', Tipoperfiles::class)->name('tipoperfil');
    Route::get('congreso/localidad', Localidades::class)->name('localidad');
    Route::get('congreso/asistencia', Asistencias::class)->name('asistencia');
    Route::get('congreso/rol', Roles::class)->name('rol');
    Route::get('congreso/conferencia/{evento?}', Conferencias::class)->name('conferencia');
    Route::get('congreso/conferencista', Conferencistas::class)->name('conferencista');
    Route::get('congreso/eventos', Eventos::class)->name('eventos');
    Route::get('congreso/persona', Personas::class)->name('persona');
    Route::get('congreso/recibo/{evento}', ReciboPagos::class)->name('recibo');
    Route::get('congreso/usuario', Usuarios::class)->name('usuario');
    Route::get('congreso/eventoVista', EventosVistas::class)->name('eventoVista');
    Route::get('congreso/diploma', Diplomas::class)->name('diploma');
    Route::get('congreso/eventos/{evento}/conferencias', VistasConferencias::class)->name('subir-comprobante');   
    Route::get('congreso/evento/{evento}/conferencias', VistaConferencias::class)->name('vistaconferencia');
    Route::get('congreso/conferencias-inscritas', ConferenciasInscritas::class)->name('conferencias-inscritas');
    Route::get('congreso/historial-conferencias', HistorialConferencias::class)->name('historial-conferencias');
    Route::get('congreso/asistencia-conferencia/{conferencia}', AsistenciasConferencias::class)->name('asistencias-Conferencia');
    Route::get('congreso/vistaDiploma/asistencia/{asistencia?}', VistaDiplomas::class)->name('vistaDiploma');
    Route::get('congreso/inscripcion-evento/{evento}', ComprobacionPago::class)->name('inscripcion-evento');
    Route::get('congreso/evento/{evento}/reporteEvento', ReporteEventos::class)->name('reporteEvento');
    Route::get('congreso/historial-eventos', HistorialEventos::class)->name('historial-eventos');
    Route::get('congreso/gafete/{evento}', Gafetes::class)->name('gafete');
    Route::get('congreso/perfilconferencista', Perfilconferencista::class)->name('perfilconferencista');
});

Route::get('congreso/registrar', [RegistrarUsarioController::class, 'index'])->name('register');
Route::post('congreso/registrar', [RegistrarUsarioController::class, 'store'])->name('registerpost');
Route::get('congreso/evento/{evento}', [EventoController::class, 'show'])->name('evento');
Route::post('congreso/nueva-persona', [RegistrarUsarioController::class, 'registrarPersona'])->name('nueva-persona');
// VALIDAR DIPLOMA
Route::get('congreso/validarDiploma/{uuid}', [ValidarDiplomaController::class, 'validarDiploma'])->name('validarDiploma');

Route::get('/', [WelcomeController::class, 'index'])->name('pagina-inicial');
Route::get('congreso/temascongreso', [TemasCongresoController::class, 'index'])->name('temascongreso');
Route::get('congreso/reporteqr/{evento}', [reporteQrController::class, 'reporte'])->name('reporteqr');
Route::get('congreso/login', [AuthenticatedSessionController::class, 'create'])->name('login');



