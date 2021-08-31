<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\BankAccount\CreateOrUpdateRequest;
use App\Http\Resources\BankAccountResource;
use App\Libraries\Paystack;
use App\Models\Bank;
use App\Models\BankAccount;
use Illuminate\Http\JsonResponse;

class CreateOrUpdateBankAccount extends Controller
{
    public function __invoke(CreateOrUpdateRequest $request): JsonResponse
    {
        $bank = Bank::find($request->get('bank'));
        $response = Paystack::createTransferRecipient($bank->code, $request->get('name'), $request->get('number'));

        if ($response['status'] === false) {
            return Output::error(['message' => $response['message']]);
        }

        BankAccount::query()->updateOrCreate([
            'user_id' => Auth::user()->id,
        ], [
            'name' => $response['data']['details']['account_name'],
            'bank' => $bank->name,
            'number' => $request->get('number'),
            'ref' => $response['data']['recipient_code']
        ]);

        return Output::success(new BankAccountResource(Auth::user()->bankAccount()->first()));
    }
}
