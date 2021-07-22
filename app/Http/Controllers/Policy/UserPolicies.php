<?php

namespace App\Http\Controllers\Policy;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginatedResource;
use App\Http\Resources\PolicyResource;
use App\Models\Policy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserPolicies extends Controller
{
    public function __invoke(Request $request, Policy $model): JsonResponse
    {
        return Output::success(
            new PaginatedResource(
                Auth::user()->policies()->latest()->paginate(),
                PolicyResource::class
            )
        );
    }
}
