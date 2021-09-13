<?php

namespace App\Http\Controllers\Notifications;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class ToggleNotificationReadStatus extends Controller
{
    public function __invoke(DatabaseNotification $notification, Request $request): JsonResponse
    {
        $notification->update([
            'read_at' => $notification->read_at ? null : now()
        ]);

        return Output::success(new NotificationResource($notification));
    }
}
