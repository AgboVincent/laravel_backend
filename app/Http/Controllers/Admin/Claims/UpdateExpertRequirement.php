<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Models\Claim;
use App\Helpers\Output;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ClaimResource;

class UpdateExpertRequirement extends Controller
{
    public function __invoke(Claim $claim, Request $request)
    {
        $request->validate(['value' => 'boolean']);

        $claim->update([
            'requires_expert' => $request->input('value')
        ]);

        $claim->touch();

        return Output::success(new ClaimResource($claim->load([
            'policy', 'accident.media', 'accident.thirdParties', 'accident.media.file',
            'items', 'user'
        ])));
    }

}