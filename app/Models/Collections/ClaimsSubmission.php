<?php

namespace App\Models\Collections;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimsSubmission extends Model
{
    use HasFactory;
    protected $fillable = [
        'pre_evaluation_id',
        'date',
        'time',
        'location',
        'purchased_policy_id',
        'landmark',
        'accident_id',
        'description',
        'status',
        'damages'
    ];
    protected $table = 'collections';
    protected $casts = [
        'damages' => 'array'
    ];
}
