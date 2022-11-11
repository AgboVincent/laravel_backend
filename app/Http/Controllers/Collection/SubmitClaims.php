<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collections\ClaimsSubmission;

class SubmitClaims extends Controller
{
    public function submit(Request $request, ClaimsSubmission $data)
    {
        $request->validate([
            'date' => 'required|string',
            'time' => 'required|string',
            'location' => 'required|string',
            'landmark' => 'required|string',
            'accident_id' => 'required|int',
            'description' => 'required|string'
        ]);

       $claim = $data->create([
            'date'=> $request['date'],
            'time'=> $request['time'],
            'location'=> $request['location'],
            'landmark'=> $request['landmark'],
            'accident_id'=> $request['accident_id'],
            'pre_evaluation_id'=> $request['id'],
            'purchased_policy_id'=> $request['policy_id'],
            'description'=> $request['description']
        ]);

        return $claim;
 
    }
}
