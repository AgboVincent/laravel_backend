<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VettedUploads extends Model
{
    use HasFactory;
    protected $fillable = [ 'pre_evaluation_id', 'uploads' ];
    protected $casts = [
        'uploads' => 'array'   
    ];
}
