<?php

namespace Database\Seeders;

use App\Models\Expert;
use Illuminate\Database\Seeder;

class ExpertsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Expert::factory(5)->create();
    }
}
