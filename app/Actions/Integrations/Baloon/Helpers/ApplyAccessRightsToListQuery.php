<?php

namespace App\Actions\Integrations\Baloon\Helpers;

use App\DTOs\Integrations\Baloon\AccessRightCollection;
use App\Helpers\Integrations\Baloon;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Lorisleiva\Actions\Concerns\AsObject;

class ApplyAccessRightsToListQuery
{
    use AsObject, ChecksAccessRight;

    public function handle(User $user, $query)
    {
        $accessRights = $this->loadAccessRights($user);
        if($accessRights){
            $query = $this->buildFilters($accessRights,$query);
        }

        return $query;
    }

    protected function buildFilters(AccessRightCollection $accessRights, $query){

        foreach ($accessRights as $accessRight){
            if(!in_array($accessRight->accessRightCode,['READ_CLAIM','MANAGE_CLAIM'])){
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
