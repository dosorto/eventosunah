<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        // Obtener los eventos paginados
        $Eventos = Evento::with('modalidad')->orderBy('created_at', 'DESC')->paginate(12);

        // Retornar la vista de bienvenida con los eventos
        return view('welcome', compact('Eventos'));
    }
}
