<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Evaluations\Evaluations\VehicleFileType;

class FileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fileTypes = [
           ['type' =>'image'],
           ['type'=>'video']
        ];
    
        DB::table('vehicle_types')->insert($fileTypes);
    }
}
