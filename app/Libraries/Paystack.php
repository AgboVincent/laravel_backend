<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;

class Paystack
{
    const URL_TRANSFER_RECIPIENT = 'https://api.paystack.co/transferrecipient';
    const URL_INIT_TRANSFER = 'https://api.paystack.co/transfer';

    public static function createTransferRecipient(string $bankCode, string $accountName, string $accountNumber)
    {
        return Http::withToken(config('keys.paystack.secret'))->post(self::URL_TRANSFER_RECIPIENT, [
            'account_number' => $accountNumber,
            'name' => $accountName,
            'bank_code' => $bankCode,
            'type' => 'nuban'
        ])->json();
    }

    public static function initializeTransfer($recipient, $amount)
    {
        return Http::withToken(config('keys.paystack.secret'))->post(self::URL_INIT_TRANSFER, [
            "source" => "balance",
            "amount" => $amount, "recipient" => $recipient
        ])->json();
    }
}
