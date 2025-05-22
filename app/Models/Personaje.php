<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personaje extends Model
{
    // Tabla asociada (opcional si sigue la convención plural 'personajes')
    protected $table = 'personajes';

    // Campos que se pueden asignar masivamente (para create/update)
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

}
