<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Moneda extends BaseModel
{
    use HasFactory;

    protected $table = 'monedas';

    protected $fillable = [
        'nombre',
        'codigo',
        'simbolo',
    ];

    public function eventoPrecios()
    {
        return $this->hasMany(EventoPrecio::class, 'moneda_id');
    }
}
