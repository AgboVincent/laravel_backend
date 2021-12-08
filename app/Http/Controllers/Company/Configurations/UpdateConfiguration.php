<?php

namespace App\Http\Controllers\Company\Configurations;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateConfiguration extends Controller
{
    public function __invoke(Configuration $configuration, Request $request): JsonResponse
    {
        if(Auth::user()->company->id != $configuration->company_id){
            return Output::error('Cannot Edit Another Company Configuration');
        }

        $configuration->update([
            'value' => $request->get('value')
        ]);

        return Output::success('done');
    }
}
