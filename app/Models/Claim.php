<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_ATTENTION_REQUESTED = 'attention requested';
    const STATUS_DECLINED = 'declined';
    protected $guarded = [];
}
