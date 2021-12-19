<?php

namespace App\Http\Controllers\Admin\Policies;

use App\Actions\Integrations\Baloon\Helpers\ApplyAccessRightsToListQuery;
use App\Helpers\Auth;
use App\Helpers\Integrations\Baloon;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PolicyResource;
use App\Http\Resources\PaginatedResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AllPolicies extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = $this->makePolicyQuery();

        return Output::success(new PaginatedResource(
            $query->filter($request->all())->paginate(),
            PolicyResource::class
        ));

    }

    protected function makePolicyQuery(){
        $user = Auth::user();

        if($user->owner){
            $query = $user->owner->policies();
            return $this->applyMoreFilters($user,$query);
        }

        $query = $user->company->policies()
            ->selectRaw('policies.*')
            ->join('users', 'users.id', '=', 'policies.user_id');

        if ($user->type === User::TYPE_BROKER) {
            $query = $query
                ->whereRaw('JSON_CONTAINS(JSON_UNQUOTE(users.meta), ?, ?)', [(string)Auth::user()->id, '$.broker_id'])
                ->whereNotNull('users.meta');
        }

        return  $query;
    }

    protected function applyMoreFilters(User $user, $query){
        if($user->owner->code==Baloon::BROKER_CODE){
            $query = ApplyAccessRightsToListQuery::make()->handle($user,$query);
        }

        return $query;
    }
}
