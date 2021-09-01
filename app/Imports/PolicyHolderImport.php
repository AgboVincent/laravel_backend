<?php

namespace App\Imports;

use App\Helpers\Auth;
use App\Models\Policy;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\User\NewAccountCreation;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Row;

class PolicyHolderImport extends HeadingRowFormatter implements OnEachRow, WithHeadingRow, WithBatchInserts
{
    public function batchSize(): int
    {
        return 100;
    }

    public function onRow(Row $row): User
    {
        $password = Str::random(10);
        $meta = null;
        if (@$row['Broker']) {
            // todo: broker should have been a company also just like insurance. Should redesign broker related tables.
            $broker = User::query()->firstOrCreate([
                'email' => $row['Broker Email'],
            ], [
                'first_name' => explode(' ', $row['Broker'], 2)[0],
                'last_name' => explode(' ', $row['Broker'], 2)[1],
                'company_id' => Auth::user()->company_id,
                'type' => User::TYPE_BROKER,
                'password' => Str::random(10)
            ]);
            $meta = ['broker_id' => $broker->id];
        }

        /**
         * @var User $user
         */
        $user = User::query()->firstOrCreate([
            'email' => $row['Email']
        ], [
            'company_id' => Auth::user()->company_id,
            'first_name' => $row['First Name'],
            'last_name' => $row['Last Name'],
            'mobile' => $row['Mobile'],
            'password' => $password,
            'type' => User::TYPE_POLICY_HOLDER,
            'meta' => $meta
        ]);

        /**
         * @var Policy $policy
         */
        $policy = $user->policies()->firstOrCreate([
            'number' => $row['Policy Number']
        ], [
            'company_id' => $user->company_id,
            'expires_at' => $row['Policy Expires At'],
            'type' => array_search($row['Policy Type'], [Policy::TYPE_COMPREHENSIVE, Policy::TYPE_THIRD_PARTY]) ? $row['Policy Type'] : Policy::TYPE_COMPREHENSIVE,
            'status' => $row['Policy Status']
        ]);

        $policy->vehicle()->firstOrCreate([
            'registration_number' => $row['Vehicle Registration Number']
        ], [
            'chassis_number' => $row['Vehicle Chassis Number'],
            'engine_number' => $row['Vehicle Engine Number'],
            'manufacturer' => $row['Vehicle Manufacturer'],
            'model' => $row['Vehicle Manufacturer'],
            'estimate' => (float)$row['Vehicle Estimate'],
            'color' => $row['Vehicle Color'],
            'gear_type' => array_search($row['Vehicle Gear Type'], [Vehicle::GEAR_TYPE_AUTO, Vehicle::GEAR_TYPE_MANUEL]) ? $row['Vehicle Gear Type'] : Vehicle::GEAR_TYPE_MANUEL,
            'year' => $row['Vehicle Year'],
        ]);

        if ($user->wasRecentlyCreated) {
            $user->notify(new NewAccountCreation($password));
        }

        return $user;
    }

}
