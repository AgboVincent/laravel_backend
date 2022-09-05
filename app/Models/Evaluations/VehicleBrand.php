<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Evaluations\VehicleModel;

class VehicleBrand extends Model
{
    use HasFactory;
    protected $fillable = ['model', 'brand', 'brand_id', ];

    public function models(){
        return $this->hasMany(VehicleModel::class, 'brand_id');
    }
}
