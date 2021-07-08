<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    const TYPE_COMPREHENSIVE = 'comprehensive';
    const TYPE_THIRD_PARTY = 'third party';

    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';

    protected $guarded = [];
}
