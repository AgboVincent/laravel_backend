<?php

namespace App\Models\Collections;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'pre_evaluation_id',
        'type_id',
        'url'
    ];

}
