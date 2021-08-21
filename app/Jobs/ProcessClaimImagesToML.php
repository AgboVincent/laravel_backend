<?php

namespace App\Jobs;

use App\Models\AccidentMedia;
use App\Models\Claim;
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
    private array $images;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
        $this->images = $this->claim->accident->media;
    }

    public function handle()
    {
        $request = Http::post('https://pmm2k4a73j.execute-api.us-east-2.amazonaws.com/test/estimates', ['images' => [
            'front' => $this->images->where('type', AccidentMedia::TYPE_FRONT)->first(),
            'back' => $this->images->where('type', AccidentMedia::TYPE_REAR)->first(),
            'close-up' => $this->images->where('type', AccidentMedia::TYPE_CLOSE_UP)->first(),
        ]]);

        if ($request->failed()) {
            Log::debug('Claim Machine Learning Process Failed with Status: ' . $request->status());
            Log::debug($request->body());
            return;
        }

    }
}
