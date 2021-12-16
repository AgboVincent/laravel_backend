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

        $expert->load(['reports' => function($query) use ($claim) {
            $query->where('claim_id', $claim->id);
        }]);

        return Output::success($expert);
    }
}