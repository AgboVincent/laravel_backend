<?php

namespace App\Http\Controllers\Company\Claims;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClaimResource;
use App\Jobs\ProcessClaimImagesToML;
use App\Models\Accident;
use App\Models\AccidentMedia;
use App\Models\Claim;
use App\Models\Policy;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateNewClaim extends Controller
{
    public function __invoke(Policy $policy, Request $request): JsonResponse
    {
        // $request->validate([
        //     'accident_type' => 'integer|required',
        //     'date' =>  'date|required',
        //     'description' => 'string|required',
        //     'p'
        // ]);

        DB::beginTransaction();
        $accident = $this->createAccident(
            $claim = $this->createClaim($policy),
            $request
        );
        $this->createThirdParties($accident, $request);
        $this->createItems($accident, (array)$request->get('quotes'));
        $this->uploadDocuments($accident, $request);
        DB::commit();

        dispatch(new ProcessClaimImagesToML($claim));

        return Output::success(new ClaimResource($claim), Response::HTTP_CREATED);
    }

    private function createAccident(Claim $claim, Request $request): Accident
    {
        /**
         * @var Accident $accident
         */
        $accident = $claim->accident()->create([
            'occurred_at' => Carbon::parse($request->get('date') . ' ' . $request->get('time')),
            'accident_type_id' => $request->get('accident_type'),
            'description' => $request->get('description'),
            'involved_third_party' => (bool)$request->get('involves_third_party')
        ]);

        return $accident;
    }

    private function createClaim(Policy $policy): Claim
    {
        /**
         * @var Claim $claim
         */
        $claim = Claim::query()->create([
            'policy_id' => $policy->id,
            'status' => Claim::STATUS_PENDING,
            'involves_insurer' => true
        ]);

        return $claim;
    }

    private function createThirdParties(Accident $accident, Request $request)
    {
        if ($accident->involved_third_party) {
            $accident->thirdParties()->createMany($request->get('third_parties'));
        }
    }

    private function createItems(Accident $accident, array $quotes)
    {
        collect($quotes)
            ->each(function ($quote) use ($accident) {
                $accident->items()->create(
                    [
                        'accident_id' => $accident->id,
                        'quote' => $quote['amount'],
                        'type_id' => $quote['type'],
                        'quantity' => $quote['quantity']
                    ]);
            });

    }

    private function uploadDocuments(Accident $accident, Request $request)
    {
        try {
            $accident->uploads()->create([
                'upload_id' => $request->input('documents.pictures')[0],
                'type' => AccidentMedia::TYPE_CLOSE_UP
            ]);

            $accident->uploads()->create([
                'upload_id' => $request->input('documents.pictures')[1],
                'type' => AccidentMedia::TYPE_WIDE_ANGLE
            ]);

            $accident->uploads()->create([
                'upload_id' => $request->input('documents.pictures')[2],
                'type' => AccidentMedia::TYPE_FRONT
            ]);

            $accident->uploads()->create([
                'upload_id' => $request->input('documents.pictures')[3],
                'type' => AccidentMedia::TYPE_REAR
            ]);
        } catch (\Throwable | \Exception $e) {
        }
    }
}
