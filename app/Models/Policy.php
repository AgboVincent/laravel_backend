<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class Policy
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property int $number
 * @property int $user_id
 * @property int $company_id
 * @property string $type
 * @property string $status
 * @property User $user
 * @property Company $company
 * @property Carbon $created_at
 * @property Carbon $expires_at
 * @property Vehicle $vehicle
 */
class Policy extends Model
{
    use HasFactory;
    use Filterable;

    const TYPE_COMPREHENSIVE = 'comprehensive';
    const TYPE_THIRD_PARTY = 'third party';

    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    public const UPDATED_AT = null;
    protected $guarded = [];
    protected $casts = [
        'expires_at' => 'date'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): HasOne
    {
        return $this->hasOne(Vehicle::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}