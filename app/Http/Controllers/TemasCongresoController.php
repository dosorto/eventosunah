<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;

class TemasCongresoController extends Controller
{
    public function index()
    {
        // Retornar la vista de temas-congreso
        return view('temas-congreso');
    }
}
