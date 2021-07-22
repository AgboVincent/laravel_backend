<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ClaimFilter extends ModelFilter
{
    public function policy(string $policy): self
    {
        return $this->related('policy', 'number', '=', $policy);
    }

    public function status(string $status): self
    {
        return $this->where('status', $status);
    }
}
