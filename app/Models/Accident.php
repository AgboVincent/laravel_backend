<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class Accident
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property int $claim_id
 * @property int $accident_type_id
 * @property string $description
 * @property bool $involved_third_party
 * @property AccidentThirdParty|null $thirdParty
 * @property Upload[]|Collection $uploads
 * @property Upload[]|Collection $media
 * @property AccidentType $type
 * @property Carbon $occurred_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Accident extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'bool' => 'involved_third_party'
    ];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    public function thirdParty(): HasOne
    {
        return $this->hasOne(AccidentThirdParty::class);
    }

    /**
     * Media method alias.
     *
     * @return HasMany
     */
    public function uploads(): HasMany
    {
        return $this->media();
    }

    public function media(): HasMany
    {
        return $this->hasMany(AccidentMedia::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ClaimItem::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(AccidentType::class,'accident_type_id');
    }
}
