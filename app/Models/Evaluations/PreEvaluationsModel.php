<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreEvaluationsModel extends Model
{
    use HasFactory;
    public function eval(){
      return  $this->hasMany(PreEvaluationsModel::class);
    }
    protected $fillable = [
        'name',
        'email',
        'chassis_number',
        'model',
        'manufacturer',
        'color',
        'year',
        'phone',
        'vehicle_regno',
        'engine_no'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = 'pre_evaluations';
}
