<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConferencistasCongresoController extends Controller
{
    public function index()
    {
        // Retornar la vista de temas-congreso
        return view('livewire.conferencistas-congreso');
    }
}
