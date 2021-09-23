<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;

class Flutterwave
{
    const URL_TRANSFER_RECIPIENT = 'https://api.flutterwave.com/v3/beneficiaries';
    const URL_INIT_TRANSFER = 'https://api.flutterwave.com/v3/transfers';
    const URL_BANK_LIST = 'https://api.flutterwave.com/v3/banks/%s';
    const URL_RESOLVE_ACCOUNT = 'https://api.flutterwave.com/v3/accounts/resolve';

    public static function bankList($country = 'NG')
    {
        return Http::withToken(config('keys.flutterwave.secret'))
            ->get(sprintf(self::URL_BANK_LIST, $country))
            ->json('data');
    }

    public static function createTransferRecipient(string $bankCode, string $accountName, string $accountNumber)
    {
        return Http::withToken(config('keys.flutterwave.secret'))
            ->post(self::URL_TRANSFER_RECIPIENT, [
                'account_number' => $accountNumber,
                'beneficiary_name' => $accountName,
                'account_bank' => $bankCode,
                'currency' => 'NGN'
            ])->json();
    }

    public static function initializeTransfer($number, $bank, $amount)
    {
        return Http::withToken(config('keys.flutterwave.secret'))
            ->post(self::URL_INIT_TRANSFER, [
                'currency' => 'NGN',
                "amount" => $amount,
                "account_number" => $number,
                "account_bank" => $bank
            ])->json();
    }

    public static function resolveAccountDetails($code, $number)
    {
        return Http::withToken(config('keys.flutterwave.secret'))
            ->post(self::URL_RESOLVE_ACCOUNT, [
                'account_bank' => $code,
                'account_number' => $number
            ])->json();
    }
}
