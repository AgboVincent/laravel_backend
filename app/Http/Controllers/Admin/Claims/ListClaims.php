<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ClaimResource;
use App\Http\Resources\PaginatedResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListClaims extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = Auth::user()->company->claims()->filter($request->all());

        if (Auth::user()->type === User::TYPE_BROKER) {
            $query = $query
                ->join('users', 'users.id', '=', 'policies.user_id')
                ->whereRaw('JSON_CONTAINS(JSON_UNQUOTE(meta), ?, ?)', [Auth::user()->id, '$.broker_id']);
        }

        return Output::success(
            new PaginatedResource(
                $query->paginate(),
                ClaimResource::class
            )
        );
    }
}
