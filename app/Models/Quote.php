<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    use HasFactory;

    public function accident(): BelongsTo
    {
        return $this->belongsTo(Accident::class);
    }
}
