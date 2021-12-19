<?php


namespace App\Actions\Integrations\Baloon\Helpers;


use App\DTOs\Integrations\Baloon\AccessRightCollection;
use App\Helpers\Integrations\Baloon;

trait ChecksAccessRight
{
    protected function loadAccessRights($user){
        $accessRights = $user->metas()
            ->firstWhere('name',Baloon::ACCESS_RIGHT_META_KEY);

        if(!$accessRights || ($accessRights && empty($accessRights->value))){
            return null;
        }

        $array = json_decode($accessRights->value,true);

        return new AccessRightCollection($array);
    }
}
