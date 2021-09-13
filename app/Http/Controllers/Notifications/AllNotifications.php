<?php

namespace App\Http\Controllers\Notifications;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\PaginatedResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AllNotifications extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return Output::success(
            new PaginatedResource(
                Auth::user()->notifications()->paginate(),
                NotificationResource::class
            )
        );
    }
}
