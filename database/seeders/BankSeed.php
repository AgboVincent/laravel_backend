<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class BankSeed extends Seeder
{
    public function run()
    {
        $banks = collect(Http::get('https://api.paystack.co/bank')->json('data'));

        if ($banks->isNotEmpty()) {
            $banks->each(function ($bank) {
                Bank::query()->firstOrCreate(['code' => $bank['code']], [
                    'name' => $bank['name']
                ]);
            });
        }
    }
}
