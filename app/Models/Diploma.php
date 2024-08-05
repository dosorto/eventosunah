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
        'URL',
        'Fecha',
        'IdConferencia',
        'IdFirma',
        'IdEvento',
    ];

    public function conferencia()
    {
        return $this->belongsTo(Conferencia::class, 'IdConferencia', 'id');
    }
    public function firma()
    {
        return $this->belongsTo(Firma::class, 'IdFirma');
    }
}