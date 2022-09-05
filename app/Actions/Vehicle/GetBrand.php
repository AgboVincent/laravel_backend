<?php

namespace  App\Actions\Vehicle;

use App\Models\Evaluations\VehicleBrand;
use Lorisleiva\Actions\Concerns\AsAction;

class GetBrand  {
    use AsAction;

    public function handle(){
        $brand = VehicleBrand::query();

        return $brand->get();
    }
}
