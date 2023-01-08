<?php namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Collections\ClaimsSubmission;
use App\Models\Collections\CollectionFile;
use App\Models\Evaluations\PreEvaluationsModel;
use App\Models\Evaluations\PurchasedPolicy;
use App\Models\Collections\QuoteItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use App\Models\Evaluations\NewPolicy;

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
    
    public static function claims($request)
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
        if($request['query']){  
            $query = $request['query'];
            $output = 
                    collect($output)->filter(function ($item) use ($query) {
                         return stristr($item->user->email, $query);
                    });
            return $output->all();                                      
        }
        if($request['status']){
            $output = collect($output)
                     ->where('status', $request['status'])
                     ->toArray();
        }
        if($request['date']){
            $output = collect($output)->whereBetween('created_at', [$request['startDate'], $request['endDate']]);
            return $output->all();
        }
        return $output;
    }

    public static function getClaim($request)
    {
        $claim = ClaimsSubmission::where('id',  $request['id'])->first();
        $user = PreEvaluationsModel::where('id', $claim['pre_evaluation_id'])->first();
        $files = CollectionFile::where('pre_evaluation_id', '=', $claim['pre_evaluation_id'])
                               ->whereDate('created_at', '=', $claim['created_at']->toDateString())
                               ->get();
        $policy = PurchasedPolicy::where('pre_evaluation_id', $claim['pre_evaluation_id'])->first();
        $quotes = QuoteItem::where('pre_evaluation_id', '=', $claim['pre_evaluation_id'])
                              ->where('created_at', $claim['created_at'])
                              ->get();
        $claim->user = $user;
        $claim->uploads = $files;
        $claim->policy = $policy->purchased_policy;
        $claim->quotes = $quotes;
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

    public static function mlValidateClaimsUpload($request)
    { 
        $output = [];
        $data = $request->all();
        foreach($data as $id => $value){
            $output[$id] = $value;
        }
        $response = Http::post(config('ml.url'), $output );
        return $response;
    }

    public static function submitClaim($request)
    {
        $request->validate([
            'date' => 'required|string',
            'time' => 'required|string',
            'location' => 'required|string',
            'landmark' => 'required|string',
            'accident_id' => 'required|int',
            'description' => 'required|string',
            'damages' => 'array|required'
        ]);

        $damages = [];
        $data = $request['damages'];
        for($i = 0; $i < count($data); $i++){
            $damages[$i+1] = $data[$i];
        }

       $claim =  ClaimsSubmission::create([
            'date'=> $request['date'],
            'time'=> $request['time'],
            'location'=> $request['location'],
            'landmark'=> $request['landmark'],
            'accident_id'=> $request['accident_id'],
            'pre_evaluation_id'=> $request['id'],
            'purchased_policy_id'=> $request['policy_id'],
            'description'=> $request['description'],
            'status' => 'Pending',
            'damages' => $damages
        ]);

        return $claim;

    }

    public static function getPurchasePolicies($request)
    {
        $purchasePolicies = PurchasedPolicy::query()->get();
        $user = PreEvaluationsModel::query()->get();
        $output = [];
        foreach($purchasePolicies as $purchasePolicy){
            $policy = PurchasedPolicy::where('id', $purchasePolicy['id'])->first();
            $user = $user->where('id',  $purchasePolicy['pre_evaluation_id'])->first();
            $newPolicy = NewPolicy::where('id', $policy['policy_id'])->first();
            $policyItem = NewPolicy::where('id', $policy['policy_id'])
                        ->firstOrFail()
                        ->policyItem()
                        ->get(['name', 'is_covered']);
            
            $policy->user = $user;
            $policy->new_policy = $newPolicy;
            $policy->new_policy->items = $policyItem;
            array_push($output,  $policy);
        }
        if($request['query']){  
            $query = $request['query'];
            $output = 
                    collect($output)->filter(function ($item) use ($query) {
                         return stristr($item->user->email, $query);
                    });
            return $output->all();                                      
        }
        if($request['status']){
            $output = collect($output)
                     ->where('evaluation_status', $request['status'])
                     ->toArray();
        }
        return $output;
    }
}
