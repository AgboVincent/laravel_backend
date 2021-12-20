<?php

namespace App\Actions\Policies;

use App\Models\Policy;
use App\Helpers\Output;
use Lorisleiva\Actions\Concerns\AsAction;

class GetInsurer
{
    use AsAction;
    public function handle(Policy $policy)
    {
        return $policy->insurer;
    }

    public function asController(Policy $policy)
    {
        return Output::success($this->handle($policy));
    }
}
