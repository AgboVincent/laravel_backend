<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Models\Claim;
use App\Helpers\Output;

class GetItems
{
    public function __invoke(Claim $claim)
    {
        $items = $claim->items()->get();
        return Output::success($items);
    }
}