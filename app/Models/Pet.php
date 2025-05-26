<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    // 🟢 Camps que es poden omplir amb create() o update()
    protected $fillable = [
        'name',
        'species',
        'age',
        'user_id',
    ];

    // 🔁 Relació amb l'usuari (propietari de la mascota)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
