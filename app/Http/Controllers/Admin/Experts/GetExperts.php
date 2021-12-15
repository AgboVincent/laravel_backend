<?php

namespace App\Http\Controllers\Admin\Experts;

use App\Models\Expert;
use App\Helpers\Output;
use App\Http\Controllers\Controller;

class GetExperts extends Controller
{
    public function __invoke()
    {
        return Output::success(Expert::all());
    }
}