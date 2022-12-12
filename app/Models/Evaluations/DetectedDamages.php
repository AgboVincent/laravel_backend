<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetectedDamages extends Model
{
    use HasFactory;
    protected $fillable = [
        'pre_evaluation_id',
        'front',
        'rear',
        'left',
        'right'
    ];
    protected $casts = [
        'front' => 'array',
        'rear' => 'array',
        'left' => 'array',
        'right' => 'array'
    ];
}
