<?php

namespace App\Http\Resources;

use App\Models\AccidentMedia;
use Illuminate\Http\Resources\Json\JsonResource;

class AccidentMediaResource extends JsonResource
{
    public function __construct(AccidentMedia $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource->load('file');
    }

    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'type' => $this->resource->type,
            'file' => new UploadResource($this->resource->file)
        ];
    }
}
