<?php

namespace App\Http\Controllers\Claims\Comments;

use App\Helpers\Auth;
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
        $comment = $claim->comments()->create([
            'comment' => $request->get('comment'),
            'user_id' => Auth::user()->id
        ]);

        //todo: notify those needed to be notified.

        return Output::success(new CommentResource($comment));
    }
}
