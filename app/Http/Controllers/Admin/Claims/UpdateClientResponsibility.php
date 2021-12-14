<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Models\Claim;
use App\Helpers\Output;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateClientResponsibility extends Controller 
{
    public function __invoke(Claim $claim, Request $request)
    {
        $request->validate(['value' => 'numeric']);

        $claim->update([
            'client_responsibility_id' => $request->input('value')
        ]);

        $claim->touch();

        return Output::success('Client Responsibility updated');
        
        return Output::success(new ClaimResource($claim->load([
            'policy', 'accident.media', 'accident.thirdParty', 'accident.media.file',
            'items', 'user'
        ])));
    }
}