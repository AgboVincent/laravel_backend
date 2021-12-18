<?php

namespace App\Actions\Claims;

use App\Helpers\Output;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Claim;

class GetGarage
{
    use AsAction;
    
    public function handle(Claim $claim)
    {
        return $claim->garage;
    }

    public function AsController(Claim $claim)
    {
        $data = $this->handle($claim);

        return Output::success($data);
    }
}