<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class AccidentMedia
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property int $upload_id
 * @property int $accident_id
 * @property string $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Accident $accident
 * @property Upload $file
 */
class AccidentMedia extends Model
{
    use HasFactory;

    const TYPE_CLOSE_UP = 'close up';
    const TYPE_WIDE_ANGLE = 'wide angle';
    const TYPE_FRONT = 'front';
    const TYPE_REAR = 'rear';
    const TYPE_VIDEO = 'video';
    const TYPE_OTHER = 'other';

    protected $guarded = [];

    public function file(): BelongsTo
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

    public function accident(): BelongsTo
    {
        return $this->belongsTo(Accident::class);
    }
}
