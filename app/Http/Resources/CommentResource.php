<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function __construct(Comment $resource)
    {
        parent::__construct($resource);
        $this->resource = $resource->load(['author']);
    }

    public function toArray($request): array
    {
        $comment = $this->resource->only([
            'id', 'comment', 'created_at'
        ]);

        $comment['author'] = $this->resource->author->only([
            'id', 'first_name', 'last_name', 'type'
        ]);

        return $comment;
    }
}
