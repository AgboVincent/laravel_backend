<?php

namespace Database\Seeders;

use App\Models\ClaimItemType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClaimItemTypeSeed extends Seeder
{
    public function run()
    {
        collect([
            'damaged_bumper', 'bent_frames', 'cracked_windshiel', 'broken_windshield', 'scratches', 'scrapes', 'dents',
            'cracks', 'door_damage', 'broken_front_lights', 'broken_rare_lights', 'broken_side_mirror', 'damaged_door_handle',
            'burst_tyre', 'deflated_tyre', 'damaged_bonnet', 'damaged_door', 'smashed_roof', 'missing_fuel_door', 'damaged_trunk',
            'damaged_side_mirror', 'total_frontal_damage', 'total_back_damage'
        ])
            ->each(function ($key) {
                ClaimItemType::query()->firstOrCreate(['key' => $key], [
                    'name' => Str::replace('_', ' ', $key)
                ]);
            });
    }
}
