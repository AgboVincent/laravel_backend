<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * @package App\Models
 * @mixin Builder
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property string $policy_number
 * @property Vehicle $vehicle
 * @property Policy[]|Collection $policies
 * @property Address[]|Collection $addresses
 * @property Carbon $email_verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $type
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    const TYPE_INSURANCE = 'insurance';
    const TYPE_POLICY_HOLDER = 'user';
    const TYPE_USER = 'user';
    const TYPE_BROKER = 'broker';

    protected $guarded = [];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function vehicles(): HasManyThrough
    {
        return $this->hasManyThrough(
            Vehicle::class,
            Policy::class,
            'user_id',
            'policy_id',
            'id',
            'id'
        );
    }

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function claims(): HasManyThrough
    {
        return $this->hasManyThrough(
            Claim::class,
            Policy::class
        );
    }

    public function createPasswordResetToken(): string
    {
        $token = Str::random(32);
        PasswordReset::query()->create([
            'email' => $this->email,
            'token' => $token,
            'created_at' => now()
        ]);

        $this->sendPasswordResetNotification($token);

        return $token;
    }
}
