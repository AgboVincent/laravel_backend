<?php

namespace App\Models;

use App\Exceptions\NotFoundException;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Znck\Eloquent\Traits\BelongsToThrough;

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
 * @property Comment[] $comments
 * @method static self filter(...$args)
 */
class Claim extends Model
{
    use HasFactory;
    use BelongsToThrough;
    use Filterable;

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

    public function user(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(
            User::class,
            Policy::class
        );
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
