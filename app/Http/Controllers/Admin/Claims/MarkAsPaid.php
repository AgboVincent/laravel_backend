<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarkAsPaid extends Controller
{
    public function __invoke(Claim $claim, Request $request): JsonResponse
    {
        $claim->update([
            'status' => Claim::STATUS_COMPLETED
        ]);

        $claim->comment('Payment Made');

        return Output::success('Claim Updated');
    }
}
