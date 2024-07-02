<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/nacionalidades', function () {
    return view('livewire.nacionalidad.nacionalidades');
});