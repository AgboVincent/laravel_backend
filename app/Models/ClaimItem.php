<?php

namespace App\Models;

use App\Models\Traits\RouteBinding;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Znck\Eloquent\Traits\BelongsToThrough;

/**
 * Class ClaimItem
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property int $accident_id
 * @property int $amount
 * @property int $quote
 * @property int $type_id
 * @property int $quantity
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Accident $accident
 * @property Claim $claim
 * @property ClaimItemType $type
 */
class ClaimItem extends Model
{
    use HasFactory;
    use BelongsToThrough;
    use RouteBinding;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    protected $guarded = [];

    public function notFoundMessage(): string
    {
        return 'Item Not Found';
    }

    public function accident(): BelongsTo
    {
        return $this->belongsTo(Accident::class);
    }

    public function claim(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(
            Claim::class,
            Accident::class
        );
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ClaimItemType::class, 'type_id');
    }
}
