<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Models\Claim;
use App\Helpers\Output;
use App\Http\Controllers\Controller;

class GetItems extends Controller
{
    public function __invoke(Claim $claim)
    {
        $items = $claim->items()->get();
        return Output::success($items);
    }
}