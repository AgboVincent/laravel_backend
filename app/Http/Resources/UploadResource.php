<?php

namespace App\Http\Resources;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

class UploadResource extends JsonResource
{
    /**
     * UploadResource constructor.
     *
     * @param Model|Upload $resource
     */
    public function __construct(Model $resource)
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
