<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['logo','nombreevento','descripcion','organizador', 'fechainicio', 'fechafinal', 'horainicio', 'horafin','idmodalidad','idlocalidad', 'IdDiploma','IdCuenta'];

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'idmodalidad');
    }

    public function localidad()
    {
        return $this->belongsTo(Localidad::class, 'idlocalidad');
    }

    public function conferencias()
    {
        return $this->hasMany(Conferencia::class, 'IdEvento');
    }
    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'IdCuenta');
    }
    public function diploma()
    {
        return $this->belongsTo(Diploma::class, 'IdDiploma');
    }

}