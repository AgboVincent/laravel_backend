<?php


namespace App\DTOs\Integrations\Baloon;


use Spatie\DataTransferObject\DataTransferObjectCollection;

class AccessRightCollection extends DataTransferObjectCollection
{
    public function current():AccessRight
    {
        return new AccessRight(parent::current());
    }

}
