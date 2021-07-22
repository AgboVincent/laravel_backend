<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class ClaimItem
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property int $accident_id
 * @property int $amount
 * @property int $quantity
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Accident $accident
 */
class ClaimItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function accident(): BelongsTo
    {
        return $this->belongsTo(Accident::class);
    }
}
