<?php

namespace App\Actions\Garages;

use App\Models\Garage;
use App\Helpers\Output;
use Lorisleiva\Actions\Concerns\AsAction;

class ListGarages
{
    use AsAction;
    
    public function handle()
    {
        $garages = Garage::with('meta')->get();
        
        return $garages;
    }

    public function AsController()
    {
        $data = $this->handle();

        return Output::success($data);
    }
}