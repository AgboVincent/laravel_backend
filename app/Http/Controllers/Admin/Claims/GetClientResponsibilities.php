<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Models\ClientResponsibility;

class GetClientResponsibilities extends Controller 
{
    public function __invoke()
    {
        return Output::success(ClientResponsibility::all());
    }
}