<?php

namespace App\Exceptions;

use App\Helpers\Output;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class NotFoundException extends Exception
{
    public function render(): JsonResponse
    {
        return Output::error($this->getMessage(), Response::HTTP_NOT_FOUND);
    }
}
