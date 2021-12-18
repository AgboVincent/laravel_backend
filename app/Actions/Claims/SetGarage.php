<?php

namespace App\Actions\Claims;

use App\Models\Claim;
use App\Helpers\Output;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class SetGarage
{
    use AsAction;
    
    public function handle(Claim $claim, $request)
    {
        $claim->garage_id = $request->input('garage_id');
        $claim->save();
        
        return $claim->garage;
    }

    public function AsController(Claim $claim, Request $request)
    {
        $data = $this->handle($claim, $request);

        return Output::success($data);
    }
}