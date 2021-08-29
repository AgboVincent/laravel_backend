<?php

namespace App\Http\Controllers\Claims\Items;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClaimItemResource;
use App\Models\Claim;
use App\Models\ClaimItem;
use Illuminate\Http\JsonResponse;

class RejectClaimItem extends Controller
{
    public function __invoke(Claim $claim, ClaimItem $claimItem): JsonResponse
    {
        $claimItem->load('type');
        $claimItem->update([
            'status' => ClaimItem::STATUS_REJECTED
        ]);

        $claim->comment(
            'Item ' . $claimItem->type->name . ' has been rejected.'
        );

        $claim->computeStatus();

        return Output::success(new ClaimItemResource($claimItem));
    }
}
