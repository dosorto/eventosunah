<?php

namespace App\Http\Controllers;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;
class reporteQrController extends Controller
{
    public function reporte(Evento $evento)
    {$evento->load('conferencias');
        return view('livewire.reporte-qr',['evento'=>$evento, 'conferencias'=>$evento->conferencias]);
    }
}
