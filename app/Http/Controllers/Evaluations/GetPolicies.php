<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evaluations\NewPolicy;
use App\Models\Evaluations\NewPolicyItem;

class GetPolicies extends Controller
{
    public function __invoke( NewPolicy $newPolicy)
    {
        $policies = NewPolicy::query()->get();
        $output = [];
        foreach($policies as $policy){
        $policy = $newPolicy->where('id', $policy['id'])->first();
       
        $policyItem=$newPolicy->where('id', $policy['id'])
                     ->firstOrFail()
                     ->policyItem()
                     ->get(['name', 'is_covered']);
        
         $policy->items = $policyItem;
         array_push($output,  $policy);
        }
        
        return $output;
    }
}
