<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CollectionService;

class GetCollection extends Controller
{
    public function claims(Request $request, CollectionService $result)
    {    
       return $result->paginate($result->claims($request));
    }

    public function getClaim(Request $request, CollectionService $claim)
    {
        return $claim->getClaim($request);
    }

    public function claimsOverview(CollectionService $claimOverview)
    {
        return $claimOverview->claimsOverview();
    }
}
