<?php

namespace App\Http\Controllers\Claims\Comments;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Requests\Claim\Comment\CreateRequest;
use App\Http\Resources\CommentResource;
use App\Models\Claim;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class AddComment extends Controller
{
    public function __invoke(Claim $claim, CreateRequest $request): JsonResponse
    {
        /**
         * @var Comment $comment
         */
        $comment = $claim->comment($request->get('comment'));

        return Output::success(new CommentResource($comment));
    }
}
