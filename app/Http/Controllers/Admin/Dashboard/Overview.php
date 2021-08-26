<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\ClaimItem;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class Overview extends Controller
{
    public function __invoke(): JsonResponse
    {
        if (Auth::user()->type === User::TYPE_BROKER) {
            $builder = Claim::query()
                ->join('policies', 'policies.id', '=', 'claims.policy_id')
                ->join('users', 'users.id', '=', 'policies.user_id')
                ->whereRaw('JSON_CONTAINS(JSON_UNQUOTE(meta), ?, ?)', [Auth::user()->id, '$.broker_id'])
                ->whereNotNull('meta');
        } else {
            $builder = Auth::user()->company->claims();
        }

        return Output::success([
            'total_claims' => $builder->count(),
            'processed_claims' => ($builder->clone())->where('claims.status', Claim::STATUS_APPROVED)->count(),
            'pending_claims' => ($builder->clone())->where('claims.status', Claim::STATUS_PENDING)->count(),
            'claims_value' => ($builder->clone())
                ->join('accidents', 'accidents.claim_id', '=', 'claims.id')
                ->join('claim_items', 'claim_items.accident_id', '=', 'accidents.id')
                ->where('claim_items.status', ClaimItem::STATUS_APPROVED)
                ->sum('claim_items.amount')
        ]);
    }
}
