<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class Accident
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property int $claim_id
 * @property string $type
 * @property string $description
 * @property bool $involved_third_party
 * @property AccidentThirdParty|null $thirdParty
 * @property Carbon $occurred_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Accident extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    public function thirdParty(): HasOne
    {
        return $this->hasOne(AccidentThirdParty::class);
    }

    public function media(): HasManyThrough
    {
        return $this->hasManyThrough(
            Upload::class,
            AccidentMedia::class,
            'upload_id',
            'id',
            'id',
            'accident_id'
        );
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}
