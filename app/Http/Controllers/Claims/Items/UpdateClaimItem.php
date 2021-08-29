<?php

namespace App\Http\Controllers\Claims\Items;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Requests\Claim\Item\UpdateItemRequest;
use App\Http\Resources\Admin\ClaimResource;
use App\Models\Claim;
use App\Models\ClaimItem;
use Illuminate\Http\JsonResponse;

class UpdateClaimItem extends Controller
{
    public function __invoke(Claim $claim, ClaimItem $claimItem, UpdateItemRequest $request): JsonResponse
    {
        $claimItem->load('type');
        $claimItem->update([
            'amount' => $request->get('amount')
        ]);

        $claim->comment('Item ' . $claimItem->type->name . ' price has been updated.');

        if ($comment = $request->get('comment')) {
            $claim->comment('Item ' . $claimItem->type->name . ': ' . $comment);
        }

        return Output::success(
            new ClaimResource($claim->load([
                'policy', 'accident.media', 'accident.thirdParty', 'accident.media.file',
                'items', 'user'
            ]))
        );
    }
}
