<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evaluations\NewPolicy;
use Illuminate\Support\Facades\DB;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json_policies = file_get_contents(base_path('database/seeders/policy/policy.json'));
        $policies = json_decode($json_policies, true);

        foreach($policies as $policy){
            if(!NewPolicy::where('name', $policy['name'])->exists()){
                DB::table('new_policies')->insert($policy);
            }
        }
    }
}
