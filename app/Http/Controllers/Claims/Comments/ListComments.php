<?php

namespace App\Http\Controllers\Claims\Comments;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PaginatedResource;
use App\Models\Claim;
use Illuminate\Http\JsonResponse;

class ListComments extends Controller
{
    public function __invoke(Claim $claim): JsonResponse
    {
        return Output::success(
            new PaginatedResource(
                $claim->comments()->paginate(),
                CommentResource::class
            )
        );
    }
}
