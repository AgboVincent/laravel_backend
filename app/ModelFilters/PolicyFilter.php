<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder;

class PolicyFilter extends ModelFilter
{
    public function query($query)
    {
        return $this
            ->join('users', 'users.id', '=', 'policies.user_id')
            ->where(function (Builder $builder) use ($query) {
                return $builder
                    ->orWhere('users.first_name', 'LIKE', '%' . $query . '%')
                    ->orWhere('users.last_name', 'LIKE', '%' . $query . '%')
                    ->orWhere('users.email', 'LIKE', '%' . $query . '%')
                    ->orWhere('number', 'LIKE', '%' . $query . '%');
            });
    }
}
