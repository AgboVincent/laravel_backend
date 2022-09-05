<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Evaluations\VehicleBrand;
use App\Models\Evaluations\VehicleModel;

class VehicleModel extends Model
{
    use HasFactory;
    protected $fillable = ['brand', 'code'];

    public function brand(){
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

}
