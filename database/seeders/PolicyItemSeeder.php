<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evaluations\NewPolicyItem;
use Illuminate\Support\Facades\DB;

class PolicyItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cityCruisePolicyItems = [
            ["name" => "Accident", "policy_id"=> 1, "is_covered" => true ],
            ["name" => "Theft", "policy_id"=> 1, "is_covered" => true ],
            ["name" => "Fire", "policy_id"=> 1, "is_covered" =>  false]
        ];

        $autoPremiumPolicyItems = [
            ["name" => "Accident", "policy_id"=> 2, "is_covered" => true ],
            ["name" => "Theft", "policy_id"=> 2, "is_covered" => true ],
            ["name" => "Fire", "policy_id"=> 2, "is_covered" =>  true]
        ];

        foreach($cityCruisePolicyItems as $item){
            if(!NewPolicyItem::where('name','policy_id', $item['name'])->exists()){
                DB::table('new_policy_items')->insert($item);
            }
        }

        foreach($autoPremiumPolicyItems as $item){
            if(!NewPolicyItem::where('name','policy_id', $item['name'])->exists()){
                DB::table('new_policy_items')->insert($item);
            }
        }
    }
}
