<?php

namespace Database\Seeders;

use App\Models\Garage;
use Illuminate\Database\Seeder;

class GarageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Garage::factory()->count(5)->create();
    }
}
