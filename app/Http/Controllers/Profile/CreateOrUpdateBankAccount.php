<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\BankAccount\CreateOrUpdateRequest;
use App\Http\Resources\BankAccountResource;
use App\Models\BankAccount;
use Illuminate\Http\JsonResponse;

class CreateOrUpdateBankAccount extends Controller
{
    public function __invoke(CreateOrUpdateRequest $request): JsonResponse
    {
        BankAccount::query()->updateOrCreate([
            'user_id' => Auth::user()->id,
        ], $request->validated());

        return Output::success(new BankAccountResource(Auth::user()->bankAccount()->first()));
    }
}
