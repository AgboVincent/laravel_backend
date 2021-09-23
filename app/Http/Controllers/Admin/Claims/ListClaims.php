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
        $query = Auth::user()->company->claims()->latest('claims.created_at');

        if (Auth::user()->type === User::TYPE_BROKER) {
            $query = $query
                ->join('users', 'users.id', '=', 'policies.user_id')
                ->whereRaw('JSON_CONTAINS(JSON_UNQUOTE(meta), ?, ?)', [(string)Auth::user()->id, '$.broker_id'])
                ->whereNotNull('meta');
        } else {
            $query = $query->where('involves_insurer', true);
        }

        return Output::success(
            new PaginatedResource(
                $query->filter($request->all())->with([
                    'policy', 'accident.media', 'accident.thirdParty', 'accident.media.file',
                    'items.type', 'user'
                ])->paginate(),
                ClaimResource::class
            )
        );
    }
}
