<?php

namespace App\Http\Controllers\Claims\Settlement;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClaimResource;
use App\Models\Claim;
use App\Models\User;
use App\Notifications\Claim\Settlement\Accepted;
use Illuminate\Http\JsonResponse;

class Approval extends Controller
{
    public function __invoke(Claim $claim): JsonResponse
    {
        if (!Auth::user()->bankAccount) {
            return Output::error('No Bank Account Found!');
        }

        $claim->update([
            'status' => Claim::STATUS_AWAITING_PAYMENT
        ]);

        $claim->company->users()->where('type', User::TYPE_INSURANCE)
            ->first()->notify(new Accepted($claim));

        return Output::success(new ClaimResource($claim));
    }
}
