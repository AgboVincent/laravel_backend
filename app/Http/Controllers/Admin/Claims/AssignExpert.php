<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Models\Claim;
use App\Models\Expert;
use App\Helpers\Output;
use App\Models\ExpertReport;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ClaimResource;

class AssignExpert extends Controller
{
    public function __invoke(Claim $claim, Expert $expert)
    {
        $report = ExpertReport::create([
            'expert_id' => $expert->id,
            'claim_id' => $claim->id,
            'file_path' => '',
            'file_name' => '',
        ]);

        $claim->touch();

        return Output::success(new ClaimResource($claim->load([
            'policy', 'accident.media', 'accident.thirdParty', 'accident.media.file',
            'items', 'user'
        ])));
    }
}