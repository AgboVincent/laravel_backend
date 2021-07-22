<?php

namespace App\Http\Controllers\Claims;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClaimResource;
use App\Models\Claim;
use Illuminate\Http\JsonResponse;

class SingleClaimInfo extends Controller
{
    public function __invoke(Claim $claim): JsonResponse
    {
        return Output::success(
            new ClaimResource($claim)
        );
    }
}
