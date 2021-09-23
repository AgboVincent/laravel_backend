<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CompanyConfigurationSeed extends Seeder
{
    public function run()
    {
        collect(config('company-config'))
            ->each(function ($value, $config) {
                \App\Models\Company::query()->whereDoesntHave('configurations', function (\Illuminate\Database\Eloquent\Builder $builder) use ($config) {
                    $builder->where('configurations.name', $config);
                })->cursor()->each(function (\App\Models\Company $company) use ($value, $config) {
                    $company->configurations()->create([
                        'name' => $config,
                        'value' => $value
                    ]);
                });
            });
    }
}
