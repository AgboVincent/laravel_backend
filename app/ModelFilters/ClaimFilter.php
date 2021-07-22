<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ClaimFilter extends ModelFilter
{
    public function policy($policy)
    {
        return $this->related('policy', 'number', $policy);
    }

    public function status(string $status)
    {
        return $this->where('status', $status);
    }
}
