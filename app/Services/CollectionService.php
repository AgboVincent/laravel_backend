<?php namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Collections\ClaimsSubmission;
use App\Models\Collections\CollectionFile;
use App\Models\Evaluations\PreEvaluationsModel;
use App\Models\Evaluations\PurchasedPolicy;
use App\Models\Collections\QuoteItem;

class CollectionService
{

    public static function paginate( $output )
    {
        $total = count($output);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice(array_reverse($output), $perPage * ($currentPage - 1), $perPage);
        $paginator = new LengthAwarePaginator($currentItems, $total, $perPage, $currentPage);
        return $paginator;
        
    }
    
    public static function claims()
    {
        $allClaims = ClaimsSubmission::query()->get();
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
        return $output;
    }

    public static function getClaim($request)
    {
        $claim = ClaimsSubmission::where('id',  $request['id'])->first();
        $user = PreEvaluationsModel::where('id', $claim['pre_evaluation_id'])->first();
        $files = CollectionFile::where('pre_evaluation_id', '=', $claim['pre_evaluation_id'])->get();
        $policy = PurchasedPolicy::where('pre_evaluation_id', $claim['pre_evaluation_id'])->first();
        $claim->user = $user;
        $claim->uploads = $files;
        $claim->policy = $policy->purchased_policy;
        return $claim;

    }

    public static function addQuotes($request)
    {
        $quotes = $request['quotes'];
        foreach($quotes as $quote){
            $item = QuoteItem::create([
                'pre_evaluation_id' => $request['id'],
                'name' => $quote['name'],
                'quantity' => $quote['quantity'],
                'amount' => $quote['amount']
            ]);
        }

    }

    public static function getUserPolicyList($request)
    {
        $result = PreEvaluationsModel::where('email', '=', $request['email'])->firstOrFail();
        $output = PurchasedPolicy::where('pre_evaluation_id', $result->id)->get();
        return $output;
    }

    public static function updateClaimStatus($request)
    {
        $result = ClaimsSubmission::where('id', '=', $request['id'])->update([
            'status' => $request['status']
       ]);
       return $result;
    }
}
