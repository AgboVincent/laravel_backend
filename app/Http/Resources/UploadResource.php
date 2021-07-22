<?php

namespace App\Http\Resources;

use App\Models\Upload;
use Illuminate\Http\Resources\Json\JsonResource;

class UploadResource extends JsonResource
{
    public function __construct(Upload $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource;
    }

    public function toArray($request): array
    {
        return $this->resource->only([
            'id', 'mime', 'ext', 'path', 'size', 'created_at'
        ]);
    }
}
