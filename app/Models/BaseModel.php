<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected bool $usesAuditColumns = true;

    protected $fillable = [
        'created_by',
        'deleted_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->usesAuditColumns) {
                return;
            }

            if (!$model->created_by && auth()->user()) {
                $model->created_by = auth()->user()->id;
            }
        });

        static::deleting(function ($model) {
            if (! $model->usesAuditColumns) {
                return;
            }

            if (!$model->deleted_by && auth()->user()) {
                $model->deleted_by = auth()->user()->id;
                $model->save();
            }
        });
        static::updating(function ($model) {
            if (! $model->usesAuditColumns) {
                return;
            }

            if (!$model->updated_by && auth()->user()) {
                $model->updated_by = auth()->user()->id;
                $model->save();
            }
        });
    }
}
