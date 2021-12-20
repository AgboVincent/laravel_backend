<?php

namespace App\Actions\Integrations\Baloon\Helpers;

use App\DTOs\Integrations\Baloon\AccessRight;
use App\DTOs\Integrations\Baloon\AccessRightCollection;
use App\Helpers\Integrations\Baloon;
use App\Models\User;
use Illuminate\Support\Facades\Log;
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

    //TODO: reduce complexity
    protected function buildFilters(AccessRightCollection $accessRights, $query){

        $query->where(function ($query) use ($accessRights){
            $query->whereRaw('1=1');

            foreach ($accessRights as $accessRight){
                if(
                    !in_array($accessRight->accessRightCode,['READ_CLAIM','MANAGE_CLAIM']) ||
                    (!$accessRight->compagnies && !$accessRight->reseaux && !$accessRight->acteursCommerciaux)
                ){
                    Log::info("Skipping access right",['right'=>$accessRight]);
                    continue;
                }

                $query->orWhere(function($query) use ($accessRight){
                    if($accessRight->compagnies){
                        $insurerIds = Baloon::getInsurerIdsForCompagnies($accessRight->compagnies);

                        $query->whereHas('insurer',function ($insurers) use ($insurerIds){
                            $insurers->whereIn('insurers.id',$insurerIds);
                        });
                    }

                    if(count($accessRight->reseaux)){
                        $query->whereHas('metas',function ($metas) use ($accessRight){
                            $metas->where('name',Baloon::META_KEY_RESEAU_ID)
                                ->whereIn('value',$accessRight->reseaux);
                        });
                    }

                    if(count($accessRight->acteursCommerciaux)){
                        $query->whereHas('metas',function ($metas) use ($accessRight){
                            $metas->where('name',Baloon::META_KEY_ACTEUR_COMMERCIAL_ID)
                                ->whereIn('value',$accessRight->acteursCommerciaux);
                        });
                    }
                });

            }
        });

        return $query;
    }



}
