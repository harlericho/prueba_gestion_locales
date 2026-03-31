<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    protected $table = 'locales';

    protected $fillable = [
        'nombre',
        'direccion',
        'estado',
        'tipo_documento',
        'nro_documento',
    ];

    protected $casts = [
        'estado' => 'integer',
    ];
}
