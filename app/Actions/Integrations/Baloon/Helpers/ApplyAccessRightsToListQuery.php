<?php

namespace App\Actions\Integrations\Baloon\Helpers;

use App\DTOs\Integrations\Baloon\AccessRightCollection;
use App\Helpers\Integrations\Baloon;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Lorisleiva\Actions\Concerns\AsObject;

class ApplyAccessRightsToListQuery
{
    use AsObject;

    public function handle(User $user, $query)
    {
        $accessRights = $this->loadAccessRights($user);
        if($accessRights){
            $query = $this->buildFilters($accessRights,$query);
        }

        return $query;
    }

    protected function loadAccessRights($user){
        $accessRights = $user->metas()
            ->firstWhere('name',Baloon::ACCESS_RIGHT_META_KEY);

        if(!$accessRights || ($accessRights && empty($accessRights->value))){
            return null;
        }

        $array = json_decode($accessRights->value,true);

        return new AccessRightCollection($array);
    }

    protected function buildFilters(AccessRightCollection $accessRights, $query){

        foreach ($accessRights as $accessRight){
            if($accessRight->accessRightCode!=='READ_CLAIM'){
                continue;
            }

            $query->where(function($query) use ($accessRight){
                if(count($accessRight->compagnies)){
                    $matchingInsurerIds = Baloon::getBrokerModel()
                        ->insurers()
                        ->wherePivotIn('insurer_id_from_broker',$accessRight->compagnies)
                        ->get(['insurers.id'])->pluck('id')
                        ->toArray();

                    $query->whereHas('insurers',function ($insurers) use ($matchingInsurerIds){
                        $insurers->whereIn('insurers.id',$matchingInsurerIds);
                    });

                }
            });

        }

        return $query;
    }

}
