<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    protected $fillable = ['user_id', 'clicks', 'points', 'duration'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
