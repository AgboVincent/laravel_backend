<?php

namespace App\Http\Controllers\Company\Configurations;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConfigurationResource;
use Illuminate\Http\JsonResponse;

class ConfigurationList extends Controller
{
    public function __invoke(): JsonResponse
    {
        return Output::success(ConfigurationResource::collection(
            Auth::user()->company->configurations()->get()
        ));
    }
}
