<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ClaimResource;
use App\Http\Resources\PaginatedResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListClaims extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return Output::success(
            new PaginatedResource(
                Auth::user()->company->claims()
                    ->filter($request->all())
                    ->paginate(),
                ClaimResource::class
            )
        );
    }
}
