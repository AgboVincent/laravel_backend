<?php

namespace App\Http\Controllers\Claims\Items;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClaimItemResource;
use App\Models\Claim;
use App\Models\ClaimItem;
use Illuminate\Http\JsonResponse;

class ApproveClaimItem extends Controller
{
    public function __invoke(Claim $claim, ClaimItem $claimItem): JsonResponse
    {
        $claimItem->update([
            'status' => ClaimItem::STATUS_APPROVED
        ]);

        // todo: mail notification
//        $claim->user->notify();
        $claim->comment(
            'Item ' . $claimItem->name . ' has been approved.'
        );

        $claim->computeStatus();

        return Output::success(new ClaimItemResource($claimItem));
    }
}
