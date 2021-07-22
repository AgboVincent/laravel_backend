<?php

namespace App\Models;

use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;

/**
 * Class Claim
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property string $status
 * @property Policy $policy
 * @property User $user
 * @property ClaimItem[]|Collection $items
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Accident $accident
 */
class Claim extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_ATTENTION_REQUESTED = 'attention requested';
    const STATUS_DECLINED = 'declined';
    protected $guarded = [];

    /**
     * @param mixed $value
     * @param null $field
     * @return Model|Claim
     * @throws NotFoundException
     */
    public function resolveRouteBinding($value, $field = null): Model
    {
        $instance = parent::resolveRouteBinding($value, $field);

        if ($instance) return $instance;

        throw new NotFoundException('Claim Not Found');
    }

    public function accident(): HasOne
    {
        return $this->hasOne(Accident::class);
    }

    public function items(): HasManyThrough
    {
        return $this->hasManyThrough(
            ClaimItem::class,
            Accident::class
        );
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Policy::class
        );
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }
}
