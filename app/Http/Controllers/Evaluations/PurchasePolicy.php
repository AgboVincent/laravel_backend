<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evaluations\PurchasedPolicy;
use App\Models\Evaluations\PreEvaluationsModel;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\CollectionService;

class PurchasePolicy extends Controller
{
    public function chosePolicy(Request $request, PurchasedPolicy $policy)
    {
        $policy = $policy->create([
            'pre_evaluation_id' => $request['id'],
            'policy_id' => $request['policy_id'],
            'purchased_policy' => $request['policy'],
            'payment_status' => 'Unpaid',
            'evaluation_status' => 'Pending'
        ]);

        return $policy;
    }

    public function getPurchasePolicies(CollectionService $result, Request $request)
    {
        return $result->paginate($result->getPurchasePolicies($request));
    }

    public function updateUserPolicyStatus(Request $request, PurchasedPolicy $purchasedPolicies)
    {
        $result = $purchasedPolicies->where('pre_evaluation_id', '=', $request['id'])->update([
             'evaluation_status' => $request['status']
        ]);
        
    }

    public function getUserPolicyStatus(Request $request)
    {
        $result = PreEvaluationsModel::where('email', '=', $request['email'])->firstOrFail();
        $output = PurchasedPolicy::where('pre_evaluation_id', $result->id)->firstOrFail();
        return $output;
    }

    public function getUserPolicyList(Request $request, CollectionService $result)
    {
        return $result->getUserPolicyList($request);
    }

}
