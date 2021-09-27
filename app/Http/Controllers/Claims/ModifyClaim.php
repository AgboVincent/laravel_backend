<?php

namespace App\Http\Controllers\Claims;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Requests\Claim\ModifyRequest;
use App\Http\Resources\Admin\ClaimResource;
use App\Models\Claim;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class ModifyClaim extends Controller
{
    public function __invoke(Claim $claim, ModifyRequest $request): JsonResponse
    {
        $claim->accident->update([
            'occurred_at' => Carbon::parse($request->get('date_time')),
            'accident_type_id' => $request->get('accident_type'),
            'description' => $request->get('description'),
            'involved_third_party' => (bool)$request->get('involved_third_party')
        ]);

        if ($claim->accident->involved_third_party) {
            $claim->accident->thirdParty->update($request->get('third_party'));
        }

        $claim->touch();
        return Output::success(new ClaimResource($claim->load([
            'policy', 'accident.media', 'accident.thirdParty', 'accident.media.file',
            'items', 'user'
        ])));
    }
}
