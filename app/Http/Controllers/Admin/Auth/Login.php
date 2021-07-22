<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\LoginResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class Login extends Controller
{
    public function __invoke(Request $request, Admin $model): JsonResponse
    {
        $user = $model->where('email', $request->get('email'))->first();

        if ($user && Hash::check($request->get('password'), $user->password)) {
            return Output::success(new LoginResource($user));
        }

        return Output::error('Invalid Credentials', Response::HTTP_NOT_ACCEPTABLE);
    }
}
