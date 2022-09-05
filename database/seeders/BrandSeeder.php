<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evaluations\VehicleBrand;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json_brand = file_get_contents(base_path('database/seeders/vehicle/brand.json'));
        $brands = json_decode($json_brand, true);

        foreach($brands as $brand){
            if(!VehicleBrand::where('code', $brand['code'])->exists()){
                DB::table('vehicle_brands')->insert($brand);
            }
        }
    }
}
