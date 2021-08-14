<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ClaimFilter extends ModelFilter
{
    public function query($query)
    {
        return $this
            ->join('accidents', 'accidents.claim_id', '=', 'claims.id')
            ->where('description', 'LIKE', '%' . $query . '%')
            ->orWhere('first_name', 'LIKE', '%' . $query . '%')
            ->orWhere('last_name', 'LIKE', '%' . $query . '%')
            ->orWhere('email', 'LIKE', '%' . $query . '%')
            ->orWhere('policies.number', 'LIKE', '%' . $query . '%');

    }

    public function status(string $status)
    {
        return $this->where('claims.status', $status);
    }
}
