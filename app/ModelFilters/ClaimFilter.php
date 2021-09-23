<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ClaimFilter extends ModelFilter
{
    public function query($query)
    {
        return $this
            ->join('accidents', 'accidents.claim_id', '=', 'claims.id')
            ->join('users', 'users.id', '=', 'policies.user_id')
            ->where('description', 'LIKE', '%' . $query . '%')
            ->orWhere('users.first_name', 'LIKE', '%' . $query . '%')
            ->orWhere('users.last_name', 'LIKE', '%' . $query . '%')
            ->orWhere('users.email', 'LIKE', '%' . $query . '%')
            ->orWhere('policies.number', 'LIKE', '%' . $query . '%');

    }

    public function status(string $status)
    {
        return $this->where('claims.status', $status);
    }
}
