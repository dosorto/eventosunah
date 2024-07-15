<?php

use App\Livewire\Tipoperfil\Tipoperfiles;
use Illuminate\Support\Facades\Route;
use App\Livewire\Nacionalidad\Nacionalidades;
use App\Livewire\Modalidad\Modalidades;
use App\Livewire\Localidad\Localidades;
use App\Livewire\Departamento\Departamentos;
use App\Livewire\Carrera\Carreras;
use App\Livewire\Rol\Roles;
use App\Livewire\Conferencia\Conferencias;
use App\Livewire\Persona\Personas;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DetailsController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/nacionalidad', Nacionalidades::class)->name('nacionalidad');
    Route::get('/modalidad', Modalidades::class)->name('modalidad');
    Route::get('/tipoperfil', Tipoperfiles::class)->name('tipoperfil');
    Route::get('/localidad', Localidades::class)->name('localidad');
    Route::get('/departamento', Departamentos::class)->name('departamento');
    Route::get('/carrera', Carreras::class)->name('carrera');
    Route::get('/rol', Roles::class)->name('rol');
    Route::get('/conferencia', Conferencias::class)->name('conferencia');
    Route::get('/persona', Personas::class)->name('persona');   
    // Route::post('/register', [RegisterController::class, 'register'])->name('register');
    //Route::get('/register/details/{userId}', [DetailsController::class, 'showForm'])->name('registerDetails');
    //Route::post('/register/details', [DetailsController::class, 'store'])->name('storeDetails');
        
    
});






