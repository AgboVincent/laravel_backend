<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class Company
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property string $name
 * @property Policy[] $policies
 * @property User[] $users
 * @property Claim[] $claims
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $admin
 * @property Configuration[]|Collection $configurations
 */
class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }

    public function claims(): HasManyThrough
    {
        return $this->hasManyThrough(
            Claim::class,
            Policy::class
        );
    }

    public function admin(): HasOne
    {
        return $this->hasOne(User::class)->where('users.type', User::TYPE_INSURANCE);
    }

    public function configurations(): HasMany
    {
        return $this->hasMany(Configuration::class);
    }

    public function meta(): MorphMany
    {
        return $this->morphMany(Meta::class, 'owner');
    }
}
