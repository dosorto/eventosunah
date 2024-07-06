<?php

use App\Livewire\Tipoperfil\Tipoperfiles;
use Illuminate\Support\Facades\Route;
use App\Livewire\Nacionalidad\Nacionalidades;
use App\Livewire\Modalidad\Modalidades;
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
});

