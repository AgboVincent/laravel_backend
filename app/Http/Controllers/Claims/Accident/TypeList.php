<?php

namespace App\Http\Controllers\Claims\Accident;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccidentTypeResource;
use App\Models\AccidentType;
use Illuminate\Http\JsonResponse;

class TypeList extends Controller
{
    public function __invoke(AccidentType $model): JsonResponse
    {
        return Output::success(
            AccidentTypeResource::collection($model->get())
        );
    }
}
