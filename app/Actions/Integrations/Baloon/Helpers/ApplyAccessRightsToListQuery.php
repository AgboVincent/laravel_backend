<?php

namespace App\Actions\Integrations\Baloon\Helpers;

use App\DTOs\Integrations\Baloon\AccessRight;
use App\DTOs\Integrations\Baloon\AccessRightCollection;
use App\Helpers\Integrations\Baloon;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsObject;

class ApplyAccessRightsToListQuery
{
    use AsObject, ChecksAccessRight;

    protected string $queryType = self::QUERY_TYPE_POLICY;

    const QUERY_TYPE_POLICY = 'policy';
    const QUERY_TYPE_CLAIM = 'claim';

    public function handle(User $user, $query, string $queryType=self::QUERY_TYPE_POLICY)
    {
        $this->queryType = $queryType;

        $accessRights = $this->loadAccessRights($user);
        if($accessRights){
            $query = $this->buildFilters($accessRights,$query);
        }

        return $query;
    }


    protected function buildFilters(AccessRightCollection $accessRights, $query){

        $query->where(function ($query) use ($accessRights){
            foreach ($accessRights as $accessRight){
                if(
                    !in_array($accessRight->accessRightCode,['READ_CLAIM','MANAGE_CLAIM']) ||
                    (!$accessRight->compagnies && !$accessRight->reseaux && !$accessRight->acteursCommerciaux)
                ){
                    continue;
                }

                $query->orWhere($this->makeFilterClosure($accessRight));
            }
        });

        return $query;
    }

    protected function makeFilterClosure(AccessRight $accessRight):\Closure{
        return function($query) use ($accessRight){
            if($accessRight->compagnies){
                $insurerIds = Baloon::getInsurerIdsForCompagnies($accessRight->compagnies);

                $relationName = 'insurer';
                if($this->queryType==self::QUERY_TYPE_CLAIM){
                    $relationName = 'policy.insurer';
                }

                $query->whereHas($relationName,function ($insurers) use ($insurerIds){
                    $insurers->whereIn('insurers.id',$insurerIds);
                });
            }

            $metaRelationName = 'metas';
            if($this->queryType==self::QUERY_TYPE_CLAIM){
                $metaRelationName = 'policy.metas';
            }

            if(count($accessRight->reseaux)){
                $query->whereHas($metaRelationName,function ($metas) use ($accessRight){
                    $metas->where('name',Baloon::META_KEY_RESEAU_ID)
                        ->whereIn('value',$accessRight->reseaux);
                });
            }

            if(count($accessRight->acteursCommerciaux)){
                $query->whereHas($metaRelationName,function ($metas) use ($accessRight){
                    $metas->where('name',Baloon::META_KEY_ACTEUR_COMMERCIAL_ID)
                        ->whereIn('value',$accessRight->acteursCommerciaux);
                });
            }
        };
    }

}
