<?php

namespace App\Http\Controllers\Claims\Items;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClaimItemTypeResource;
use App\Models\ClaimItemType;
use Illuminate\Http\JsonResponse;

class ListTypes extends Controller
{
    public function __invoke(): JsonResponse
    {
        return Output::success(ClaimItemTypeResource::collection(ClaimItemType::all()));
    }
}
