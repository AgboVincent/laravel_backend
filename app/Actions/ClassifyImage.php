<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;

class ClassifyImage
{
    use AsAction;
    const CLASSIFICATION_URL = "https://24lf5b0s7j.execute-api.us-east-2.amazonaws.com/production/vehicleAccidentClassification";

    public function handle(string $imagePath): int
    {
        $imageClassificationRequest = Http::post(self::CLASSIFICATION_URL, [
            'image_data' => $imagePath
        ]);

        if ($imageClassificationRequest->failed()) {
            Log::debug('Image classification model process failed with Status: ' . $imageClassificationRequest->status());
            Log::debug($imageClassificationRequest->body());
            return 0;
        }

        if (!$imageClassificationRequest->json()['is_damaged']) {
            return 0;
        }

        return 1;
    }
}
