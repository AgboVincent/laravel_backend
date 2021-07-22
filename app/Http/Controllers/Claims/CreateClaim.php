<?php

namespace App\Http\Controllers\Claims;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Requests\Claim\CreateRequest;
use App\Http\Resources\ClaimResource;
use App\Models\AccidentMedia;
use App\Models\Claim;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CreateClaim extends Controller
{
    public function __invoke(CreateRequest $request, Claim $model): JsonResponse
    {
        /**
         * @var Vehicle $vehicle
         */
        $vehicle = Auth::user()->vehicles()
            ->where('registration_number', $request->get('vehicle_number'))
            ->first(); // No need checking if vehicle belongs to user since will be checked from UserVehicleExists rule

        DB::beginTransaction();

        $claim = $model->create([
            'policy_id' => $vehicle->policy_id,
            'status' => Claim::STATUS_PENDING
        ]);

        $accident = $claim->accident()->create([
            'occurred_at' => $request->get('date_time'),
            'type' => $request->get('accident_type'),
            'description' => $request->get('description'),
            'involved_third_party' => (bool)$request->get('involved_third_party')
        ]);

        if ($accident->involved_third_party) {
            $accident->thirdParty()->create($request->get('third_party'));
        }

        collect($request->get('quotes'))
            ->each(fn($quote) => $claim->items()->create(
                array_merge(['accident_id' => $claim->id],$quote)
            ));

        $accident->uploads()->create([
            'upload_id' => $request->input('documents.pictures.close_up'),
            'type' => AccidentMedia::TYPE_CLOSE_UP
        ]);

        $accident->uploads()->create([
            'upload_id' => $request->input('documents.pictures.wide_angle'),
            'type' => AccidentMedia::TYPE_WIDE_ANGLE
        ]);

        $accident->uploads()->create([
            'upload_id' => $request->input('documents.pictures.front'),
            'type' => AccidentMedia::TYPE_FRONT
        ]);

        $accident->uploads()->create([
            'upload_id' => $request->input('documents.pictures.rear'),
            'type' => AccidentMedia::TYPE_REAR
        ]);

        DB::commit();

        return Output::success(new ClaimResource($claim));
    }
}
