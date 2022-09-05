<?php

namespace  App\Actions\Vehicle;

use App\Models\Evaluations\VehicleBrand;
use App\Models\Evaluations\VehicleModel;
use Lorisleiva\Actions\Concerns\AsAction;

class GetModel{
    use AsAction;

    // public function rules(){
    //     // return [
    //     //     'brand' => 'required|string'
    //     // ];
    //  }
    public function handle($name){
        $brand = VehicleBrand::where('brand', $name)->firstOrFail();
        $models = $brand->models();

        $output = $models->get(['brand_id', 'model', 'brand']);

        return $output->toArray();
    }
}
