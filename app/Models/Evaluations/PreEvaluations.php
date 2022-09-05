<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreEvaluations extends Model
{
    use HasFactory;
    public function eval(){
      return  $this->hasMany(PreEvaluations::class)
    }
    protected $fillable = [
        'name',
        'email',
        'chassis_number',
        'model',
        'manufacturer',
        'color',
        'year',
        'status',
        'estimate',
        'evaluation_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = 'pre_evaluations';
}
