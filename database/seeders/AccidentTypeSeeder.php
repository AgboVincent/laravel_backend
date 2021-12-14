<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\AccidentType;
use Illuminate\Database\Seeder;

class AccidentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            'Head-on collisions', 'Rear-end collisions', 'Side-impact accidents',
            'Chain reactions', 'Rollovers', 'Other',
        ])
            ->each(function ($key) {
                AccidentType::query()->firstOrCreate([
                    'name' => Str::replace('_', ' ', $key)
                ]);
            });
    }
}
