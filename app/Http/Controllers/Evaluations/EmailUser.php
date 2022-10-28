<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PolicyStatus;
use Illuminate\Support\Facades\Config;

class EmailUser extends Controller
{
    public function __invoke(Request $request)
    {      
        $data = array(
            'name'=> $request['name'],
            'url' => config('mail.url'),
            'msg' => $request['msg']
        );
   
         Mail::to($request['email'])->send(new PolicyStatus($data));
      
    }
}
