<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PasswordReset
 * @package App\Models
 *
 * @property Carbon $created_at
 */
class PasswordReset extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $primaryKey = 'email';

    public function setUpdatedAt($value)
    {
        return;
    }
}
