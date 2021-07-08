<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccidentMedia extends Model
{
    use HasFactory;

    const TYPE_CLOSE_UP = 'close up';
    const TYPE_WIDE_ANGLE = 'wide angle';
    const TYPE_FRONT = 'front';
    const TYPE_REAR = 'rear';
    const TYPE_VIDEO = 'video';

    protected $guarded = [];

    public function file(): BelongsTo
    {
        return $this->belongsTo(Upload::class);
    }

    public function accident(): BelongsTo
    {
        return $this->belongsTo(Accident::class);
    }
}
