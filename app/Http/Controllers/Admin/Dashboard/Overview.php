<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\ClaimItem;
use Illuminate\Http\JsonResponse;

class Overview extends Controller
{
    public function __invoke(): JsonResponse
    {
        return Output::success([
            'total_claims' => Auth::user()->company->claims()->count(),
            'processed_claims' => Auth::user()->company->claims()->where('claims.status', Claim::STATUS_APPROVED)->count(),
            'pending_claims' => Auth::user()->company->claims()->where('claims.status', Claim::STATUS_APPROVED)->count(),
            'claims_value' => Auth::user()->company->claims()
                ->join('accidents', 'accidents.claim_id', '=', 'claims.id')
                ->join('claim_items', 'claim_items.accident_id', '=', 'accidents.id')
                ->where('claim_items.status', ClaimItem::STATUS_APPROVED)
                ->sum('claim_items.amount')
        ]);
    }
}
