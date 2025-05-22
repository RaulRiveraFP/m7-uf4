<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    // Resto de propiedades y métodos...

    // app/Models/Card.php
    protected $fillable = ['nombre', 'url_imagen', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

