<?php

namespace App\Http\Controllers\Admin\Policies;

use App\Models\Policy;
use App\Helpers\Output;
use App\Models\Guarantee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PolicyResource;

class SaveGuarantees extends Controller
{
    public function __invoke(Policy $policy, Request $request)
    {   
        $request->validate([
            'guarantees' => 'required|array',
        ]);
         
        Guarantee::where('policy_id', $policy->id)->delete();

        $guarantees = collect($request->input('guarantees'))->map(fn($guarantee) => [
            'guarantee_type_id' => $guarantee,
        ]);

        $policy->guarantees()->createMany($guarantees->toArray());

        return Output::success(new PolicyResource($policy));
    }
}