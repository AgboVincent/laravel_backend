<?php


namespace App\DTOs\Integrations\Baloon;


use Spatie\DataTransferObject\DataTransferObject;

class AccessRight extends DataTransferObject
{
    public string $accessRightCode;

    public array $compagnies;

    public array $reseaux;

    public array $acteursCommerciaux;
}
