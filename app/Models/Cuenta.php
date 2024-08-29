<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuenta extends Model
{
    use HasFactory;
    use SoftDeletes;

    
    protected $fillable = [
        'numeroDeCuenta',
        'CuentaHabiente',
        'Banco',
        'TipoCuenta',
        'saldoActual',
        
    ];

    public function evento()
    {
        return $this->hasMany(Evento::class, 'IdCuenta', 'id'); // Ajusta los nombres de columna si es necesario
    }


}
