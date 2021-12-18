<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Auth;
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
            return $user->owner->customers();
        }

        $query = Auth::user()->company->users();

        if (Auth::user()->type === User::TYPE_BROKER) {
            $query = $query->whereRaw('JSON_CONTAINS(JSON_UNQUOTE(meta), ?, ?)', [(string)Auth::user()->id, '$.broker_id'])
                ->whereNotNull('meta');
        }

        return  $query;

    }
}
