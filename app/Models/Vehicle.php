<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * Class Vehicle
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property string $number
 * @property int $user_id
 * @property int $company_id
 * @property int $policy_id
 * @property string $type
 * @property string $status
 * @property Company $company
 * @property Claim[] $claims
 * @property Policy $policy
 * @property User $user
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Vehicle extends Model
{
    use HasFactory;

    const GEAR_TYPE_AUTO = 'auto';
    const GEAR_TYPE_MANUEL = 'manual';
    protected $guarded = [];

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function claims(): HasManyThrough
    {
        return $this->hasManyThrough(
            Claim::class,
            Policy::class
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
