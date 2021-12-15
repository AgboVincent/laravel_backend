<?php

namespace App\Http\Controllers\Admin\Policies;

use App\Models\Claim;
use App\Helpers\Output;
use App\Models\Guarantee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ClaimResource;

class SaveGuarantees extends Controller
{
    public function __invoke(Claim $claim, Request $request)
    {   
        $request->validate([
            'guarantees' => 'required|array',
        ]);
        
        Guarantee::where('claim_id', $claim->id)->delete();

        \collect($request->input('guarantees'))->each(function ($guarantee) use ($claim) {
            Guarantee::create([
                'claim_id' => $claim->id,
                'guarantee_type_id' => $guarantee
            ]);
        });

        $claim->touch();
        
        return Output::success(new ClaimResource($claim->load([
            'policy', 'accident.media', 'accident.thirdParty', 'accident.media.file',
            'items', 'user'
        ])));
    }
}