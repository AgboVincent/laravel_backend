<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Integrations\Baloon\Helpers\ApplyAccessRightsToListQuery;
use App\Helpers\Auth;
use App\Helpers\Integrations\Baloon;
use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginatedResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerList extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {

        $query = $this->makeCustomerQuery();

        return Output::success(
            new PaginatedResource(
                $query->paginate(),
                UserResource::class
            )
        );
    }

    protected function makeCustomerQuery(){
        $user = Auth::user();

        if($user->owner){
            $query = $user->owner->customers();
            return $this->applyMoreFilters($user,$query);
        }

        $query = $user->company->users();

        if ($user->type === User::TYPE_BROKER) {
            $query = $query->whereRaw('JSON_CONTAINS(JSON_UNQUOTE(meta), ?, ?)', [(string)Auth::user()->id, '$.broker_id'])
                ->whereNotNull('meta');
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
