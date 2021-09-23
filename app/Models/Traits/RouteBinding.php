<?php

namespace App\Models\Traits;

use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Model;

trait RouteBinding
{
    /**
     * @param mixed $value
     * @param null $field
     * @return Model|$this
     * @throws NotFoundException
     */
    public function resolveRouteBinding($value, $field = null): Model
    {
        $instance = parent::resolveRouteBinding($value, $field);

        if ($instance) return $instance;

        throw new NotFoundException($this->notFoundMessage());
    }

    public function notFoundMessage(): string
    {
        return 'Not Found';
    }

}
