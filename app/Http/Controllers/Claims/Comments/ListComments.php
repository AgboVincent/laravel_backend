<?php

namespace App\Http\Controllers\Claims\Comments;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PaginatedResource;
use App\Models\Claim;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ListComments extends Controller
{
    public function __invoke(Claim $claim): JsonResponse
    {
        $query = $claim->comments()->orderByRaw('created_at desc, id desc');

        if (Auth::user()->type === User::TYPE_INSURANCE) {
            $query->where('involves_insurer', true);
        }

        return Output::success(
            new PaginatedResource(
                $query->paginate(),
                CommentResource::class
            )
        );
    }
}
