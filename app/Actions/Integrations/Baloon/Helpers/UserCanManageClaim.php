<?php

namespace App\Actions\Integrations\Baloon\Helpers;

use App\DTOs\Integrations\Baloon\AccessRight;
use App\DTOs\Integrations\Baloon\AccessRightCollection;
use App\Helpers\Integrations\Baloon;
use App\Models\Claim;
use App\Models\Policy;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsObject;

class UserCanManageClaim
{
    use AsObject, ChecksAccessRight;

    public function handle(User $user, $object)
    {
        $accessRights = $this->loadAccessRights($user);
        if(!$accessRights){
            return true;
        }

        return $this->rightsCanManageClaim($accessRights,$object);
    }



    protected function rightsCanManageClaim(AccessRightCollection $accessRights, $object){

        //this iteration simulates OR comparison as baloon wants
        foreach ($accessRights as $accessRight){
            if($accessRight->accessRightCode!=='MANAGE_CLAIM'){
                continue;
            }

            $rulesPassed = $this->iterateAccessRules($accessRight,$object);

            //check if $rulesPassed has all elements to be true
            //by looking for any ocurrence of false to simulate AND comparison
            if(in_array(false, $rulesPassed, true) === false){
                return true;
            }
        }

        return false;
    }

    /**
     * Check all the rules of the accessRight to see if they provide
     * edit access to the object. An output of [true,true,etc] indicates full access
     * @param AccessRight $accessRight
     * @param $object
     * @return array
     */
    protected function iterateAccessRules(AccessRight $accessRight,$object){
        $result = [];

        $result[] = $this->checkRuleCompagnies($accessRight,$object);

        $result[] = $this->checkRuleReseaux($accessRight,$object);

        $result[] = $this->checkRuleActeurCommercial($accessRight,$object);

        return $result;
    }


    protected function checkRuleCompagnies(AccessRight $accessRight,$object){
        if(count($accessRight->compagnies)){
            $InsurerIds  = Baloon::getInsurerIdsForCompagnies($accessRight->compagnies);

            if($object instanceof Claim){
                $policy = $object->policy;

            }elseif ($object instanceof Policy){
                $policy = $object;
            }

            if($policy->insurer){
                return in_array($policy->insurer->id, $InsurerIds);
            }
            return false;
            //prevent access if the user has an insurer restriction
            // but somehow the claim's policy doesnt have to an insurer
        }

        return true;
    }

    protected function checkRuleReseaux(AccessRight $accessRight,$object){
        if(count($accessRight->reseaux)){

            if($object instanceof Claim){
                $policy = $object->policy;

            }elseif ($object instanceof Policy){
                $policy = $object;

            }else {
                return true;
            }

            return $policy->metas()
                ->where('name',Baloon::META_KEY_RESEAU_ID)
                ->whereIn('value',$accessRight->reseaux)
                ->exists();
        }

        return true;
    }

    protected function checkRuleActeurCommercial(AccessRight $accessRight,$object){
        if(count($accessRight->acteursCommerciaux)){

            if($object instanceof Claim){
                $policy = $object->policy;

            }elseif ($object instanceof Policy){
                $policy = $object;

            }else {
                return true;
            }

            return $policy->metas()
                ->where('name',Baloon::META_KEY_ACTEUR_COMMERCIAL_ID)
                ->whereIn('value',$accessRight->acteursCommerciaux)
                ->exists();
        }

        return true;
    }

}
