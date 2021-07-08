<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class Address
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property int $number
 * @property int $user_id
 * @property string $street
 * @property string $city
 * @property string $state
 * @property string $country
 * @property User $user
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Address extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
