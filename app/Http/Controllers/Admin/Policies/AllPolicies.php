<?php

namespace App\Http\Controllers\Admin\Policies;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PolicyResource;
use App\Http\Resources\PaginatedResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AllPolicies extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = Auth::user()->company->policies();

        if (Auth::user()->type === User::TYPE_BROKER) {
            $query = $query
                ->join('users', 'users.id', '=', 'policies.user_id')
                ->selectRaw('policies.*')
                ->whereRaw('JSON_CONTAINS(JSON_UNQUOTE(users.meta), ?, ?)', [Auth::user()->id, '$.broker_id']);
        }

        return Output::success(new PaginatedResource(
            $query->filter($request->all())->paginate(),
            PolicyResource::class
        ));

    }
}
