<?php

namespace App\Http\Controllers\Claims;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClaimResource;
use App\Http\Resources\PaginatedResource;
use App\Models\Claim;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class All extends Controller
{
    public function __invoke(Request $request, Claim $model): JsonResponse
    {
        $claims = Auth::user()->claims()->latest()
            ->filter($request->all())
            ->with([
                'policy', 'accident.media', 'accident.thirdParty', 'accident.media.file',
                'items', 'user'
            ])
            ->paginate();

        return Output::success(
            new PaginatedResource(
                $claims,
                ClaimResource::class
            )
        );
    }
}
