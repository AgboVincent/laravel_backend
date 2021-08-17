<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\JsonResponse;

class InvolveInsurer extends Controller
{
    public function __invoke(Claim $claim): JsonResponse
    {
        $claim->update([
            'involves_insurer' => true
        ]);

        $claim->comment('Now Involves insurer');

        return Output::success('Done');
    }
}
