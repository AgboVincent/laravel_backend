<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Models\Claim;
use App\Models\Expert;
use App\Helpers\Output;
use App\Models\ExpertReport;
use App\Http\Controllers\Controller;

class GetClaimExperts extends Controller
{
    public function __invoke(Claim $claim)
    {
        $expertIds = ExpertReport::where('claim_id', $claim->id)->pluck('expert_id');

        $expert = Expert::with(['reports' => function($query) use ($claim) {
            $query->where('claim_id', $claim->id);
        }])
        ->latest()
        ->find($expertIds);
        
        return Output::success($expert);
    }
}