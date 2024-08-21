<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diploma extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'Codigo',
        'Plantilla',
       // 'IdConferencia',
        'Titulo1',
        'NombreFirma1',
        'Firma1',
        'Sello1',
        'Titulo2',
        'NombreFirma2',
        'Firma2',
        'Sello2',
        'Titulo3',
        'NombreFirma3',
        'Firma3',
        'Sello3',
    ];

  /*  public function conferencia()
    {
        return $this->belongsTo(Conferencia::class, 'IdConferencia', 'id');
    }*/
    public function firma()
    {
        return $this->belongsTo(Firma::class, 'IdFirma');
    }
    public function evento()
    {
        return $this->hasMany(Evento::class, 'IdDiploma');
    }
}