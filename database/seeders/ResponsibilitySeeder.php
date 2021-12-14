<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientResponsibility;

class ResponsibilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClientResponsibility::insert([
            ['value' => 100],
            ['value' => 50],
            ['value' => 0]
        ]);
        
    }
}
