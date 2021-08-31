<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * BankAccount Model
 * @property string $name
 * @property string $number
 * @property string $bank
 * @property string $ref
 * @property User $user
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class BankAccount extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
