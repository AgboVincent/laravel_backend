<?php

namespace Database\Seeders;

use App\Libraries\Flutterwave;
use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeed extends Seeder
{
    public function run()
    {
        $banks = collect(Flutterwave::bankList());

        if ($banks->isNotEmpty()) {
            $banks->each(function ($bank) {
                Bank::query()->firstOrCreate(['code' => $bank['code']], [
                    'name' => $bank['name']
                ]);
            });
        }
    }
}
