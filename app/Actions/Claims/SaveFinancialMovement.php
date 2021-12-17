<?php

namespace App\Actions\Claims;

use App\Http\Requests\Claim\SaveFinancialMovementRequest;
use App\Models\Claim;
use App\Models\ClaimFinancialMovement;
use Lorisleiva\Actions\Concerns\AsAction;

class SaveFinancialMovement
{
    use AsAction;

    public function handle(SaveFinancialMovementRequest $request, Claim $claim)
    {
        $movement = new ClaimFinancialMovement();
        $movement->fill($request->validated());
        $movement->claim()->associate($claim);
        $movement->save();

        return response()->json([],201);
    }


}
