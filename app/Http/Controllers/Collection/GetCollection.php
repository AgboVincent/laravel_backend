<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collections\ClaimsSubmission;
use App\Models\Collections\CollectionFile;
use App\Models\Evaluations\PreEvaluationsModel;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Evaluations\PurchasedPolicy;

class GetCollection extends Controller
{
    public function claims(Request $request, ClaimsSubmission $claims)
    {
        $allClaims = $claims->query()->get();
        $user = PreEvaluationsModel::query()->get();
        $purchasePolicies = PurchasedPolicy::query()->get();
        $output = [];
        foreach($allClaims as $claim){
          $user = $user->where('id',  $claim['pre_evaluation_id'])->first();
          $policy = $purchasePolicies->where('pre_evaluation_id', $claim['pre_evaluation_id'])->first();
          $claim->user = $user;
          $claim->policy = $policy->purchased_policy;
          array_push($output,  $claim);
        }
        $total = count($output);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice(array_reverse($output), $perPage * ($currentPage - 1), $perPage);
        $paginator = new LengthAwarePaginator($currentItems, $total, $perPage, $currentPage);
       return $paginator;
    }

    public function getClaim(Request $request, ClaimsSubmission $claims)
    {
        $claim = $claims->where('id',  $request['id'])->first();
        $user = PreEvaluationsModel::where('id', $claim['pre_evaluation_id'])->first();
        $files = CollectionFile::where('pre_evaluation_id', '=', $claim['pre_evaluation_id'])->get();
        $policy = PurchasedPolicy::where('pre_evaluation_id', $claim['pre_evaluation_id'])->first();
        $claim->user = $user;
        $claim->uploads = $files;
        $claim->policy = $policy->purchased_policy;
        return $claim;



    }
}
