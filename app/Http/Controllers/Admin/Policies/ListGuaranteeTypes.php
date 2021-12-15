<?php

namespace App\Http\Controllers\Admin\Policies;

use App\Helpers\Output;
use App\Models\GuaranteeType;
use App\Http\Controllers\Controller;

class ListGuaranteeTypes extends Controller
{
    public function __invoke()
    {
        return Output::success(GuaranteeType::all());
    }
}