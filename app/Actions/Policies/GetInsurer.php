<?php

namespace App\Actions\Policies;

use App\Models\Policy;
use App\Helpers\Output;

class GetInsurer
{
    public function handle(Policy $policy)
    {
        return $policy->insurer;
    }

    public function asController(Policy $policy)
    {
        return Output::success($this->handle($policy));
    }
}