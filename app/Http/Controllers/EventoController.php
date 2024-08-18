<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Conferencia;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function show($id)
    {
        $evento = Evento::findOrFail($id);
        $conferencias = Conferencia::where('IdEvento', $id)->paginate(10);

        return view('evento', compact('evento', 'conferencias'));
    }
}
