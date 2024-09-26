<?php

namespace App\Http\Controllers;

class TemasCongresoController extends Controller
{
    public function index()
    {
        // Retornar la vista de temas-congreso
        return view('temas-congreso');
    }
}
