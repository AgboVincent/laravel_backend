<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Actions\Integrations\Baloon\Helpers\ApplyAccessRightsToListQuery;
use App\Helpers\Auth;
use App\Helpers\Integrations\Baloon;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ClaimResource;
use App\Models\Claim;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class SingleClaim extends Controller
{
    public function __invoke( $claim_id): JsonResponse
    {
        $query = $this->makeQuery();

        $claim = $query->findOrFail($claim_id);

        return Output::success(
            new ClaimResource(
                $claim->load([
                    'policy', 'accident.media', 'accident.thirdParties', 'accident.media.file',
                    'items', 'items.type', 'user'
                ])
            )
        );
    }

    protected function makeQuery(){
        $user = Auth::user();

        if($user->owner){
            $query = $user->owner->claims();
            return $this->applyMoreFilters($user,$query);
        }

        return $this->oldQuery();

    }

    protected function applyMoreFilters(User $user, $query){
        if($user->owner->code==Baloon::BROKER_CODE){
            $query = ApplyAccessRightsToListQuery::make()
                ->handle($user,$query,ApplyAccessRightsToListQuery::QUERY_TYPE_CLAIM);
        }

        return $query;
    }

    protected function oldQuery(){
        $query = Auth::user()->company->claims()->latest('claims.created_at');

        if (Auth::user()->type === User::TYPE_BROKER) {
            $query = $query
                ->join('users', 'users.id', '=', 'policies.user_id')
                ->whereRaw('JSON_CONTAINS(JSON_UNQUOTE(meta), ?, ?)', [(string)Auth::user()->id, '$.broker_id'])
                ->whereNotNull('meta');
        } else {
            $query = $query->where('involves_insurer', true);
        }

        return  $query;
    }
}
