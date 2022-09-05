<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evaluations\VehicleModel;
use App\Models\Evaluations\VehicleBrand;
use Illuminate\Support\Facades\DB;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json_acura = file_get_contents(base_path('database/seeders/vehicle/vehicle_models/acura.json'));
        $json_ford = file_get_contents(base_path('database/seeders/vehicle/vehicle_models/ford.json'));
        $json_honda = file_get_contents(base_path('database/seeders/vehicle/vehicle_models/honda.json'));
        $json_hyundai = file_get_contents(base_path('database/seeders/vehicle/vehicle_models/hyundai.json'));
        $json_kia = file_get_contents(base_path('database/seeders/vehicle/vehicle_models/kia.json'));
        $json_lr = file_get_contents(base_path('database/seeders/vehicle/vehicle_models/lr.json'));
        $json_lexus = file_get_contents(base_path('database/seeders/vehicle/vehicle_models/lexus.json'));
        $json_nissan = file_get_contents(base_path('database/seeders/vehicle/vehicle_models/nissan.json'));
        $json_peugeot = file_get_contents(base_path('database/seeders/vehicle/vehicle_models/peugeot.json'));
        $json_toyota = file_get_contents(base_path('database/seeders/vehicle/vehicle_models/toyota.json'));

        $acura = json_decode($json_acura, true);
        $ford = json_decode($json_ford, true);
        $honda = json_decode($json_honda, true);
        $hyundai = json_decode($json_hyundai, true);
        $kia = json_decode($json_kia, true);
        $lr = json_decode($json_lr, true);
        $lexus = json_decode($json_lexus, true);
        $nissan = json_decode($json_nissan, true);
        $peugeot = json_decode($json_peugeot, true);
        $toyota = json_decode($json_toyota, true);

        $brandAcura = VehicleBrand::where('brand', 'Acura')->first();
        $brandFord = VehicleBrand::where('brand', 'Ford')->first();
        $brandHonda = VehicleBrand::where('brand', 'Honda')->first();
        $brandHyundai = VehicleBrand::where('brand', 'Hyundai')->first();
        $brandKia = VehicleBrand::where('brand', 'Kia')->first();
        $brandLR = VehicleBrand::where('brand', 'Land Rover')->first();
        $brandLexus = VehicleBrand::where('brand', 'Lexus')->first();
        $brandNissan = VehicleBrand::where('brand', 'Nissan')->first();
        $brandPeugeot = VehicleBrand::where('brand', 'Peugeot')->first();
        $brandToyota = VehicleBrand::where('brand', 'Toyota')->first();

        foreach($acura as $key => $model) $acura[$key]['brand_id'] = $brandAcura['id'];
        foreach($ford as $key => $model) $ford[$key]['brand_id'] = $brandFord['id'];
        foreach($honda as $key => $model) $honda[$key]['brand_id'] = $brandHonda['id'];
        foreach($hyundai as $key => $model) $hyundai[$key]['brand_id'] = $brandHyundai['id'];
        foreach($kia as $key => $model) $kia[$key]['brand_id'] = $brandKia['id'];
        foreach($lr as $key => $model) $lr[$key]['brand_id'] = $brandLR['id'];
        foreach($lexus as $key => $model) $lexus[$key]['brand_id'] = $brandLexus['id'];
        foreach($nissan as $key => $model) $nissan[$key]['brand_id'] = $brandNissan['id'];
        foreach($peugeot as $key => $model) $peugeot[$key]['brand_id'] = $brandPeugeot['id'];
        foreach($toyota as $key => $model) $toyota[$key]['brand_id'] = $brandToyota['id'];

        $models = array_merge(
            $acura,$ford,$honda,$hyundai,$kia,$lr,$lexus,$nissan,$peugeot,$toyota
        );
        foreach($models as $model){
            if(!VehicleModel::where($model)->exists()){
                DB::table('vehicle_models')->insert(array_merge($model));
            }
        }
  
    }
}
