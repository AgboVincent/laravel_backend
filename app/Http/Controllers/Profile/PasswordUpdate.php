<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\Auth;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordUpdate extends Controller
{
    public function __invoke(\App\Http\Requests\Profile\PasswordUpdate $request): JsonResponse
    {
        Auth::user()->update([
            'password' => Hash::make($request->get('new_password'))
        ]);

        return Output::success('Updated');
    }
}
