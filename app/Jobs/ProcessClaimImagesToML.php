<?php

namespace App\Jobs;

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

        $imageClassificationService = "https://24lf5b0s7j.execute-api.us-east-2.amazonaws.com/production/vehicleAccidentClassification";
        $uploadId = $this->image->where('type', AccidentMedia::TYPE_CLOSE_UP)->first()->upload_id;
        $imagePath = (new \App\Models\Upload)->find($uploadId)->path;

        $imageClassificationRequest = Http::post($imageClassificationService, [
            'image_data' => $imagePath
        ]);

        if($imageClassificationRequest->failed()) {
            Log::debug('Image classification model process failed with Status: ' . $imageClassificationRequest->status());
            Log::debug($imageClassificationRequest->body());
        } else {
            if($imageClassificationRequest->json()['is_damaged']) {
                $objectDetectionServiceRequest = Http::post($objectDetectionService, [
                    'image' => (new \App\Models\Upload)->find($uploadId)->path,
                    'model' => $this->claim->policy->vehicle->model,
                    'year' => $this->claim->policy->vehicle->year
                ]);

                if ($objectDetectionServiceRequest->failed()) {
                    Log::debug('Claim Machine Learning Process Failed with Status: ' . $objectDetectionServiceRequest->status());
                    Log::debug($objectDetectionServiceRequest->body());
                    return;
                }

                $data = collect($objectDetectionServiceRequest->json()['detected_damages']);

                $data->each(function ($result) {
                    if (isset($result['pred_boxes'])) {
                        $type = ClaimItemType::query()->where('key', $result['damage'])->first();

                        if ($type) {
                            $this->claim->items()->where('type_id', $type->id)->update([
                                'amount' => $result['price']
                            ]);
                        }
                    }
                });
            }
        }

    }
}
