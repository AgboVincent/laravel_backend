<?php

namespace Database\Seeders;

use App\Models\GuaranteeType;
use Illuminate\Database\Seeder;

class GuaranteeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GuaranteeType::factory(5)->create();
    }
}
