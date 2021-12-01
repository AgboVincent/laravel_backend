<?php

namespace App\Jobs;

use App\Actions\ClassifyImage;
use App\Models\AccidentMedia;
use App\Models\Claim;
use App\Models\ClaimItemType;
use App\Models\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessClaimImagesToML implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Claim $claim;
    /**
     * @var Upload[]|Collection
     */
    private $image;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
        $this->image = $this->claim->accident->media;
    }

    public function handle()
    {
//        'https://pmm2k4a73j.execute-api.us-east-2.amazonaws.com/test/estimates'
        $objectDetectionService = 'https://curacel-vehicle-report.herokuapp.com/report';
        $uploadId = $this->image->where('type', AccidentMedia::TYPE_CLOSE_UP)->first()->upload_id;
        $imagePath = (new \App\Models\Upload)->find($uploadId)->path;

        $isVehicleDamaged = ClassifyImage::run($imagePath);

        if ($isVehicleDamaged) {
            Log::info('Image classification model classified vehicle as damaged');
            $objectDetectionServiceRequest = Http::post($objectDetectionService, [
                'image' => $imagePath,
                'model' => $this->claim->policy->vehicle->model,
                'year' => $this->claim->policy->vehicle->year
            ]);

            if ($objectDetectionServiceRequest->failed()) {
                Log::debug('Claim Machine Learning Process Failed with Status: ' . $objectDetectionServiceRequest->status());
                Log::debug($objectDetectionServiceRequest->body());
                return;
            }

            $data = collect($objectDetectionServiceRequest->json()['detected_damages']);
            Log::info('Object detection model ran successfully and got this result', [$data]);

            $data->each(function ($result) {
                if (isset($result['pred_boxes'])) {
                    $type = ClaimItemType::query()->where('key', $result['damage'])->first();

                    if ($type) {
                        $claimItemType = $this->claim->items()->where('type_id', $type->id)->first();
                        if ($claimItemType) {
                            $claimItemType->update([
                                'amount' => $result['price']
                            ]);
                        } else {
                            $this->claim->items()->create([
                                'accident_id' => $this->claim->accident->id,
                                'type_id' => $type->id,
                                'quantity' => 1,
                                'amount' => $result['price'],
                                'ml_prediction' => 1
                            ]);
                        }
                    } else {
                        $newClaimType = ClaimItemType::create(['name' => ucfirst(str_replace('_', " ", $result['damage'])), 'key' => $result['damage']]);
                        $this->claim->items()->create([
                            'accident_id' => $this->claim->accident->id,
                            'type_id' => $newClaimType->id,
                            'quantity' => 1,
                            'amount' => $result['price'],
                            'ml_prediction' => 1
                        ]);
                    }
                }
            });
        }
    }
}
