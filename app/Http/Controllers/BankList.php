<?php

namespace App\Http\Controllers;

use App\Helpers\Output;
use App\Models\Bank;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankList extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return Output::success(Bank::oldest('name')->get());
    }
}
