<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class AccidentThirdParty
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property int $accident_id
 * @property Accident $accident
 * @property string $full_name
 * @property string $policy_number
 * @property string $mobile
 * @property string $company
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class AccidentThirdParty extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function accident(): BelongsTo
    {
        return $this->belongsTo(Accident::class);
    }
}
