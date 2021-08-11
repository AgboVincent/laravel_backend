<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ClaimResource;
use App\Models\Claim;
use Illuminate\Http\JsonResponse;

class SingleClaim extends Controller
{
    public function __invoke(Claim $claim): JsonResponse
    {
        return Output::success(
            new ClaimResource(
                $claim
            )
        );
    }
}
