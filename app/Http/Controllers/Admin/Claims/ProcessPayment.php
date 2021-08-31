<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Libraries\Paystack;
use App\Models\Claim;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProcessPayment extends Controller
{
    public function __invoke(Claim $claim, Request $request): JsonResponse
    {
        $amount = $claim->amount();

        if($claim->status === Claim::STATUS_DECLINED){
            return Output::error('Cannot Process Declined Claim');
        }

        if($claim->status === Claim::STATUS_COMPLETED){
            return Output::error('Cannot Process Already Completed Claim');
        }

        if($claim->status === Claim::STATUS_PENDING){
            return Output::error('Cannot Process Pending Claim');
        }

        if($claim->status === Claim::STATUS_APPROVED){
            return Output::error('Cannot Process Claim, policy holder has not accepted settlement.');
        }

        $paystack = Paystack::initializeTransfer($claim->user->bankAccount->ref, $amount);

        if ($paystack['status'] === false) {
            Log::debug('error processing transfer', $paystack);
            return Output::error('Error Processing Transfer: ' . $paystack['message']);
        }

        $claim->update([
            'status' => Claim::STATUS_COMPLETED
        ]);

        $claim->comment('Payment Made');

        return Output::success('Claim Updated');
    }
}
