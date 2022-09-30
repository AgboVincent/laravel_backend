<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewPolicyItem extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'covered', ];

}
