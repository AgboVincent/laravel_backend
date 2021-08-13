<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginatedResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerList extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = Auth::user()->company->users();

        if (Auth::user()->type === User::TYPE_BROKER) {
            $query = $query->whereRaw('JSON_CONTAINS(JSON_UNQUOTE(meta), ?, ?)', [Auth::user()->id, '$.broker_id']);
        }

        return Output::success(
            new PaginatedResource(
                $query->paginate(),
                UserResource::class
            )
        );
    }
}
